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
        // 1. Drop redundant legacy pivot tables (Replaced by master_course_skills & master_course_tags)
        Schema::dropIfExists('course_skills');
        Schema::dropIfExists('course_tags');

        // 2. Ensure user_interest_profiles exists for student digital portfolio
        if (!Schema::hasTable('user_interest_profiles')) {
            Schema::create('user_interest_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
                $table->decimal('interest_score', 8, 2)->default(0);
                $table->unsignedInteger('interaction_count')->default(0);
                $table->timestamp('last_activity_at')->nullable();
                $table->timestamp('last_calculated_at')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'tag_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate empty tables if rolled back
        if (!Schema::hasTable('course_skills')) {
            Schema::create('course_skills', function (Blueprint $table) {
                $table->id();
                $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
                $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
                $table->decimal('weight', 5, 2)->default(1.00);
                $table->boolean('is_main')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('course_tags')) {
            Schema::create('course_tags', function (Blueprint $table) {
                $table->id();
                $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
                $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
                $table->decimal('weight', 5, 2)->default(1.00);
                $table->timestamps();
            });
        }
    }
};
