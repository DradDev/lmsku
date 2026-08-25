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
        // 1. Add polymorphic columns
        Schema::table('quizzes', function (Blueprint $table) {
            if (!Schema::hasColumn('quizzes', 'quizzable_type')) {
                $table->string('quizzable_type')->nullable()->after('id');
            }
            if (!Schema::hasColumn('quizzes', 'quizzable_id')) {
                $table->unsignedBigInteger('quizzable_id')->nullable()->after('quizzable_type');
                $table->index(['quizzable_type', 'quizzable_id'], 'quizzes_quizzable_index');
            }
        });

        // 2. Auto-backfill existing data
        if (Schema::hasColumn('quizzes', 'master_course_id')) {
            DB::table('quizzes')
                ->whereNotNull('master_course_id')
                ->whereNull('quizzable_id')
                ->update([
                    'quizzable_type' => 'App\\Models\\MasterCourse',
                    'quizzable_id' => DB::raw('master_course_id'),
                ]);

            // 3. Drop legacy column master_course_id
            Schema::table('quizzes', function (Blueprint $table) {
                try {
                    $table->dropForeign(['master_course_id']);
                } catch (\Throwable $e) {}

                $table->dropColumn('master_course_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            if (!Schema::hasColumn('quizzes', 'master_course_id')) {
                $table->foreignId('master_course_id')->nullable()->after('id')->constrained('master_courses')->onDelete('cascade');
            }
        });

        // Backfill master_course_id from MasterCourse quizzables
        DB::table('quizzes')
            ->where('quizzable_type', 'App\\Models\\MasterCourse')
            ->update([
                'master_course_id' => DB::raw('quizzable_id'),
            ]);

        Schema::table('quizzes', function (Blueprint $table) {
            if (Schema::hasColumn('quizzes', 'quizzable_id')) {
                $table->dropIndex('quizzes_quizzable_index');
                $table->dropColumn(['quizzable_type', 'quizzable_id']);
            }
        });
    }
};
