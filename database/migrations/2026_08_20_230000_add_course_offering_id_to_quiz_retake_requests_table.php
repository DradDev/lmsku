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
        if (Schema::hasTable('quiz_retake_requests') && !Schema::hasColumn('quiz_retake_requests', 'course_offering_id')) {
            Schema::table('quiz_retake_requests', function (Blueprint $table) {
                $table->foreignId('course_offering_id')
                    ->nullable()
                    ->after('quiz_id')
                    ->constrained('course_offerings')
                    ->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('quiz_retake_requests') && Schema::hasColumn('quiz_retake_requests', 'course_offering_id')) {
            Schema::table('quiz_retake_requests', function (Blueprint $table) {
                $table->dropForeign(['course_offering_id']);
                $table->dropColumn('course_offering_id');
            });
        }
    }
};
