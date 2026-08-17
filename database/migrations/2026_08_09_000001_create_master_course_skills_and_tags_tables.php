<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('master_course_skills')) {
            Schema::create('master_course_skills', function (Blueprint $table) {
                $table->id();
                $table->foreignId('master_course_id')->constrained('master_courses')->onDelete('cascade');
                $table->foreignId('skill_id')->constrained('skills')->onDelete('cascade');
                $table->boolean('is_main')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('master_course_tags')) {
            Schema::create('master_course_tags', function (Blueprint $table) {
                $table->id();
                $table->foreignId('master_course_id')->constrained('master_courses')->onDelete('cascade');
                $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_course_tags');
        Schema::dropIfExists('master_course_skills');
    }
};
