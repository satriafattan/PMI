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
        'surat_instansi_path',
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
        // Opsional admin
        'status',
    ];
}
