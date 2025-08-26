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
    Schema::create('verifikasi_pemesanan', function (Blueprint $t) {
        $t->id();                                   // ID verifikasi
        $t->foreignId('pemesanan_id')->constrained('pemesanan_darah')->cascadeOnDelete();
        $t->string('nama_pemesan');                 // redundan utk snapshot verifikasi
        $t->string('rs_pemesan')->nullable();
        $t->enum('golongan_darah',['A','B','AB','O']);
        $t->enum('rhesus',['+','-']);
        $t->string('produk_darah');
        $t->date('tanggal_permintaan');
        $t->enum('status',['pending','approved','rejected'])->default('pending');
        $t->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikasi_pemesanans');
    }
};
