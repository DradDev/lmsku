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
        if (Schema::hasTable('quiz_attempts') && Schema::hasColumn('quiz_attempts', 'offering_quiz_id')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                try {
                    $table->dropForeign(['offering_quiz_id']);
                } catch (\Throwable $e) {
                    // Ignore if foreign key constraint does not exist
                }
                $table->dropColumn('offering_quiz_id');
            });
        }

        Schema::dropIfExists('offering_quizzes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('offering_quizzes')) {
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

        if (Schema::hasTable('quiz_attempts') && !Schema::hasColumn('quiz_attempts', 'offering_quiz_id')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                $table->foreignId('offering_quiz_id')->nullable()->after('quiz_id')->constrained('offering_quizzes')->nullOnDelete();
            });
        }
    }
};
