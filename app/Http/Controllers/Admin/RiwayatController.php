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
        // Filter opsional
        $q   = trim((string) $r->input('q'));
        $st  = $r->input('status'); // pending/approved/rejected
        $gol = $r->input('gol');    // A/B/AB/O

        $query = RiwayatPemesanan::query()
            ->with(['pemesanan'])     // pastikan relasi ada di model
            ->latest();

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                  ->orWhereHas('pemesanan', function ($p) use ($q) {
                      $p->where('rs_pemesan', 'like', "%{$q}%")
                        ->orWhere('nama_pasien','like',"%{$q}%");
                  });
            });
        }

        if (!empty($st)) {
            $query->whereHas('pemesanan', fn($p) => $p->where('status', $st));
        }

        if (!empty($gol)) {
            $query->where(function ($w) use ($gol) {
                $w->where('gol_darah', $gol)
                  ->orWhereHas('pemesanan', fn($p) => $p->where('gol_darah', $gol));
            });
        }

        $items = $query->take(500)->get();

        $rows = $items->map(function ($it) {
            $p = $it->pemesanan; // relasi ke PemesananDarah

            // tanggal ringkas untuk list
            $tglRaw = $it->tanggal ?? $it->created_at ?? optional($p)->created_at;
            $tglList = $tglRaw ? Carbon::parse($tglRaw)->format('d-m-Y') : '-';

            // tanggal ISO untuk modal (biar konsisten)
            $tglPesanIso  = optional($p->tanggal_pemesanan ?? $p->created_at)->toDateString();
            $tglMintaIso  = optional($p->tanggal_permintaan)->toDateString();

            return [
                // kolom list (untuk tabel/cards)
                'id'      => $it->id,
                'nama'    => $it->nama ?? ($p->nama_pasien ?? '-'),
                'tgl'     => $tglList,
                'gol'     => $it->gol_darah ?? ($p->gol_darah ?? '-'),
                'rhesus'  => $it->rhesus ?? ($p->rhesus ?? '-'),
                'produk'  => $it->produk ?? ($p->produk ?? '-'),
                'kantong' => (int)($it->jumlah_kantong ?? ($p->jumlah_kantong ?? 0)),
                'status'  => $p ? ucfirst($p->status) : '-',

                // ===== PAYLOAD LENGKAP (meniru verifikasi) untuk modal =====
                'payload' => [
                    'id' => $p->id ?? $it->id,
                    'status' => $p->status ?? '-',
                    'tanggal' => $tglPesanIso ?? null, // fallback
                    'tanggal_pemesanan' => $tglPesanIso,
                    'tanggal_permintaan' => $tglMintaIso,

                    // A. Pasien & RS
                    'nama_pasien'   => $p->nama_pasien ?? $it->nama,
                    'rs_pemesan'    => $p->rs_pemesan ?? null,
                    'jenis_kelamin' => $p->jenis_kelamin ?? null, // 'L'/'P'
                    'nama_dokter'   => $p->nama_dokter ?? null,
                    'email'         => $p->email ?? null,
                    'nomor_telepon' => $p->nomor_telepon ?? null,
                    'no_regis_rs'   => $p->no_regis_rs ?? null,
                    'nama_suami_istri' => $p->nama_suami_istri ?? null,

                    // B. Klinis
                    'alasan_transfusi' => $p->alasan_transfusi ?? null,
                    'alasan_tambahan' => $p->alasan_tambahan ?? null,
                    'cek_transfusi'    => isset($p->cek_transfusi) ? (bool)$p->cek_transfusi : null,

                    'diagnosa_klinik'  => $p->diagnosa_klinik ?? null,
                    'pernah_serologi'  => $p->pernah_serologi ?? null, // true/false/'Ya'/'Tidak'
                    'lokasi_serologi'  => $p->lokasi_serologi ?? null,
                    'tanggal_serologi' => optional($p->tanggal_serologi)->toDateString(),
                    'tanggal_transfusi'=> optional($p->tanggal_transfusi)->toDateString(),
                    'hasil_serologi'   => $p->hasil_serologi ?? null,

                    // C. Permintaan darah
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
