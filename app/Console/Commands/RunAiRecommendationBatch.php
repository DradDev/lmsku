<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class RunAiRecommendationBatch extends Command
{
    protected $signature = 'ai:run-recommendation-batch 
                            {--user_id= : Generate recommendation for specific user only}
                            {--top_n=10 : Number of recommendations per user}';

    protected $description = 'Run full AI recommendation batch: aggregation, feature snapshot, and Flask prediction';

    public function handle(): int
    {
        $this->info('Starting AI recommendation batch...');

        $userId = $this->option('user_id');
        $topN = (int) $this->option('top_n');

        $this->info('Step 1: Calculate user skill profiles...');
        $this->call('ai:calculate-user-skill-profiles', array_filter([
            '--user_id' => $userId,
        ]));

        $this->info('Step 2: Calculate user interest profiles...');
        $this->call('ai:calculate-user-interest-profiles', array_filter([
            '--user_id' => $userId,
        ]));

        $this->info('Step 3: Calculate item statistics...');
        $this->call('ai:calculate-item-statistics');

        $this->info('Step 4: Generate recommendation feature snapshots...');
        $this->call('ai:generate-recommendation-features', array_filter([
            '--user_id' => $userId,
        ]));

        $this->info('Step 5: Call Flask AI service...');

        $flaskUrl = config('services.ai.url', 'http://127.0.0.1:5000');
        $token = config('services.ai.token', 'lms_ai_secret_token');

        try {
            $response = Http::timeout(120)
                ->withHeaders([
                    'X-AI-TOKEN' => $token,
                    'Accept' => 'application/json',
                ])
                ->post($flaskUrl . '/recommendations/run-batch', [
                    'top_n' => $topN,
                ]);

            if (! $response->successful()) {
                $this->error('Flask AI service failed.');
                $this->error('Status: ' . $response->status());
                $this->error('Response: ' . $response->body());

                return self::FAILURE;
            }

            $data = $response->json();

            $this->info('Flask AI service response:');
            $this->line(json_encode($data, JSON_PRETTY_PRINT));

            $this->info('AI recommendation batch completed successfully.');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Failed to call Flask AI service.');
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}
