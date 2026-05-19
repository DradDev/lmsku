<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('project_tags', function (Blueprint $table) {
        $table->id();

        $table->foreignId('project_id')
            ->constrained('projects')
            ->cascadeOnDelete();

        $table->foreignId('tag_id')
            ->constrained('tags')
            ->cascadeOnDelete();

        $table->decimal('weight', 5, 2)->default(1.00);

        $table->timestamps();

        $table->unique(['project_id', 'tag_id']);
    });
}
};
