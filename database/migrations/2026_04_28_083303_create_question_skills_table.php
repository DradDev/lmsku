<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_skills', function (Blueprint $table) {
            $table->id();

            $table->foreignId('question_id')
                ->constrained('questions')
                ->cascadeOnDelete();

            $table->foreignId('skill_id')
                ->constrained('skills')
                ->cascadeOnDelete();

            $table->decimal('weight', 5, 2)->default(1.00);

            $table->timestamps();

            $table->unique(['question_id', 'skill_id']);
        });
    }
};
