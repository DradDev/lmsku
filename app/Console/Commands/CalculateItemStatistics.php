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
        $masterCourses = DB::table('master_courses')->get();
        $count = 0;

        foreach ($masterCourses as $masterCourse) {
            $offeringIds = DB::table('course_offerings')
                ->where('master_course_id', $masterCourse->id)
                ->pluck('id')
                ->toArray();

            $viewedCount = DB::table('learning_activity_logs')
                ->where('activity_type', 'view_course')
                ->whereIn('course_offering_id', $offeringIds)
                ->count();

            $takenCount = DB::table('enrollments')
                ->whereIn('course_offering_id', $offeringIds)
                ->count();

            $finalQuiz = DB::table('quizzes')
                ->where('master_course_id', $masterCourse->id)
                ->where('quiz_type', 'final')
                ->first();

            $completedCount = 0;

            if ($finalQuiz) {
                $completedCount = DB::table('quiz_attempts')
                    ->where('quiz_id', $finalQuiz->id)
                    ->where('score', '>=', $masterCourse->certificate_threshold ?? 70)
                    ->distinct('user_id')
                    ->count('user_id');
            }

            $completionRate = $takenCount > 0
                ? round(($completedCount / $takenCount) * 100, 2)
                : 0;

            $lastActivityAt = DB::table('learning_activity_logs')
                ->whereIn('course_offering_id', $offeringIds)
                ->max('occurred_at');

            ItemStatistic::updateOrCreate(
                [
                    'item_type' => 'course',
                    'item_id' => $masterCourse->id,
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