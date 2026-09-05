<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->string('gcash_name')->nullable()->after('gcash_number');
            $table->string('gotyme_name')->nullable()->after('gotyme_number');
            $table->string('maya_name')->nullable()->after('maya_number');
        });
    }

    public function down(): void
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->dropColumn(['gcash_name', 'gotyme_name', 'maya_name']);
        });
    }
};
