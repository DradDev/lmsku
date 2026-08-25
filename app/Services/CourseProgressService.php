<?php

namespace App\Services;

use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\MasterCourse;
use App\Models\Material;
use App\Models\MaterialProgress;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\DB;

class CourseProgressService
{
    public function markMaterialAsCompleted(int $userId, int $offeringId, int $materialId): void
    {
        $existingProgress = MaterialProgress::where('user_id', $userId)
            ->where('material_id', $materialId)
            ->first();

        MaterialProgress::updateOrCreate(
            [
                'user_id' => $userId,
                'material_id' => $materialId,
            ],
            [
                'course_offering_id' => $offeringId,
                'is_completed' => true,
                'first_viewed_at' => $existingProgress?->first_viewed_at ?? now(),
                'last_viewed_at' => now(),
                'completed_at' => $existingProgress?->completed_at ?? now(),
            ]
        );

        $this->recalculate($userId, $offeringId);
    }

    public function recalculate(int $userId, ?int $offeringId): ?Enrollment
    {
        if (! $offeringId) {
            return null;
        }

        $enrollment = Enrollment::where('user_id', $userId)
            ->where('course_offering_id', $offeringId)
            ->first();

        if (! $enrollment) {
            return null;
        }

        $offering = CourseOffering::with(['masterCourse.materials', 'masterCourse.quizzes'])->find($offeringId);
        $masterCourseId = $offering?->master_course_id;

        // Ambil ID seluruh materi yang berlaku untuk rombel/batch ini (Materi Induk + Materi Khusus Rombel)
        $applicableMaterialIds = Material::where(function ($q) use ($offeringId, $masterCourseId) {
            $q->where(function ($sub) use ($masterCourseId) {
                $sub->where('materialable_type', MasterCourse::class)
                    ->where('materialable_id', $masterCourseId);
            })->orWhere(function ($sub) use ($offeringId) {
                $sub->where('materialable_type', CourseOffering::class)
                    ->where('materialable_id', $offeringId);
            });
        })->pluck('id')->unique();

        $totalMaterialCount = $applicableMaterialIds->count();

        // Ambil ID seluruh kuis yang berlaku untuk rombel/batch ini (Kuis Induk + Kuis Khusus Rombel)
        $applicableQuizIds = Quiz::where(function ($q) use ($offeringId, $masterCourseId) {
            $q->where(function ($sub) use ($masterCourseId) {
                $sub->where('quizzable_type', MasterCourse::class)
                    ->where('quizzable_id', $masterCourseId);
            })->orWhere(function ($sub) use ($offeringId) {
                $sub->where('quizzable_type', CourseOffering::class)
                    ->where('quizzable_id', $offeringId);
            });
        })->pluck('id')->unique();

        $totalQuizCount = $applicableQuizIds->count();

        $completedMaterialCount = $applicableMaterialIds->isNotEmpty()
            ? MaterialProgress::where('user_id', $userId)
                ->where('is_completed', true)
                ->whereIn('material_id', $applicableMaterialIds)
                ->distinct('material_id')
                ->count('material_id')
            : 0;

        $completedQuizCount = $applicableQuizIds->isNotEmpty()
            ? QuizAttempt::where('user_id', $userId)
                ->where('is_verified', true)
                ->whereIn('quiz_id', $applicableQuizIds)
                ->distinct('quiz_id')
                ->count('quiz_id')
            : 0;

        $totalItemCount = $totalMaterialCount + $totalQuizCount;
        $completedItemCount = $completedMaterialCount + $completedQuizCount;

        $progressPercent = $totalItemCount > 0
            ? (int) round(($completedItemCount / $totalItemCount) * 100)
            : 0;

        $progressPercent = min(100, max(0, $progressPercent));

        $status = match (true) {
            $progressPercent >= 100 => 'completed',
            $progressPercent > 0 => 'in_progress',
            default => 'not_started',
        };

        $enrollment->update([
            'progress_percent' => $progressPercent,
            'completed_material_count' => $completedMaterialCount,
            'completed_quiz_count' => $completedQuizCount,
            'total_material_count' => $totalMaterialCount,
            'total_quiz_count' => $totalQuizCount,
            'status' => $status,
            'completed_at' => $progressPercent >= 100
                ? ($enrollment->completed_at ?? now())
                : null,
            'last_activity_at' => now(),
        ]);

        return $enrollment->fresh();
    }

    public function recalculateAllForCourse(int $offeringId): void
    {
        Enrollment::where('course_offering_id', $offeringId)
            ->chunkById(100, function ($enrollments) use ($offeringId) {
                foreach ($enrollments as $enrollment) {
                    $this->recalculate($enrollment->user_id, $offeringId);
                }
            });
    }

    public function recalculateAllForUser(int $userId): void
    {
        Enrollment::where('user_id', $userId)
            ->chunkById(100, function ($enrollments) {
                foreach ($enrollments as $enrollment) {
                    $this->recalculate($enrollment->user_id, $enrollment->course_offering_id);
                }
            });
    }
}