<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatPemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RiwayatController extends Controller
{
    /**
     * Hapus satu entri riwayat.
     */
    public function destroy($id)
    {
        try {
            $riwayat = RiwayatPemesanan::findOrFail($id);
            $riwayat->delete();

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tampilkan daftar riwayat pemesanan (approved / rejected).
     */
    public function index(Request $r)
    {
        $q       = trim((string) $r->input('q'));
        $st      = $r->input('status');
        $gol     = $r->input('gol');
        $produk  = $r->input('produk');

        $subquery = RiwayatPemesanan::select('pemesanan_id', DB::raw('MAX(id) as latest_id'))
            ->groupBy('pemesanan_id');

        $query = RiwayatPemesanan::query()
            ->joinSub($subquery, 'latest', function ($join) {
                $join->on('riwayat_pemesanan.id', '=', 'latest.latest_id');
            })
            ->with(['pemesanan' => function ($q) {
                $q->select(
                    'id',
                    'nama_pasien',
                    'rs_pemesan',
                    'status',
                    'gol_darah',
                    'rhesus',
                    'produk',
                    'jumlah_kantong',
                    'tanggal_pemesanan',
                    'tanggal_permintaan',
                    'jenis_kelamin',
                    'nama_dokter',
                    'email',
                    'nomor_telepon',
                    'no_regis_rs',
                    'nama_suami_istri',
                    'alasan_transfusi',
                    'alasan_tambahan',
                    'cek_transfusi',
                    'diagnosa_klinik',
                    'pernah_serologi',
                    'lokasi_serologi',
                    'tanggal_serologi',
                    'tanggal_transfusi',
                    'hasil_serologi',
                    'created_at'
                );
            }, 'pemesanan.verifikasi' => function ($q) {
                $q->select('id', 'pemesanan_id', 'status', 'updated_at')
                    ->whereIn('status', ['approved', 'rejected']);
            }])
            ->whereHas('pemesanan', function ($p) {
                $p->whereIn('status', ['approved', 'rejected']);
            });

        if ($q !== '') {
            $query->whereHas('pemesanan', function ($p) use ($q) {
                $p->where('rs_pemesan', 'like', "%{$q}%")
                    ->orWhere('nama_pasien', 'like', "%{$q}%");
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

        if (!empty($produk)) {
            $query->where(function ($w) use ($produk) {
                $w->where('produk', $produk)
                    ->orWhereHas('pemesanan', fn($p) => $p->where('produk', $produk));
            });
        }

        $items = $query->latest('riwayat_pemesanan.created_at')->limit(500)->get();

        $rows = $items->map(function ($it) {
            $p = $it->pemesanan;
            $tglRaw   = $it->tanggal ?? $it->created_at ?? optional($p)->created_at;
            $tglList  = $tglRaw ? Carbon::parse($tglRaw)->format('d-m-Y') : '-';
            $tglPesan = optional($p->tanggal_pemesanan ?? $p->created_at)->toDateString();
            $tglMinta = optional($p->tanggal_permintaan)->toDateString();

            // Ambil waktu verifikasi dari relasi
            $verifikasi = optional($p)->verifikasi;
            $waktuVerifikasi = $verifikasi && $verifikasi->updated_at
                ? Carbon::parse($verifikasi->updated_at)->format('d-m-Y')
                : null;

            return [
                'id'      => $it->id,
                'nama'    => $it->nama ?? ($p->nama_pasien ?? '-'),
                'tgl'     => $tglList,
                'gol'     => $it->gol_darah ?? ($p->gol_darah ?? '-'),
                'rhesus'  => $it->rhesus ?? ($p->rhesus ?? '-'),
                'produk'  => $it->produk ?? ($p->produk ?? '-'),
                'kantong' => (int) ($it->jumlah_kantong ?? ($p->jumlah_kantong ?? 0)),
                'status'  => $p ? ucfirst($p->status) : '-',
                'waktu_verifikasi' => $waktuVerifikasi,

                'payload' => [
                    'id'                => $p->id ?? $it->id,
                    'status'            => $p->status ?? '-',
                    'tanggal'           => $tglPesan ?? null,
                    'tanggal_pemesanan' => $tglPesan,
                    'tanggal_permintaan' => $tglMinta,
                    'waktu_verifikasi'  => $waktuVerifikasi,
                    'nama_pasien'       => $p->nama_pasien ?? $it->nama,
                    'rs_pemesan'        => $p->rs_pemesan ?? null,
                    'jenis_kelamin'     => $p->jenis_kelamin ?? null,
                    'nama_dokter'       => $p->nama_dokter ?? null,
                    'email'             => $p->email ?? null,
                    'nomor_telepon'     => $p->nomor_telepon ?? null,
                    'no_regis_rs'       => $p->no_regis_rs ?? null,
                    'nama_suami_istri'  => $p->nama_suami_istri ?? null,
                    'alasan_transfusi'  => $p->alasan_transfusi ?? null,
                    'alasan_tambahan'   => $p->alasan_tambahan ?? null,
                    'cek_transfusi'     => isset($p->cek_transfusi) ? (bool)$p->cek_transfusi : null,
                    'diagnosa_klinik'   => $p->diagnosa_klinik ?? null,
                    'pernah_serologi'   => $p->pernah_serologi ?? null,
                    'lokasi_serologi'   => $p->lokasi_serologi ?? null,
                    'tanggal_serologi'  => optional($p->tanggal_serologi)->toDateString(),
                    'tanggal_transfusi' => optional($p->tanggal_transfusi)->toDateString(),
                    'hasil_serologi'    => $p->hasil_serologi ?? null,
                    'gol_darah'         => $p->gol_darah ?? $it->gol_darah,
                    'rhesus'            => $p->rhesus ?? $it->rhesus,
                    'produk'            => $p->produk ?? $it->produk,
                    'jumlah_kantong'    => (int)($p->jumlah_kantong ?? $it->jumlah_kantong ?? 0),
                ],
            ];
        });

        return view('admin.riwayat.index', [
            'rowsJson' => $rows->toJson(),
        ]);
    }
}
