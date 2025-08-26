<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\stok;

class StokController extends Controller
{
    public function stok()
    {
        // Ambil/olah data asli di sini…
        $rows = [
            ['komponen' => 'PRC', 'A' => 120, 'B' => 90, 'O' => 130, 'AB' => 40],
            // …
        ];
        return view('public.stok', ['komponenRows' => $rows]);
    }

    public function __invoke()
    {
        // Ambil dari DB/Service kamu, ini contoh dummy:
        return view('public/stokdarah', [
            'stokA'  => 76,
            'stokB'  => 41,
            'stokO'  => 18,
            'stokAB' => 7,
        ]);
    }
}
