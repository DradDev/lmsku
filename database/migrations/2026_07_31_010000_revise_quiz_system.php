<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->enum('quiz_type', ['daily', 'weekly', 'final'])->default('daily')->after('time_limit');
            $table->unsignedInteger('max_attempts')->default(1)->after('quiz_type');
            $table->dateTime('start_date')->nullable()->after('max_attempts');
            $table->dateTime('end_date')->nullable()->after('start_date');
        });

        // Update existing quizzes based on is_final before dropping it
        DB::table('quizzes')->where('is_final', true)->update(['quiz_type' => 'final']);
        DB::table('quizzes')->where('is_final', false)->update(['quiz_type' => 'daily']);

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('is_final');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->boolean('is_final')->default(false)->after('time_limit');
        });

        // Revert data
        DB::table('quizzes')->where('quiz_type', 'final')->update(['is_final' => true]);
        DB::table('quizzes')->where('quiz_type', '!=', 'final')->update(['is_final' => false]);

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn(['quiz_type', 'max_attempts', 'start_date', 'end_date']);
        });
    }
};
