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
        // Mengambil data stok per golongan darah
        $stokPerGolongan = StokDarah::select(
            DB::raw('SUM(CASE WHEN gol_darah = "A" THEN jumlah ELSE 0 END) as A'),
            DB::raw('SUM(CASE WHEN gol_darah = "B" THEN jumlah ELSE 0 END) as B'),
            DB::raw('SUM(CASE WHEN gol_darah = "AB" THEN jumlah ELSE 0 END) as AB'),
            DB::raw('SUM(CASE WHEN gol_darah = "O" THEN jumlah ELSE 0 END) as O')
        )->first();

        // Data untuk statistik darah masuk
        $bulanIni = Carbon::now()->startOfMonth();
        $bulanLalu = Carbon::now()->subMonth()->startOfMonth();

        $masukBulanIni = StokDarah::where('tgl_masuk', '>=', $bulanIni)->sum('jumlah');
        $masukBulanLalu = StokDarah::whereBetween('tgl_masuk', [$bulanLalu, $bulanIni])->sum('jumlah');

        // Data untuk statistik darah keluar
        $keluarBulanIni = RiwayatPemesanan::where('tanggal', '>=', $bulanIni)
            ->where('aksi', 'keluar')
            ->sum('jumlah_kantong');
        $keluarBulanLalu = RiwayatPemesanan::whereBetween('tanggal', [$bulanLalu, $bulanIni])
            ->where('aksi', 'keluar')
            ->sum('jumlah_kantong');

        // Menghitung trend (persentase perubahan)
        $trendMasuk = $masukBulanLalu > 0 ? round((($masukBulanIni - $masukBulanLalu) / $masukBulanLalu) * 100) : null;
        $trendKeluar = $keluarBulanLalu > 0 ? round((($keluarBulanIni - $keluarBulanLalu) / $keluarBulanLalu) * 100) : null;

        // Data untuk grafik stok per produk
        $stokProduk = StokDarah::select('produk', DB::raw('SUM(jumlah) as total'))
            ->groupBy('produk')
            ->pluck('total', 'produk')
            ->toArray();

        // Data statistik tambahan
        $totalPermintaan = PemesananDarah::count();
        $permintaanDiproses = PemesananDarah::where('status', 'diproses')->count();
        $stokKritis = StokDarah::where('jumlah', '<', 20)->count();
        $totalStok = StokDarah::sum('jumlah');

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
