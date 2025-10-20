<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekapStok;
use Illuminate\Http\Request;

class RekapStokController extends Controller
{
    public function index(Request $r)
    {
        $q = trim((string) $r->get('q', ''));

        // Tabel daftar
        $rekap = RekapStok::query()
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('id_darah', 'like', "%{$q}%")
                        ->orWhere('komponen', 'like', "%{$q}%")
                        ->orWhere('keterangan', 'like', "%{$q}%");
                });
            })
            ->latest('tanggal_masuk')
            ->paginate(15);

        // Kartu ringkasan
        $activeUnits  = RekapStok::whereNull('tanggal_keluar')->count();
        $totalUnits   = RekapStok::count();
        $outThisMonth = RekapStok::whereNotNull('tanggal_keluar')
            ->whereYear('tanggal_keluar', now()->year)
            ->whereMonth('tanggal_keluar', now()->month)
            ->count();

        // Jumlah tipe golongan+resus yang terdata (contoh: A+, A-, B+, ...)
        $bloodGroupsCount = RekapStok::query()
            ->selectRaw('COUNT(DISTINCT CONCAT(gol_darah, rhesus)) AS c')
            ->value('c');
        // Kalau hanya ingin ABO saja: $bloodGroupsCount = RekapStok::distinct('gol_darah')->count('gol_darah');

        return view('admin.rekap.index', compact(
            'rekap',
            'activeUnits',
            'totalUnits',
            'outThisMonth',
            'bloodGroupsCount'
        ));
    }

    public function create()
    {
        return view('admin.rekap.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'id_darah'        => ['required', 'string', 'unique:rekap_stok,id_darah'],
            'komponen'        => ['required', 'string', 'max:100'],
            'gol_darah'       => ['required', 'in:A,B,AB,O'],
            'rhesus'          => ['required', 'in:+,-'],
            'tanggal_masuk'   => ['required', 'date'],
            'tanggal_keluar'  => ['nullable', 'date'],
            'keterangan'      => ['nullable', 'string'],
        ]);

        RekapStok::create($data);
        return redirect()->route('admin.rekap-stok.index')->with('success', 'Stok ditambahkan.');
    }

    public function edit(RekapStok $rekap_stok)
    {
        return view('admin.rekap.edit', ['item' => $rekap_stok]);
    }

    public function update(Request $r, RekapStok $rekap_stok)
    {
        $data = $r->validate([
            'id_darah'        => ['required', 'string', 'unique:rekap_stok,id_darah,' . $rekap_stok->id],
            'komponen'        => ['required', 'string', 'max:100'],
            'gol_darah'       => ['required', 'in:A,B,AB,O'],
            'rhesus'          => ['required', 'in:+,-'],
            'tanggal_masuk'   => ['required', 'date'],
            'tanggal_keluar'  => ['nullable', 'date'],
            'keterangan'      => ['nullable', 'string'],
        ]);

        $rekap_stok->update($data);
        return back()->with('success', 'Rekap stok diperbarui.');
    }

    public function destroy(RekapStok $rekap_stok)
    {
        $rekap_stok->delete();
        return back()->with('success', 'Rekap stok dihapus.');
    }
}
