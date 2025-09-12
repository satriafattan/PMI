<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // PRC, Trombosit, FFP, WB, dll.
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique('name');                // hindari duplikasi nama produk
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
