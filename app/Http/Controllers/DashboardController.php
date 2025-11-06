<?php

namespace App\Http\Controllers;

use App\Models\StokDarah;
use App\Models\RiwayatPemesanan;
use App\Models\PemesananDarah;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni = Carbon::now()->startOfMonth();
        $bulanLalu = Carbon::now()->subMonth()->startOfMonth();

        // OPTIMASI: Gabungkan query stok dalam 1 query
        $stokPerGolongan = StokDarah::select(
            DB::raw('SUM(CASE WHEN gol_darah = "A" THEN jumlah ELSE 0 END) as A'),
            DB::raw('SUM(CASE WHEN gol_darah = "B" THEN jumlah ELSE 0 END) as B'),
            DB::raw('SUM(CASE WHEN gol_darah = "AB" THEN jumlah ELSE 0 END) as AB'),
            DB::raw('SUM(CASE WHEN gol_darah = "O" THEN jumlah ELSE 0 END) as O')
        )
            ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString()) // Hanya hitung stok yang belum expired
            ->first();

        // OPTIMASI: Gabungkan query stok masuk bulan ini dan lalu dalam 1 query
        $stokMasuk = StokDarah::selectRaw(
            'SUM(CASE WHEN tgl_masuk >= ? THEN jumlah ELSE 0 END) as bulan_ini,
             SUM(CASE WHEN tgl_masuk >= ? AND tgl_masuk < ? THEN jumlah ELSE 0 END) as bulan_lalu',
            [$bulanIni, $bulanLalu, $bulanIni]
        )
            ->where('tgl_masuk', '>=', $bulanLalu)
            ->first();

        $masukBulanIni = $stokMasuk->bulan_ini ?? 0;
        $masukBulanLalu = $stokMasuk->bulan_lalu ?? 0;

        // OPTIMASI: Gabungkan query riwayat keluar dalam 1 query
        $riwayatKeluar = RiwayatPemesanan::selectRaw(
            'SUM(CASE WHEN tanggal >= ? THEN jumlah_kantong ELSE 0 END) as bulan_ini,
             SUM(CASE WHEN tanggal >= ? AND tanggal < ? THEN jumlah_kantong ELSE 0 END) as bulan_lalu',
            [$bulanIni, $bulanLalu, $bulanIni]
        )
            ->where('tanggal', '>=', $bulanLalu)
            ->where('aksi', 'like', '%verifikasi: approved%')
            ->first();

        $keluarBulanIni = $riwayatKeluar->bulan_ini ?? 0;
        $keluarBulanLalu = $riwayatKeluar->bulan_lalu ?? 0;

        // Menghitung trend (persentase perubahan)
        $trendMasuk = $masukBulanLalu > 0 ? round((($masukBulanIni - $masukBulanLalu) / $masukBulanLalu) * 100) : null;
        $trendKeluar = $keluarBulanLalu > 0 ? round((($keluarBulanIni - $keluarBulanLalu) / $keluarBulanLalu) * 100) : null;

        // OPTIMASI: Data untuk grafik stok per produk (hanya yang belum expired)
        $stokProduk = StokDarah::select('produk', DB::raw('SUM(jumlah) as total'))
            ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString())
            ->groupBy('produk')
            ->pluck('total', 'produk')
            ->toArray();

        // OPTIMASI: Gabungkan query statistik tambahan dalam 1 query
        $statsPemesanan = PemesananDarah::select(
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending')
        )->first();

        // OPTIMASI: Query stok kritis dan total dalam 1 query
        $statsStok = StokDarah::select(
            DB::raw('SUM(jumlah) as total'),
            DB::raw('SUM(CASE WHEN jumlah < 20 THEN 1 ELSE 0 END) as kritis')
        )
            ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString())
            ->first();

        $totalPermintaan = $statsPemesanan->total ?? 0;
        $permintaanDiproses = $statsPemesanan->pending ?? 0;
        $stokKritis = $statsStok->kritis ?? 0;
        $totalStok = $statsStok->total ?? 0;

        return view('admin.dashboard', [
            'stats' => [
                'stok' => $stokPerGolongan,
                'masuk' => [
                    'jumlah' => $masukBulanIni,
                    'trend' => $trendMasuk
                ],
                'keluar' => [
                    'jumlah' => $keluarBulanIni,
                    'trend' => $trendKeluar
                ],
                'stok_produk' => array_values($stokProduk),
                'permintaan' => [
                    'total' => $totalPermintaan,
                    'diproses' => $permintaanDiproses
                ],
                'stok_kritis' => $stokKritis,
                'total_stok' => $totalStok
            ]
        ]);
    }
}
