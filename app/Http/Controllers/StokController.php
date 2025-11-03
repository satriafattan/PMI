<?php

namespace App\Http\Controllers;

use App\Models\StokDarah;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class StokController extends Controller
{
    public function getStokGolongan(): JsonResponse
    {
        $stok = StokDarah::where('produk', 'PRC')
            ->select(
                DB::raw('SUM(CASE WHEN gol_darah = "A" THEN jumlah ELSE 0 END) as A'),
                DB::raw('SUM(CASE WHEN gol_darah = "B" THEN jumlah ELSE 0 END) as B'),
                DB::raw('SUM(CASE WHEN gol_darah = "AB" THEN jumlah ELSE 0 END) as AB'),
                DB::raw('SUM(CASE WHEN gol_darah = "O" THEN jumlah ELSE 0 END) as O')
            )->first();

        return response()->json([
            'stok' => [
                'A' => $stok->A ?? 0,
                'B' => $stok->B ?? 0,
                'AB' => $stok->AB ?? 0,
                'O' => $stok->O ?? 0,
            ],
            'lastUpdated' => now()->format('d M Y H:i')
        ]);
    }
}
