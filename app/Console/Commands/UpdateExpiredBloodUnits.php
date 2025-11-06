<?php

namespace App\Console\Commands;

use App\Models\BloodUnit;
use App\Models\StokDarah;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateExpiredBloodUnits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blood:update-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status unit darah yang sudah kadaluarsa dan sync dengan stok';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai proses update unit darah kadaluarsa...');

        DB::transaction(function () {
            // Update blood units yang sudah expired
            $updatedUnits = BloodUnit::where('status', 'available')
                ->whereDate('tgl_kadaluarsa', '<', now()->toDateString())
                ->update(['status' => 'expired']);

            $this->info("✅ {$updatedUnits} unit darah di-update menjadi expired.");

            // Sinkronkan stok_darah (set jumlah = 0 untuk batch yang sudah expired)
            $expiredBatches = StokDarah::whereDate('tgl_kadaluarsa', '<', now()->toDateString())
                ->where('jumlah', '>', 0)
                ->get();

            $count = 0;
            foreach ($expiredBatches as $batch) {
                // Set jumlah ke 0 untuk batch yang expired
                $batch->update(['jumlah' => 0]);
                $count++;
            }

            $this->info("✅ {$count} batch stok di-update menjadi 0 (expired).");

            // Clear cache stok
            \App\Services\StokCacheService::clearCache();
            $this->info("✅ Cache stok berhasil di-clear.");
        });

        $this->info('🎉 Proses selesai!');
        return Command::SUCCESS;
    }
}
