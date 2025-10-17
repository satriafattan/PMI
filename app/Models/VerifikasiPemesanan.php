<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifikasiPemesanan extends Model
{
    // Tetap mengikuti nama tabel yang ada di DB kamu (singular)
    protected $table = 'verifikasi_pemesanan';

    protected $fillable = [
        'pemesanan_id',
        'nama_pemesan',
        'rs_pemesan',
        'golongan_darah',
        'rhesus',
        'produk_darah',
        'tanggal_permintaan',
        'status',   // pending | approved | rejected (jika memang kamu simpan status di sini juga)
        // tambahkan field lain di tabel ini bila ada (mis. note, verified_by, dsb.)
    ];

    protected $casts = [
        'tanggal_permintaan' => 'date',
    ];

    /** -----------------------
     *  Relasi ke pemesanan
     *  ----------------------*/
    public function pemesanan()
    {
        // FK yang kamu pakai sudah benar: pemesanan_id -> pemesanan_darah.id
        return $this->belongsTo(PemesananDarah::class, 'pemesanan_id');
    }

    /** -----------------------
     *  (Opsional) Scopes status
     *  ----------------------*/
    public function scopeApproved($q) { return $q->where('status', 'approved'); }
    public function scopeRejected($q) { return $q->where('status', 'rejected'); }
    public function scopePending($q)  { return $q->where('status', 'pending'); }
}
