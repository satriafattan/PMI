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
        // Tambah index untuk query yang sering digunakan
        Schema::table('pemesanan_darah', function (Blueprint $table) {
            // Index untuk filter status yang sering digunakan
            $table->index('status');

            // Index untuk pencarian
            $table->index('nama_pasien');
            $table->index('rs_pemesan');

            // Index untuk tanggal (sering difilter)
            $table->index('tanggal_pemesanan');
            $table->index('created_at');
        });

        Schema::table('riwayat_pemesanan', function (Blueprint $table) {
            // Index untuk join dengan pemesanan
            if (!Schema::hasColumn('riwayat_pemesanan', 'pemesanan_id')) {
                $table->index('pemesanan_id');
            }

            // Index untuk filter tanggal
            $table->index('tanggal');

            // Index untuk pencarian nama
            $table->index('nama');
        });

        Schema::table('stok_darah', function (Blueprint $table) {
            // Index untuk filter tanggal kadaluarsa (sering digunakan)
            $table->index('tgl_masuk');

            // Composite index untuk query stok per golongan + produk
            // sudah ada: ['produk', 'gol_darah', 'rhesus']

            // Index untuk jumlah (filter stok kritis)
            $table->index('jumlah');
        });

        Schema::table('event_schedules', function (Blueprint $table) {
            // Index untuk status
            $table->index('status');

            // Index untuk pencarian
            $table->index('nama');
            $table->index('institusi_pemohon');

            // Index untuk tanggal event
            $table->index('tanggal_event');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan_darah', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['nama_pasien']);
            $table->dropIndex(['rs_pemesan']);
            $table->dropIndex(['tanggal_pemesanan']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('riwayat_pemesanan', function (Blueprint $table) {
            $table->dropIndex(['tanggal']);
            $table->dropIndex(['nama']);
        });

        Schema::table('stok_darah', function (Blueprint $table) {
            $table->dropIndex(['tgl_masuk']);
            $table->dropIndex(['jumlah']);
        });

        Schema::table('event_schedules', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['nama']);
            $table->dropIndex(['institusi_pemohon']);
            $table->dropIndex(['tanggal_event']);
        });
    }
};
