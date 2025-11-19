<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePemesananRequest;
use App\Models\PemesananDarah;
use App\Models\RiwayatPemesanan;
use App\Events\PemesananBaruEvent;
use Illuminate\Support\Facades\DB;

class PublicPemesananController extends Controller
{
    /**
     * Tampilkan form pemesanan untuk user publik.
     */
    public function create()
    {
        return view('public.pemesanan.create');
    }

    /**
     * Simpan pemesanan, catat riwayat, lalu redirect.
     *
     * Catatan:
     * - Normalisasi & validasi tanggal sudah ditangani di StorePemesananRequest.
     * - Model melakukan cast date untuk tanggal_* sehingga di Blade aman.
     */
    public function store(StorePemesananRequest $r)
    {
        // Ambil data tervalidasi (sudah dinormalisasi di prepareForValidation)
        $data = $r->validated();

        // Pastikan alasan_tambahan berupa string (bukan array) & rapi
        // (sebenarnya rules sudah 'nullable|string|max:255', ini hanya pengaman tambahan)
        $data['alasan_tambahan'] = isset($data['alasan_tambahan'])
            ? trim((string) $data['alasan_tambahan'])
            : null;

        // Default tanggal_pemesanan bila kosong
        if (empty($data['tanggal_pemesanan'])) {
            $data['tanggal_pemesanan'] = now()->toDateString();
        }

        if (empty($data['tanggal_permintaan'])) {
            $data['tanggal_permintaan'] = $data['tanggal_pemesanan'];
        }

        // Legacy support: jika UI lama masih kirim checkbox alasan_multi (array),
        // gabungkan ke alasan_transfusi agar tidak hilang informasinya.
        if ($r->has('alasan_multi') && is_array($r->input('alasan_multi'))) {
            $join = array_filter(array_map('trim', $r->input('alasan_multi')));
            if (!empty($join)) {
                $prefix = !empty($data['alasan_transfusi']) ? ($data['alasan_transfusi'] . '; ') : '';
                $data['alasan_transfusi'] = $prefix . implode('; ', $join);
            }
        }

        // Normalisasi boolean (backup — sudah dilakukan di FormRequest, aman kalau dobel)
        $data['cek_transfusi'] = (bool) ($data['cek_transfusi'] ?? false);

        // Status default
        $data['status'] = $data['status'] ?? 'pending';

        /** @var PemesananDarah $order */
        $order = DB::transaction(function () use ($data) {
            // Simpan pemesanan
            $order = PemesananDarah::create($data);

            // Catat riwayat awal (opsional)
            if (class_exists(RiwayatPemesanan::class)) {
                RiwayatPemesanan::create([
                    'pemesanan_id' => $order->id,
                    'nama' => $order->nama_pasien,
                    'tanggal' => $order->tanggal_pemesanan,
                    'gol_darah' => $order->gol_darah,
                    'rhesus' => $order->rhesus,
                    'jumlah_kantong' => $order->jumlah_kantong,
                    'produk' => $order->produk,
                    'aksi' => 'dibuat (public)',
                ]);
            }

            return $order;
        });

        // 🔔 BROADCAST: Notifikasi real-time ke admin
        event(new PemesananBaruEvent($order));

        return redirect()
            ->route('pemesanan.create')
            ->with('success', 'Pemesanan Anda sedang diproses.');
    }

    /**
     * Halaman konfirmasi (menampilkan ringkasan pemesanan).
     */
    public function konfirmasi(int $id)
    {
        $order = PemesananDarah::findOrFail($id);
        return view('public.pemesanan.konfirmasi', compact('order'));
    }
}
