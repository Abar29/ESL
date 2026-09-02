<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('teacher_id')->constrained('teacher_profiles')->onDelete('cascade');
            $table->foreignUuid('slot_id')->constrained('availability_slots')->onDelete('cascade');
            $table->enum('status', ['pending_payment', 'pending_verification', 'confirmed', 'declined', 'cancelled', 'completed'])->default('pending_payment');
            $table->enum('payment_method', ['gcash', 'gotyme', 'maya'])->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('screenshot_path')->nullable();
            $table->timestamp('held_until')->nullable();
            $table->timestamps();

            $table->unique(['slot_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
