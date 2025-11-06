<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodUnit;
use Illuminate\Http\Request;

class BloodUnitController extends Controller
{
    public function index(Request $r)
    {
        // PERBAIKAN: Batasi jumlah data yang di-load, gunakan pagination
        // Tab 1: Tersedia (belum exp)
        $avail = BloodUnit::available()
            ->select('kode_unit as id_darah', 'gol_darah', 'rhesus', 'produk as komponen', 'tgl_masuk', 'tgl_kadaluarsa')
            ->orderBy('tgl_kadaluarsa') // FEFO
            ->limit(500) // Batasi untuk performa
            ->get();

        // Tab 2: Keluar/riwayat - OPTIMASI: gunakan chunk atau pagination
        $riwayat = BloodUnit::query()
            ->select('kode_unit', 'gol_darah', 'rhesus', 'produk', 'tgl_masuk', 'tgl_kadaluarsa', 'penerima', 'status', 'updated_at')
            ->whereIn('status', ['dispensed', 'reserved', 'discarded'])
            ->orderByDesc('updated_at')
            ->limit(500) // Batasi untuk performa
            ->get()
            ->map(fn($u) => [
                'id'       => $u->kode_unit,
                'gol'      => $u->gol_darah,
                'rh'       => $u->rhesus,
                'produk'   => $u->produk,
                'masuk'    => $u->tgl_masuk?->toDateString(),
                'exp'      => $u->tgl_kadaluarsa?->toDateString(),
                'penerima' => $u->penerima ?? '-',
                'status'   => ucfirst($u->status),
            ]);

        // Tab 3: Kedaluwarsa - OPTIMASI: select only needed columns
        $expired = BloodUnit::expired()
            ->select('kode_unit', 'gol_darah', 'rhesus', 'produk', 'tgl_masuk', 'tgl_kadaluarsa')
            ->orderBy('tgl_kadaluarsa')
            ->limit(500) // Batasi untuk performa
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
            'expiredRows' => $expired,
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
