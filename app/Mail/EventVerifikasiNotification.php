<?php

namespace App\Mail;

use App\Models\EventSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventVerifikasiNotification extends Mailable
{
    use Queueable, SerializesModels;

    public EventSchedule $event;
    public string $status;      // approved|rejected
    public ?string $catatan;

    public function __construct(EventSchedule $event, string $status, ?string $catatan = null)
    {
        $this->event   = $event;
        $this->status  = strtolower($status);
        $this->catatan = $catatan;
    }

    public function build()
    {
        $statusLabel = match ($this->status) {
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default    => ucfirst($this->status),
        };

        return $this->subject("[PMI] Status Pengajuan Event: {$statusLabel}")
            ->view('emails.event-verifikasi-notification', [
                'event'   => $this->event,
                'status'  => $this->status,
                'catatan' => $this->catatan,
            ]);
    }
}
