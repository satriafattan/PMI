<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodUnit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DarahTersediaExport;
use App\Exports\DarahKeluarExport;
use App\Exports\DarahKadaluwarsaExport;
use Illuminate\Support\Facades\Log;

class BloodUnitController extends Controller
{
    public function index(Request $r)
    {
        // Redirect ke tersedia sebagai default
        return redirect()->route('admin.detail-darah.tersedia');
    }

    public function tersedia(Request $r)
    {
        // Default per_page
        $perPage = $r->integer('per_page', 100);

        // Tab 1: Tersedia (belum exp) - dengan pagination
        // Urutkan berdasarkan tgl_masuk DESC agar data terbaru tampil di atas
        $availQuery = BloodUnit::available()
            ->select('kode_unit', 'gol_darah', 'rhesus', 'produk', 'tgl_masuk', 'tgl_kadaluarsa')
            ->orderByDesc('tgl_masuk'); // Data terbaru di atas

        // Untuk client-side filtering, ambil semua data tapi batasi jika terlalu banyak
        $totalAvailable = $availQuery->count();
        $avail = ($totalAvailable > 5000
            ? $availQuery->limit(5000)->get()
            : $availQuery->get()
        )->map(fn($u) => [
            'id_darah'       => $u->kode_unit,
            'gol_darah'      => $u->gol_darah,
            'rhesus'         => $u->rhesus,
            'komponen'       => $u->produk,
            'tgl_masuk'      => $u->tgl_masuk?->toDateString(),
            'tgl_kadaluarsa' => $u->tgl_kadaluarsa?->toDateString(),
        ]);

        return view('admin.detail.tersedia', [
            'rows' => $avail,
            'total' => $totalAvailable,
        ]);
    }

    public function keluar(Request $r)
    {
        // Tab 2: Keluar/riwayat - dengan limit yang lebih reasonable
        $riwayatQuery = BloodUnit::query()
            ->select('kode_unit', 'gol_darah', 'rhesus', 'produk', 'tgl_masuk', 'tgl_kadaluarsa', 'penerima', 'status', 'updated_at')
            ->whereIn('status', ['dispensed', 'reserved', 'discarded'])
            ->orderByDesc('updated_at');

        $totalRiwayat = $riwayatQuery->count();
        $riwayat = ($totalRiwayat > 5000
            ? $riwayatQuery->limit(5000)->get()
            : $riwayatQuery->get()
        )->map(fn($u) => [
            'id'       => $u->kode_unit,
            'gol'      => $u->gol_darah,
            'rh'       => $u->rhesus,
            'produk'   => $u->produk,
            'keluar'   => $u->updated_at?->toDateString(),
            'exp'      => $u->tgl_kadaluarsa?->toDateString(),
            'penerima' => $u->penerima ?? '-',
            'status'   => ucfirst($u->status),
        ]);

        return view('admin.detail.keluar', [
            'rows' => $riwayat,
            'total' => $totalRiwayat,
        ]);
    }

    public function kadaluwarsa(Request $r)
    {
        // Tab 3: Kedaluwarsa
        $expiredQuery = BloodUnit::expired()
            ->select('kode_unit', 'gol_darah', 'rhesus', 'produk', 'tgl_masuk', 'tgl_kadaluarsa')
            ->orderBy('tgl_kadaluarsa');

        $totalExpired = $expiredQuery->count();
        $expired = ($totalExpired > 5000
            ? $expiredQuery->limit(5000)->get()
            : $expiredQuery->get()
        )->map(fn($u) => [
            'id'     => $u->kode_unit,
            'gol'    => $u->gol_darah,
            'rh'     => $u->rhesus,
            'produk' => $u->produk,
            'masuk'  => $u->tgl_masuk?->toDateString(),
            'exp'    => $u->tgl_kadaluarsa?->toDateString(),
        ]);

        return view('admin.detail.kadaluwarsa', [
            'rows' => $expired,
            'total' => $totalExpired,
        ]);
    }

