<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananDarah extends Model
{
    protected $table = 'pemesanan_darah';

    protected $fillable = [
        'status',
        'tanggal_pemesanan',
        'rs_pemesan',
        'nama_pasien',
        'nama_suami_istri',
        'diagnosa_klinik',
        'pernah_serologi',
        'lokasi_serologi',
        'tanggal_transfusi',
        'hasil_serologi',
        'alasan_transfusi',
        'gejala_transfusi',
        'produk',
        'jumlah_kantong',
        'gol_darah',
        'rhesus',
        'nomor_telepon',
        'email',
        'cek_transfusi',
        'no_rekap_rs',
        'no_regis_rs',
        'nama_dokter',
        'jenis_kelamin',
        'tanggal_permintaan',
    ];

    protected $casts = [
        'tanggal_pemesanan' => 'date',
        'tanggal_transfusi' => 'date',
        'tanggal_permintaan' => 'date',
        'cek_transfusi'     => 'boolean',
        'jumlah_kantong'    => 'integer',
    ];

    public function verifikasis()
    {
        return $this->hasMany(VerifikasiPemesanan::class, 'pemesanan_id');
    }

    public function verifikasiTerakhir()
    {
        return $this->hasOne(VerifikasiPemesanan::class, 'pemesanan_id')
            ->latestOfMany('tanggal_permintaan');
    }

    public function scopePending($q)
    {
        return $q->where('status', 'pending');
    }
    public function scopeApproved($q)
    {
        return $q->where('status', 'approved');
    }
    public function scopeRejected($q)
    {
        return $q->where('status', 'rejected');
    }
}
