<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifikasiPemesanan extends Model
{
    protected $table = 'verifikasi_pemesanan';
    protected $fillable = [
        'pemesanan_id','nama_pemesan','rs_pemesan','golongan_darah','rhesus',
        'produk_darah','tanggal_permintaan','status'
    ];

    public function pemesanan()
    {
        return $this->belongsTo(PemesananDarah::class,'pemesanan_id');
    }
}
