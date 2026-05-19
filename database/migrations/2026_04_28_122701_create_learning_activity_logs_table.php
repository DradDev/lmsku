<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('learning_activity_logs', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->foreignId('course_id')
            ->nullable()
            ->constrained('courses')
            ->nullOnDelete();

        $table->foreignId('project_id')
            ->nullable()
            ->constrained('projects')
            ->nullOnDelete();

        $table->foreignId('material_id')
            ->nullable()
            ->constrained('materials')
            ->nullOnDelete();

        $table->foreignId('quiz_id')
            ->nullable()
            ->constrained('quizzes')
            ->nullOnDelete();

        $table->string('activity_type');
        $table->decimal('activity_value', 8, 2)->nullable();
        $table->unsignedInteger('duration_seconds')->nullable();

        $table->json('metadata')->nullable();

        $table->timestamp('occurred_at')->nullable();

        $table->timestamps();

        $table->index(['user_id', 'activity_type']);
        $table->index(['course_id', 'activity_type']);
        $table->index(['project_id', 'activity_type']);
    });
}
};
