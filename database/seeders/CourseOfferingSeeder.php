<?php

namespace Database\Seeders;

use App\Models\AcademicTerm;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\MasterCourse;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseOfferingSeeder extends Seeder
{
    public function run(): void
    {
        $activeTerm = AcademicTerm::where('is_active', true)->first() ?? AcademicTerm::first();
        $lecturers = User::where('role', 'lecturer')->get();
        $primaryLecturer = $lecturers->first();
        $secondaryLecturer = $lecturers->skip(1)->first() ?? $primaryLecturer;

        $masterCourses = MasterCourse::all();

        foreach ($masterCourses as $index => $mc) {
            $assignedLecturer = ($index % 2 === 0) ? $primaryLecturer : $secondaryLecturer;

            // 1. Create CourseOffering (Kelas Paralel)
            $offering = CourseOffering::updateOrCreate(
                [
                    'master_course_id' => $mc->id,
                    'academic_term_id' => $activeTerm->id,
                    'section_name' => 'A',
                ],
                [
                    'lecturer_id' => $assignedLecturer ? $assignedLecturer->id : null,
                    'capacity' => 40,
                    'start_date' => now()->startOfMonth(),
                    'end_date' => now()->addMonths(4),
                    'is_archived' => false,
                    'certificate_threshold' => $mc->certificate_threshold ?? 75,
                    'status' => 'published',
                ]
            );

            // 2. Create Course entity for compatibility
            $course = Course::updateOrCreate(
                [
                    'master_course_id' => $mc->id,
                    'user_id' => $assignedLecturer ? $assignedLecturer->id : null,
                    'batch_name' => 'Kelas A - ' . $activeTerm->name,
                ],
                [
                    'name' => $mc->name,
                    'description' => $mc->description,
                    'level' => $mc->level,
                    'progress' => 0,
                    'duration_weeks' => 12,
                    'start_date' => now()->startOfMonth(),
                    'end_date' => now()->addMonths(4),
                    'is_archived' => false,
                    'certificate_threshold' => $mc->certificate_threshold ?? 75,
                    'category_id' => $mc->category_id,
                    'moderation_status' => 'published',
                ]
            );

            // Sync skills & tags from MasterCourse to Course
            $course->skills()->sync($mc->skills->pluck('id'));
            $course->tags()->sync($mc->tags->pluck('id'));
        }
    }
}
