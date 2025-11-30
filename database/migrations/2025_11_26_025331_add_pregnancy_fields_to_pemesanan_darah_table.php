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
            $table->unsignedTinyInteger('jumlah_kehamilan')->nullable()->after('jenis_kelamin');
            $table->enum('abortus', ['Ya', 'Tidak'])->nullable()->after('jumlah_kehamilan');
            $table->enum('riwayat_hemolitik', ['Ya', 'Tidak'])->nullable()->after('abortus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan_darah', function (Blueprint $table) {
            $table->dropColumn(['jumlah_kehamilan', 'abortus', 'riwayat_hemolitik']);
        });
    }
};
