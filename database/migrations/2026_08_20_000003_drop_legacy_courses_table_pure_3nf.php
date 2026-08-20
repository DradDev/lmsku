<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Clean up enrollments
        if (Schema::hasTable('enrollments') && Schema::hasColumn('enrollments', 'course_id')) {
            try {
                DB::statement('ALTER TABLE enrollments DROP FOREIGN KEY enrollments_course_id_foreign');
            } catch (\Throwable $e) {}

            try {
                DB::statement('ALTER TABLE enrollments DROP INDEX enrollments_user_id_course_id_unique');
            } catch (\Throwable $e) {}

            try {
                DB::statement('ALTER TABLE enrollments DROP COLUMN course_id');
            } catch (\Throwable $e) {}
        }

        // 2. Clean up learning_activity_logs
        if (Schema::hasTable('learning_activity_logs') && Schema::hasColumn('learning_activity_logs', 'course_id')) {
            try {
                DB::statement('ALTER TABLE learning_activity_logs DROP FOREIGN KEY learning_activity_logs_course_id_foreign');
            } catch (\Throwable $e) {}

            try {
                DB::statement('ALTER TABLE learning_activity_logs DROP COLUMN course_id');
            } catch (\Throwable $e) {}
        }

        // 3. Clean up material_progresses
        if (Schema::hasTable('material_progresses') && Schema::hasColumn('material_progresses', 'course_id')) {
            try {
                DB::statement('ALTER TABLE material_progresses DROP FOREIGN KEY material_progresses_course_id_foreign');
            } catch (\Throwable $e) {}

            try {
                DB::statement('ALTER TABLE material_progresses DROP INDEX mp_user_course_idx');
            } catch (\Throwable $e) {}

            try {
                DB::statement('ALTER TABLE material_progresses DROP INDEX mp_course_material_idx');
            } catch (\Throwable $e) {}

            try {
                DB::statement('ALTER TABLE material_progresses DROP COLUMN course_id');
            } catch (\Throwable $e) {}
        }

        // 4. Clean up quiz_retake_requests
        if (Schema::hasTable('quiz_retake_requests') && Schema::hasColumn('quiz_retake_requests', 'course_id')) {
            try {
                DB::statement('ALTER TABLE quiz_retake_requests DROP FOREIGN KEY quiz_retake_requests_course_id_foreign');
            } catch (\Throwable $e) {}

            try {
                DB::statement('ALTER TABLE quiz_retake_requests DROP COLUMN course_id');
            } catch (\Throwable $e) {}
        }

        // 5. Drop the legacy redundant courses table
        Schema::dropIfExists('courses');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('courses')) {
            Schema::create('courses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('master_course_id')->nullable()->constrained('master_courses')->nullOnDelete();
                $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
                $table->string('name');
                $table->string('batch_name')->nullable();
                $table->text('description')->nullable();
                $table->string('level')->default('Beginner');
                $table->integer('progress')->default(0);
                $table->integer('duration_weeks')->default(4);
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->boolean('is_archived')->default(false);
                $table->integer('certificate_threshold')->default(75);
                $table->string('moderation_status')->nullable();
                $table->text('moderation_note')->nullable();
                $table->timestamps();
            });
        }
    }
};
