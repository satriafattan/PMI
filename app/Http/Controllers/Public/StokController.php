<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\StokDarah;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    /** Halaman utama stok publik (invokable) */
    public function __invoke()
    {
        $stokAll      = StokDarah::query()->get();
        $komponenRows = $this->aggregateByProduk($stokAll)->toArray();
        $totalsGol    = $this->totalsByGolongan($stokAll);

        return view('public.stokdarah', [
            // untuk kartu (total per golongan)
            'stokA'  => $totalsGol['A'],
            'stokAB' => $totalsGol['AB'],
            'stokB'  => $totalsGol['B'],
            'stokO'  => $totalsGol['O'],

            // untuk tabel (agregat per produk)
            'komponenRows' => $komponenRows,
        ]);
    }

    /** Agregasi per produk (WB/PRC/TC/...) */
    private function aggregateByProduk(Collection $stok): Collection
    {
        return $stok->groupBy('produk')->map(function ($items, $produk) {
            $sumA  = (int)$items->where('gol_darah', 'A')->sum('jumlah');
            $sumAB = (int)$items->where('gol_darah', 'AB')->sum('jumlah');
            $sumB  = (int)$items->where('gol_darah', 'B')->sum('jumlah');
            $sumO  = (int)$items->where('gol_darah', 'O')->sum('jumlah');

            return [
                'produk' => $produk,
                'A'      => $sumA,
                'AB'     => $sumAB,
                'B'      => $sumB,
                'O'      => $sumO,
                'total'  => $sumA + $sumAB + $sumB + $sumO,
            ];
        })->values();
    }

    /** Total per golongan untuk kartu */
    private function totalsByGolongan(Collection $stok): array
    {
        return [
            'A'  => (int)$stok->where('gol_darah', 'A')->sum('jumlah'),
            'AB' => (int)$stok->where('gol_darah', 'AB')->sum('jumlah'),
            'B'  => (int)$stok->where('gol_darah', 'B')->sum('jumlah'),
            'O'  => (int)$stok->where('gol_darah', 'O')->sum('jumlah'),
        ];
    }

    /** API endpoint untuk mendapatkan stok per golongan */
    public function getStokGolongan()
    {
        // Ambil stok PRC per golongan darah (sama seperti di WelcomeController)
        $stok = StokDarah::where('produk', 'PRC')
            ->select(
                DB::raw('SUM(CASE WHEN gol_darah = "A" THEN jumlah ELSE 0 END) as stokA'),
                DB::raw('SUM(CASE WHEN gol_darah = "B" THEN jumlah ELSE 0 END) as stokB'),
                DB::raw('SUM(CASE WHEN gol_darah = "AB" THEN jumlah ELSE 0 END) as stokAB'),
                DB::raw('SUM(CASE WHEN gol_darah = "O" THEN jumlah ELSE 0 END) as stokO')
            )
            ->first();

        return response()->json([
            'success' => true,
            'stok' => [
                'A'  => (int)($stok->stokA ?? 0),
                'AB' => (int)($stok->stokAB ?? 0),
                'B'  => (int)($stok->stokB ?? 0),
                'O'  => (int)($stok->stokO ?? 0),
            ],
            'lastUpdated' => now()->translatedFormat('d M Y, H:i'),
        ]);
    }
}
