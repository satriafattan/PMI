<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StokDarahRequest;
use App\Models\StokDarah;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Services\GenerateBloodUnits;

class StokDarahController extends Controller
{
    public function index(Request $r)
    {
        // Ambil semua stok (boleh tambahkan filter dari query jika perlu)
        $stok = StokDarah::query()
            // ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString())
            ->orderBy('produk')
            ->orderBy('gol_darah')
            ->orderBy('tgl_kadaluarsa')
            ->get();

        // Ringkasan per-produk untuk kartu summary
        $summary = $this->aggregateRows($stok);

        // Detail grouped per produk (langsung kirim modelnya ke Blade)
        $grouped = $stok->groupBy('produk');

        // Riwayat stok (untuk modal per-produk/golongan)
        $riwayat = $stok->groupBy(function ($item) {
            return $item->produk . '_' . $item->gol_darah;
        })->map(function ($items) {
            return $items->map(function ($item) {
                return [
                    'id'              => $item->id,
                    'jumlah'          => (int) $item->jumlah,
                    'rhesus'          => $item->rhesus,
                    'tgl_masuk'       => optional($item->tgl_masuk)->format('Y-m-d'),
                    'tgl_kadaluarsa'  => optional($item->tgl_kadaluarsa)->format('Y-m-d'),
                    'created_at'      => optional($item->created_at)->format('Y-m-d H:i'),
                ];
            })->sortByDesc('created_at')->values();
        });

        // Total keseluruhan
        $grandTotal = (int) $summary->sum('total');

        return view('admin.stok.index', compact('summary', 'grouped', 'grandTotal', 'riwayat'));
    }

    public function store(StokDarahRequest $request)
    {
        $data   = $request->validated();
        $jumlah = (int) ($data['jumlah'] ?? 0);

        // Simpan stok induk
        $stok = StokDarah::create($data);

        // Generate unit-unit darah (BloodUnit) jika diminta
        if ($jumlah > 0) {
            // Jika service static:
            GenerateBloodUnits::run($stok, $jumlah);

            // Jika service non-static, gunakan:
            // app(GenerateBloodUnits::class)->run($stok, $jumlah);
        }

        return redirect()
            ->route('admin.stok.index')
            ->with('success', 'Stok berhasil disimpan.');
    }

    /** Agregasi per produk (untuk kartu ringkasan) */
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
}
