<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatPemesanan;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $r)
    {
        // pagination size (aman dari input aneh)
        $per = max(1, (int) $r->input('per_page', 12));

        $q   = $r->input('q');        // kata kunci
        $st  = $r->input('status');   // status pemesanan (pending/approved/rejected)
        $gol = $r->input('gol');      // golongan darah

        // Riwayat + pemesanan untuk akses field rs_pemesan/status via relasi
        $query = RiwayatPemesanan::query()
            ->with(['pemesanan.verifikasiTerakhir']) // pastikan model RiwayatPemesanan punya belongsTo('pemesanan')
            ->latest(); // urut terbaru (created_at / tanggal riwayat)
        
        // Pencarian bebas: di tabel riwayat (kolom 'nama') + di relasi pemesanan (rs_pemesan)
        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                    ->orWhereHas('pemesanan', function ($p) use ($q) {
                        $p->where('rs_pemesan', 'like', "%{$q}%");
                    });
            });
        }

        // Filter status (ada di tabel pemesanan)
        if ($st) {
            $query->whereHas('pemesanan', function ($p) use ($st) {
                $p->where('status', $st);
            });
        }

        // Filter golongan darah (tersedia di riwayat; jika kamu simpan di pemesanan, ganti ke whereHas)
        if ($gol) {
            $query->where('gol_darah', $gol);
            // Atau kalau mau pakai sumber dari tabel pemesanan:
            // $query->whereHas('pemesanan', fn($p) => $p->where('gol_darah', $gol));
        }

        $pemesanan = $query->paginate($per)->appends($r->query());

        return view('admin.riwayat.index', compact('pemesanan'));
    }
}