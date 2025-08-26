<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PemesananDarah;
use App\Models\VerifikasiPemesanan;
use App\Models\RiwayatPemesanan;
use Illuminate\Http\Request;

class VerifikasiPemesananController extends Controller
{
    public function index()
    {
        $pemesanan = PemesananDarah::with('verifikasi')->latest()->paginate(12);
        return view('admin.verifikasi.index', compact('pemesanan'));
    }

    // buat catatan verifikasi untuk pemesanan tertentu
    public function store(Request $r, PemesananDarah $pemesanan)
    {
        $data = $r->validate([
            'status' => ['required','in:pending,approved,rejected'],
        ]);
        $verif = VerifikasiPemesanan::updateOrCreate(
            ['pemesanan_id'=>$pemesanan->id],
            [
                'nama_pemesan' => $pemesanan->nama_pemesan,
                'rs_pemesan'   => $pemesanan->rs_pemesan,
                'golongan_darah'=> $pemesanan->gol_darah,
                'rhesus'        => $pemesanan->rhesus,
                'produk_darah'  => $pemesanan->produk,
                'tanggal_permintaan'=> $pemesanan->tanggal_permintaan ?? $pemesanan->tanggal_pemesanan,
                'status' => $data['status'],
            ]
        );

        RiwayatPemesanan::create([
            'pemesanan_id'   => $pemesanan->id,
            'nama'           => $pemesanan->nama_pasien,
            'tanggal'        => now()->toDateString(),
            'gol_darah'      => $pemesanan->gol_darah,
            'rhesus'         => $pemesanan->rhesus,
            'jumlah_kantong' => $pemesanan->jumlah_kantong,
            'produk'         => $pemesanan->produk,
            'aksi'           => 'verifikasi: '.$data['status'],
        ]);

        return back()->with('success','Status verifikasi disimpan.');
    }

    public function updateStatus(Request $r, VerifikasiPemesanan $verifikasi)
    {
        $r->validate(['status'=>['required','in:pending,approved,rejected']]);
        $verifikasi->update(['status'=>$r->input('status')]);
        RiwayatPemesanan::create([
            'pemesanan_id'=>$verifikasi->pemesanan_id,
            'nama'=>$verifikasi->pemesanan->nama_pasien,
            'tanggal'=>now()->toDateString(),
            'gol_darah'=>$verifikasi->golongan_darah,
            'rhesus'=>$verifikasi->rhesus,
            'jumlah_kantong'=>$verifikasi->pemesanan->jumlah_kantong,
            'produk'=>$verifikasi->produk_darah,
            'aksi'=>'ubah status: '.$r->input('status'),
        ]);
        return back()->with('success','Status diperbarui.');
    }
}
