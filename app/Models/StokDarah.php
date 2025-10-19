<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokDarah extends Model
{
    protected $table = 'stok_darah';

    protected $fillable = [
        'produk',
        'gol_darah',          // 'A','AB','B','O'
        'jumlah',             // int
        'tgl_masuk',          // date
        'tgl_kadaluarsa',     // date
    ];

    protected $casts = [
        'jumlah'         => 'integer',
        'tgl_masuk'      => 'date',
        'tgl_kadaluarsa' => 'date',
    ];
}
