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
        Schema::table('materials', function (Blueprint $table) {
            if (!Schema::hasColumn('materials', 'materialable_type')) {
                $table->string('materialable_type')->nullable()->after('id');
            }
            if (!Schema::hasColumn('materials', 'materialable_id')) {
                $table->unsignedBigInteger('materialable_id')->nullable()->after('materialable_type');
                $table->index(['materialable_type', 'materialable_id'], 'materials_materialable_index');
            }
        });

        // Auto-backfill data lama yang sudah ada di database
        // 1. Data yang memiliki course_offering_id diisi sebagai CourseOffering
        DB::table('materials')
            ->whereNotNull('course_offering_id')
            ->whereNull('materialable_id')
            ->update([
                'materialable_type' => 'App\\Models\\CourseOffering',
                'materialable_id' => DB::raw('course_offering_id'),
            ]);

        // 2. Data yang hanya memiliki master_course_id diisi sebagai MasterCourse
        DB::table('materials')
            ->whereNotNull('master_course_id')
            ->whereNull('materialable_id')
            ->update([
                'materialable_type' => 'App\\Models\\MasterCourse',
                'materialable_id' => DB::raw('master_course_id'),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            if (Schema::hasColumn('materials', 'materialable_id')) {
                $table->dropIndex('materials_materialable_index');
                $table->dropColumn(['materialable_type', 'materialable_id']);
            }
        });
    }
};