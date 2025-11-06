<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodUnit;
use Illuminate\Http\Request;

class BloodUnitController extends Controller
{
    public function index(Request $r)
    {
        // Tab 1: Tersedia (belum exp)
        $avail = BloodUnit::available()
            ->select('kode_unit as id_darah', 'gol_darah', 'rhesus', 'produk as komponen', 'tgl_masuk', 'tgl_kadaluarsa')
            ->orderBy('kode_unit')
            ->limit(200)
            ->get();

        // Tab 2: Keluar/riwayat
        $riwayat = BloodUnit::query()
            ->whereIn('status', ['dispensed', 'reserved', 'discarded'])
            ->orderByDesc('updated_at')
            ->limit(200)
            ->get()
            ->map(fn($u) => [
                'id'       => $u->kode_unit,
                'gol'      => $u->gol_darah,
                'rh'       => $u->rhesus,
                'produk'   => $u->produk,
                'masuk'    => $u->tgl_masuk?->toDateString(),
                'exp'      => $u->tgl_kadaluarsa?->toDateString(),
                'penerima' => $u->penerima ?? optional($u->stok?->pemesanan)->rs_pemesan, // sesuaikan jika ada relasi lain
                'status'   => ucfirst($u->status),
            ]);

        // Tab 3: Kedaluwarsa
        $expired = BloodUnit::expired()
            ->orderBy('tgl_kadaluarsa')
            ->limit(200)
            ->get()
            ->map(fn($u) => [
                'id'     => $u->kode_unit,
                'gol'    => $u->gol_darah,
                'rh'     => $u->rhesus,
                'produk' => $u->produk,
                'masuk'  => $u->tgl_masuk?->toDateString(),
                'exp'    => $u->tgl_kadaluarsa?->toDateString(),
            ]);

        return view('admin.detail.index', [
            'rows'        => $avail,
            'historyRows' => $riwayat,
            'expiredRows' => $expired, // kalau Blade-mu butuh
        ]);
    }

    public function show(BloodUnit $unit)
    {
        return view('admin.detail.show', compact('unit'));
    }

    public function data(Request $r)
    {
        $q = BloodUnit::query();

        if ($v = $r->input('produk'))  $q->where('produk', $v);
        if ($v = $r->input('gol'))     $q->where('gol_darah', $v);
        if ($v = $r->input('rhesus'))  $q->where('rhesus', $v);
        if ($v = $r->input('status'))  $q->where('status', $v);
        if ($v = $r->input('search'))  $q->where('kode_unit', 'like', "%$v%");

        return $q->paginate($r->integer('per_page', 10));
    }
}
