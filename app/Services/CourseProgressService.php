<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Material;
use App\Models\MaterialProgress;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\DB;

class CourseProgressService
{
    public function markMaterialAsCompleted(int $userId, int $courseId, int $materialId): void
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
                'course_id' => $courseId,
                'is_completed' => true,
                'first_viewed_at' => $existingProgress?->first_viewed_at ?? now(),
                'last_viewed_at' => now(),
                'completed_at' => $existingProgress?->completed_at ?? now(),
            ]
        );

        $this->recalculate($userId, $courseId);
    }

    public function recalculate(int $userId, int $courseId): ?Enrollment
    {
        $enrollment = Enrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if (! $enrollment) {
            return null;
        }

        $totalMaterialCount = Material::where('course_id', $courseId)->count();

        $totalQuizCount = Quiz::where('course_id', $courseId)->count();

        $completedMaterialCount = MaterialProgress::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('is_completed', true)
            ->distinct('material_id')
            ->count('material_id');

        $completedQuizCount = QuizAttempt::query()
            ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
            ->where('quiz_attempts.user_id', $userId)
            ->where('quizzes.course_id', $courseId)
            ->distinct('quiz_attempts.quiz_id')
            ->count('quiz_attempts.quiz_id');

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

    public function recalculateAllForCourse(int $courseId): void
    {
        Enrollment::where('course_id', $courseId)
            ->chunkById(100, function ($enrollments) use ($courseId) {
                foreach ($enrollments as $enrollment) {
                    $this->recalculate($enrollment->user_id, $courseId);
                }
            });
    }

    public function recalculateAllForUser(int $userId): void
    {
        Enrollment::where('user_id', $userId)
            ->chunkById(100, function ($enrollments) use ($userId) {
                foreach ($enrollments as $enrollment) {
                    $this->recalculate($userId, $enrollment->course_id);
                }
            });
    }
}
