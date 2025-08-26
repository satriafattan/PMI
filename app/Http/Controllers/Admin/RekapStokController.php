<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRekapStokRequest;
use App\Models\RekapStok;
use Illuminate\Http\Request;

class RekapStokController extends Controller
{
    public function index() {
        $items = RekapStok::latest()->paginate(15);
        return view('admin.rekap.index', compact('items'));
    }
    public function create() { return view('admin.rekap.create'); }

    public function store(Request $r) {
        $data = $r->validate([
            'id_darah'=>'required|string|unique:rekap_stok,id_darah',
            'komponen'=>'required|string|max:100',
            'gol_darah'=>'required|in:A,B,AB,O',
            'rhesus'=>'required|in:+,-',
            'tanggal_masuk'=>'required|date',
            'tanggal_keluar'=>'nullable|date',
            'keterangan'=>'nullable|string',
        ]);
        RekapStok::create($data);
        return redirect()->route('admin.rekap-stok.index')->with('success','Stok ditambahkan.');
    }

    public function edit(RekapStok $rekap_stok) {
        return view('admin.rekap.edit', ['item'=>$rekap_stok]);
    }

    public function update(Request $r, RekapStok $rekap_stok) {
        $data = $r->validate([
            'id_darah'=>'required|string|unique:rekap_stok,id_darah,'.$rekap_stok->id,
            'komponen'=>'required|string|max:100',
            'gol_darah'=>'required|in:A,B,AB,O',
            'rhesus'=>'required|in:+,-',
            'tanggal_masuk'=>'required|date',
            'tanggal_keluar'=>'nullable|date',
            'keterangan'=>'nullable|string',
        ]);
        $rekap_stok->update($data);
        return back()->with('success','Rekap stok diperbarui.');
    }

    public function destroy(RekapStok $rekap_stok) {
        $rekap_stok->delete();
        return back()->with('success','Rekap stok dihapus.');
    }
}
