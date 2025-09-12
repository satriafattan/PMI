<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('event_schedules', function (Blueprint $table) {
            $table->time('jam_mulai')->nullable()->after('tanggal_event');
            $table->time('jam_selesai')->nullable()->after('jam_mulai');
            $table->text('lokasi_lengkap')->nullable()->after('jenis_event');

            $table->unsignedInteger('jumlah_peserta')->nullable()->after('lokasi_lengkap');
            $table->string('target_peserta')->nullable()->after('jumlah_peserta');
            $table->boolean('butuh_mobil_unit')->default(false)->after('target_peserta');
            $table->text('fasilitas_tersedia')->nullable()->after('butuh_mobil_unit');

            $table->text('catatan_tambahan')->nullable()->after('fasilitas_tersedia');
            $table->boolean('izin_publikasi')->default(false)->after('catatan_tambahan');
        });
    }

    public function down(): void
    {
        Schema::table('event_schedules', function (Blueprint $table) {
            $table->dropColumn([
                'jam_mulai',
                'jam_selesai',
                'lokasi_lengkap',
                'jumlah_peserta',
                'target_peserta',
                'butuh_mobil_unit',
                'fasilitas_tersedia',
                'catatan_tambahan',
                'izin_publikasi',
            ]);
        });
    }
};
