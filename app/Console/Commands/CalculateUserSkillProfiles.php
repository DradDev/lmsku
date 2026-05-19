<?php

namespace App\Console\Commands;

use App\Models\UserSkillProfile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CalculateUserSkillProfiles extends Command
{
    protected $signature = 'ai:calculate-user-skill-profiles {--user_id=}';

    protected $description = 'Calculate user skill profiles from quiz answers and question skills';

    public function handle(): int
    {
        $userId = $this->option('user_id');

        $query = DB::table('quiz_answers')
            ->join('question_skills', 'quiz_answers.question_id', '=', 'question_skills.question_id')
            ->select(
                'quiz_answers.user_id',
                'question_skills.skill_id',
                DB::raw('AVG(COALESCE(quiz_answers.score, 0)) * 100 as avg_score'),
                DB::raw('MIN(COALESCE(quiz_answers.score, 0)) * 100 as lowest_score'),
                DB::raw('MAX(COALESCE(quiz_answers.score, 0)) * 100 as highest_score'),
                DB::raw('COUNT(quiz_answers.id) as attempt_count'),
                DB::raw('SUM(CASE WHEN quiz_answers.is_correct = 1 THEN 1 ELSE 0 END) as correct_count'),
                DB::raw('SUM(CASE WHEN quiz_answers.is_correct = 0 THEN 1 ELSE 0 END) as wrong_count'),
                DB::raw('MAX(quiz_answers.created_at) as last_activity_at')
            )
            ->whereNotNull('quiz_answers.score')
            ->groupBy('quiz_answers.user_id', 'question_skills.skill_id');

        if ($userId) {
            $query->where('quiz_answers.user_id', $userId);
        }

        $profiles = $query->get();

        if ($profiles->isEmpty()) {
            $this->warn('Belum ada data quiz answer yang bisa dihitung.');
            return self::SUCCESS;
        }

        foreach ($profiles as $profile) {
            UserSkillProfile::updateOrCreate(
                [
                    'user_id' => $profile->user_id,
                    'skill_id' => $profile->skill_id,
                ],
                [
                    'avg_score' => round($profile->avg_score, 2),
                    'lowest_score' => round($profile->lowest_score, 2),
                    'highest_score' => round($profile->highest_score, 2),
                    'attempt_count' => (int) $profile->attempt_count,
                    'correct_count' => (int) $profile->correct_count,
                    'wrong_count' => (int) $profile->wrong_count,
                    'last_activity_at' => $profile->last_activity_at,
                    'last_calculated_at' => now(),
                ]
            );
        }

        $this->info('User skill profiles berhasil dihitung: ' . $profiles->count() . ' profile.');

        return self::SUCCESS;
    }
}