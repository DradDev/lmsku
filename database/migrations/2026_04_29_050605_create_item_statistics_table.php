<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_statistics', function (Blueprint $table) {
            $table->id();

            $table->enum('item_type', ['course', 'project']);
            $table->unsignedBigInteger('item_id');

            $table->unsignedInteger('viewed_count')->default(0);
            $table->unsignedInteger('clicked_count')->default(0);
            $table->unsignedInteger('taken_count')->default(0);
            $table->unsignedInteger('completed_count')->default(0);

            $table->decimal('popularity_score', 10, 2)->default(0);
            $table->decimal('completion_rate', 8, 2)->default(0);

            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('last_calculated_at')->nullable();

            $table->timestamps();

            $table->unique(['item_type', 'item_id']);
            $table->index(['item_type', 'popularity_score']);
            $table->index(['item_type', 'completion_rate']);
        });
    }
};
