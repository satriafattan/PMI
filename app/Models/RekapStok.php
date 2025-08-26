<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapStok extends Model
{
    protected $table = 'rekap_stok';
    protected $fillable = ['id_darah','komponen','gol_darah','rhesus','tanggal_masuk','tanggal_keluar','keterangan'];
}