<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokDarah extends Model
{
    protected $table = 'stok_darah';

    protected $fillable = [
        'produk',
        'gol_darah',
        'jumlah',
        'tgl_masuk',
        'tgl_kadaluarsa',
    ];

    protected $casts = [
        'tgl_masuk'      => 'date',
        'tgl_kadaluarsa' => 'date',
    ];
}
