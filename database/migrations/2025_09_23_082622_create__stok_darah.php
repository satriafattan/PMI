<?php
// database/migrations/2025_09_23_000000_create_blood_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stok_darah', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->nullable();      // mis: WB, PRC, TC, FFP, dll (opsional)
            $table->string('name');                       // mis: WB: Whole Blood, PRC: Packed Red Cell, dst.

            // stok per golongan
            $table->unsignedInteger('a_stock')->default(0);
            $table->unsignedInteger('ab_stock')->default(0);
            $table->unsignedInteger('b_stock')->default(0);
            $table->unsignedInteger('o_stock')->default(0);

            // ambang untuk kartu "Stok Menipis" & "Stok Kritis"
            $table->unsignedInteger('low_threshold')->default(25);
            $table->unsignedInteger('critical_threshold')->default(10);

            $table->timestamps();
            $table->index(['name', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_darah');
    }
};