    // Method index() yang lama - untuk backward compatibility
    public function indexOld(Request $r)
    {
        // Default per_page
        $perPage = $r->integer('per_page', 100); // Default 100 item per halaman

        // Tab 1: Tersedia (belum exp) - dengan pagination
        $availQuery = BloodUnit::available()
            ->select('kode_unit as id_darah', 'gol_darah', 'rhesus', 'produk as komponen', 'tgl_masuk', 'tgl_kadaluarsa')
            ->orderBy('tgl_kadaluarsa'); // FEFO

        // Untuk client-side filtering, ambil semua data tapi batasi jika terlalu banyak
        $totalAvailable = $availQuery->count();
        $avail = $totalAvailable > 5000
            ? $availQuery->limit(5000)->get()
            : $availQuery->get();

        // Tab 2: Keluar/riwayat - dengan limit yang lebih reasonable
        $riwayatQuery = BloodUnit::query()
            ->select('kode_unit', 'gol_darah', 'rhesus', 'produk', 'tgl_masuk', 'tgl_kadaluarsa', 'penerima', 'status', 'updated_at')
            ->whereIn('status', ['dispensed', 'reserved', 'discarded'])
            ->orderByDesc('updated_at');

        $totalRiwayat = $riwayatQuery->count();
        $riwayat = ($totalRiwayat > 5000
            ? $riwayatQuery->limit(5000)->get()
            : $riwayatQuery->get()
        )->map(fn($u) => [
            'id'       => $u->kode_unit,
            'gol'      => $u->gol_darah,
            'rh'       => $u->rhesus,
            'produk'   => $u->produk,
            'keluar'   => $u->updated_at?->toDateString(),
            'exp'      => $u->tgl_kadaluarsa?->toDateString(),
            'penerima' => $u->penerima ?? '-',
            'status'   => ucfirst($u->status),
        ]);

        // Tab 3: Kedaluwarsa
        $expiredQuery = BloodUnit::expired()
            ->select('kode_unit', 'gol_darah', 'rhesus', 'produk', 'tgl_masuk', 'tgl_kadaluarsa')
            ->orderBy('tgl_kadaluarsa');

        $totalExpired = $expiredQuery->count();
        $expired = ($totalExpired > 5000
            ? $expiredQuery->limit(5000)->get()
            : $expiredQuery->get()
        )->map(fn($u) => [
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
            'totals' => [
                'available' => $totalAvailable,
                'riwayat'   => $totalRiwayat,
                'expired'   => $totalExpired,
            ],
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

    public function exportTersedia(Request $r)
    {
        try {
            $availQuery = BloodUnit::available()
                ->select('kode_unit', 'gol_darah', 'rhesus', 'produk', 'tgl_masuk', 'tgl_kadaluarsa')
                ->orderBy('tgl_kadaluarsa');

            $totalAvailable = $availQuery->count();
            $avail = ($totalAvailable > 5000
                ? $availQuery->limit(5000)->get()
                : $availQuery->get()
            )->map(fn($u) => [
                'id_darah'       => $u->kode_unit,
                'gol_darah'      => $u->gol_darah,
                'rhesus'         => $u->rhesus,
                'komponen'       => $u->produk,
                'tgl_masuk'      => $u->tgl_masuk?->format('d-m-Y'),
                'tgl_kadaluarsa' => $u->tgl_kadaluarsa?->format('d-m-Y'),
            ]);

            $export = new DarahTersediaExport($avail);
            return Excel::download($export, 'darah-tersedia-' . now()->format('Ymd_His') . '.xlsx');
        } catch (\Exception $e) {
            Log::error('Export Darah Tersedia error: ' . $e->getMessage(), [
                'user' => auth('admin')->id(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('admin.detail-darah.tersedia')
                ->with('error', 'Gagal membuat file Excel. Silakan coba lagi.');
        }
    }

    public function exportKeluar(Request $r)
    {
        try {
            $riwayatQuery = BloodUnit::query()
                ->select('kode_unit', 'gol_darah', 'rhesus', 'produk', 'tgl_masuk', 'tgl_kadaluarsa', 'penerima', 'status', 'updated_at')
                ->whereIn('status', ['dispensed', 'reserved', 'discarded'])
                ->orderByDesc('updated_at');

            $totalRiwayat = $riwayatQuery->count();
            $riwayat = ($totalRiwayat > 5000
                ? $riwayatQuery->limit(5000)->get()
                : $riwayatQuery->get()
            )->map(fn($u) => [
                'id'       => $u->kode_unit,
                'gol'      => $u->gol_darah,
                'rh'       => $u->rhesus,
                'produk'   => $u->produk,
                'keluar'   => $u->updated_at?->format('d-m-Y'),
                'exp'      => $u->tgl_kadaluarsa?->format('d-m-Y'),
                'penerima' => $u->penerima ?? '-',
                'status'   => ucfirst($u->status),
            ]);

            $export = new DarahKeluarExport($riwayat);
            return Excel::download($export, 'darah-keluar-' . now()->format('Ymd_His') . '.xlsx');
        } catch (\Exception $e) {
            Log::error('Export Darah Keluar error: ' . $e->getMessage(), [
                'user' => auth('admin')->id(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('admin.detail-darah.keluar')
                ->with('error', 'Gagal membuat file Excel. Silakan coba lagi.');
        }
    }

    public function exportKadaluwarsa(Request $r)
    {
        try {
            $expiredQuery = BloodUnit::expired()
                ->select('kode_unit', 'gol_darah', 'rhesus', 'produk', 'tgl_masuk', 'tgl_kadaluarsa')
                ->orderBy('tgl_kadaluarsa');

            $totalExpired = $expiredQuery->count();
            $expired = ($totalExpired > 5000
                ? $expiredQuery->limit(5000)->get()
                : $expiredQuery->get()
            )->map(fn($u) => [
                'id'     => $u->kode_unit,
                'gol'    => $u->gol_darah,
                'rh'     => $u->rhesus,
                'produk' => $u->produk,
                'masuk'  => $u->tgl_masuk?->format('d-m-Y'),
                'exp'    => $u->tgl_kadaluarsa?->format('d-m-Y'),
            ]);

            $export = new DarahKadaluwarsaExport($expired);
            return Excel::download($export, 'darah-kadaluwarsa-' . now()->format('Ymd_His') . '.xlsx');
        } catch (\Exception $e) {
            Log::error('Export Darah Kadaluwarsa error: ' . $e->getMessage(), [
                'user' => auth('admin')->id(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('admin.detail-darah.kadaluwarsa')
                ->with('error', 'Gagal membuat file Excel. Silakan coba lagi.');
        }
    }
}
