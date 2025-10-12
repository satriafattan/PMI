<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananDarah extends Model
{
    protected $table = 'pemesanan_darah';
    protected $fillable = [
        'kode',
        'status',
        'tanggal_pemesanan',
        'rs_pemesan',
        'nama_pasien',
        'nama_dokter',
        'jenis_kelamin',
        'no_regis_rs',
        'nama_suami_istri',
        'diagnosa_klinik',
        'pernah_serologi',
        'lokasi_serologi',
        'tanggal_transfusi',
        'hasil_serologi',
        'alasan_transfusi',
        'produk',
        'jumlah_kantong',
        'gol_darah',
        'rhesus',
        'nomor_telepon',
        'email',
        'cek_transfusi',
        // tambahkan field lain yang memang ada di skema kamu
    ];

    protected $casts = [
        'tanggal_pemesanan' => 'date',
        'tanggal_transfusi' => 'date',
        'cek_transfusi'     => 'boolean',
    ];
}
