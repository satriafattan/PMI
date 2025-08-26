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
    Schema::create('rekap_stok', function (Blueprint $t) {
        $t->id();
        $t->string('id_darah')->unique();           // ID darah
        $t->string('komponen');                     // komponen darah
        $t->enum('gol_darah',['A','B','AB','O']);
        $t->enum('rhesus',['+','-']);
        $t->date('tanggal_masuk');
        $t->date('tanggal_keluar')->nullable();
        $t->string('keterangan')->nullable();
        $t->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_stoks');
    }
};
