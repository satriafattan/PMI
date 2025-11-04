<?php

namespace App\Mail;

use App\Models\PemesananDarah;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class VerifikasiPemesananMail extends Mailable
{
    use Queueable, SerializesModels;

    public PemesananDarah $pemesanan;
    public string $status;      // approved|rejected|pending
    public ?string $refNumber;  // opsional (ditampilkan jika view PDF memakainya)

    public function __construct(PemesananDarah $pemesanan, string $status, ?string $refNumber = null)
    {
        $this->pemesanan = $pemesanan;
        $this->status    = strtolower($status);
        $this->refNumber = $refNumber;
    }

    public function build()
    {
        // Subjek lebih rapi & konsisten
        $statusLabel = match ($this->status) {
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'pending'  => 'Menunggu Verifikasi',
            default    => ucfirst($this->status),
        };

        // Render PDF dari view pdf.*
        $pdf = Pdf::loadView('pdf.verifikasi-pemesanan', [
            'pemesanan' => $this->pemesanan,
            'status'    => $this->status,
            'refNumber' => $this->refNumber,
        ])->setPaper('a4');

        return $this->subject("[PMI] Status Pemesanan Darah: {$statusLabel}")
            // Pakai VIEW HTML (bukan markdown) agar styling/branding maksimal
            ->view('emails.verifikasi-pemesanan', [
                'pemesanan' => $this->pemesanan,
                'status'    => $this->status,
                'refNumber' => $this->refNumber,
            ])
            // Lampirkan PDF
            ->attachData($pdf->output(), 'verifikasi-pemesanan.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
