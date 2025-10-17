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
        // 1) data tervalidasi
        $data = $r->validated();

        // 2) tanggal default
        $data['tanggal_pemesanan'] = $data['tanggal_pemesanan'] ?? now()->toDateString();

        // 3) mapping nama field (request -> kolom DB)
        //    Form minta "tanggal_diperlukan", di DB kolomnya "tanggal_permintaan"
        if (!empty($data['tanggal_diperlukan'])) {
            $data['tanggal_permintaan'] = $data['tanggal_diperlukan'];
            unset($data['tanggal_diperlukan']);
        }

        // 4) gabung multi-value -> single column
        $produkMulti = $data['produk_multi'] ?? [];
        $alasanMulti = $data['alasan_multi'] ?? [];

        if (is_array($produkMulti) && count($produkMulti)) {
            $data['produk'] = implode(', ', $produkMulti);
        } else {
            $data['produk'] = $data['produk'] ?? null; // pastikan rules menutup ini agar tidak null
        }

        if (is_array($alasanMulti) && count($alasanMulti)) {
            $data['alasan_transfusi'] = implode('; ', $alasanMulti);
        } elseif (!empty($data['diagnosa_klinik']) && empty($data['alasan_transfusi'])) {
            $data['alasan_transfusi'] = $data['diagnosa_klinik'];
        }

        // bersihkan key non-kolom
        unset($data['produk_multi'], $data['alasan_multi']);

        // 5) boolean normalize
        $data['cek_transfusi'] = (bool)($data['cek_transfusi'] ?? false);

        // 6) status + kode unik (kalau memang ada kolom "kode" di tabel & fillable)
        $data['status'] = $data['status'] ?? 'pending';
        // Jika kamu punya kolom "kode" di tabel:
        // $data['kode'] = $data['kode'] ?? Str::upper(Str::random(8));

        // --- FAIL FAST untuk email (agar errornya jelas di level validasi, bukan SQL) ---
        if (empty($data['email'])) {
            return back()->withErrors(['email' => 'Email wajib diisi.'])->withInput();
        }

        // 7) Simpan atomic
        /** @var PemesananDarah $order */
        $order = DB::transaction(function () use ($data) {
            $order = PemesananDarah::create($data);

            // RiwayatPemesanan: jangan pakai $order->nama_pasien jika kolom itu tidak ada
            // RiwayatPemesanan::create([
            //     'pemesanan_id'   => $order->id,
            //     'nama'           => $order->nama_pemesan, // pakai nama_pemesan yang memang ada
            //     'tanggal'        => $order->tanggal_pemesanan,
            //     'gol_darah'      => $order->gol_darah,
            //     'rhesus'         => $order->rhesus,
            //     'jumlah_kantong' => $order->jumlah_kantong,
            //     'produk'         => $order->produk,
            //     'aksi'           => 'dibuat (public)',
            // ]);

            return $order;
        });

        // 8) Redirect
        // Jika kamu TIDAK punya kolom "kode", jangan pakai ->kode di route
        // return redirect()->route('pemesanan.konfirmasi', $order->id);
        // Jika kamu ADA kolom "kode":
        // return redirect()->route('pemesanan.konfirmasi', $order->kode)
        //                  ->with('success', 'Pemesanan Anda sedang diproses. Simpan kode: '.$order->kode);

        return redirect()
            ->route('pemesanan.create')
            ->with('success', 'Pemesanan Anda sedang diproses.');
            
    }



    /**
     * Halaman konfirmasi (menampilkan ringkasan pemesanan berbasis kode).
     * Public dapat memantau status: pending/approved/rejected
     */
    public function konfirmasi(int $id)
    {
        $order = PemesananDarah::findOrFail($id);
        return view('public.pemesanan.konfirmasi', compact('order'));
    }


    /**
     * Generator kode unik: PMI-yymmdd-ABCDE, retry jika bentrok.
     */
    // private function generateKodeUnik(): string
    // {
    //     do {
    //         $kode = 'PMI-' . now()->format('ymd') . '-' . strtoupper(Str::random(5));
    //     } while (PemesananDarah::where('kode', $kode)->exists());

    //     return $kode;
    // }
}
