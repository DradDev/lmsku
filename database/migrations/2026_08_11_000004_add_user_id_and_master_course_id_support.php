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
        if (Schema::hasTable('master_courses')) {
            Schema::table('master_courses', function (Blueprint $table) {
                if (! Schema::hasColumn('master_courses', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
                }
            });
        }

        if (Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                if (! Schema::hasColumn('courses', 'master_course_id')) {
                    $table->foreignId('master_course_id')->nullable()->after('user_id')->constrained('master_courses')->nullOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('master_courses')) {
            Schema::table('master_courses', function (Blueprint $table) {
                if (Schema::hasColumn('master_courses', 'user_id')) {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                }
            });
        }

        if (Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                if (Schema::hasColumn('courses', 'master_course_id')) {
                    $table->dropForeign(['master_course_id']);
                    $table->dropColumn('master_course_id');
                }
            });
        }
    }
};
