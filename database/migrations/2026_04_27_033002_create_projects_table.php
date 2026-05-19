<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('projects', function (Blueprint $table) {
        $table->id();

        $table->string('title');
        $table->text('description')->nullable();

        $table->enum('difficulty_level', [
            'Beginner',
            'Intermediate',
            'Advanced',
        ])->default('Beginner');

        $table->unsignedInteger('duration_days')->default(7);

        $table->foreignId('created_by')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->boolean('is_published')->default(false);

        $table->timestamps();
    });
}
};
