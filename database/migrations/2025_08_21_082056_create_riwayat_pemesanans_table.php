<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riwayat_pemesanan', function (Blueprint $t) {
            $t->id();
            $t->foreignId('pemesanan_id')->nullable()->constrained('pemesanan_darah')->nullOnDelete();
            $t->string('nama');                         // nama pasien / pemesan, sesuai kebutuhan laporan
            $t->date('tanggal');
            $t->enum('gol_darah',['A','B','AB','O']);
            $t->enum('rhesus',['+','-']);
            $t->unsignedInteger('jumlah_kantong');
            $t->string('produk');
            $t->string('aksi');                         // dibuat, diubah, diverifikasi, ditolak, dll.
            $t->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pemesanans');
    }
};
