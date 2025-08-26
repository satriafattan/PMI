<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePemesananRequest;
use App\Models\PemesananDarah;
use App\Models\RiwayatPemesanan;

class PublicPemesananController extends Controller
{
    public function create()
    {
        return view('public.pemesanan.create');
    }

    public function store(StorePemesananRequest $r)
    {
        $data = $r->validated();

        // Jika tanggal_pemesanan tidak diisi di UI, pakai hari ini
        $data['tanggal_pemesanan'] = $data['tanggal_pemesanan'] ?? now()->toDateString();

        // Pastikan boolean
        $data['cek_transfusi'] = (bool)($data['cek_transfusi'] ?? false);

        // OPTIONAL: mapping field UI → kolom yang ada
        // Contoh: kalau kamu mau simpan 'diagnosa_klinik' ke 'alasan_transfusi'
        if (!empty($data['diagnosa_klinik']) && empty($data['alasan_transfusi'])) {
            $data['alasan_transfusi'] = $data['diagnosa_klinik'];
        }
        $data = $r->validated();

            // default tanggal jika kosong
            $data['tanggal_pemesanan'] = $data['tanggal_pemesanan'] ?? now()->toDateString();

            // gabungkan input multipilih -> kolom yang ada
            $data['produk'] = implode(', ', $data['produk_multi']);                // "Segar, Biasa"
            $data['alasan_transfusi'] = implode('; ', $data['alasan_multi']);      // "Plasma Biasa; FFP ..."
            unset($data['produk_multi'], $data['alasan_multi']);

            // boolean
            $data['cek_transfusi'] = (bool)($data['cek_transfusi'] ?? false);

            // simpan (pastikan $fillable model sudah benar)
        

        // Field tambahan (jenis_kelamin, data khusus wanita) tidak ada kolomnya:
        // biarkan tetap di $data tapi tidak akan dipakai oleh create() jika tidak ada di $fillable.
        // Alternatif: simpan ke metadata terpisah jika kamu punya kolom JSON.

        $p = PemesananDarah::create($data);

        RiwayatPemesanan::create([
            'pemesanan_id'   => $p->id,
            'nama'           => $p->nama_pasien,
            'tanggal'        => $p->tanggal_pemesanan,
            'gol_darah'      => $p->gol_darah,
            'rhesus'         => $p->rhesus,
            'jumlah_kantong' => $p->jumlah_kantong,
            'produk'         => $p->produk,
            'aksi'           => 'dibuat (public)',
        ]);

        return redirect()
            ->route('pemesanan.create')
            ->with('success','Pemesanan berhasil dikirim. Petugas akan memverifikasi.');
    }
}
