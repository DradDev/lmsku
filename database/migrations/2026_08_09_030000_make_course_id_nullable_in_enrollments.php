<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat kolom course_id pada enrollments menjadi nullable
     * dan menambahkan unique constraint (user_id, course_offering_id) untuk proteksi retake student per semester.
     */
    public function up(): void
    {
        // Drop old legacy unique constraint (user_id, course_id) to allow retakes in new semesters
        try {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->dropUnique('enrollments_user_id_course_id_unique');
            });
        } catch (\Exception $e) {
            // Index may not exist or have different name
        }

        // Make course_id nullable
        DB::statement("ALTER TABLE `enrollments` MODIFY `course_id` BIGINT UNSIGNED NULL;");

        // Add unique constraint (user_id, course_offering_id) if not exists
        try {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->unique(['user_id', 'course_offering_id'], 'enrollments_user_offering_unique');
            });
        } catch (\Exception $e) {
            // Index already exists
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->dropUnique('enrollments_user_offering_unique');
            });
        } catch (\Exception $e) {
            //
        }
    }
};
