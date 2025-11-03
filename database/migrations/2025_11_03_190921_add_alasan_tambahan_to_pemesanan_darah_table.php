<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('pemesanan_darah', function (Blueprint $table) {
        $table->text('alasan_tambahan')->nullable()->after('alasan_transfusi');
    });
}


    /**
     * Reverse the migrations.
     */
   public function down()
{
    Schema::table('pemesanan_darah', function (Blueprint $table) {
        $table->dropColumn('alasan_tambahan');
    });
}

};
