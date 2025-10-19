<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stok_darah', function (Blueprint $table) {
            $table->id();

            // Produk (misalnya: WB, PRC, TC, dll)
            $table->string('produk', 191);

            // Golongan darah (A, B, AB, O)
            $table->enum('gol_darah', ['A', 'B', 'AB', 'O']);

            // Jumlah unit kantong
            $table->unsignedInteger('jumlah')->default(0);

            // Tanggal masuk & kadaluarsa
            $table->date('tgl_masuk');
            $table->date('tgl_kadaluarsa');

            $table->timestamps();

            // Index untuk pencarian cepat
            $table->index(['produk', 'gol_darah']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_darah');
    }
};
