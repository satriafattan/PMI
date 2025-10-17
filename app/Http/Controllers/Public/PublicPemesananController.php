<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePemesananRequest;
use App\Models\PemesananDarah;
use App\Models\RiwayatPemesanan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PublicPemesananController extends Controller
{
    /**
     * Tampilkan form pemesanan untuk user publik.
     */
    public function create()
    {
        // contoh: resources/views/public/pemesanan/create.blade.php
        return view('public.pemesanan.create');
    }

    /**
     * Simpan pemesanan, buat kode unik, catat riwayat, lalu redirect ke halaman konfirmasi.
     */
    public function store(StorePemesananRequest $r)
    {
        // 1) data tervalidasi dari FormRequest
        dd('masuk store'); // kalau ini pun tidak muncul, berarti belum masuk method
        $data = $r->validated();
        dd($data);

        // dd($order);
        // 2) default tanggal pemesanan kalau tidak diisi UI
        $data['tanggal_pemesanan'] = $data['tanggal_pemesanan'] ?? now()->toDateString();

        // 3) gabungkan input multipilih (kalau ada) → kolom yang tersedia di tabel
        $produkMulti = $data['produk_multi'] ?? [];
        $alasanMulti = $data['alasan_multi'] ?? [];

        if (is_array($produkMulti) && count($produkMulti)) {
            $data['produk'] = implode(', ', $produkMulti);
        } else {
            // pakai single value bila ada
            $data['produk'] = $data['produk'] ?? null;
        }

        if (is_array($alasanMulti) && count($alasanMulti)) {
            $data['alasan_transfusi'] = implode('; ', $alasanMulti);
        } elseif (!empty($data['diagnosa_klinik']) && empty($data['alasan_transfusi'])) {
            // fallback: jika alasan kosong, pakai diagnosa_klinik
            $data['alasan_transfusi'] = $data['diagnosa_klinik'];
        }

        // bersihkan key yang tidak ada kolomnya
        unset($data['produk_multi'], $data['alasan_multi']);

        // 4) boolean
        $data['cek_transfusi'] = (bool)($data['cek_transfusi'] ?? false);

        // 5) status + kode unik
        $data['status'] = $data['status'] ?? 'pending';
        $data['kode']   = $data['kode'] ?? $this->generateKodeUnik();

        // 6) Simpan atomic: order + riwayat
        /** @var PemesananDarah $order */
        $order = DB::transaction(function () use ($data) {
            // simpan order
            $order = PemesananDarah::create($data);

            // simpan riwayat
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

            return $order;
        });

        

        // 7) redirect ke halaman konfirmasi (tracking by kode)
        // return redirect()
        //     ->route('pemesanan.konfirmasi', $order->kode)
        //     ->with('success', 'Pemesanan Anda sedang diproses. Simpan kode: '.$order->kode);
    }

    /**
     * Halaman konfirmasi (menampilkan ringkasan pemesanan berbasis kode).
     * Public dapat memantau status: pending/approved/rejected
     */
    public function konfirmasi(string $kode)
    {
        // load relasi verifikasi terbaru jika diperlukan di UI:
        // ->with('verifikasiTerakhir.verifier')  (opsional, jika kamu tambahkan relasi itu di model)
        $order = PemesananDarah::where('kode', $kode)->firstOrFail();

        return view('public.pemesanan.konfirmasi', compact('order'));
    }

    /**
     * Generator kode unik: PMI-yymmdd-ABCDE, retry jika bentrok.
     */
    private function generateKodeUnik(): string
    {
        do {
            $kode = 'PMI-' . now()->format('ymd') . '-' . strtoupper(Str::random(5));
        } while (PemesananDarah::where('kode', $kode)->exists());

        return $kode;
    }
}
