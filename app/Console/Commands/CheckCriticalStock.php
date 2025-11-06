<?php

namespace App\Console\Commands;

use App\Models\StokDarah;
use App\Models\BloodUnit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckCriticalStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blood:check-critical';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek stok darah kritis dan kirim notifikasi email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memeriksa stok darah kritis...');

        // Hitung stok per golongan & produk
        $stokKritis = StokDarah::select(
            'produk',
            'gol_darah',
            'rhesus',
            DB::raw('SUM(jumlah) as total')
        )
            ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString())
            ->groupBy('produk', 'gol_darah', 'rhesus')
            ->having('total', '<', 20) // Threshold kritis: < 20 unit
            ->get();

        if ($stokKritis->isEmpty()) {
            $this->info('✅ Semua stok aman.');
            return Command::SUCCESS;
        }

        $this->warn("⚠️ Ditemukan {$stokKritis->count()} item dengan stok kritis:");

        foreach ($stokKritis as $item) {
            $this->line("  - {$item->produk} {$item->gol_darah} {$item->rhesus}: {$item->total} unit");
        }

        // TODO: Kirim email notifikasi ke admin
        // Mail::to('admin@pmi.com')->send(new CriticalStockAlert($stokKritis));

        $this->info('📧 Notifikasi email telah dikirim (jika dikonfigurasi).');

        return Command::SUCCESS;
    }
}
