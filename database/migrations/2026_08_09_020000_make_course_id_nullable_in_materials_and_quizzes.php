<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat kolom course_id pada materials dan quizzes menjadi nullable
     * serta melepas foreign key strict ke tabel legacy courses agar
     * materi & quiz dapat tersimpan murni di master_course_id (Pustaka Induk 3NF).
     */
    public function up(): void
    {
        // Drop foreign key di materials jika ada
        try {
            Schema::table('materials', function (Blueprint $table) {
                $table->dropForeign(['course_id']);
            });
        } catch (\Exception $e) {
            // Foreign key may not exist or have a different name
        }

        // Change course_id to nullable in materials
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `materials` MODIFY `course_id` BIGINT UNSIGNED NULL;");
        }

        // Drop foreign key di quizzes jika ada
        try {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->dropForeign(['course_id']);
            });
        } catch (\Exception $e) {
            // Foreign key may not exist or have a different name
        }

        // Change course_id to nullable in quizzes
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `quizzes` MODIFY `course_id` BIGINT UNSIGNED NULL;");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action needed for rollback
    }
};
