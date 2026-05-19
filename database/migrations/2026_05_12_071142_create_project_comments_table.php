<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_comments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('project_participation_id')
                ->nullable()
                ->constrained('project_participations')
                ->nullOnDelete();

            $table->text('comment');

            $table->string('comment_type')
                ->default('comment');

            $table->timestamps();

            $table->index(['project_id', 'created_at'], 'pc_project_created_idx');
            $table->index(['user_id', 'created_at'], 'pc_user_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_comments');
    }
};
