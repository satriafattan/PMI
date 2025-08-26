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
        Schema::create('pemesanan_darah', function (Blueprint $t) {
            $t->id();                                 // ID pemesanan
            $t->date('tanggal_pemesanan');            // tanggal pemesanan
            // identitas pasien & RS
            $t->string('nama_pasien');
            $t->string('nama_dokter');
            $t->string('no_rekap_rs')->nullable();    // no rekam medis (catatan: ERD menyebut no_rekap_rs)
            $t->string('no_regis_rs')->nullable();
            // data pemesan (public user non-login)
            $t->string('nama_pemesan');
            $t->string('rs_pemesan')->nullable();
            $t->date('tanggal_permintaan')->nullable();
    
            // kebutuhan darah
            $t->enum('gol_darah', ['A','B','AB','O']);
            $t->enum('rhesus', ['+','-']);
            $t->string('produk');                     // jenis produk darah
            $t->unsignedInteger('jumlah_kantong')->default(1);
    
            // alasan & pemeriksaan
            $t->text('alasan_transfusi')->nullable();
            $t->text('gejala_transfusi')->nullable();
            $t->boolean('cek_transfusi')->default(false);
    
            $t->timestamps();
        });
    }    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan_darahs');
    }
};
