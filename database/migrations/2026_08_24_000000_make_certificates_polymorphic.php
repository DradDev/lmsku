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
        // 1. Add polymorphic columns
        Schema::table('certificates', function (Blueprint $table) {
            if (!Schema::hasColumn('certificates', 'certifiable_type')) {
                $table->string('certifiable_type')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('certificates', 'certifiable_id')) {
                $table->unsignedBigInteger('certifiable_id')->nullable()->after('certifiable_type');
                $table->index(['certifiable_type', 'certifiable_id'], 'certificates_certifiable_index');
            }
        });

        // 2. Data backfill from course_offering_id
        if (Schema::hasColumn('certificates', 'course_offering_id')) {
            DB::table('certificates')
                ->whereNotNull('course_offering_id')
                ->whereNull('certifiable_id')
                ->update([
                    'certifiable_type' => 'App\\Models\\CourseOffering',
                    'certifiable_id'   => DB::raw('course_offering_id'),
                ]);
        }

        // 3. Data backfill from project_id
        if (Schema::hasColumn('certificates', 'project_id')) {
            DB::table('certificates')
                ->whereNotNull('project_id')
                ->whereNull('certifiable_id')
                ->update([
                    'certifiable_type' => 'App\\Models\\Project',
                    'certifiable_id'   => DB::raw('project_id'),
                ]);
        }

        // 4. Drop legacy columns
        Schema::table('certificates', function (Blueprint $table) {
            if (Schema::hasColumn('certificates', 'course_offering_id')) {
                try {
                    $table->dropForeign(['course_offering_id']);
                } catch (\Throwable $e) {}
                $table->dropColumn('course_offering_id');
            }

            if (Schema::hasColumn('certificates', 'project_id')) {
                try {
                    $table->dropForeign(['project_id']);
                } catch (\Throwable $e) {}
                $table->dropColumn('project_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            if (!Schema::hasColumn('certificates', 'course_offering_id')) {
                $table->foreignId('course_offering_id')->nullable()->after('user_id')->constrained('course_offerings')->nullOnDelete();
            }
            if (!Schema::hasColumn('certificates', 'project_id')) {
                $table->foreignId('project_id')->nullable()->after('course_offering_id')->constrained('projects')->nullOnDelete();
            }
        });

        // Re-populate legacy columns
        DB::table('certificates')
            ->where('certifiable_type', 'App\\Models\\CourseOffering')
            ->update([
                'course_offering_id' => DB::raw('certifiable_id'),
            ]);

        DB::table('certificates')
            ->where('certifiable_type', 'App\\Models\\Project')
            ->update([
                'project_id' => DB::raw('certifiable_id'),
            ]);

        Schema::table('certificates', function (Blueprint $table) {
            if (Schema::hasColumn('certificates', 'certifiable_id')) {
                $table->dropIndex('certificates_certifiable_index');
                $table->dropColumn('certifiable_id');
            }
            if (Schema::hasColumn('certificates', 'certifiable_type')) {
                $table->dropColumn('certifiable_type');
            }
        });
    }
};