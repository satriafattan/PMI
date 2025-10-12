<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePemesananRequest;
use App\Models\PemesananDarah;
use App\Models\RiwayatPemesanan;
use Illuminate\Support\Str;

class PublicPemesananController extends Controller
{
    /**
     * Tampilkan form pemesanan untuk user publik.
     */
    public function create()
    {
        // sesuaikan dengan view form kamu saat ini
        // contoh: resources/views/public/pemesanan/create.blade.php
        return view('public.pemesanan.create');
    }

    /**
     * Simpan pemesanan, buat kode unik, catat riwayat, lalu redirect ke halaman konfirmasi.
     */
    public function store(StorePemesananRequest $r)
    {
        // 1) data tervalidasi dari FormRequest
        $data = $r->validated();

        // 2) default tanggal pemesanan kalau tidak diisi UI
        $data['tanggal_pemesanan'] = $data['tanggal_pemesanan'] ?? now()->toDateString();

        // 3) gabungkan input multipilih (kalau ada) → kolom yang tersedia
        $produkMulti = $data['produk_multi'] ?? [];
        $alasanMulti = $data['alasan_multi'] ?? [];

        if (is_array($produkMulti) && count($produkMulti)) {
            $data['produk'] = implode(', ', $produkMulti); // contoh: "Segar, Biasa"
        } else {
            // biarkan null jika tidak ada
            $data['produk'] = $data['produk'] ?? null;
        }

        if (is_array($alasanMulti) && count($alasanMulti)) {
            $data['alasan_transfusi'] = implode('; ', $alasanMulti); // contoh: "Plasma Biasa; FFP ..."
        } elseif (!empty($data['diagnosa_klinik']) && empty($data['alasan_transfusi'])) {
            // fallback kalau mau pakai diagnosa_klinik
            $data['alasan_transfusi'] = $data['diagnosa_klinik'];
        }

        unset($data['produk_multi'], $data['alasan_multi']); // tidak ada kolom di DB

        // 4) boolean
        $data['cek_transfusi'] = (bool)($data['cek_transfusi'] ?? false);

        // 5) status + kode unik
        $data['status'] = $data['status'] ?? 'pending';
        $data['kode']   = 'PMI-' . now()->format('ymd') . '-' . strtoupper(Str::random(5));

        // 6) simpan (pastikan $fillable di model sudah sesuai)
        $order = PemesananDarah::create($data);

        // 7) simpan riwayat
        RiwayatPemesanan::create([
            'pemesanan_id'   => $order->id,
            'nama'           => $order->nama_pasien,
            'tanggal'        => $order->tanggal_pemesanan,
            'gol_darah'      => $order->gol_darah ?? null,
            'rhesus'         => $order->rhesus ?? null,
            'jumlah_kantong' => $order->jumlah_kantong ?? null,
            'produk'         => $order->produk ?? null,
            'aksi'           => 'dibuat (public)',
        ]);

        // 8) redirect ke halaman konfirmasi
        return redirect()
            ->route('pemesanan.create')
            ->with('success', 'Pemesanan Anda sedang diproses. Silakan cek email secara berkala.');
    }

    /**
     * Halaman konfirmasi (menampilkan ringkasan pemesanan berbasis kode).
     */
    public function konfirmasi(string $kode)
    {
        $order = PemesananDarah::where('kode', $kode)->firstOrFail();

        return view('public.pemesanan.konfirmasi', compact('order'));
    }
}
