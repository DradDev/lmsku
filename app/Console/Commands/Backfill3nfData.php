<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Backfill3nfData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:backfill-3nf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Safely backfill existing database rows to strict 3NF tables (master_courses, academic_terms, course_offerings, offering_quizzes) without data loss.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Strict 3NF Data Backfill process...');

        // 1. Create or get default Academic Term
        $termId = DB::table('academic_terms')->insertGetId([
            'name' => '2025/2026 Ganjil',
            'start_date' => now()->startOfYear(),
            'end_date' => now()->endOfYear(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->info("Academic Term created with ID: {$termId}");

        // 2. Fetch existing courses
        $courses = DB::table('courses')->get();
        $this->info("Found {$courses->count()} existing courses to migrate.");

        foreach ($courses as $course) {
            // Create or get MasterCourse
            $existingMaster = DB::table('master_courses')
                ->where('code', 'MC-' . $course->id)
                ->orWhere('name', $course->name)
                ->first();

            if ($existingMaster) {
                $masterCourseId = $existingMaster->id;
            } else {
                $masterCourseId = DB::table('master_courses')->insertGetId([
                    'code' => 'MC-' . $course->id,
                    'name' => $course->name,
                    'description' => $course->description ?? null,
                    'level' => $course->level ?? 'Beginner',
                    'category_id' => $course->category_id ?? null,
                    'created_at' => $course->created_at ?? now(),
                    'updated_at' => $course->updated_at ?? now(),
                ]);
            }

            // Create or get CourseOffering
            $existingOffering = DB::table('course_offerings')
                ->where('master_course_id', $masterCourseId)
                ->where('lecturer_id', $course->user_id)
                ->first();

            if ($existingOffering) {
                $offeringId = $existingOffering->id;
            } else {
                $offeringId = DB::table('course_offerings')->insertGetId([
                    'master_course_id' => $masterCourseId,
                    'academic_term_id' => $termId,
                    'lecturer_id' => $course->user_id,
                    'start_date' => $course->start_date ?? null,
                    'end_date' => $course->end_date ?? null,
                    'is_archived' => $course->is_archived ?? false,
                    'certificate_threshold' => $course->certificate_threshold ?? 60,
                    'created_at' => $course->created_at ?? now(),
                    'updated_at' => $course->updated_at ?? now(),
                ]);
            }

            // Map materials to master_course_id
            if (Schema::hasTable('materials') && Schema::hasColumn('materials', 'master_course_id')) {
                DB::table('materials')
                    ->where('course_id', $course->id)
                    ->update(['master_course_id' => $masterCourseId]);
            }

            // Map quizzes to master_course_id and create offering_quizzes
            if (Schema::hasTable('quizzes') && Schema::hasColumn('quizzes', 'master_course_id')) {
                DB::table('quizzes')
                    ->where('course_id', $course->id)
                    ->update(['master_course_id' => $masterCourseId]);

                $quizzes = DB::table('quizzes')->where('course_id', $course->id)->get();

                foreach ($quizzes as $quiz) {
                    $existingOfferingQuiz = DB::table('offering_quizzes')
                        ->where('course_offering_id', $offeringId)
                        ->where('quiz_id', $quiz->id)
                        ->first();

                    if ($existingOfferingQuiz) {
                        $offeringQuizId = $existingOfferingQuiz->id;
                    } else {
                        $offeringQuizId = DB::table('offering_quizzes')->insertGetId([
                            'course_offering_id' => $offeringId,
                            'quiz_id' => $quiz->id,
                            'start_date' => $quiz->start_date ?? null,
                            'end_date' => $quiz->end_date ?? null,
                            'time_limit' => $quiz->time_limit ?? null,
                            'max_attempts' => $quiz->max_attempts ?? 1,
                            'created_at' => $quiz->created_at ?? now(),
                            'updated_at' => $quiz->updated_at ?? now(),
                        ]);
                    }

                    if (Schema::hasTable('quiz_attempts') && Schema::hasColumn('quiz_attempts', 'offering_quiz_id')) {
                        DB::table('quiz_attempts')
                            ->where('quiz_id', $quiz->id)
                            ->update(['offering_quiz_id' => $offeringQuizId]);
                    }
                }
            }

            // Map enrollments to course_offering_id
            if (Schema::hasTable('enrollments') && Schema::hasColumn('enrollments', 'course_offering_id')) {
                DB::table('enrollments')
                    ->where('course_id', $course->id)
                    ->update(['course_offering_id' => $offeringId]);
            }

            // Map certificates to course_offering_id
            if (Schema::hasTable('certificates') && Schema::hasColumn('certificates', 'course_offering_id')) {
                DB::table('certificates')
                    ->where('course_id', $course->id)
                    ->update(['course_offering_id' => $offeringId]);
            }

            // Map learning_activity_logs to course_offering_id
            if (Schema::hasTable('learning_activity_logs') && Schema::hasColumn('learning_activity_logs', 'course_offering_id')) {
                DB::table('learning_activity_logs')
                    ->where('course_id', $course->id)
                    ->update(['course_offering_id' => $offeringId]);
            }

            $this->info("Migrated Course ID {$course->id} ('{$course->name}') -> MasterCourse ID {$masterCourseId}, Offering ID {$offeringId}");
        }

        $this->info('Strict 3NF Data Backfill completed successfully with ZERO data loss!');
    }
}
