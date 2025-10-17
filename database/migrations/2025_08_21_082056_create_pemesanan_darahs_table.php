<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemesanan_darah', function (Blueprint $t) {
            $t->id();                                 // ID pemesanan
            $t->date('tanggal_pemesanan');            // tanggal pemesanan

            // identitas pasien & RS
            $t->string('status');                     // pending | approved | rejected (opsional: ubah ke enum)
            $t->string('nama_suami_istri');
            $t->string('diagnosa_klinik');
            $t->string('pernah_serologi');
            $t->string('lokasi_serologi');
            $t->date('tanggal_transfusi')->nullable(); // <-- date, bukan string
            $t->string('hasil_serologi');

            $t->string('email');
            $t->string('nomor_telepon');
            $t->string('nama_dokter');
            $t->enum('jenis_kelamin', ['L', 'P']);
            $t->string('no_rekap_rs')->nullable();
            $t->string('no_regis_rs')->nullable();

            // data pemesan (public user non-login)
            $t->string('nama_pasien');
            $t->string('rs_pemesan')->nullable();
            $t->date('tanggal_permintaan')->nullable();

            // kebutuhan darah
            $t->enum('gol_darah', ['A', 'B', 'AB', 'O']);
            $t->enum('rhesus', ['+', '-']);
            $t->string('produk');                      // jenis produk darah
            $t->unsignedInteger('jumlah_kantong')->default(1);

            // alasan & pemeriksaan
            $t->text('alasan_transfusi')->nullable();
            $t->text('gejala_transfusi')->nullable();
            $t->boolean('cek_transfusi')->default(false);

            $t->timestamps();
        });
    }

    public function down(): void
    {
        // perbaiki nama tabel (tanpa 's')
        Schema::dropIfExists('pemesanan_darah');
    }
};
