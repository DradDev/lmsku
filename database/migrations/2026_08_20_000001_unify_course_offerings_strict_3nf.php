<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Modify course_offerings to support both Academic & Vendor offerings
        Schema::table('course_offerings', function (Blueprint $table) {
            if (!Schema::hasColumn('course_offerings', 'type')) {
                $table->enum('type', ['academic', 'vendor'])->default('academic')->after('master_course_id');
            }
        });

        // Make academic_term_id nullable for vendor offerings
        DB::statement('ALTER TABLE `course_offerings` MODIFY `academic_term_id` BIGINT UNSIGNED NULL;');

        // 2. Data Migration: Backfill Vendor Courses into CourseOfferings
        $vendorCourses = DB::table('courses')->whereNotNull('master_course_id')->get();
        $courseToOfferingMap = [];

        // Academic courses 1-4 map to offerings 1-4
        $courseToOfferingMap[1] = 1;
        $courseToOfferingMap[2] = 2;
        $courseToOfferingMap[3] = 3;
        $courseToOfferingMap[4] = 4;

        foreach ($vendorCourses as $vc) {
            if (isset($courseToOfferingMap[$vc->id])) {
                continue;
            }

            // Check if a course_offering already exists for this master course and batch name
            $existingOffering = DB::table('course_offerings')
                ->where('master_course_id', $vc->master_course_id)
                ->where('section_name', $vc->batch_name ?? 'Batch 1')
                ->where('type', 'vendor')
                ->first();

            if ($existingOffering) {
                $offeringId = $existingOffering->id;
            } else {
                $offeringId = DB::table('course_offerings')->insertGetId([
                    'master_course_id'      => $vc->master_course_id,
                    'type'                  => 'vendor',
                    'academic_term_id'      => null,
                    'lecturer_id'           => $vc->user_id,
                    'section_name'          => $vc->batch_name ?? 'Batch 1',
                    'capacity'              => 40,
                    'start_date'            => $vc->start_date ?? now(),
                    'end_date'              => $vc->end_date ?? now()->addMonths(2),
                    'is_archived'           => $vc->is_archived ?? false,
                    'certificate_threshold' => $vc->certificate_threshold ?? 75,
                    'status'                => $vc->moderation_status ?? 'published',
                    'created_at'            => $vc->created_at ?? now(),
                    'updated_at'            => $vc->updated_at ?? now(),
                ]);
            }

            $courseToOfferingMap[$vc->id] = $offeringId;
        }

        // 3. Update Foreign Keys on Enrollments
        $enrollments = DB::table('enrollments')->get();
        foreach ($enrollments as $enr) {
            if (empty($enr->course_offering_id) && !empty($enr->course_id)) {
                $mappedOfferingId = $courseToOfferingMap[$enr->course_id] ?? null;
                if ($mappedOfferingId) {
                    $alreadyEnrolled = DB::table('enrollments')
                        ->where('user_id', $enr->user_id)
                        ->where('course_offering_id', $mappedOfferingId)
                        ->where('id', '!=', $enr->id)
                        ->exists();

                    if ($alreadyEnrolled) {
                        DB::table('enrollments')->where('id', $enr->id)->delete();
                    } else {
                        DB::table('enrollments')->where('id', $enr->id)->update([
                            'course_offering_id' => $mappedOfferingId,
                        ]);
                    }
                }
            }
        }

        // 4. Update Foreign Keys on Certificates
        $certificates = DB::table('certificates')->whereNull('project_id')->get();
        foreach ($certificates as $cert) {
            if (empty($cert->course_offering_id) && !empty($cert->course_id)) {
                $mappedOfferingId = $courseToOfferingMap[$cert->course_id] ?? null;
                if ($mappedOfferingId) {
                    DB::table('certificates')->where('id', $cert->id)->update([
                        'course_offering_id' => $mappedOfferingId,
                    ]);
                }
            }
        }

        // 5. Update Foreign Keys on Materials & Quizzes
        $materials = DB::table('materials')->get();
        foreach ($materials as $mat) {
            if (empty($mat->master_course_id) && !empty($mat->course_id)) {
                $course = DB::table('courses')->where('id', $mat->course_id)->first();
                if ($course && $course->master_course_id) {
                    DB::table('materials')->where('id', $mat->id)->update([
                        'master_course_id' => $course->master_course_id,
                    ]);
                }
            }
        }

        $quizzes = DB::table('quizzes')->get();
        foreach ($quizzes as $quiz) {
            if (empty($quiz->master_course_id) && !empty($quiz->course_id)) {
                $course = DB::table('courses')->where('id', $quiz->course_id)->first();
                if ($course && $course->master_course_id) {
                    DB::table('quizzes')->where('id', $quiz->id)->update([
                        'master_course_id' => $course->master_course_id,
                    ]);
                }
            }
        }

        // 6. Sync skills & tags from course_skills & course_tags into master_course_skills & master_course_tags
        if (Schema::hasTable('course_skills')) {
            $courseSkills = DB::table('course_skills')->get();
            foreach ($courseSkills as $cs) {
                $course = DB::table('courses')->where('id', $cs->course_id)->first();
                if ($course && $course->master_course_id) {
                    $exists = DB::table('master_course_skills')
                        ->where('master_course_id', $course->master_course_id)
                        ->where('skill_id', $cs->skill_id)
                        ->exists();
                    if (!$exists) {
                        DB::table('master_course_skills')->insert([
                            'master_course_id' => $course->master_course_id,
                            'skill_id'         => $cs->skill_id,
                            'is_main'          => $cs->is_main ?? false,
                            'created_at'       => now(),
                            'updated_at'       => now(),
                        ]);
                    }
                }
            }
        }

        if (Schema::hasTable('course_tags')) {
            $courseTags = DB::table('course_tags')->get();
            foreach ($courseTags as $ct) {
                $course = DB::table('courses')->where('id', $ct->course_id)->first();
                if ($course && $course->master_course_id) {
                    $exists = DB::table('master_course_tags')
                        ->where('master_course_id', $course->master_course_id)
                        ->where('tag_id', $ct->tag_id)
                        ->exists();
                    if (!$exists) {
                        DB::table('master_course_tags')->insert([
                            'master_course_id' => $course->master_course_id,
                            'tag_id'           => $ct->tag_id,
                            'created_at'       => now(),
                            'updated_at'       => now(),
                        ]);
                    }
                }
            }
        }

        // 7. Update Learning Activity Logs
        if (Schema::hasTable('learning_activity_logs')) {
            $logs = DB::table('learning_activity_logs')->whereNotNull('course_id')->whereNull('course_offering_id')->get();
            foreach ($logs as $log) {
                $mappedOfferingId = $courseToOfferingMap[$log->course_id] ?? null;
                if ($mappedOfferingId) {
                    DB::table('learning_activity_logs')->where('id', $log->id)->update([
                        'course_offering_id' => $mappedOfferingId,
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('course_offerings', 'type')) {
            Schema::table('course_offerings', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
};
