<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifikasiPemesanan extends Model
{
    // Nama tabel
    protected $table = 'verifikasi_pemesanan';

    protected $fillable = [
        'pemesanan_id',
        'stok_id',
        'nama_pemesan',
        'rs_pemesan',
        'gol_darah',          // <- sudah diganti dari golongan_darah
        'rhesus',
        'produk',             // <- sudah diganti dari produk_darah
        'tanggal_permintaan',
        'status',             // pending | approved | rejected
        'note',               // Catatan untuk verifikasi
    ];

    protected $casts = [
        'tanggal_permintaan' => 'date',
    ];

    /** Relasi ke PemesananDarah */
    public function stok()
    {
        return $this->belongsTo(StokDarah::class, 'stok_id');
    }

    public function pemesanan()
    {
        return $this->belongsTo(PemesananDarah::class, 'pemesanan_id');
    }

    /** Scopes status */
    public function scopeApproved($q)
    {
        return $q->where('status', 'approved');
    }
    public function scopeRejected($q)
    {
        return $q->where('status', 'rejected');
    }
    public function scopePending($q)
    {
        return $q->where('status', 'pending');
    }
}
