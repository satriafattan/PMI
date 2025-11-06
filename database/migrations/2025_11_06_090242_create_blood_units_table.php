<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
public function up(): void {
Schema::create('blood_units', function (Blueprint $t) {
$t->id();
$t->string('kode_unit', 32)->unique(); // BD0001 dst
$t->foreignId('stok_id')->constrained('stok_darah')->cascadeOnDelete();

$t->string('produk', 16);
$t->enum('gol_darah', ['A','B','AB','O']);
$t->enum('rhesus', ['Rh+','Rh-'])->nullable();

$t->date('tgl_masuk');
$t->date('tgl_kadaluarsa');

$t->enum('status', ['available','reserved','dispensed','expired','discarded'])->index()->default('available');

$t->foreignId('pemesanan_id')->nullable()->constrained('pemesanan_darah')->nullOnDelete();
$t->string('penerima')->nullable();

$t->timestamps();
$t->softDeletes();

$t->index(['produk','gol_darah','rhesus']);
$t->index(['tgl_kadaluarsa']);
});
}
public function down(): void { Schema::dropIfExists('blood_units'); }
};