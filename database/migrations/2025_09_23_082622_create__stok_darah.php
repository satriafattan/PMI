<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
       Schema::create('stok_darah', function (Blueprint $table) {
            $table->id();
            $table->string('code', 16)->nullable()->unique();
            $table->string('produk', 191);

            $table->unsignedInteger('a_stock')->default(0);
            $table->unsignedInteger('ab_stock')->default(0);
            $table->unsignedInteger('b_stock')->default(0);
            $table->unsignedInteger('o_stock')->default(0);

            $table->unsignedInteger('low_threshold')->default(25);
            $table->unsignedInteger('critical_threshold')->default(10);

            $table->timestamps();
            $table->index('produk');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('stok_darah');
    }
};
