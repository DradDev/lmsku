<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('recommendation_feature_snapshots')) {
            return;
        }

        Schema::create('recommendation_feature_snapshots', function (Blueprint $table) {
            $table->id();

            $table->date('snapshot_date');

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('item_type', ['course', 'project']);
            $table->unsignedTinyInteger('item_type_encoded');
            $table->unsignedBigInteger('item_id');

            $table->decimal('user_avg_skill_score', 8, 2)->default(0);
            $table->decimal('user_lowest_skill_score', 8, 2)->default(0);

            $table->unsignedInteger('user_completed_course_count')->default(0);
            $table->unsignedInteger('user_completed_project_count')->default(0);

            $table->decimal('user_recent_activity_score', 8, 2)->default(0);

            $table->foreignId('user_top_interest_tag_id')
                ->nullable()
                ->constrained('tags')
                ->nullOnDelete();

            $table->unsignedTinyInteger('item_difficulty_level')->default(1);

            $table->foreignId('item_main_skill_id')
                ->nullable()
                ->constrained('skills')
                ->nullOnDelete();

            $table->decimal('item_popularity_score', 10, 2)->default(0);
            $table->decimal('item_completion_rate', 8, 2)->default(0);

            $table->decimal('interest_match_score', 8, 2)->default(0);
            $table->decimal('weakness_match_score', 8, 2)->default(0);
            $table->decimal('readiness_score', 8, 2)->default(0);

            $table->boolean('label_clicked')->default(false);
            $table->boolean('label_taken')->default(false);
            $table->boolean('label_completed')->default(false);
            $table->boolean('already_started_flag')->default(false);

            $table->unsignedInteger('item_skill_count')->default(0);
            $table->unsignedInteger('item_tag_count')->default(0);

            $table->timestamps();

            $table->unique(
                ['snapshot_date', 'user_id', 'item_type', 'item_id'],
                'unique_snapshot_user_item'
            );

            $table->index(['snapshot_date', 'user_id']);
            $table->index(['item_type', 'item_id']);
            $table->index(['label_taken']);
        });
    }
};
