<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_interest_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('tag_id')
                ->constrained('tags')
                ->cascadeOnDelete();

            $table->decimal('interest_score', 8, 2)->default(0);
            $table->unsignedInteger('interaction_count')->default(0);

            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('last_calculated_at')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'tag_id']);
            $table->index(['user_id', 'interest_score']);
            $table->index(['tag_id', 'interest_score']);
        });
    }
};
