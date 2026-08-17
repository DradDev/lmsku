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
        if (! Schema::hasTable('course_offerings')) {
            Schema::create('course_offerings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('master_course_id')->constrained('master_courses')->cascadeOnDelete();
                $table->foreignId('academic_term_id')->constrained('academic_terms')->cascadeOnDelete();
                $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->boolean('is_archived')->default(false);
                $table->integer('certificate_threshold')->default(60);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_offerings');
    }
};
