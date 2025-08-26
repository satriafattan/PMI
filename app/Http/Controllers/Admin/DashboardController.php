<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekapStok;
use App\Models\VerifikasiPemesanan;
use App\Models\PemesananDarah;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // stok tersedia per golongan (tanggal_keluar NULL)
        $stok = RekapStok::select('gol_darah', DB::raw('count(*) as total'))
            ->whereNull('tanggal_keluar')
            ->groupBy('gol_darah')
            ->pluck('total','gol_darah'); // ['A'=>85, 'AB'=>42, ...]

        // darah masuk/keluar per bulan (berdasar rekap_stok)
        $now = Carbon::now();
        $first = $now->copy()->startOfMonth();
        $prevFirst = $now->copy()->subMonth()->startOfMonth();
        $prevEnd   = $now->copy()->subMonth()->endOfMonth();

        $masuk_bulan_ini  = RekapStok::whereBetween('tanggal_masuk', [$first, $now])->count();
        $masuk_bulan_lalu = RekapStok::whereBetween('tanggal_masuk', [$prevFirst, $prevEnd])->count();

        $keluar_bulan_ini  = RekapStok::whereBetween('tanggal_keluar', [$first, $now])->count();
        $keluar_bulan_lalu = RekapStok::whereBetween('tanggal_keluar', [$prevFirst, $prevEnd])->count();

        // hitung tren (%)
        $trend = function($current, $prev) {
            if ($prev == 0) return null;
            return round((($current - $prev) / $prev) * 100, 1);
        };

        $stats = [
            'stok' => [
                'A'  => $stok['A']  ?? 0,
                'AB' => $stok['AB'] ?? 0,
                'B'  => $stok['B']  ?? 0,
                'O'  => $stok['O']  ?? 0,
            ],
            'masuk' => [
                'jumlah' => $masuk_bulan_ini,
                'trend'  => $trend($masuk_bulan_ini, $masuk_bulan_lalu),
            ],
            'keluar' => [
                'jumlah' => $keluar_bulan_ini,
                'trend'  => $trend($keluar_bulan_ini, $keluar_bulan_lalu),
            ],
            'menunggu_verifikasi' => VerifikasiPemesanan::where('status','pending')->count(),
            'total_pemesanan'     => PemesananDarah::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}