<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('verifikasi_pemesanan', function (Blueprint $t) {
            $t->id();

            $t->foreignId('pemesanan_id')
                ->constrained('pemesanan_darah')
                ->cascadeOnDelete();

            // Relasi ke stok darah
            $t->foreignId('stok_id')
                ->nullable()
                ->constrained('stok_darah')
                ->nullOnDelete();

            $t->string('nama_pemesan');
            $t->string('rs_pemesan')->nullable();
            $t->enum('gol_darah', ['A', 'B', 'AB', 'O']);
            $t->enum('rhesus', ['Rh+', 'Rh-']);
            $t->string('produk');
            $t->date('tanggal_permintaan');
            $t->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $t->timestamps();

            // Index untuk query cepat
            $t->index(['status', 'gol_darah', 'rhesus', 'produk']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifikasi_pemesanan');
    }
};
