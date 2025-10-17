<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananDarah extends Model
{
    // Tetap mengikuti nama tabel yang ada di DB kamu (singular)
    protected $table = 'pemesanan_darah';

    protected $fillable = [
        'kode',
        'status',                // pending | approved | rejected
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

    /** -----------------------
     *  Relasi ke verifikasi
     *  ----------------------*/
    // Riwayat verifikasi (banyak)
    public function verifikasis()
    {
        return $this->hasMany(VerifikasiPemesanan::class, 'pemesanan_id');
    }

    // Verifikasi terakhir (untuk ringkas di UI)
    // Jika tabel verifikasi PUNYA kolom timestamps, ini akan pakai created_at.
    // Kalau kamu ingin berdasarkan tanggal_permintaan, pakai latestOfMany('tanggal_permintaan').
    public function verifikasiTerakhir()
    {
        return $this->hasOne(VerifikasiPemesanan::class, 'pemesanan_id')->latestOfMany('tanggal_permintaan');
    }

    /** -----------------------
     *  Scopes status
     *  ----------------------*/
    public function scopePending($q)  { return $q->where('status', 'pending'); }
    public function scopeApproved($q) { return $q->where('status', 'approved'); }
    public function scopeRejected($q) { return $q->where('status', 'rejected'); }
}
