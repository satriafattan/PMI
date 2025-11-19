<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PemesananDarah;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PemesananExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class LaporanController extends Controller
{
    public function index(Request $r)
    {
        $per = (int) $r->input('per_page', 10); // page size seperti rekap

        [$q, $summary] = $this->buildQueryAndSummary($r);

        $items = $q->latest('created_at')
            ->paginate($per)
            ->appends($r->query());

        return view('admin.laporan.index', [
            'items'   => $items,
            'summary' => $summary,
            'filters' => [
                'start'    => $r->input('start'),
                'end'      => $r->input('end'),
                'q'        => $r->input('q'),        // search (RS/pasien)
                'status'   => $r->input('status'),
                'produk'   => $r->input('produk'),
                'gol'      => $r->input('gol'),
                'rhesus'   => $r->input('rhesus'),
                'per_page' => $per,
            ],
        ]);
    }

    public function exportPdf(Request $r)
    {
        try {
            [$q, $summary] = $this->buildQueryAndSummary($r);

            $rows = $q->select([
                'id',
                'created_at',
                'tanggal_pemesanan',
                'rs_pemesan',
                'nama_pasien',
                'produk',
                'gol_darah',
                'rhesus',
                'jumlah_kantong',
                'status',
            ])
                ->orderBy('created_at')
                ->get();

            $pdf = Pdf::loadView('admin.laporan.pdf', [
                'rows'    => $rows,
                'summary' => $summary,
                'periode' => $this->periodeLabel($r),
                'filters' => $r->all(),
            ])->setPaper('a4', 'portrait');

            return $pdf->download('laporan-pemesanan-' . now()->format('Ymd_His') . '.pdf');
        } catch (\Exception $e) {
            Log::error('Export PDF error: ' . $e->getMessage(), [
                'user' => auth('admin')->id(),
                'filters' => $r->all(),
            ]);

            return redirect()
                ->route('admin.laporan.index')
                ->with('error', 'Gagal membuat file PDF. Silakan coba lagi atau hubungi administrator.');
        }
    }

    public function exportExcel(Request $r)
    {
        try {
            [$q] = $this->buildQueryAndSummary($r);

            $rows = $q->select([
                'id',
                'created_at',
                'tanggal_pemesanan',
                'rs_pemesan',
                'nama_pasien',
                'produk',
                'gol_darah',
                'rhesus',
                'jumlah_kantong',
                'status',
            ])
                ->orderBy('created_at')
                ->get();

            $export = new \App\Exports\PemesananExport($rows, $this->periodeLabel($r));

            return Excel::download($export, 'laporan-pemesanan-' . now()->format('Ymd_His') . '.xlsx');
        } catch (\Exception $e) {
            Log::error('Export Excel error: ' . $e->getMessage(), [
                'user' => auth('admin')->id(),
                'filters' => $r->all(),
            ]);

            return redirect()
                ->route('admin.laporan.index')
                ->with('error', 'Gagal membuat file Excel. Silakan coba lagi atau hubungi administrator.');
        }
    }

    private function buildQueryAndSummary(Request $r): array
    {
        $q = PemesananDarah::query();

        // Periode (created_at)
        if ($r->filled('start')) {
            $q->whereDate('created_at', '>=', $r->date('start'));
        }
        if ($r->filled('end')) {
            $q->whereDate('created_at', '<=', $r->date('end'));
        }

        // Search seperti rekap: cari RS atau nama pasien
        if ($search = trim((string) $r->input('q'))) {
            $q->where(function ($w) use ($search) {
                $w->where('rs_pemesan', 'like', "%{$search}%")
                    ->orWhere('nama_pasien', 'like', "%{$search}%");
            });
        }

        if ($produk = $r->input('produk')) {
            $q->where('produk', $produk);
        }
        if ($gol = $r->input('gol')) {
            $q->where('gol_darah', $gol);
        }
        if ($rhesus = $r->input('rhesus')) {
            $q->where('rhesus', $rhesus);
        }
        if ($status = $r->input('status')) {
            $q->where('status', $status);
        }

        // Summary (clone biar aman)
        $base = (clone $q)->select(['produk', 'gol_darah', 'rhesus', 'status', 'jumlah_kantong'])->get();

        $summary = [
            'total_pemesanan' => $base->count(),
            'total_kantong'   => (int) $base->sum('jumlah_kantong'),
            'per_status'      => $base->groupBy('status')->map->count()->toArray(),
            'per_produk'      => $base->groupBy('produk')->map(fn($g) => [
                'count'   => $g->count(),
                'kantong' => (int) $g->sum('jumlah_kantong'),
            ])->toArray(),
            'per_gol'         => $base->groupBy('gol_darah')->map->count()->toArray(),
        ];

        return [$q, $summary];
    }

    private function periodeLabel(Request $r): string
    {
        $start = $r->input('start');
        $end   = $r->input('end');

        if ($start && $end) {
            return Carbon::parse($start)->isoFormat('D MMM YYYY') . ' s/d ' . Carbon::parse($end)->isoFormat('D MMM YYYY');
        } elseif ($start) {
            return 'Mulai ' . Carbon::parse($start)->isoFormat('D MMM YYYY');
        } elseif ($end) {
            return 'Sampai ' . Carbon::parse($end)->isoFormat('D MMM YYYY');
        }
        return 'Semua Periode';
    }
}
