<?php
namespace App\Services;

use App\Models\BloodUnit;
use App\Models\StokDarah;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class GenerateBloodUnits
{
/**
* Buat N unit dari satu entri stok.
*/
public static function run(StokDarah $stok, int $jumlah): void
{
if ($jumlah <= 0) return;

    // Masa simpan per produk (silakan sesuaikan kebijakan UDD)
    $shelf=[ 'WB'=> 35, // Whole Blood
    'PRC' => 42, // Packed Red Cells
    'TC' => 5, // Trombocyte Concentrate
    'TRC' => 5, // jika dipakai
    'FFP' => 365, // Fresh Frozen Plasma
    'AHF' => 365,
    'LP' => 365,
    'TCA' => 5, // TC Aferesis (sesuaikan)
    'PK' => 365, // Plasma Konvalesen
    ];

    $masuk = $stok->tgl_masuk ? Carbon::parse($stok->tgl_masuk) : now();
    $days = $shelf[$stok->produk] ?? 30;
    $exp = $stok->tgl_kadaluarsa
    ? Carbon::parse($stok->tgl_kadaluarsa)
    : (clone $masuk)->addDays($days);

    for ($i = 0; $i < $jumlah; $i++) {
        BloodUnit::create([ 'stok_id'=> $stok->id,
        'kode_unit' => sprintf(
        '%s-%s-%s',
        $stok->produk,
        $stok->gol_darah,
        now()->format('Ymd') . '-' . strtoupper(Str::random(4))
        ),
        'produk' => $stok->produk,
        'gol_darah' => $stok->gol_darah,
        'rhesus' => $stok->rhesus, // 'Rh+' | 'Rh-'
        'tgl_masuk' => $masuk,
        'tgl_kadaluarsa' => $exp,
        'status' => 'available',
        ]);
        }
        }
        }