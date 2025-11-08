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
        $stokProdukData = StokDarah::select('produk', DB::raw('SUM(jumlah) as total'))
            ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString())
            ->groupBy('produk')
            ->pluck('total', 'produk')
            ->toArray();

        // Urutkan sesuai label chart: WB, PRC, TC, FFP, CRYO, LP, TCA, CP
        $produkOrder = ['WB', 'PRC', 'TC', 'FFP', 'CRYO', 'LP', 'TCA', 'CP'];
        $stokProduk = array_map(fn($p) => $stokProdukData[$p] ?? 0, $produkOrder);

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

        // ===== DATA BARU UNTUK ANALYTICS =====

        // 1. Trend pemesanan 6 bulan terakhir (untuk line chart)
        $trendPemesanan = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = PemesananDarah::whereYear('tanggal_pemesanan', $month->year)
                ->whereMonth('tanggal_pemesanan', $month->month)
                ->count();
            $trendPemesanan->push([
                'month' => $month->format('M Y'),
                'count' => $count
            ]);
        }

        // 2. Top 5 Rumah Sakit Pemesan (bulan ini)
        $topHospitals = PemesananDarah::select('rs_pemesan', DB::raw('COUNT(*) as total'))
            ->where('tanggal_pemesanan', '>=', $bulanIni)
            ->groupBy('rs_pemesan')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 3. Rata-rata waktu verifikasi (dalam jam)
        $avgVerificationTime = DB::table('pemesanan_darah as p')
            ->join('verifikasi_pemesanan as v', 'p.id', '=', 'v.pemesanan_id')
            ->whereNotNull('v.created_at')
            ->where('v.status', '!=', 'pending')
            ->where('p.created_at', '>=', $bulanIni)
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, p.created_at, v.updated_at)) as avg_hours'))
            ->value('avg_hours');

        // 4. Distribusi Status Pemesanan (untuk pie chart)
        $statusDistribution = PemesananDarah::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // 5. Stock Alert Level (golongan darah dengan stok < 30)
        $stockAlerts = StokDarah::select('gol_darah', 'rhesus', DB::raw('SUM(jumlah) as total'))
            ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString())
            ->groupBy('gol_darah', 'rhesus')
            ->havingRaw('SUM(jumlah) < 30')
            ->orderBy('total')
            ->get();

        // 6. Produk Terlaris (bulan ini)
        $topProducts = RiwayatPemesanan::select('produk', DB::raw('SUM(jumlah_kantong) as total'))
            ->where('tanggal', '>=', $bulanIni)
            ->groupBy('produk')
            ->orderByDesc('total')
            ->limit(6)
            ->pluck('total', 'produk')
            ->toArray();

        return view('admin.dashboard', [
            'stats' => [
                'stok' => [
                    'A' => $stokPerGolongan->A ?? 0,
                    'AB' => $stokPerGolongan->AB ?? 0,
                    'B' => $stokPerGolongan->B ?? 0,
                    'O' => $stokPerGolongan->O ?? 0,
                ],
                'masuk' => [
                    'jumlah' => $masukBulanIni,
                    'trend' => $trendMasuk
                ],
                'keluar' => [
                    'jumlah' => $keluarBulanIni,
                    'trend' => $trendKeluar
                ],
                'stok_produk' => $stokProduk,
                'permintaan' => [
                    'total' => $totalPermintaan,
                    'diproses' => $permintaanDiproses
                ],
                'stok_kritis' => $stokKritis,
                'total_stok' => $totalStok,

                // Analytics baru
                'trend_pemesanan' => $trendPemesanan->toArray(),
                'top_hospitals' => $topHospitals,
                'avg_verification_hours' => round($avgVerificationTime ?? 0, 1),
                'status_distribution' => $statusDistribution,
                'stock_alerts' => $stockAlerts,
                'top_products' => $topProducts,
            ]
        ]);
    }
}
