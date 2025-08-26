<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PemesananDarah;
use App\Models\RiwayatPemesanan;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function index()
    {
        $items = PemesananDarah::latest()->paginate(12);
        return view('admin.pemesanan.index', compact('items'));
    }

    public function create()
    {
        return view('admin.pemesanan.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'tanggal_pemesanan'=>'required|date',
            'nama_pasien'=>'required|string|max:150',
            'nama_dokter'=>'required|string|max:150',
            'no_rekap_rs'=>'nullable|string|max:100',
            'no_regis_rs'=>'nullable|string|max:100',
            'nama_pemesan'=>'required|string|max:150',
            'rs_pemesan'=>'nullable|string|max:150',
            'tanggal_permintaan'=>'nullable|date',
            'gol_darah'=>'required|in:A,B,AB,O',
            'rhesus'=>'required|in:+,-',
            'produk'=>'required|string|max:100',
            'jumlah_kantong'=>'required|integer|min:1',
            'alasan_transfusi'=>'nullable|string',
            'gejala_transfusi'=>'nullable|string',
            'cek_transfusi'=>'nullable|boolean',
        ]);
        $data['cek_transfusi'] = (bool)($data['cek_transfusi'] ?? false);

        $p = PemesananDarah::create($data);

        RiwayatPemesanan::create([
            'pemesanan_id'=>$p->id,
            'nama'=>$p->nama_pasien,
            'tanggal'=>$p->tanggal_pemesanan,
            'gol_darah'=>$p->gol_darah,
            'rhesus'=>$p->rhesus,
            'jumlah_kantong'=>$p->jumlah_kantong,
            'produk'=>$p->produk,
            'aksi'=>'dibuat (admin)',
        ]);

        return redirect()->route('admin.pemesanan.index')->with('success','Pemesanan dibuat.');
    }

    public function edit(PemesananDarah $pemesanan)
    {
        return view('admin.pemesanan.edit', compact('pemesanan'));
    }

    public function update(Request $r, PemesananDarah $pemesanan)
    {
        $data = $r->validate([
            'tanggal_pemesanan'=>'required|date',
            'nama_pasien'=>'required|string|max:150',
            'nama_dokter'=>'required|string|max:150',
            'no_rekap_rs'=>'nullable|string|max:100',
            'no_regis_rs'=>'nullable|string|max:100',
            'nama_pemesan'=>'required|string|max:150',
            'rs_pemesan'=>'nullable|string|max:150',
            'tanggal_permintaan'=>'nullable|date',
            'gol_darah'=>'required|in:A,B,AB,O',
            'rhesus'=>'required|in:+,-',
            'produk'=>'required|string|max:100',
            'jumlah_kantong'=>'required|integer|min:1',
            'alasan_transfusi'=>'nullable|string',
            'gejala_transfusi'=>'nullable|string',
            'cek_transfusi'=>'nullable|boolean',
        ]);
        $data['cek_transfusi'] = (bool)($data['cek_transfusi'] ?? false);

        $pemesanan->update($data);

        RiwayatPemesanan::create([
            'pemesanan_id'=>$pemesanan->id,
            'nama'=>$pemesanan->nama_pasien,
            'tanggal'=>now()->toDateString(),
            'gol_darah'=>$pemesanan->gol_darah,
            'rhesus'=>$pemesanan->rhesus,
            'jumlah_kantong'=>$pemesanan->jumlah_kantong,
            'produk'=>$pemesanan->produk,
            'aksi'=>'diubah',
        ]);

        return back()->with('success','Pemesanan diperbarui.');
    }

    public function destroy(PemesananDarah $pemesanan)
    {
        $pemesanan->delete();
        RiwayatPemesanan::create([
            'pemesanan_id'=>null,
            'nama'=>$pemesanan->nama_pasien,
            'tanggal'=>now()->toDateString(),
            'gol_darah'=>$pemesanan->gol_darah,
            'rhesus'=>$pemesanan->rhesus,
            'jumlah_kantong'=>$pemesanan->jumlah_kantong,
            'produk'=>$pemesanan->produk,
            'aksi'=>'dihapus',
        ]);
        return back()->with('success','Pemesanan dihapus.');
    }
}
