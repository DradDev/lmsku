<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            if (! Schema::hasColumn('enrollments', 'progress_percent')) {
                $table->unsignedTinyInteger('progress_percent')
                    ->default(0)
                    ->after('course_id');
            }

            if (! Schema::hasColumn('enrollments', 'completed_material_count')) {
                $table->unsignedInteger('completed_material_count')
                    ->default(0)
                    ->after('progress_percent');
            }

            if (! Schema::hasColumn('enrollments', 'completed_quiz_count')) {
                $table->unsignedInteger('completed_quiz_count')
                    ->default(0)
                    ->after('completed_material_count');
            }

            if (! Schema::hasColumn('enrollments', 'total_material_count')) {
                $table->unsignedInteger('total_material_count')
                    ->default(0)
                    ->after('completed_quiz_count');
            }

            if (! Schema::hasColumn('enrollments', 'total_quiz_count')) {
                $table->unsignedInteger('total_quiz_count')
                    ->default(0)
                    ->after('total_material_count');
            }

            if (! Schema::hasColumn('enrollments', 'status')) {
                $table->string('status')
                    ->default('in_progress')
                    ->after('total_quiz_count');
            }

            if (! Schema::hasColumn('enrollments', 'started_at')) {
                $table->timestamp('started_at')
                    ->nullable()
                    ->after('status');
            }

            if (! Schema::hasColumn('enrollments', 'completed_at')) {
                $table->timestamp('completed_at')
                    ->nullable()
                    ->after('started_at');
            }

            if (! Schema::hasColumn('enrollments', 'last_activity_at')) {
                $table->timestamp('last_activity_at')
                    ->nullable()
                    ->after('completed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $columns = [
                'progress_percent',
                'completed_material_count',
                'completed_quiz_count',
                'total_material_count',
                'total_quiz_count',
                'status',
                'started_at',
                'completed_at',
                'last_activity_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('enrollments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
