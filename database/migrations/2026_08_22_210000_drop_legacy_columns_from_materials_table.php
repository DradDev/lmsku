<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            if (Schema::hasColumn('materials', 'master_course_id')) {
                try {
                    $table->dropForeign(['master_course_id']);
                } catch (\Throwable $e) {}
            }
            if (Schema::hasColumn('materials', 'course_offering_id')) {
                try {
                    $table->dropForeign(['course_offering_id']);
                } catch (\Throwable $e) {}
            }
            $columnsToDrop = [];
            if (Schema::hasColumn('materials', 'master_course_id')) {
                $columnsToDrop[] = 'master_course_id';
            }
            if (Schema::hasColumn('materials', 'course_offering_id')) {
                $columnsToDrop[] = 'course_offering_id';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->foreignId('master_course_id')->nullable()->constrained('master_courses')->onDelete('cascade');
            $table->foreignId('course_offering_id')->nullable()->constrained('course_offerings')->onDelete('cascade');
        });
    }
};