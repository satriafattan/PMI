<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemesanan_darah', function (Blueprint $t) {
            $t->id();

            // meta
            $t->date('tanggal_pemesanan');
            $t->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            // identitas pasien & RS
            $t->string('nama_suami_istri')->nullable();
            $t->string('diagnosa_klinik');
            $t->enum('pernah_serologi', ['Ya', 'Tidak']);
            $t->string('lokasi_serologi')->nullable();
            $t->date('tanggal_serologi')->nullable();
            $t->date('tanggal_transfusi')->nullable();
            $t->text('hasil_serologi');

            $t->string('email', 150);
            $t->string('nomor_telepon', 30);
            $t->string('nama_dokter');
            $t->enum('jenis_kelamin', ['L', 'P']);
            $t->string('no_rekap_rs')->nullable();
            $t->string('no_regis_rs');

            // data pemesan (public user non-login)
            $t->string('nama_pasien');
            $t->string('rs_pemesan');
            $t->date('tanggal_permintaan');

            // kebutuhan darah
            $t->enum('gol_darah', ['A', 'B', 'AB', 'O']);
            $t->enum('rhesus', ['Rh+', 'Rh-']);
            $t->enum('produk', ['WB', 'PRC', 'TC', 'FFP', 'CRYO', 'LP', 'TCA', 'CP']); // ⬅️ enum, sesuai form
            $t->unsignedTinyInteger('jumlah_kantong')->default(1);

            // alasan & pemeriksaan
            $t->text('alasan_transfusi');
            $t->text('gejala_transfusi')->nullable();
            $t->boolean('cek_transfusi')->default(false);

            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemesanan_darah');
    }
};
