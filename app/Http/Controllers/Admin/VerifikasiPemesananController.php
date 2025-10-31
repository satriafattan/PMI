<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PemesananDarah;
use App\Models\VerifikasiPemesanan;
use App\Models\RiwayatPemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiPemesananController extends Controller
{
    /**
     * Daftar pemesanan dengan verifikasi terakhir (ringkas di tabel) + filter.
     * Route: GET /admin/verifikasi
     */
    public function index(Request $r)
    {
        $per = (int) $r->input('per_page', 12);
        $q   = $r->input('q');
        $st  = $r->input('status');
        $gol = $r->input('gol');

        $query = PemesananDarah::query()
            ->with('verifikasiTerakhir')
            ->latest();

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('nama_pasien', 'like', "%{$q}%")
                    ->orWhere('rs_pemesan', 'like', "%{$q}%");
            });
        }
        if ($st)  $query->where('status', $st);
        if ($gol) $query->where('gol_darah', $gol);

        $pemesanan = $query->paginate($per)->appends($r->query());

        return view('admin.verifikasi.index', compact('pemesanan'));
    }

    /**
     * Buat/catat verifikasi untuk 1 pemesanan + sinkronkan status pemesanan.
     * Route: POST /admin/verifikasi/{pemesanan}
     */
    public function store(Request $r, PemesananDarah $pemesanan)
    {
        $data = $r->validate([
            'status'             => ['required', 'in:pending,approved,rejected'],
            'tanggal_permintaan' => ['nullable', 'date'],
            'note'               => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($pemesanan, $data) {
            $tanggalPermintaan = $data['tanggal_permintaan']
                ?? ($pemesanan->tanggal_pemesanan ?? now()->toDateString());

            // Upsert verifikasi berdasarkan pemesanan_id
            VerifikasiPemesanan::updateOrCreate(
                ['pemesanan_id' => $pemesanan->id],
                [
                    'nama_pemesan'       => $pemesanan->nama_pasien,
                    'rs_pemesan'         => $pemesanan->rs_pemesan,
                    'gol_darah'          => $pemesanan->gol_darah,
                    'rhesus'             => $pemesanan->rhesus,
                    'produk'             => $pemesanan->produk,     // <- sudah 'produk'
                    'tanggal_permintaan' => $tanggalPermintaan,
                    'status'             => $data['status'],
                    // 'note'            => $data['note'] ?? null, // aktifkan jika kolom ada
                ]
            );

            // Sinkronkan status di tabel pemesanan
            $pemesanan->update(['status' => $data['status']]);

            // (Opsional) Sesuaikan stok jika approved
            // if ($data['status'] === 'approved') {
            //     app(\App\Services\StokDarahService::class)
            //         ->kurangi($pemesanan->produk, $pemesanan->gol_darah, (int) $pemesanan->jumlah_kantong);
            // }

            // Catat ke riwayat
            RiwayatPemesanan::create([
                'pemesanan_id'   => $pemesanan->id,
                'nama'           => $pemesanan->nama_pasien,
                'tanggal'        => now()->toDateString(),
                'gol_darah'      => $pemesanan->gol_darah ?? null,
                'rhesus'         => $pemesanan->rhesus,
                'jumlah_kantong' => $pemesanan->jumlah_kantong,
                'produk'         => $pemesanan->produk,
                'aksi'           => 'verifikasi: ' . $data['status'],
            ]);
        });

        return back()->with('success', 'Status verifikasi disimpan & status pemesanan disinkronkan.');
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
