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
        // Ambil semua stok (urutkan agar enak dibaca)
        $stok = StokDarah::query()
            // ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString())
            ->orderBy('produk')
            ->orderBy('gol_darah')
            ->orderBy('tgl_kadaluarsa')
            ->get();

        // Ringkasan per produk
        $summary = $this->aggregateRows($stok);

        // Detail dikelompokkan per produk (langsung kirim modelnya)
        $grouped = $stok->groupBy('produk');

        // Riwayat stok untuk modal
        $riwayat = $stok->groupBy(function ($item) {
            return $item->produk . '_' . $item->gol_darah;
        })->map(function ($items) {
            return $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'jumlah' => $item->jumlah,
                    'rhesus' => $item->rhesus,
                    'tgl_masuk' => $item->tgl_masuk->format('Y-m-d'),
                    'tgl_kadaluarsa' => $item->tgl_kadaluarsa->format('Y-m-d'),
                    'created_at' => $item->created_at->format('Y-m-d H:i')
                ];
            })->sortByDesc('created_at')->values();
        });

        // Total keseluruhan 
        $grandTotal = (int) $summary->sum('total');

        return view('admin.stok.index', compact('summary', 'grouped', 'grandTotal', 'riwayat'));
    }

    private function aggregateRows(Collection $stok): Collection
    {
        return $stok->groupBy('produk')->map(function ($items, $produk) {
            $sumA  = (int) $items->where('gol_darah', 'A')->sum('jumlah');
            $sumAB = (int) $items->where('gol_darah', 'AB')->sum('jumlah');
            $sumB  = (int) $items->where('gol_darah', 'B')->sum('jumlah');
            $sumO  = (int) $items->where('gol_darah', 'O')->sum('jumlah');

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

    public function store(StokDarahRequest $request)
    {
        StokDarah::create($request->validated());

        return redirect()
            ->route('admin.stok.index')
            ->with('success', 'Stok berhasil disimpan.');
    }

    /** Agregasi per produk */
    // private function aggregateRows(Collection $stok): Collection
    // {
    //     return $stok->groupBy('produk')->map(function ($items, $produk) {
    //         $sumA  = (int)$items->where('gol_darah', 'A')->sum('jumlah');
    //         $sumAB = (int)$items->where('gol_darah', 'AB')->sum('jumlah');
    //         $sumB  = (int)$items->where('gol_darah', 'B')->sum('jumlah');
    //         $sumO  = (int)$items->where('gol_darah', 'O')->sum('jumlah');

    //         return [
    //             'produk' => $produk,
    //             'A'      => $sumA,
    //             'AB'     => $sumAB,
    //             'B'      => $sumB,
    //             'O'      => $sumO,
    //             'total'  => $sumA + $sumAB + $sumB + $sumO,
    //         ];
    //     })->values();
    // }
}
