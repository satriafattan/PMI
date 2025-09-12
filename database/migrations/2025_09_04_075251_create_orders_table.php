<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();                                        // ID pemesanan
            // Data pemesan (user publik, tanpa login)
            $table->string('nama_pemesan');
            $table->string('rs_pemesan');

            // Data klinis
            $table->date('tanggal');                             // tanggal pemesanan
            $table->string('nama_pasien');
            $table->string('nama_dokter')->nullable();
            $table->string('no_rekap_rs')->nullable();
            $table->string('no_regis_rs')->nullable();

            // Kebutuhan darah
            $table->enum('blood_type', ['A', 'B', 'O', 'AB']);
            $table->enum('rhesus', ['+', '-']);
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('jumlah_kantong')->default(1);

            // Keterangan klinis tambahan
            $table->string('alasan_transfusi')->nullable();
            $table->string('gejala_transfusi')->nullable();
            $table->string('cek_transfusi')->nullable();

            $table->timestamps();

            // Index untuk query laporan/riwayat
            $table->index(['tanggal']);
            $table->index(['blood_type', 'rhesus']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
