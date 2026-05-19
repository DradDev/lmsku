<?php

namespace App\Console\Commands;

use App\Models\ItemStatistic;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CalculateItemStatistics extends Command
{
    protected $signature = 'ai:calculate-item-statistics';

    protected $description = 'Calculate item statistics for courses and projects';

    public function handle(): int
    {
        $courseCount = $this->calculateCourseStatistics();
        $projectCount = $this->calculateProjectStatistics();

        $this->info("Item statistics berhasil dihitung. Course: {$courseCount}, Project: {$projectCount}");

        return self::SUCCESS;
    }

    private function calculateCourseStatistics(): int
    {
        $courses = DB::table('courses')->get();
        $count = 0;

        foreach ($courses as $course) {
            $viewedCount = DB::table('learning_activity_logs')
                ->where('activity_type', 'view_course')
                ->where('course_id', $course->id)
                ->count();

            $takenCount = DB::table('enrollments')
                ->where('course_id', $course->id)
                ->count();

            /*
             * Untuk sementara completed course dihitung dari final quiz:
             * - quiz is_final = true
             * - attempt is_verified = true
             * - score >= 70
             */
            $finalQuiz = DB::table('quizzes')
                ->where('course_id', $course->id)
                ->where('is_final', true)
                ->first();

            $completedCount = 0;

            if ($finalQuiz) {
                $completedCount = DB::table('quiz_attempts')
                    ->where('quiz_id', $finalQuiz->id)
                    ->where('is_verified', true)
                    ->where('score', '>=', 70)
                    ->distinct('user_id')
                    ->count('user_id');
            }

            $completionRate = $takenCount > 0
                ? round(($completedCount / $takenCount) * 100, 2)
                : 0;

            $lastActivityAt = DB::table('learning_activity_logs')
                ->where('course_id', $course->id)
                ->max('occurred_at');

            ItemStatistic::updateOrCreate(
                [
                    'item_type' => 'course',
                    'item_id' => $course->id,
                ],
                [
                    'viewed_count' => $viewedCount,
                    'clicked_count' => 0,
                    'taken_count' => $takenCount,
                    'completed_count' => $completedCount,
                    'popularity_score' => $takenCount,
                    'completion_rate' => $completionRate,
                    'last_activity_at' => $lastActivityAt,
                    'last_calculated_at' => now(),
                ]
            );

            $count++;
        }

        return $count;
    }

    private function calculateProjectStatistics(): int
    {
        $projects = DB::table('projects')->get();
        $count = 0;

        foreach ($projects as $project) {
            $viewedCount = DB::table('learning_activity_logs')
                ->where('activity_type', 'view_project')
                ->where('project_id', $project->id)
                ->count();

            $takenCount = DB::table('project_participations')
                ->where('project_id', $project->id)
                ->count();

            $completedCount = DB::table('project_participations')
                ->where('project_id', $project->id)
                ->where('status', 'completed')
                ->count();

            $completionRate = $takenCount > 0
                ? round(($completedCount / $takenCount) * 100, 2)
                : 0;

            $lastActivityAt = DB::table('learning_activity_logs')
                ->where('project_id', $project->id)
                ->max('occurred_at');

            ItemStatistic::updateOrCreate(
                [
                    'item_type' => 'project',
                    'item_id' => $project->id,
                ],
                [
                    'viewed_count' => $viewedCount,
                    'clicked_count' => 0,
                    'taken_count' => $takenCount,
                    'completed_count' => $completedCount,
                    'popularity_score' => $takenCount,
                    'completion_rate' => $completionRate,
                    'last_activity_at' => $lastActivityAt,
                    'last_calculated_at' => now(),
                ]
            );

            $count++;
        }

        return $count;
    }
}