<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_verifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('event_schedule_id')
                ->constrained('event_schedules')
                ->cascadeOnDelete();

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan')->nullable();

            // PENTING: guard admin → referensi ke tabel admins (bukan users)
            $table->foreignId('decided_by')
                ->nullable()
                ->constrained('admins')
                ->nullOnDelete();

            $table->timestamp('decided_at')->nullable();
            $table->timestamps();

            $table->index(['event_schedule_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_verifications');
    }
};
