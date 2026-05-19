<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recommendation_results', function (Blueprint $table) {
            $table->id();

            $table->date('snapshot_date');

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('item_type', ['course', 'project']);
            $table->unsignedBigInteger('item_id');

            $table->decimal('prediction_score', 10, 6)->default(0);
            $table->unsignedInteger('rank')->default(0);

            $table->string('model_name')->nullable();
            $table->string('model_version')->nullable();

            $table->timestamps();

            $table->unique(
                ['snapshot_date', 'user_id', 'item_type', 'item_id'],
                'unique_recommendation_result'
            );

            $table->index(['user_id', 'rank']);
            $table->index(['item_type', 'item_id']);
            $table->index(['prediction_score']);
        });
    }
};
