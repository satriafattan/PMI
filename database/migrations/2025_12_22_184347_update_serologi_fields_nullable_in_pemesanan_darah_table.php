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
        Schema::table('pemesanan_darah', function (Blueprint $table) {
            // Ubah kolom serologi menjadi nullable
            $table->string('pernah_serologi', 10)->nullable()->change();
            $table->string('lokasi_serologi', 120)->nullable()->change();
            $table->date('tanggal_serologi')->nullable()->change();
            $table->text('hasil_serologi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan_darah', function (Blueprint $table) {
            // Kembalikan kolom serologi menjadi NOT NULL
            $table->string('pernah_serologi', 10)->nullable(false)->change();
            $table->string('lokasi_serologi', 120)->nullable(false)->change();
            $table->date('tanggal_serologi')->nullable(false)->change();
            $table->text('hasil_serologi')->nullable(false)->change();
        });
    }
};
