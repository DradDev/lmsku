<?php

namespace App\Console\Commands;

use App\Models\UserInterestProfile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CalculateUserInterestProfiles extends Command
{
    protected $signature = 'ai:calculate-user-interest-profiles {--user_id=}';

    protected $description = 'Calculate user interest profiles from learning activity logs and item tags';

    public function handle(): int
    {
        $userId = $this->option('user_id');

        $courseInterestRows = $this->calculateCourseInterest($userId);
        $projectInterestRows = $this->calculateProjectInterest($userId);

        $merged = [];

        foreach ($courseInterestRows as $row) {
            $key = $row->user_id . '-' . $row->tag_id;

            if (! isset($merged[$key])) {
                $merged[$key] = [
                    'user_id' => $row->user_id,
                    'tag_id' => $row->tag_id,
                    'interest_score' => 0,
                    'interaction_count' => 0,
                    'last_activity_at' => null,
                ];
            }

            $merged[$key]['interest_score'] += (float) $row->interest_score;
            $merged[$key]['interaction_count'] += (int) $row->interaction_count;
            $merged[$key]['last_activity_at'] = $this->maxDate(
                $merged[$key]['last_activity_at'],
                $row->last_activity_at
            );
        }

        foreach ($projectInterestRows as $row) {
            $key = $row->user_id . '-' . $row->tag_id;

            if (! isset($merged[$key])) {
                $merged[$key] = [
                    'user_id' => $row->user_id,
                    'tag_id' => $row->tag_id,
                    'interest_score' => 0,
                    'interaction_count' => 0,
                    'last_activity_at' => null,
                ];
            }

            $merged[$key]['interest_score'] += (float) $row->interest_score;
            $merged[$key]['interaction_count'] += (int) $row->interaction_count;
            $merged[$key]['last_activity_at'] = $this->maxDate(
                $merged[$key]['last_activity_at'],
                $row->last_activity_at
            );
        }

        if (empty($merged)) {
            $this->warn('Belum ada data activity log dan tag yang bisa dihitung.');
            return self::SUCCESS;
        }

        foreach ($merged as $profile) {
            UserInterestProfile::updateOrCreate(
                [
                    'user_id' => $profile['user_id'],
                    'tag_id' => $profile['tag_id'],
                ],
                [
                    'interest_score' => round($profile['interest_score'], 2),
                    'interaction_count' => $profile['interaction_count'],
                    'last_activity_at' => $profile['last_activity_at'],
                    'last_calculated_at' => now(),
                ]
            );
        }

        $this->info('User interest profiles berhasil dihitung: ' . count($merged) . ' profile.');

        return self::SUCCESS;
    }

    private function calculateCourseInterest(?string $userId = null)
    {
        $query = DB::table('learning_activity_logs')
            ->join('course_offerings', 'learning_activity_logs.course_offering_id', '=', 'course_offerings.id')
            ->join('master_course_tags', 'course_offerings.master_course_id', '=', 'master_course_tags.master_course_id')
            ->select(
                'learning_activity_logs.user_id',
                'master_course_tags.tag_id',
                DB::raw('SUM(
                    CASE learning_activity_logs.activity_type
                        WHEN "view_course" THEN 1
                        WHEN "view_material" THEN 2
                        WHEN "start_quiz" THEN 1
                        WHEN "finish_quiz" THEN 3
                        WHEN "submit_assignment" THEN 3
                        WHEN "enroll_course" THEN 4
                        ELSE 0
                    END
                ) as interest_score'),
                DB::raw('COUNT(learning_activity_logs.id) as interaction_count'),
                DB::raw('MAX(learning_activity_logs.created_at) as last_activity_at')
            )
            ->whereNotNull('learning_activity_logs.course_offering_id')
            ->whereIn('learning_activity_logs.activity_type', [
                'view_course',
                'view_material',
                'start_quiz',
                'finish_quiz',
                'submit_assignment',
                'enroll_course',
            ])
            ->groupBy('learning_activity_logs.user_id', 'master_course_tags.tag_id');

        if ($userId) {
            $query->where('learning_activity_logs.user_id', $userId);
        }

        return $query->get();
    }

    private function calculateProjectInterest(?string $userId = null)
    {
        $query = DB::table('learning_activity_logs')
            ->join('project_tags', 'learning_activity_logs.project_id', '=', 'project_tags.project_id')
            ->select(
                'learning_activity_logs.user_id',
                'project_tags.tag_id',
                DB::raw('SUM(
                    CASE learning_activity_logs.activity_type
                        WHEN "view_project" THEN 1
                        WHEN "join_project" THEN 4
                        WHEN "complete_project" THEN 6
                        ELSE 0
                    END * project_tags.weight
                ) as interest_score'),
                DB::raw('COUNT(learning_activity_logs.id) as interaction_count'),
                DB::raw('MAX(learning_activity_logs.occurred_at) as last_activity_at')
            )
            ->whereNotNull('learning_activity_logs.project_id')
            ->whereIn('learning_activity_logs.activity_type', [
                'view_project',
                'join_project',
                'complete_project',
            ])
            ->groupBy('learning_activity_logs.user_id', 'project_tags.tag_id');

        if ($userId) {
            $query->where('learning_activity_logs.user_id', $userId);
        }

        return $query->get();
    }

    private function maxDate(?string $currentDate, ?string $newDate): ?string
    {
        if (! $currentDate) {
            return $newDate;
        }

        if (! $newDate) {
            return $currentDate;
        }

        return strtotime($newDate) > strtotime($currentDate)
            ? $newDate
            : $currentDate;
    }
}