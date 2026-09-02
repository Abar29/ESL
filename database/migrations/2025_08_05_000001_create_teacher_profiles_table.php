<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->text('bio')->nullable();
            $table->string('profile_pic')->nullable();
            $table->string('gcash_number')->nullable();
            $table->string('gotyme_number')->nullable();
            $table->string('maya_number')->nullable();
            $table->string('zoom_link')->nullable();
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_profiles');
    }
};
