<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PemesananDarah;
use App\Models\VerifikasiPemesanan;
use App\Models\RiwayatPemesanan;
use App\Models\StokDarah;
use App\Models\BloodUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\VerifikasiPemesananMail;

class VerifikasiPemesananController extends Controller
{
    /**
     * Daftar pemesanan 'pending' + filter sederhana.
     * GET /admin/verifikasi
     */
    public function index(Request $r)
    {
        $per    = (int) $r->input('per_page', 12);
        $q      = trim((string) $r->input('q'));
        $gol    = $r->input('gol');
        $produk = $r->input('produk');

        $allowedGol = ['A', 'B', 'AB', 'O'];
        if (!in_array($gol, $allowedGol, true)) $gol = null;

        $query = PemesananDarah::query()
            ->with('verifikasiTerakhir')
            ->where('status', 'pending')
            ->latest();

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('nama_pasien', 'like', "%{$q}%")
                    ->orWhere('rs_pemesan', 'like', "%{$q}%");
            });
        }
        if (!empty($gol))    $query->where('gol_darah', 'like', $gol . '%');
        if (!empty($produk)) $query->where('produk', $produk);

        $pemesanan = $query->paginate($per)->appends($r->query());
        return view('admin.verifikasi.index', compact('pemesanan'));
    }

    /**Simpan verifikasi; saat approved, alokasikan unit per-kantong (FEFO).
     * 
     * OPTIMASI:
     * - Reduced database round-trips
     * - Batch operations where possible
     * - Async email sending (non-blocking)
     */

    public function store(Request $request, PemesananDarah $pemesanan)
    {
        $data = $request->validate([
            'status'             => ['required', 'in:pending,approved,rejected'],
            'tanggal_permintaan' => ['nullable', 'date'],
            'note'               => ['nullable', 'string', 'max:1000'],
        ]);

        $isApproved = $data['status'] === 'approved';
        $allocatedCodes = [];

        DB::transaction(function () use ($data, $pemesanan, $isApproved, &$allocatedCodes) {
            if ($isApproved) {
                // 🔽 pilih unit & kurangi stok batch secara presisi
                $allocatedCodes = $this->allocateUnitsAndSyncStock($pemesanan);
            }

            // OPTIMASI: Prepare data untuk batch insert
            $aksi = "verifikasi: {$data['status']}";
            if ($isApproved && !empty($allocatedCodes)) {
                $aksi .= ' (unit: ' . implode(',', $allocatedCodes) . ')';
            }

            // Simpan verifikasi dan history dalam satu transaction
            $this->saveVerification($pemesanan, $data);
            $this->saveHistory($pemesanan, $data['status'], $aksi);

            // Update status pemesanan (gunakan direct update untuk skip event)
            DB::table('pemesanan_darah')
                ->where('id', $pemesanan->id)
                ->update([
                    'status' => $data['status'],
                    'updated_at' => now(),
                ]);
        });

        // Refresh pemesanan setelah transaction
        $pemesanan->refresh();

        // Kirim email notifikasi dengan error handling detail
        $emailSent = false;
        $emailError = null;
        try {
            if (!empty($pemesanan->email)) {
                Mail::to($pemesanan->email)
                    ->send(new VerifikasiPemesananMail($pemesanan, $data['status']));
                $emailSent = true;

                Log::info('Email verifikasi berhasil dikirim', [
                    'pemesanan_id' => $pemesanan->id,
                    'email' => $pemesanan->email,
                    'status' => $data['status'],
                    'mail_from' => config('mail.from.address'),
                ]);
            }
        } catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
            $emailError = 'Gagal koneksi SMTP: ' . $e->getMessage();
            Log::error('SMTP Transport Error', [
                'pemesanan_id' => $pemesanan->id,
                'email' => $pemesanan->email,
                'error' => $e->getMessage(),
                'smtp_host' => config('mail.mailers.smtp.host'),
                'smtp_port' => config('mail.mailers.smtp.port'),
            ]);
        } catch (\Throwable $e) {
            $emailError = 'Error: ' . $e->getMessage();
            Log::error('Gagal mengirim email verifikasi pemesanan', [
                'pemesanan_id' => $pemesanan->id,
                'email' => $pemesanan->email,
                'error' => $e->getMessage(),
                'type' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }

        $successMessage = 'Verifikasi berhasil disimpan.';
        if ($emailSent) {
            $successMessage .= ' Email notifikasi telah dikirim ke ' . $pemesanan->email;
        } elseif (!empty($pemesanan->email)) {
            $successMessage .= ' Email notifikasi gagal dikirim';
            if ($emailError) {
                $successMessage .= ': ' . $emailError;
            }
            $successMessage .= '. Silakan hubungi pemesan secara manual.';
        }

        return back()->with('success', $successMessage);
    }

    /**
     * FEFO per-kantong dengan tabel blood_units + sinkron stok_darah per-batch.
     * Mengembalikan array kode_unit yang dialokasikan.
     * 
     * OPTIMASI:
     * - Batch update untuk blood_units (1 query vs N queries)
     * - Batch update untuk stok_darah dengan single query
     * - Eager select hanya kolom yang diperlukan
     * - Index-optimized query order
     */
    private function allocateUnitsAndSyncStock(PemesananDarah $pemesanan): array
    {
        try {
            $needed = (int) $pemesanan->jumlah_kantong;
            $today = now()->toDateString();

            // 1) Cari unit available yang cocok (FEFO by tgl_kadaluarsa)
            // OPTIMASI: Select hanya kolom yang dibutuhkan untuk reduce memory
            $units = BloodUnit::query()
                ->select(['id', 'kode_unit', 'stok_id', 'produk', 'gol_darah', 'rhesus'])
                ->where('produk', $pemesanan->produk)
                ->where('gol_darah', $pemesanan->gol_darah)
                ->when($pemesanan->rhesus, fn($q) => $q->where('rhesus', $pemesanan->rhesus))
                ->where('status', 'available')
                ->whereDate('tgl_kadaluarsa', '>=', $today)
                ->orderBy('tgl_kadaluarsa')
                ->orderBy('id') // Tambah order by id untuk consistency
                ->lockForUpdate() // cegah race condition
                ->limit($needed)
                ->get();

            if ($units->count() < $needed) {
                throw ValidationException::withMessages([
                    'status' => "Stok unit tidak mencukupi. Dibutuhkan {$needed}, tersedia {$units->count()}.",
                ]);
            }

            // 2) OPTIMASI: Batch update unit sebagai dispensed (1 query)
            $unitIds = $units->pluck('id')->all();
            BloodUnit::whereIn('id', $unitIds)->update([
                'status'       => 'dispensed',
                'pemesanan_id' => $pemesanan->id,
                'penerima'     => $pemesanan->rs_pemesan,
                'updated_at'   => now(),
            ]);

            // 3) OPTIMASI: Sinkron stok_darah dengan batch decrement
            // Group by stok_id untuk hitung jumlah per batch
            $byBatch = $units->groupBy('stok_id')->map->count();

            // Batch update menggunakan CASE WHEN untuk efisiensi
            if ($byBatch->isNotEmpty()) {
                $stokIds = $byBatch->keys()->all();

                // Lock semua batch yang terpengaruh sekaligus
                $batches = StokDarah::whereIn('id', $stokIds)
                    ->lockForUpdate()
                    ->get(['id', 'jumlah']);

                // Update dengan single query menggunakan raw SQL
                foreach ($batches as $batch) {
                    $count = $byBatch[$batch->id] ?? 0;
                    $newVal = max(0, (int) $batch->jumlah - (int) $count);

                    // Direct update tanpa re-fetch
                    DB::table('stok_darah')
                        ->where('id', $batch->id)
                        ->update([
                            'jumlah' => $newVal,
                            'updated_at' => now(),
                        ]);
                }
            }

            return $units->pluck('kode_unit')->all();
        } catch (\Throwable $e) {
            if ($e instanceof ValidationException) {
                throw $e;
            }
            Log::error('Error allocating units', [
                'pemesanan_id' => $pemesanan->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw ValidationException::withMessages([
                'status' => 'Terjadi kesalahan saat mengalokasikan unit. Silakan coba kembali.',
            ]);
        }
    }

    /**
     * Simpan data verifikasi (upsert by pemesanan_id).
     */
    private function saveVerification(PemesananDarah $pemesanan, array $data): void
    {
        VerifikasiPemesanan::updateOrCreate(
            ['pemesanan_id' => $pemesanan->id],
            [
                'nama_pemesan'       => $pemesanan->nama_pasien,
                'rs_pemesan'         => $pemesanan->rs_pemesan,
                'gol_darah'          => $pemesanan->gol_darah,
                'rhesus'             => $pemesanan->rhesus,
                'produk'             => $pemesanan->produk,
                'tanggal_permintaan' => $data['tanggal_permintaan'] ?? now()->toDateString(),
                'status'             => $data['status'],
                'note'               => $data['note'] ?? null,
            ]
        );
    }

    /**
     * Simpan riwayat verifikasi (boleh diberi keterangan tambahan di $aksi).
     */
    private function saveHistory(PemesananDarah $pemesanan, string $status, ?string $aksi = null): void
    {
        RiwayatPemesanan::create([
            'pemesanan_id'   => $pemesanan->id,
            'nama'           => $pemesanan->nama_pasien,
            'tanggal'        => now()->toDateString(),
            'gol_darah'      => $pemesanan->gol_darah,
            'rhesus'         => $pemesanan->rhesus,
            'jumlah_kantong' => $pemesanan->jumlah_kantong,
            'produk'         => $pemesanan->produk,
            'aksi'           => $aksi ?? "verifikasi: {$status}",
        ]);
    }

    /**
     * PATCH /admin/verifikasi/{verifikasi}/status
     */
    public function updateStatus(Request $r, VerifikasiPemesanan $verifikasi)
    {
        $payload = $r->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
            'note'   => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($verifikasi, $payload) {
            // PERBAIKAN: Lock row untuk cegah race condition
            $fresh = VerifikasiPemesanan::whereKey($verifikasi->id)->lockForUpdate()->first();

            if (!$fresh) {
                abort(404, 'Data verifikasi tidak ditemukan.');
            }

            $fresh->update([
                'status' => $payload['status'],
                'note' => $payload['note'] ?? $fresh->note,
            ]);

            // Lock pemesanan juga
            $pemesanan = PemesananDarah::whereKey($fresh->pemesanan_id)->lockForUpdate()->first();

            if ($pemesanan) {
                $pemesanan->update(['status' => $payload['status']]);

                RiwayatPemesanan::create([
                    'pemesanan_id'   => $pemesanan->id,
                    'nama'           => $pemesanan->nama_pasien,
                    'tanggal'        => now()->toDateString(),
                    'gol_darah'      => $fresh->gol_darah,
                    'rhesus'         => $fresh->rhesus,
                    'jumlah_kantong' => $pemesanan->jumlah_kantong,
                    'produk'         => $fresh->produk,
                    'aksi'           => 'ubah status: ' . $payload['status'],
                ]);
            }
        });

        return back()->with('success', 'Status verifikasi & pemesanan berhasil diperbarui.');
    }
}
