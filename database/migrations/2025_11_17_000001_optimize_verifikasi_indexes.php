<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Optimasi index untuk proses verifikasi pemesanan.
     * 
     * 1. Composite index untuk FEFO query pada blood_units
     * 2. Index pada pemesanan_darah untuk filter admin
     * 3. Index pada stok_darah untuk update batch
     */
    public function up(): void
    {
        Schema::table('blood_units', function (Blueprint $table) {
            // Drop index lama yang kurang optimal
            $table->dropIndex(['produk', 'gol_darah', 'rhesus']);
            $table->dropIndex(['tgl_kadaluarsa']);

            // Composite index optimal untuk FEFO query
            // Covering index: produk + gol_darah + rhesus + status + tgl_kadaluarsa
            // Urutan sesuai WHERE clause dan ORDER BY
            $table->index(
                ['produk', 'gol_darah', 'rhesus', 'status', 'tgl_kadaluarsa'],
                'idx_blood_units_fefo_allocation'
            );

            // Index untuk query by pemesanan_id (saat cek alokasi)
            $table->index('pemesanan_id', 'idx_blood_units_pemesanan');

            // Index untuk query by stok_id (saat sync batch)
            $table->index('stok_id', 'idx_blood_units_stok');
        });

        Schema::table('pemesanan_darah', function (Blueprint $table) {
            // Composite index untuk filter admin (status + produk + gol_darah)
            $table->index(['status', 'produk', 'gol_darah'], 'idx_pemesanan_admin_filter');

            // Index untuk search by nama & RS
            $table->index('nama_pasien', 'idx_pemesanan_nama');
            $table->index('rs_pemesan', 'idx_pemesanan_rs');
        });

        Schema::table('stok_darah', function (Blueprint $table) {
            // Index untuk lookup by ID (saat update batch)
            // Note: id sudah auto-index sebagai primary key, tapi pastikan ada

            // Tambah index untuk tgl_kadaluarsa (untuk filter expired)
            $table->index('tgl_kadaluarsa', 'idx_stok_kadaluarsa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_units', function (Blueprint $table) {
            $table->dropIndex('idx_blood_units_fefo_allocation');
            $table->dropIndex('idx_blood_units_pemesanan');
            $table->dropIndex('idx_blood_units_stok');

            // Restore original indexes
            $table->index(['produk', 'gol_darah', 'rhesus']);
            $table->index(['tgl_kadaluarsa']);
        });

        Schema::table('pemesanan_darah', function (Blueprint $table) {
            $table->dropIndex('idx_pemesanan_admin_filter');
            $table->dropIndex('idx_pemesanan_nama');
            $table->dropIndex('idx_pemesanan_rs');
        });

        Schema::table('stok_darah', function (Blueprint $table) {
            $table->dropIndex('idx_stok_kadaluarsa');
        });
    }
};
