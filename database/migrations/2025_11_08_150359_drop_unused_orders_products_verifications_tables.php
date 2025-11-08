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
        // Hapus tabel yang tidak terpakai (bagian dari sistem lama)
        Schema::dropIfExists('verifications');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate tables jika rollback (dari migration asli)
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique('name');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemesan');
            $table->string('rs_pemesan');
            $table->date('tanggal');
            $table->string('nama_pasien');
            $table->string('nama_dokter')->nullable();
            $table->string('no_rekap_rs')->nullable();
            $table->string('no_regis_rs')->nullable();
            $table->enum('blood_type', ['A', 'B', 'O', 'AB']);
            $table->enum('rhesus', ['+', '-']);
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('jumlah_kantong')->default(1);
            $table->string('alasan_transfusi')->nullable();
            $table->string('gejala_transfusi')->nullable();
            $table->string('cek_transfusi')->nullable();
            $table->timestamps();
            $table->index(['tanggal']);
            $table->index(['blood_type', 'rhesus']);
        });

        Schema::create('verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('requested_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'requested_at']);
        });
    }
};
