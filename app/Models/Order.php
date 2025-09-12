<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'nama_pemesan',
        'rs_pemesan',
        'tanggal',
        'nama_pasien',
        'nama_dokter',
        'no_rekap_rs',
        'no_regis_rs',
        'blood_type',
        'rhesus',
        'product_id',
        'jumlah_kantong',
        'alasan_transfusi',
        'gejala_transfusi',
        'cek_transfusi'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function verification()
    {
        return $this->hasOne(Verification::class);
    }
}
