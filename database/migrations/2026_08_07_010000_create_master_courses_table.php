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
        if (! Schema::hasTable('master_courses')) {
            Schema::create('master_courses', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique()->nullable();
                $table->string('name');
                $table->text('description')->nullable();
                $table->enum('level', ['Beginner', 'Intermediate', 'Advanced'])->default('Beginner');
                $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_courses');
    }
};
