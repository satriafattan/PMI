<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSchedule extends Model
{
    protected $fillable = [
        // A
        'nama',
        'institusi_pemohon',
        'nomor_telefon',
        'email',
        // B
        'tanggal_event',
        'jam_mulai',
        'jam_selesai',
        'jenis_event',
        'lokasi_lengkap',
        // C
        'jumlah_peserta',
        'target_peserta',
        'butuh_mobil_unit',
        'fasilitas_tersedia',
        // D
        'catatan_tambahan',
        'izin_publikasi',
        // opsional
        'status',
    ];

    protected $casts = [
        'tanggal_event'   => 'date',
        'jam_mulai'       => 'datetime:H:i',
        'jam_selesai'     => 'datetime:H:i',
        'butuh_mobil_unit' => 'boolean',
        'izin_publikasi'  => 'boolean',
    ];
}
