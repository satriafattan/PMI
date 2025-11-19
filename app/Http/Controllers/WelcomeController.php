<?php

namespace App\Http\Controllers;

use App\Models\StokDarah;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    public function index(): View
    {
        // Ambil stok PRC per golongan darah yang belum kadaluwarsa
        $stok = StokDarah::where('produk', 'PRC')
            ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString()) // Filter expired
            ->where('jumlah', '>', 0) // Hanya yang masih ada stoknya
            ->select(
                DB::raw('SUM(CASE WHEN gol_darah = "A" THEN jumlah ELSE 0 END) as stokA'),
                DB::raw('SUM(CASE WHEN gol_darah = "B" THEN jumlah ELSE 0 END) as stokB'),
                DB::raw('SUM(CASE WHEN gol_darah = "AB" THEN jumlah ELSE 0 END) as stokAB'),
                DB::raw('SUM(CASE WHEN gol_darah = "O" THEN jumlah ELSE 0 END) as stokO')
            )
            ->first();

        return view('welcome', [
            'stokA' => $stok->stokA ?? 0,
            'stokB' => $stok->stokB ?? 0,
            'stokAB' => $stok->stokAB ?? 0,
            'stokO' => $stok->stokO ?? 0,
            'lastUpdated' => now()->timezone('Asia/Jakarta')->format('d M Y H:i') . ' WIB'
        ]);
    }
}
