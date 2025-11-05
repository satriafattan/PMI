<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSchedule extends Model
{
    protected $fillable = [
        'nama',
        'institusi_pemohon',
        'nomor_telefon',
        'email',
        'surat_instansi_path',
        'tanggal_event',
        'jam_mulai',
        'jam_selesai',
        'jenis_event',
        'lokasi_lengkap',
        'jumlah_peserta',
        'target_peserta',
        'butuh_mobil_unit',
        'fasilitas_tersedia',
        'catatan_tambahan',
        'izin_publikasi',
        'status',
    ];

    protected $casts = [
        'tanggal_event'   => 'date',
        'butuh_mobil_unit' => 'boolean',
        'izin_publikasi'  => 'boolean',
    ];

    public function verifikasi()
    {
        return $this->hasMany(EventVerification::class, 'event_schedule_id');
    }

    public function verifikasiTerakhir()
    {
        return $this->hasOne(EventVerification::class, 'event_schedule_id')->latestOfMany();
    }
}
