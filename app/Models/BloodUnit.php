<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class BloodUnit extends Model
{
    use HasFactory;

    protected $table = 'blood_units';

    protected $fillable = [
        'stok_id',
        'kode_unit',
        'produk',
        'gol_darah',
        'rhesus',
        'tgl_masuk',
        'tgl_kadaluarsa',
        'status',        // available|reserved|dispensed|discarded
    ];

    protected $casts = [
        'tgl_masuk'      => 'date',
        'tgl_kadaluarsa' => 'date',
    ];

    public function stok()
    {
        return $this->belongsTo(StokDarah::class, 'stok_id');
    }

    /* ============
     * Local scopes
     * ============ */

    /** Unit yang siap pakai & belum kedaluwarsa */
    public function scopeAvailable(Builder $q): Builder
    {
        return $q->where('status', 'available')
            ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString());
    }

    /** Unit yang sudah kedaluwarsa (exp < hari ini) */
    public function scopeExpired(Builder $q): Builder
    {
        return $q->whereDate('tgl_kadaluarsa', '<', now()->toDateString());
    }

    /** Opsional: masih valid (belum exp), status apa pun */
    public function scopeActive(Builder $q): Builder
    {
        return $q->whereDate('tgl_kadaluarsa', '>=', now()->toDateString());
    }
}
