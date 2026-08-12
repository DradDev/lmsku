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
        Schema::table('master_courses', function (Blueprint $table) {
            if (! Schema::hasColumn('master_courses', 'certificate_threshold')) {
                $table->integer('certificate_threshold')->default(75)->after('level');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_courses', function (Blueprint $table) {
            if (Schema::hasColumn('master_courses', 'certificate_threshold')) {
                $table->dropColumn('certificate_threshold');
            }
        });
    }
};
