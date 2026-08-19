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
        if (Schema::hasTable('quizzes') && Schema::hasColumn('quizzes', 'course_id')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->dropColumn('course_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('quizzes') && !Schema::hasColumn('quizzes', 'course_id')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->foreignId('course_id')->nullable()->after('id')->constrained('courses')->nullOnDelete();
            });
        }
    }
};
