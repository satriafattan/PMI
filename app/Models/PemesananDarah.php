<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananDarah extends Model
{
    protected $table = 'pemesanan_darah';
    protected $fillable = [
        'tanggal_pemesanan','nama_pasien','nama_dokter','no_rekap_rs','no_regis_rs',
        'nama_pemesan','rs_pemesan','tanggal_permintaan',
        'gol_darah','rhesus','produk','jumlah_kantong',
        'alasan_transfusi','gejala_transfusi','cek_transfusi'
    ];

    public function verifikasi()
    {
        return $this->hasOne(VerifikasiPemesanan::class,'pemesanan_id');
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatPemesanan::class,'pemesanan_id');
    }
}
