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
        // Ambil semua stok aktif (opsional: exclude kadaluarsa)
        $stok = StokDarah::query()
            // ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString())
            ->get();

        // Bentuk agregasi untuk tabel (per produk): { produk, A, AB, B, O }
        $rows = $this->aggregateRows($stok);

        return view('admin.stok.index', [
            'rowsJson' => $rows->values()->toJson(),
        ]);
    }

    public function store(StokDarahRequest $request)
    {
        $data = $request->validated();
        
        // Simpan batch
        $row = StokDarah::create($data);

        // Hitung agregasi terkini untuk produk yang sama (menghemat bandwidth)
        $rows = $this->aggregateRows(
            StokDarah::where('produk', $row->produk)->get()
        );

        $agg = $rows->firstWhere('produk', $row->produk);

        return response()->json([
            'ok'   => true,
            'item' => $row,
            'agg'  => $agg, // {produk, A, AB, B, O, total}
        ]);
    }

    /** @return \Illuminate\Support\Collection<int,array> */
    private function aggregateRows(Collection $stok)
    {
        // groupBy produk, lalu sum per gol
        $grouped = $stok->groupBy('produk')->map(function ($items, $produk) {
            $sumA  = (int)$items->where('gol','A')->sum('jumlah');
            $sumAB = (int)$items->where('gol','AB')->sum('jumlah');
            $sumB  = (int)$items->where('gol','B')->sum('jumlah');
            $sumO  = (int)$items->where('gol','O')->sum('jumlah');

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

