<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stok_darah', function (Blueprint $table) {
            $table->id();

            $table->enum('produk', ['WB', 'PRC', 'TC', 'FFP', 'CRYO', 'LP', 'TCA', 'CP']);
            $table->enum('gol_darah', ['A', 'B', 'AB', 'O']);
            $table->enum('rhesus', ['Rh+', 'Rh-']);
            $table->unsignedInteger('jumlah')->default(0);

            $table->date('tgl_masuk');
            $table->date('tgl_kadaluarsa');

            $table->timestamps();

            $table->index(['produk', 'gol_darah', 'rhesus']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_darah');
    }
};
