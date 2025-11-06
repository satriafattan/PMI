<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokDarah extends Model
{
    use HasFactory;

    protected $table = 'stok_darah';

    protected $fillable = [
        'produk',
        'gol_darah',
        'rhesus',
        'jumlah',
        'tgl_masuk',
        'tgl_kadaluarsa',
    ];

    protected $casts = [
        'jumlah'         => 'integer',
        'tgl_masuk'      => 'date',
        'tgl_kadaluarsa' => 'date',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];
}
