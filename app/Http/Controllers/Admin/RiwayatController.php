<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatPemesanan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RiwayatController extends Controller
{
    public function index(Request $r)
    {
        // Ambil filter dari query string (opsional untuk server-side filter)
        $q   = trim((string) $r->input('q'));
        $st  = $r->input('status'); // pending/approved/rejected
        $gol = $r->input('gol');    // A/B/AB/O

        // Ambil data + relasi pemesanan
        $query = RiwayatPemesanan::query()
            ->with(['pemesanan'])     // pastikan relasi ada di model
            ->latest();               // berdasarkan created_at riwayat

        // Pencarian bebas: nama (riwayat) & rs_pemesan (pemesanan)
        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                  ->orWhereHas('pemesanan', function ($p) use ($q) {
                      $p->where('rs_pemesan', 'like', "%{$q}%");
                  });
            });
        }

        // Filter status pada tabel pemesanan
        if (!empty($st)) {
            $query->whereHas('pemesanan', fn($p) => $p->where('status', $st));
        }

        // Filter golongan darah: cari di riwayat dulu, fallback ke pemesanan
        if (!empty($gol)) {
            $query->where(function ($w) use ($gol) {
                $w->where('gol_darah', $gol)
                  ->orWhereHas('pemesanan', fn($p) => $p->where('gol_darah', $gol));
            });
        }

        // Ambil banyak data (biarkan frontend yang paginate)
        // Boleh dibatasi kalau dataset besar, mis. 200–500 baris
        $items = $query->take(500)->get();

        // Bentukkan ke struktur yang diharapkan JS frontend
        $rows = $items->map(function ($it) {
            $p = $it->pemesanan;

            // tanggal: pakai kolom 'tanggal' di riwayat jika ada, kalau tidak created_at
            $tglRaw = $it->tanggal ?? $it->created_at;
            $tgl    = $tglRaw ? Carbon::parse($tglRaw)->format('d-m-Y') : '-';

            return [
                'id'      => $it->id,
                'nama'    => $it->nama ?? ($p->nama_pasien ?? '-'),
                'tgl'     => $tgl,
                'gol'     => $it->gol_darah ?? ($p->gol_darah ?? '-'),
                'rhesus'  => $it->rhesus ?? ($p->rhesus ?? '-'),
                'produk'  => $it->produk ?? ($p->produk ?? '-'),
                'kantong' => (int)($it->jumlah_kantong ?? ($p->jumlah_kantong ?? 0)),
                'status'  => $p ? ucfirst($p->status) : '-', // jadi 'Approved/Pending/Rejected'
            ];
        });

        // Kirim ke Blade sebagai JSON (biar langsung dipakai JS)
        return view('admin.riwayat.index', [
            'rows' => $rows,               // jika ingin juga dipakai server-side
            'rowsJson' => $rows->toJson(), // ini yang dipakai JS di <script>
        ]);
    }
}
