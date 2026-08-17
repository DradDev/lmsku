<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat kolom course_id pada certificates menjadi nullable
     * dan melepaskan constraint FK strict ke tabel legacy courses agar
     * sertifikat terikat murni ke course_offering_id (3NF).
     */
    public function up(): void
    {
        // Drop foreign key course_id di certificates jika ada
        try {
            Schema::table('certificates', function (Blueprint $table) {
                $table->dropForeign(['course_id']);
            });
        } catch (\Exception $e) {
            // Foreign key may have different name
        }

        // Make course_id nullable
        DB::statement("ALTER TABLE `certificates` MODIFY `course_id` BIGINT UNSIGNED NULL;");

        // Change blockchain_hash column length to 255
        if (Schema::hasColumn('certificates', 'blockchain_hash')) {
            DB::statement("ALTER TABLE `certificates` MODIFY `blockchain_hash` VARCHAR(255) NULL;");
        }
        if (Schema::hasColumn('quiz_attempts', 'blockchain_hash')) {
            DB::statement("ALTER TABLE `quiz_attempts` MODIFY `blockchain_hash` VARCHAR(255) NULL;");
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
