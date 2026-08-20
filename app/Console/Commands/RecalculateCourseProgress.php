<?php

namespace App\Console\Commands;

use App\Models\Enrollment;
use App\Services\CourseProgressService;
use Illuminate\Console\Command;

class RecalculateCourseProgress extends Command
{
    protected $signature = 'course:recalculate-progress 
                            {--user_id= : Recalculate progress for specific user}
                            {--course_id= : Recalculate progress for specific course}';

    protected $description = 'Recalculate course progress for enrollments';

    public function handle(CourseProgressService $courseProgressService): int
    {
        $userId = $this->option('user_id');
        $courseId = $this->option('course_id');

        $query = Enrollment::query()->whereNotNull('course_offering_id');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($courseId) {
            $query->where('course_offering_id', $courseId);
        }

        $total = $query->count();

        if ($total === 0) {
            $this->warn('Tidak ada enrollment yang perlu dihitung ulang.');
            return self::SUCCESS;
        }

        $this->info("Menghitung ulang progress untuk {$total} enrollment...");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $query->chunkById(100, function ($enrollments) use ($courseProgressService, $bar) {
            foreach ($enrollments as $enrollment) {
                if ($enrollment->course_offering_id) {
                    $courseProgressService->recalculate(
                        $enrollment->user_id,
                        $enrollment->course_offering_id
                    );
                }

                $bar->advance();
            }
        });

        $bar->finish();

        $this->newLine(2);
        $this->info('Progress course berhasil dihitung ulang.');

        return self::SUCCESS;
    }
}
