<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPemesanan extends Model
{
    protected $table = 'riwayat_pemesanan';
    protected $fillable = [
        'pemesanan_id',
        'nama',
        'tanggal',
        'gol_darah',
        'rhesus',
        'jumlah_kantong',
        'produk',
        'aksi'
    ];

    public function pemesanan()
    {
        return $this->belongsTo(PemesananDarah::class, 'pemesanan_id');
    }
}
