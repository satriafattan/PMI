<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StokDarah;
use App\Models\RiwayatPemesanan;
use App\Models\PemesananDarah;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now       = Carbon::now();
        $first     = $now->copy()->startOfMonth();
        $prevFirst = $now->copy()->subMonth()->startOfMonth();
        $prevEnd   = $now->copy()->subMonth()->endOfMonth();

        // --- Stok per golongan (yang belum kadaluarsa)
        $stokByGol = StokDarah::select('gol_darah', DB::raw('SUM(jumlah) as total'))
            ->whereDate('tgl_kadaluarsa', '>=', $now->toDateString())
            ->groupBy('gol_darah')
            ->pluck('total', 'gol_darah')
            ->all();

        $stok = [
            'A'  => $stokByGol['A']  ?? 0,
            'AB' => $stokByGol['AB'] ?? 0,
            'B'  => $stokByGol['B']  ?? 0,
            'O'  => $stokByGol['O']  ?? 0,
        ];

        // --- Darah MASUK (dari stok_darah.tgl_masuk)
        $masukNow = StokDarah::whereDate('tgl_masuk', '>=', $first)
            ->whereDate('tgl_masuk', '<=', $now)
            ->sum('jumlah');

        $masukPrev = StokDarah::whereDate('tgl_masuk', '>=', $prevFirst)
            ->whereDate('tgl_masuk', '<=', $prevEnd)
            ->sum('jumlah');

        // --- Darah KELUAR (riwayat_pemesanan; aksi mengandung "approved")
        $keluarNow = RiwayatPemesanan::whereDate('tanggal', '>=', $first)
            ->whereDate('tanggal', '<=', $now)
            ->where('aksi', 'like', '%approved%')
            ->sum('jumlah_kantong');

        $keluarPrev = RiwayatPemesanan::whereDate('tanggal', '>=', $prevFirst)
            ->whereDate('tanggal', '<=', $prevEnd)
            ->where('aksi', 'like', '%approved%')
            ->sum('jumlah_kantong');

        // --- Tren %
        $trend = fn($cur, $prev) => $prev == 0 ? null : round((($cur - $prev) / $prev) * 100, 1);

        // --- Stok per produk (untuk chart)
        $produkOrder = ['WB', 'PRC', 'TC', 'FFP', 'AHF', 'LP'];
        $byProduk = StokDarah::select('produk', DB::raw('SUM(jumlah) as total'))
            ->groupBy('produk')->pluck('total', 'produk')->all();

        $stokProduk = array_map(fn($p) => $byProduk[$p] ?? 0, $produkOrder);

        // --- Permintaan (sesuaikan status bila perlu)
        $totalPermintaan = PemesananDarah::count();
        $diproses        = PemesananDarah::whereIn('status', ['diproses', 'approved'])->count();

        $stats = [
            'stok'        => $stok,
            'masuk'       => ['jumlah' => $masukNow,  'trend' => $trend($masukNow,  $masukPrev)],
            'keluar'      => ['jumlah' => $keluarNow, 'trend' => $trend($keluarNow, $keluarPrev)],
            'stok_produk' => $stokProduk,
            'permintaan'  => ['total' => $totalPermintaan, 'diproses' => $diproses],
            'stok_kritis' => 0,
            'total_stok'  => array_sum($stok),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
