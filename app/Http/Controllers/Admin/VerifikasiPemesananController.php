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

    /**
     * POST /admin/verifikasi/{pemesanan}
     * Simpan verifikasi; saat approved, alokasikan unit per-kantong (FEFO).
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

            // verifikasi terakhir (upsert)
            $this->saveVerification($pemesanan, $data);

            // catat riwayat + cantumkan kode unit bila ada
            $aksi = "verifikasi: {$data['status']}";
            if ($isApproved && !empty($allocatedCodes)) {
                $aksi .= ' (unit: ' . implode(',', $allocatedCodes) . ')';
            }
            $this->saveHistory($pemesanan, $data['status'], $aksi);

            // sinkronkan status pemesanan
            $pemesanan->update(['status' => $data['status']]);
        });

        $pemesanan = $pemesanan->fresh();

        try {
            if (!empty($pemesanan->email)) {
                Mail::to($pemesanan->email)
                    ->send(new VerifikasiPemesananMail($pemesanan, $data['status']));
            }
        } catch (\Throwable $e) {
            return back()->with('success', 'Verifikasi disimpan, namun email gagal dikirim.');
        }

        return back()->with('success', 'Verifikasi berhasil disimpan.');
    }

    /**
     * FEFO per-kantong dengan tabel blood_units + sinkron stok_darah per-batch.
     * Mengembalikan array kode_unit yang dialokasikan.
     */
    private function allocateUnitsAndSyncStock(PemesananDarah $pemesanan): array
    {
        try {
            $needed = (int) $pemesanan->jumlah_kantong;

            // 1) Cari unit available yang cocok (FEFO by tgl_kadaluarsa)
            $units = BloodUnit::query()
                ->where('produk', $pemesanan->produk)
                ->where('gol_darah', $pemesanan->gol_darah)
                ->when($pemesanan->rhesus, fn($q) => $q->where('rhesus', $pemesanan->rhesus))
                ->where('status', 'available')
                ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString())
                ->orderBy('tgl_kadaluarsa')
                ->lockForUpdate() // cegah race
                ->limit($needed)
                ->get();

            if ($units->count() < $needed) {
                throw ValidationException::withMessages([
                    'status' => "Stok unit tidak mencukupi. Dibutuhkan {$needed}, tersedia {$units->count()}.",
                ]);
            }

            // 2) Tandai unit sebagai dispensed & kaitkan ke pemesanan
            foreach ($units as $u) {
                $u->update([
                    'status'       => 'dispensed',
                    'pemesanan_id' => $pemesanan->id,
                    'penerima'     => $pemesanan->rs_pemesan,
                ]);
            }

            // 3) Sinkronkan stok_darah.jumlah per-batch PERSIS sebanyak unit yang terpakai
            //    (hindari scan semua batch; pakai groupBy stok_id dari unit yang dipilih)
            $byBatch = $units->groupBy('stok_id')->map->count();
            foreach ($byBatch as $stokId => $count) {
                // lock baris stok yang relevan saja
                $batch = StokDarah::whereKey($stokId)->lockForUpdate()->first();
                if ($batch) {
                    // batas bawah nol (jaga-jaga)
                    $newVal = max(0, (int) $batch->jumlah - (int) $count);
                    $batch->update(['jumlah' => $newVal]);
                }
            }

            return $units->pluck('kode_unit')->all();
        } catch (\Throwable $e) {
            if ($e instanceof ValidationException) {
                throw $e;
            }
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
