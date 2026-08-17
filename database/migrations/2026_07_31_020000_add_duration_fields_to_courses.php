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
        Schema::table('courses', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('duration_weeks');
            $table->date('end_date')->nullable()->after('start_date');
            $table->boolean('is_archived')->default(false)->after('end_date');
            $table->integer('certificate_threshold')->default(60)->after('is_archived');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'start_date',
                'end_date',
                'is_archived',
                'certificate_threshold',
            ]);
        });
    }
};
