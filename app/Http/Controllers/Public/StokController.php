<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\StokDarah;
use Illuminate\Support\Collection;

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
}
