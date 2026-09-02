<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('availability_slots', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('teacher_id')->constrained('teacher_profiles')->onDelete('cascade');
            $table->date('slot_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['available', 'held', 'booked', 'unavailable'])->default('available');
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->unique(['teacher_id', 'slot_date', 'start_time', 'end_time'], 'uniq_teacher_slot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('availability_slots');
    }
};
