<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventVerification extends Model
{
    protected $fillable = [
        'event_schedule_id',
        'status',
        'catatan',
        'decided_by',
        'decided_at',
    ];

    protected $casts = [
        'decided_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(EventSchedule::class, 'event_schedule_id');
    }

    public function petugas()
    {
        // Guard admin -> tabel admins
        return $this->belongsTo(\App\Models\Admin::class, 'decided_by');
    }
}