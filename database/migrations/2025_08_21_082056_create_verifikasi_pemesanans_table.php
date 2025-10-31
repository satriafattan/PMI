<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('verifikasi_pemesanan')) {
            Schema::create('verifikasi_pemesanan', function (Blueprint $t) {
                $t->id();
                $t->foreignId('pemesanan_id')
                    ->constrained('pemesanan_darah')
                    ->cascadeOnDelete();
                $t->string('nama_pemesan');
                $t->string('rs_pemesan')->nullable();
                $t->enum('gol_darah', ['A', 'B', 'AB', 'O']); // <- final
                $t->enum('rhesus', ['Rh+', 'Rh-']);
                $t->string('produk');                      // <- final
                $t->date('tanggal_permintaan');
                $t->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $t->timestamps();
            });
        } else {
            // Jika tabel sudah ada tapi kolom belum sesuai, gunakan migration rename di bawah.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikasi_pemesanan'); // nama tabel yang benar (tanpa 's')
    }
};
