<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunAiFullPipeline extends Command
{
    protected $signature = 'ai:run-full-pipeline {--top_n=10 : Jumlah rekomendasi teratas per user}';

    protected $description = 'Run full AI recommendation pipeline';

    public function handle(): int
    {
        $topN = (int) $this->option('top_n');

        $this->info('Memulai full AI recommendation pipeline...');

        try {
            $this->info('1. Menghitung user skill profiles...');
            Artisan::call('ai:calculate-user-skill-profiles');
            $this->line(Artisan::output());

            $this->info('2. Menghitung user interest profiles...');
            Artisan::call('ai:calculate-user-interest-profiles');
            $this->line(Artisan::output());

            $this->info('3. Menghitung item statistics...');
            Artisan::call('ai:calculate-item-statistics');
            $this->line(Artisan::output());

            $this->info('4. Generate recommendation feature snapshots...');
            Artisan::call('ai:generate-recommendation-features');
            $this->line(Artisan::output());

            $this->info('5. Menjalankan AI recommendation batch...');
            Artisan::call('ai:run-recommendation-batch', [
                '--top_n' => $topN,
            ]);
            $this->line(Artisan::output());

            $this->info('Full AI recommendation pipeline selesai.');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Full AI recommendation pipeline gagal.');
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}