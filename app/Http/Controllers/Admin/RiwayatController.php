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
        $q   = trim((string) $r->input('q'));
        $st  = $r->input('status');
        $gol = $r->input('gol');

        $query = RiwayatPemesanan::query()
            ->with(['pemesanan'])
            // ✅ Hanya tampilkan data dengan status approved / rejected
            ->whereHas('pemesanan', function ($p) {
                $p->whereIn('status', ['approved', 'rejected']); // ⬅ filter utama
            })
            ->latest();

        // 🔍 Filter pencarian nama / rumah sakit
        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                  ->orWhereHas('pemesanan', function ($p) use ($q) {
                      $p->where('rs_pemesan', 'like', "%{$q}%")
                        ->orWhere('nama_pasien', 'like', "%{$q}%");
                  });
            });
        }

        // 🔍 Filter status (opsional dari dropdown)
        if (!empty($st)) {
            $query->whereHas('pemesanan', fn($p) => $p->where('status', $st));
        }

        // 🔍 Filter golongan darah
        if (!empty($gol)) {
            $query->where(function ($w) use ($gol) {
                $w->where('gol_darah', $gol)
                  ->orWhereHas('pemesanan', fn($p) => $p->where('gol_darah', $gol));
            });
        }

        // Ambil hanya riwayat terakhir per pemesanan
        $items = $query
            ->whereIn('id', function ($sub) {
                $sub->selectRaw('MAX(id)')
                    ->from('riwayat_pemesanan')
                    ->groupBy('pemesanan_id');
            })
            ->latest()
            ->take(500)
            ->get();

        // Format data untuk dikirim ke Blade
        $rows = $items->map(function ($it) {
            $p = $it->pemesanan;
            $tglRaw = $it->tanggal ?? $it->created_at ?? optional($p)->created_at;
            $tglList = $tglRaw ? Carbon::parse($tglRaw)->format('d-m-Y') : '-';

            $tglPesanIso  = optional($p->tanggal_pemesanan ?? $p->created_at)->toDateString();
            $tglMintaIso  = optional($p->tanggal_permintaan)->toDateString();

            return [
                'id'      => $it->id,
                'nama'    => $it->nama ?? ($p->nama_pasien ?? '-'),
                'tgl'     => $tglList,
                'gol'     => $it->gol_darah ?? ($p->gol_darah ?? '-'),
                'rhesus'  => $it->rhesus ?? ($p->rhesus ?? '-'),
                'produk'  => $it->produk ?? ($p->produk ?? '-'),
                'kantong' => (int)($it->jumlah_kantong ?? ($p->jumlah_kantong ?? 0)),
                'status'  => $p ? ucfirst($p->status) : '-',

                'payload' => [
                    'id' => $p->id ?? $it->id,
                    'status' => $p->status ?? '-',
                    'tanggal' => $tglPesanIso ?? null,
                    'tanggal_pemesanan' => $tglPesanIso,
                    'tanggal_permintaan' => $tglMintaIso,

                    // A. Pasien & RS
                    'nama_pasien'   => $p->nama_pasien ?? $it->nama,
                    'rs_pemesan'    => $p->rs_pemesan ?? null,
                    'jenis_kelamin' => $p->jenis_kelamin ?? null,
                    'nama_dokter'   => $p->nama_dokter ?? null,
                    'email'         => $p->email ?? null,
                    'nomor_telepon' => $p->nomor_telepon ?? null,
                    'no_regis_rs'   => $p->no_regis_rs ?? null,
                    'nama_suami_istri' => $p->nama_suami_istri ?? null,

                    // B. Klinis
                    'alasan_transfusi' => $p->alasan_transfusi ?? null,
                    'alasan_tambahan'  => $p->alasan_tambahan ?? null,
                    'cek_transfusi'    => isset($p->cek_transfusi) ? (bool)$p->cek_transfusi : null,

                    'diagnosa_klinik'  => $p->diagnosa_klinik ?? null,
                    'pernah_serologi'  => $p->pernah_serologi ?? null,
                    'lokasi_serologi'  => $p->lokasi_serologi ?? null,
                    'tanggal_serologi' => optional($p->tanggal_serologi)->toDateString(),
                    'tanggal_transfusi'=> optional($p->tanggal_transfusi)->toDateString(),
                    'hasil_serologi'   => $p->hasil_serologi ?? null,

                    // C. Permintaan Darah
                    'gol_darah'      => $p->gol_darah ?? $it->gol_darah,
                    'rhesus'         => $p->rhesus ?? $it->rhesus,
                    'produk'         => $p->produk ?? $it->produk,
                    'jumlah_kantong' => (int)($p->jumlah_kantong ?? $it->jumlah_kantong ?? 0),
                ],
            ];
        });

        return view('admin.riwayat.index', [
            'rowsJson' => $rows->toJson(),
        ]);
    }
}
