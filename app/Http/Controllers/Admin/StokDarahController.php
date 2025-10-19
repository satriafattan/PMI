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
        // Ambil semua stok (opsional: filter kadaluwarsa)
        $stok = StokDarah::query()
            // ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString())
            ->get();
            // Bentuk agregasi per produk
            $rows = $this->aggregateRows($stok);
        // dd($rows);

        return view('admin.stok.index', [
            'rows' => $rows, // Collection atau array
        ]);
    }

    public function store(StokDarahRequest $request)
    {
        // Simpan satu entri stok
        StokDarah::create($request->validated());

        // Monolith: redirect balik ke index + flash success
        return redirect()
            ->route('admin.stok.index')
            ->with('success', 'Stok berhasil disimpan.');
    }

    /** @return \Illuminate\Support\Collection<int,array> */
    private function aggregateRows(Collection $stok)
    {
        // groupBy produk, lalu sum per golongan
        $grouped = $stok->groupBy('produk')->map(function ($items, $produk) {
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
        });

        return $grouped->values();
    }
}

