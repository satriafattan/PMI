<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StokDarahRequest;
use App\Models\StokDarah;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class StokDarahController extends Controller
{
    public function index(Request $r)
    {
        $stok = StokDarah::query()
            // ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString())
            ->get();

        $rows = $this->aggregateRows($stok);

        return view('admin.stok.index', [
            'rows' => $rows, // dipakai JS di view admin
        ]);
    }

    public function store(StokDarahRequest $request)
    {
        StokDarah::create($request->validated());

        return redirect()
            ->route('admin.stok.index')
            ->with('success', 'Stok berhasil disimpan.');
    }

    /** Agregasi per produk */
    private function aggregateRows(Collection $stok): Collection
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
}
