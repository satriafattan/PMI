<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PemesananDarah;
use App\Models\VerifikasiPemesanan;
use App\Models\RiwayatPemesanan;
use App\Models\StokDarah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifikasiPemesananMail;

class VerifikasiPemesananController extends Controller
{
    /**
     * Daftar pemesanan dengan verifikasi terakhir (ringkas di tabel) + filter.
     * NOTE: Dipaksa hanya menampilkan status 'pending'.
     * Route: GET /admin/verifikasi
     */
    public function index(Request $r)
    {
        $per    = (int) $r->input('per_page', 12);
        $q      = trim((string) $r->input('q'));
        $gol    = $r->input('gol');
        $produk = $r->input('produk');      // ⬅️ baca filter produk dari UI

        $allowedGol = ['A','B','AB','O'];
        if (!in_array($gol, $allowedGol, true)) {
            $gol = null;
        }

        $query = PemesananDarah::query()
            ->with('verifikasiTerakhir')
            ->where('status', 'pending')     // ✅ hanya tampilkan yang pending
            ->latest();

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('nama_pasien', 'like', "%{$q}%")
                  ->orWhere('rs_pemesan', 'like', "%{$q}%");
            });
        }

        if (!empty($gol)) {
           $query->where('gol_darah', 'like', $gol.'%');
        }

        if (!empty($produk)) {               // ⬅️ terapkan filter produk jika dipilih
            $query->where('produk', $produk);
        }

        $pemesanan = $query->paginate($per)->appends($r->query());

        return view('admin.verifikasi.index', compact('pemesanan'));
    }

    /**
     * Buat/catat verifikasi untuk 1 pemesanan + sinkronkan status pemesanan.
     * Route: POST /admin/verifikasi/{pemesanan}
     */
    public function store(Request $request, PemesananDarah $pemesanan)
    {
        $data = $request->validate([
            'status'             => ['required', 'in:pending,approved,rejected'],
            'tanggal_permintaan' => ['nullable', 'date'],
            'note'               => ['nullable', 'string', 'max:1000'],
        ]);

        $isApproved = $data['status'] === 'approved';

        DB::transaction(function () use ($data, $pemesanan, $isApproved) {
            if ($isApproved) {
                $this->approveAndReduceStock($pemesanan);
            }

            $this->saveVerification($pemesanan, $data);
            $this->saveHistory($pemesanan, $data['status']);

            $pemesanan->update(['status' => $data['status']]);
        });

        $pemesanan = $pemesanan->fresh();

        try {
            if (!empty($pemesanan->email)) {
                Mail::to($pemesanan->email)
                    ->send(new VerifikasiPemesananMail($pemesanan, $data['status']));
            }
        } catch (\Throwable $e) {
            // Email gagal tidak membatalkan verifikasi
            return back()->with('success', 'Verifikasi disimpan, namun email gagal dikirim.');
        }

        return back()->with('success', 'Verifikasi berhasil disimpan.');
    }

    /**
     * Kurangi stok jika disetujui
     */
    private function approveAndReduceStock(PemesananDarah $pemesanan): void
    {
        try {
            $criteria = [
                'produk'    => $pemesanan->produk,
                'gol_darah' => $pemesanan->gol_darah,
            ];

            if ($pemesanan->rhesus) {
                $criteria['rhesus'] = $pemesanan->rhesus;
            }

            // Lock stok berdasarkan kriterianya (FEFO)
            $batchList = StokDarah::where($criteria)
                ->orderBy('tgl_kadaluarsa')
                ->lockForUpdate()
                ->get();

            $needed = (int) $pemesanan->jumlah_kantong;
            $total  = $batchList->sum('jumlah');

            // Stok tidak cukup
            if ($total < $needed) {
                throw ValidationException::withMessages([
                    'status' => "Stok kurang. Dibutuhkan {$needed}, tersedia {$total}.",
                ]);
            }

            // Kurangi stok per batch
            foreach ($batchList as $batch) {
                if ($needed <= 0) break;

                $take = min($batch->jumlah, $needed);
                $batch->decrement('jumlah', $take);
                $needed -= $take;
            }

            // Jika masih ada sisa (harusnya tidak terjadi)
            if ($needed > 0) {
                throw ValidationException::withMessages([
                    'status' => 'Terjadi konflik stok saat pengurangan. Silakan coba ulang.',
                ]);
            }

        } catch (\Throwable $e) {

            // Jika error bawaan ValidationException → lempar lagi agar tampil ke user
            if ($e instanceof ValidationException) {
                throw $e;
            }

            // Untuk error tak terduga, bungkus dengan pesan lebih ramah
            throw ValidationException::withMessages([
                'status' => 'Terjadi kesalahan sistem saat mengurangi stok. Silakan coba kembali.',
            ]);
        }
    }

    /**
     * Simpan data verifikasi (upsert by pemesanan_id untuk menghindari duplikasi).
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
     * Simpan riwayat verifikasi.
     */
    private function saveHistory(PemesananDarah $pemesanan, string $status): void
    {
        RiwayatPemesanan::create([
            'pemesanan_id'   => $pemesanan->id,
            'nama'           => $pemesanan->nama_pasien,
            'tanggal'        => now()->toDateString(),
            'gol_darah'      => $pemesanan->gol_darah,
            'rhesus'         => $pemesanan->rhesus,
            'jumlah_kantong' => $pemesanan->jumlah_kantong,
            'produk'         => $pemesanan->produk,
            'aksi'           => "verifikasi: {$status}",
        ]);
    }

    /**
     * Koreksi status pada entri verifikasi tertentu + sinkronkan status pemesanan.
     * Route: PATCH /admin/verifikasi/{verifikasi}/status
     */
    public function updateStatus(Request $r, VerifikasiPemesanan $verifikasi)
    {
        $payload = $r->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
            'note'   => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($verifikasi, $payload) {
            // Update entri verifikasi
            $verifikasi->update([
                'status' => $payload['status'],
                // 'note' => $payload['note'] ?? $verifikasi->note, // aktifkan bila kolom ada
            ]);

            // Sinkronkan status pemesanan
            $verifikasi->pemesanan->update(['status' => $payload['status']]);

            // Catat riwayat koreksi
            RiwayatPemesanan::create([
                'pemesanan_id'   => $verifikasi->pemesanan_id,
                'nama'           => $verifikasi->pemesanan->nama_pasien,
                'tanggal'        => now()->toDateString(),
                'gol_darah'      => $verifikasi->gol_darah,
                'rhesus'         => $verifikasi->rhesus,
                'jumlah_kantong' => $verifikasi->pemesanan->jumlah_kantong,
                'produk'         => $verifikasi->produk,
                'aksi'           => 'ubah status: ' . $payload['status'],
            ]);
        });

        return back()->with('success', 'Status verifikasi & pemesanan berhasil diperbarui.');
    }
}
