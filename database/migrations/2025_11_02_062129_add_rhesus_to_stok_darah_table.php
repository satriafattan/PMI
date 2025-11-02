<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('stok_darah', function (Blueprint $table) {
            // tambah kolom rhesus (+ / -)
            $table->enum('rhesus', ['+', '-'])
                ->after('gol_darah');

            // index komposit baru untuk pencarian cepat
            $table->index(['produk', 'gol_darah', 'rhesus']);
        });
    }

    public function down(): void
    {
        Schema::table('stok_darah', function (Blueprint $table) {
            $table->dropIndex(['produk', 'gol_darah', 'rhesus']);
            $table->dropColumn('rhesus');
        });
    }
};
