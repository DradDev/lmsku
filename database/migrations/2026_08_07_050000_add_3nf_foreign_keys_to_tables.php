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
        // Add master_course_id to materials
        if (Schema::hasTable('materials')) {
            Schema::table('materials', function (Blueprint $table) {
                if (! Schema::hasColumn('materials', 'master_course_id')) {
                    $table->foreignId('master_course_id')->nullable()->after('course_id')->constrained('master_courses')->nullOnDelete();
                }
            });
        }

        // Add master_course_id to quizzes
        if (Schema::hasTable('quizzes')) {
            Schema::table('quizzes', function (Blueprint $table) {
                if (! Schema::hasColumn('quizzes', 'master_course_id')) {
                    $table->foreignId('master_course_id')->nullable()->after('course_id')->constrained('master_courses')->nullOnDelete();
                }
            });
        }

        // Add course_offering_id to enrollments
        if (Schema::hasTable('enrollments')) {
            Schema::table('enrollments', function (Blueprint $table) {
                if (! Schema::hasColumn('enrollments', 'course_offering_id')) {
                    $table->foreignId('course_offering_id')->nullable()->after('course_id')->constrained('course_offerings')->nullOnDelete();
                }
            });
        }

        // Add course_offering_id to certificates
        if (Schema::hasTable('certificates')) {
            Schema::table('certificates', function (Blueprint $table) {
                if (! Schema::hasColumn('certificates', 'course_offering_id')) {
                    $table->foreignId('course_offering_id')->nullable()->after('course_id')->constrained('course_offerings')->nullOnDelete();
                }
            });
        }

        // Add course_offering_id to learning_activity_logs
        if (Schema::hasTable('learning_activity_logs')) {
            Schema::table('learning_activity_logs', function (Blueprint $table) {
                if (! Schema::hasColumn('learning_activity_logs', 'course_offering_id')) {
                    $table->foreignId('course_offering_id')->nullable()->after('course_id')->constrained('course_offerings')->nullOnDelete();
                }
            });
        }

        // Add offering_quiz_id to quiz_attempts
        if (Schema::hasTable('quiz_attempts')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                if (! Schema::hasColumn('quiz_attempts', 'offering_quiz_id')) {
                    $table->foreignId('offering_quiz_id')->nullable()->after('quiz_id')->constrained('offering_quizzes')->nullOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('quiz_attempts')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                if (Schema::hasColumn('quiz_attempts', 'offering_quiz_id')) {
                    $table->dropForeign(['offering_quiz_id']);
                    $table->dropColumn('offering_quiz_id');
                }
            });
        }

        if (Schema::hasTable('learning_activity_logs')) {
            Schema::table('learning_activity_logs', function (Blueprint $table) {
                if (Schema::hasColumn('learning_activity_logs', 'course_offering_id')) {
                    $table->dropForeign(['course_offering_id']);
                    $table->dropColumn('course_offering_id');
                }
            });
        }

        if (Schema::hasTable('certificates')) {
            Schema::table('certificates', function (Blueprint $table) {
                if (Schema::hasColumn('certificates', 'course_offering_id')) {
                    $table->dropForeign(['course_offering_id']);
                    $table->dropColumn('course_offering_id');
                }
            });
        }

        if (Schema::hasTable('enrollments')) {
            Schema::table('enrollments', function (Blueprint $table) {
                if (Schema::hasColumn('enrollments', 'course_offering_id')) {
                    $table->dropForeign(['course_offering_id']);
                    $table->dropColumn('course_offering_id');
                }
            });
        }

        if (Schema::hasTable('quizzes')) {
            Schema::table('quizzes', function (Blueprint $table) {
                if (Schema::hasColumn('quizzes', 'master_course_id')) {
                    $table->dropForeign(['master_course_id']);
                    $table->dropColumn('master_course_id');
                }
            });
        }

        if (Schema::hasTable('materials')) {
            Schema::table('materials', function (Blueprint $table) {
                if (Schema::hasColumn('materials', 'master_course_id')) {
                    $table->dropForeign(['master_course_id']);
                    $table->dropColumn('master_course_id');
                }
            });
        }
    }
};
