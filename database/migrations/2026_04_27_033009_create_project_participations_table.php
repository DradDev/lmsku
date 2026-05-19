<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('project_participations', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->foreignId('project_id')
            ->constrained('projects')
            ->cascadeOnDelete();

        $table->enum('status', [
            'in_progress',
            'completed',
            'dropped',
        ])->default('in_progress');

        $table->unsignedTinyInteger('progress_percent')->default(0);

        $table->timestamp('started_at')->nullable();
        $table->timestamp('completed_at')->nullable();
        $table->timestamp('last_activity_at')->nullable();

        $table->timestamps();

        $table->unique(['user_id', 'project_id']);
    });
}
};
