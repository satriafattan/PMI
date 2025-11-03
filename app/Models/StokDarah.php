<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokDarah extends Model
{
    protected $table = 'stok_darah';

    protected $fillable = [
        'id',
        'produk',
        'gol_darah',
        'rhesus', 
        'jumlah',
        'tgl_masuk',
        'tgl_kadaluarsa',
    ];

    protected $casts = [
        'tgl_masuk'      => 'date',
        'tgl_kadaluarsa' => 'date',
    ];

    public function verifikasi()
    {
        return $this->hasMany(VerifikasiPemesanan::class, 'stok_id');
    }
}
