<?php

namespace App\Mail;

use App\Models\PemesananDarah;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifikasiPemesananMail extends Mailable
{
    use Queueable, SerializesModels;

    public PemesananDarah $pemesanan;
    public string $status;         // approved|rejected|pending
    public ?string $refNumber;     // opsional, untuk PDF nanti

    public function __construct(PemesananDarah $pemesanan, string $status, ?string $refNumber = null)
    {
        $this->pemesanan = $pemesanan;
        $this->status    = $status;
        $this->refNumber = $refNumber;
    }

    public function build()
    {
        // Subjek bergantung status (E3)
        $subject = '[PMI] Status Pemesanan Darah Anda: ' . ucfirst($this->status);

        return $this->subject($subject)
            ->markdown('emails.verifikasi-pemesanan', [
                'pemesanan' => $this->pemesanan,
                'status'    => $this->status,
                'refNumber' => $this->refNumber, // belum dipakai sampai PDF siap
            ]);
    }
}
