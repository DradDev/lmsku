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
        if (! Schema::hasTable('offering_quizzes')) {
            Schema::create('offering_quizzes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('course_offering_id')->constrained('course_offerings')->cascadeOnDelete();
                $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();
                $table->dateTime('start_date')->nullable();
                $table->dateTime('end_date')->nullable();
                $table->integer('time_limit')->nullable();
                $table->integer('max_attempts')->default(1);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offering_quizzes');
    }
};
