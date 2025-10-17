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
     * Daftar pemesanan dengan verifikasi terakhir (untuk ringkas di tabel).
     */
    public function index()
    {
        // Gunakan relasi verifikasiTerakhir dari model PemesananDarah
        $pemesanan = PemesananDarah::with('verifikasiTerakhir')->latest()->paginate(12);
        return view('admin.verifikasi.index', compact('pemesanan'));

        $per = (int) $r->input('per_page', 10);
        $q   = $r->input('q');
        $st  = $r->input('status');
        $gol = $r->input('gol');

        $query = \App\Models\PemesananDarah::query()
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
            'status'              => ['required', 'in:pending,approved,rejected'],
            'tanggal_permintaan'  => ['nullable', 'date'],
            'note'                => ['nullable', 'string', 'max:1000'], // kalau mau simpan catatan (opsional)
        ]);

        DB::transaction(function () use ($pemesanan, $data) {
            // tanggal permintaan fallback ke tanggal pemesanan jika tidak diisi
            $tanggalPermintaan = $data['tanggal_permintaan'] ?? ($pemesanan->tanggal_pemesanan ?? now()->toDateString());

            // 1) Upsert verifikasi (satu baris per pemesanan — sesuai pola kodenya)
            $verif = VerifikasiPemesanan::updateOrCreate(
                ['pemesanan_id' => $pemesanan->id],
                [
                    'nama_pemesan'       => $pemesanan->nama_pasien,  // perbaikan: ambil dari field yg ada
                    'rs_pemesan'         => $pemesanan->rs_pemesan,
                    'golongan_darah'     => $pemesanan->gol_darah,
                    'rhesus'             => $pemesanan->rhesus,
                    'produk_darah'       => $pemesanan->produk,
                    'tanggal_permintaan' => $tanggalPermintaan,
                    'status'             => $data['status'],
                    // 'note'            => $data['note'] ?? null, // aktifkan bila kolom ada
                ]
            );

            // 2) Sinkronkan status utama di pemesanan
            $pemesanan->update(['status' => $data['status']]);

            // 3) (Opsional) sesuaikan stok jika status approved
            // if ($data['status'] === 'approved') {
            //     app(\App\Services\StokDarahService::class)
            //         ->kurangi($pemesanan->produk, $pemesanan->gol_darah, (int) $pemesanan->jumlah_kantong);
            // }

            // 4) Catat ke riwayat
            RiwayatPemesanan::create([
                'pemesanan_id'   => $pemesanan->id,
                'nama'           => $pemesanan->nama_pasien,
                'tanggal'        => now()->toDateString(),
                'gol_darah'      => $pemesanan->gol_darah ?? $pemesanan->gol_darah ?? null,
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
            // 1) Update entri verifikasi
            $verifikasi->update([
                'status' => $payload['status'],
                // 'note' => $payload['note'] ?? $verifikasi->note, // aktifkan bila kolom ada
            ]);

            // 2) Sinkronkan status pemesanan utamanya
            $verifikasi->pemesanan->update(['status' => $payload['status']]);

            // 3) Catat riwayat koreksi
            RiwayatPemesanan::create([
                'pemesanan_id'   => $verifikasi->pemesanan_id,
                'nama'           => $verifikasi->pemesanan->nama_pasien,
                'tanggal'        => now()->toDateString(),
                'gol_darah'      => $verifikasi->golongan_darah,
                'rhesus'         => $verifikasi->rhesus,
                'jumlah_kantong' => $verifikasi->pemesanan->jumlah_kantong,
                'produk'         => $verifikasi->produk_darah,
                'aksi'           => 'ubah status: ' . $payload['status'],
            ]);
        });

        return back()->with('success', 'Status verifikasi & pemesanan berhasil diperbarui.');
    }
}
