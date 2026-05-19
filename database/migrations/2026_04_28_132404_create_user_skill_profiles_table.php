<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('user_skill_profiles', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->foreignId('skill_id')
            ->constrained('skills')
            ->cascadeOnDelete();

        $table->decimal('avg_score', 8, 2)->default(0);
        $table->decimal('lowest_score', 8, 2)->default(0);
        $table->decimal('highest_score', 8, 2)->default(0);

        $table->unsignedInteger('attempt_count')->default(0);
        $table->unsignedInteger('correct_count')->default(0);
        $table->unsignedInteger('wrong_count')->default(0);

        $table->timestamp('last_activity_at')->nullable();
        $table->timestamp('last_calculated_at')->nullable();

        $table->timestamps();

        $table->unique(['user_id', 'skill_id']);
        $table->index(['user_id', 'avg_score']);
        $table->index(['skill_id', 'avg_score']);
    });
}
};
