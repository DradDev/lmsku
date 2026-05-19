<?php

namespace App\Console\Commands;

use App\Models\RecommendationFeatureSnapshot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateRecommendationFeatureSnapshots extends Command
{
    protected $signature = 'ai:generate-recommendation-features {--date=} {--user_id=}';

    protected $description = 'Generate recommendation feature snapshots for course and project recommendation';

    public function handle(): int
    {
        $snapshotDate = $this->option('date') ?: now()->toDateString();
        $userId = $this->option('user_id');

        $users = DB::table('users')
            ->where('role', 'student')
            ->when($userId, function ($query) use ($userId) {
                $query->where('id', $userId);
            })
            ->get();

        if ($users->isEmpty()) {
            $this->warn('Tidak ada student yang bisa diproses.');
            return self::SUCCESS;
        }

        $courseItems = DB::table('courses')->get();
        $projectItems = DB::table('projects')
            ->where('is_published', true)
            ->get();

        $createdOrUpdated = 0;

        foreach ($users as $user) {
            $userFeatures = $this->getUserFeatures($user->id);

            foreach ($courseItems as $course) {
                $itemFeatures = $this->getCourseItemFeatures($course->id, $course);
                $labelFeatures = $this->getCourseLabelFeatures($user->id, $course->id);

                RecommendationFeatureSnapshot::updateOrCreate(
                    [
                        'snapshot_date' => $snapshotDate,
                        'user_id' => $user->id,
                        'item_type' => 'course',
                        'item_id' => $course->id,
                    ],
                    array_merge(
                        [
                            'item_type_encoded' => 0,
                        ],
                        $userFeatures,
                        $itemFeatures,
                        $this->getMatchFeatures($user->id, 'course', $course->id),
                        $labelFeatures
                    )
                );

                $createdOrUpdated++;
            }

            foreach ($projectItems as $project) {
                $itemFeatures = $this->getProjectItemFeatures($project->id, $project);
                $labelFeatures = $this->getProjectLabelFeatures($user->id, $project->id);

                RecommendationFeatureSnapshot::updateOrCreate(
                    [
                        'snapshot_date' => $snapshotDate,
                        'user_id' => $user->id,
                        'item_type' => 'project',
                        'item_id' => $project->id,
                    ],
                    array_merge(
                        [
                            'item_type_encoded' => 1,
                        ],
                        $userFeatures,
                        $itemFeatures,
                        $this->getMatchFeatures($user->id, 'project', $project->id),
                        $labelFeatures
                    )
                );

                $createdOrUpdated++;
            }
        }

        $this->info("Recommendation feature snapshots berhasil dibuat/update: {$createdOrUpdated} rows.");

        return self::SUCCESS;
    }

    private function getUserFeatures(int $userId): array
    {
        $skillStats = DB::table('user_skill_profiles')
            ->where('user_id', $userId)
            ->selectRaw('
                AVG(avg_score) as user_avg_skill_score,
                MIN(avg_score) as user_lowest_skill_score
            ')
            ->first();

        $completedCourseCount = $this->getCompletedCourseCount($userId);
        $completedProjectCount = $this->getCompletedProjectCount($userId);
        $recentActivityScore = $this->getRecentActivityScore($userId);

        $topInterest = DB::table('user_interest_profiles')
            ->where('user_id', $userId)
            ->orderByDesc('interest_score')
            ->first();

        return [
            'user_avg_skill_score' => round((float) ($skillStats->user_avg_skill_score ?? 0), 2),
            'user_lowest_skill_score' => round((float) ($skillStats->user_lowest_skill_score ?? 0), 2),

            'user_completed_course_count' => $completedCourseCount,
            'user_completed_project_count' => $completedProjectCount,

            'user_recent_activity_score' => $recentActivityScore,
            'user_top_interest_tag_id' => $topInterest->tag_id ?? null,
        ];
    }

    private function getCourseItemFeatures(int $courseId, object $course): array
    {
        $mainSkill = DB::table('course_skills')
            ->where('course_id', $courseId)
            ->where('is_main', true)
            ->first();

        $skillCount = DB::table('course_skills')
            ->where('course_id', $courseId)
            ->count();

        $tagCount = DB::table('course_tags')
            ->where('course_id', $courseId)
            ->count();

        $statistic = DB::table('item_statistics')
            ->where('item_type', 'course')
            ->where('item_id', $courseId)
            ->first();

        return [
            'item_difficulty_level' => $this->encodeDifficulty($course->level ?? 'Beginner'),
            'item_main_skill_id' => $mainSkill->skill_id ?? null,

            'item_popularity_score' => round((float) ($statistic->popularity_score ?? 0), 2),
            'item_completion_rate' => round((float) ($statistic->completion_rate ?? 0), 2),

            'item_skill_count' => $skillCount,
            'item_tag_count' => $tagCount,
        ];
    }

    private function getProjectItemFeatures(int $projectId, object $project): array
    {
        $mainSkill = DB::table('project_skills')
            ->where('project_id', $projectId)
            ->where('is_main', true)
            ->first();

        $skillCount = DB::table('project_skills')
            ->where('project_id', $projectId)
            ->count();

        $tagCount = DB::table('project_tags')
            ->where('project_id', $projectId)
            ->count();

        $statistic = DB::table('item_statistics')
            ->where('item_type', 'project')
            ->where('item_id', $projectId)
            ->first();

        return [
            'item_difficulty_level' => $this->encodeDifficulty($project->difficulty_level ?? 'Beginner'),
            'item_main_skill_id' => $mainSkill->skill_id ?? null,

            'item_popularity_score' => round((float) ($statistic->popularity_score ?? 0), 2),
            'item_completion_rate' => round((float) ($statistic->completion_rate ?? 0), 2),

            'item_skill_count' => $skillCount,
            'item_tag_count' => $tagCount,
        ];
    }

    private function getMatchFeatures(int $userId, string $itemType, int $itemId): array
    {
        $interestMatchScore = $this->calculateInterestMatchScore($userId, $itemType, $itemId);
        $weaknessMatchScore = $this->calculateWeaknessMatchScore($userId, $itemType, $itemId);
        $readinessScore = $this->calculateReadinessScore($userId, $itemType, $itemId);

        return [
            'interest_match_score' => $interestMatchScore,
            'weakness_match_score' => $weaknessMatchScore,
            'readiness_score' => $readinessScore,
        ];
    }

    private function getCourseLabelFeatures(int $userId, int $courseId): array
    {
        $enrollment = DB::table('enrollments')
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        $isCompleted = false;

        $finalQuiz = DB::table('quizzes')
            ->where('course_id', $courseId)
            ->where('is_final', true)
            ->first();

        if ($finalQuiz) {
            $isCompleted = DB::table('quiz_attempts')
                ->where('user_id', $userId)
                ->where('quiz_id', $finalQuiz->id)
                ->where('is_verified', true)
                ->where('score', '>=', 70)
                ->exists();
        }

        return [
            'label_clicked' => false,
            'label_taken' => (bool) $enrollment,
            'label_completed' => $isCompleted,
            'already_started_flag' => (bool) $enrollment,
        ];
    }

    private function getProjectLabelFeatures(int $userId, int $projectId): array
    {
        $participation = DB::table('project_participations')
            ->where('user_id', $userId)
            ->where('project_id', $projectId)
            ->first();

        return [
            'label_clicked' => false,
            'label_taken' => (bool) $participation,
            'label_completed' => $participation
                ? $participation->status === 'completed'
                : false,
            'already_started_flag' => (bool) $participation,
        ];
    }

    private function calculateInterestMatchScore(int $userId, string $itemType, int $itemId): float
    {
        $itemTagTable = $itemType === 'course'
            ? 'course_tags'
            : 'project_tags';

        $itemIdColumn = $itemType === 'course'
            ? 'course_id'
            : 'project_id';

        $score = DB::table('user_interest_profiles')
            ->join($itemTagTable, 'user_interest_profiles.tag_id', '=', $itemTagTable . '.tag_id')
            ->where('user_interest_profiles.user_id', $userId)
            ->where($itemTagTable . '.' . $itemIdColumn, $itemId)
            ->selectRaw('SUM(user_interest_profiles.interest_score * ' . $itemTagTable . '.weight) as score')
            ->value('score');

        return round((float) ($score ?? 0), 2);
    }

    private function calculateWeaknessMatchScore(int $userId, string $itemType, int $itemId): float
    {
        $itemSkillTable = $itemType === 'course'
            ? 'course_skills'
            : 'project_skills';

        $itemIdColumn = $itemType === 'course'
            ? 'course_id'
            : 'project_id';

        /*
         * Semakin rendah avg_score user pada skill item,
         * semakin tinggi weakness_match_score.
         */
        $score = DB::table('user_skill_profiles')
            ->join($itemSkillTable, 'user_skill_profiles.skill_id', '=', $itemSkillTable . '.skill_id')
            ->where('user_skill_profiles.user_id', $userId)
            ->where($itemSkillTable . '.' . $itemIdColumn, $itemId)
            ->selectRaw('AVG((100 - user_skill_profiles.avg_score) * ' . $itemSkillTable . '.weight) as score')
            ->value('score');

        return round((float) ($score ?? 0), 2);
    }

    private function calculateReadinessScore(int $userId, string $itemType, int $itemId): float
    {
        $itemSkillTable = $itemType === 'course'
            ? 'course_skills'
            : 'project_skills';

        $itemIdColumn = $itemType === 'course'
            ? 'course_id'
            : 'project_id';

        /*
         * Readiness sementara:
         * rata-rata avg_score user pada skill yang dibutuhkan item.
         */
        $score = DB::table('user_skill_profiles')
            ->join($itemSkillTable, 'user_skill_profiles.skill_id', '=', $itemSkillTable . '.skill_id')
            ->where('user_skill_profiles.user_id', $userId)
            ->where($itemSkillTable . '.' . $itemIdColumn, $itemId)
            ->selectRaw('AVG(user_skill_profiles.avg_score * ' . $itemSkillTable . '.weight) as score')
            ->value('score');

        return round((float) ($score ?? 0), 2);
    }

    private function getCompletedCourseCount(int $userId): int
    {
        $finalQuizzes = DB::table('quizzes')
            ->where('is_final', true)
            ->get();

        $completedCourseIds = [];

        foreach ($finalQuizzes as $quiz) {
            $hasPassed = DB::table('quiz_attempts')
                ->where('user_id', $userId)
                ->where('quiz_id', $quiz->id)
                ->where('is_verified', true)
                ->where('score', '>=', 70)
                ->exists();

            if ($hasPassed) {
                $completedCourseIds[] = $quiz->course_id;
            }
        }

        return count(array_unique($completedCourseIds));
    }

    private function getCompletedProjectCount(int $userId): int
    {
        return DB::table('project_participations')
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->count();
    }

    private function getRecentActivityScore(int $userId): float
    {
        /*
         * Hitung aktivitas 30 hari terakhir.
         * Bobot dibuat sederhana dulu.
         */
        $score = DB::table('learning_activity_logs')
            ->where('user_id', $userId)
            ->where('occurred_at', '>=', now()->subDays(30))
            ->selectRaw('SUM(
                CASE activity_type
                    WHEN "view_course" THEN 1
                    WHEN "view_material" THEN 2
                    WHEN "start_quiz" THEN 1
                    WHEN "finish_quiz" THEN 3
                    WHEN "submit_assignment" THEN 3
                    WHEN "view_project" THEN 1
                    WHEN "join_project" THEN 4
                    WHEN "complete_project" THEN 6
                    ELSE 0
                END
            ) as score')
            ->value('score');

        return round((float) ($score ?? 0), 2);
    }

    private function encodeDifficulty(?string $difficulty): int
    {
        return match ($difficulty) {
            'Beginner' => 1,
            'Intermediate' => 2,
            'Advanced' => 3,
            default => 1,
        };
    }
}
