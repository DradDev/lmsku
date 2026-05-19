<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_status_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnDelete();

            $table->foreignId('project_participation_id')
                ->constrained('project_participations')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('old_status')->nullable();
            $table->string('new_status');

            $table->unsignedTinyInteger('old_progress_percent')->nullable();
            $table->unsignedTinyInteger('new_progress_percent')->nullable();

            $table->text('note')->nullable();

            $table->timestamps();

            $table->index(['project_id', 'created_at'], 'psh_project_created_idx');
            $table->index(['project_participation_id', 'created_at'], 'psh_participation_created_idx');
            $table->index(['user_id', 'created_at'], 'psh_user_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_status_histories');
    }
};
