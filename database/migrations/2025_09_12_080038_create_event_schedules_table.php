<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_schedules', function (Blueprint $table) {
            $table->id();

            // A. Data Pemohon
            $table->string('nama');
            $table->string('institusi_pemohon');
            $table->string('nomor_telefon', 30);
            $table->string('email');
            $table->string('surat_instansi_path')->nullable();

            // B. Detail Event
            $table->date('tanggal_event');
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('jenis_event');      
            $table->text('lokasi_lengkap')->nullable();

            // C. Estimasi & Kebutuhan
            $table->unsignedInteger('jumlah_peserta')->nullable();
            $table->string('target_peserta')->nullable();   
            $table->boolean('butuh_mobil_unit')->default(false);
            $table->text('fasilitas_tersedia')->nullable(); 

            // D. Lainnya
            $table->text('catatan_tambahan')->nullable();
            $table->boolean('izin_publikasi')->default(false);

            // Opsional (untuk admin kelak)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();

            $table->index(['tanggal_event', 'jenis_event']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_schedules');
    }
};
