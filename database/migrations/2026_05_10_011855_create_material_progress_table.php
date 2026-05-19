<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_progresses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('course_id')
                ->constrained('courses')
                ->cascadeOnDelete();

            $table->foreignId('material_id')
                ->constrained('materials')
                ->cascadeOnDelete();

            $table->boolean('is_completed')->default(true);

            $table->timestamp('first_viewed_at')->nullable();
            $table->timestamp('last_viewed_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'material_id'], 'mp_user_material_unique');
            $table->index(['user_id', 'course_id'], 'mp_user_course_idx');
            $table->index(['course_id', 'material_id'], 'mp_course_material_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_progresses');
    }
};