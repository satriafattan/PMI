<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Mail\VerifikasiPemesananMail;
use App\Models\PemesananDarah;

try {
    // Ambil pemesanan terakhir untuk test
    $pemesanan = PemesananDarah::latest()->first();
    
    if (!$pemesanan) {
        echo "❌ Tidak ada data pemesanan untuk di-test\n";
        exit(1);
    }
    
    echo "📧 Testing email untuk pemesanan ID: {$pemesanan->id}\n";
    echo "📬 Email tujuan: {$pemesanan->email}\n";
    echo "📝 Status: approved\n\n";
    
    echo "Mengirim email...\n";
    
    Mail::to($pemesanan->email)
        ->send(new VerifikasiPemesananMail($pemesanan, 'approved'));
    
    echo "✅ Email berhasil dikirim!\n";
    echo "Silakan cek inbox atau folder spam: {$pemesanan->email}\n";
    
} catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
    echo "❌ SMTP Transport Error:\n";
    echo "   " . $e->getMessage() . "\n";
    echo "\n🔧 Cek konfigurasi SMTP di .env:\n";
    echo "   MAIL_HOST=" . config('mail.mailers.smtp.host') . "\n";
    echo "   MAIL_PORT=" . config('mail.mailers.smtp.port') . "\n";
    echo "   MAIL_USERNAME=" . config('mail.mailers.smtp.username') . "\n";
    exit(1);
} catch (\Throwable $e) {
    echo "❌ Error: " . get_class($e) . "\n";
    echo "   Message: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}
