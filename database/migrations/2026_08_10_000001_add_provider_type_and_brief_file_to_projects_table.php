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
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'provider_type')) {
                $table->enum('provider_type', ['internal', 'external'])->default('internal')->after('created_by');
            }

            if (!Schema::hasColumn('projects', 'brief_file')) {
                $table->string('brief_file')->nullable()->after('provider_type');
            }

            if (!Schema::hasColumn('projects', 'benefits')) {
                $table->text('benefits')->nullable()->after('brief_file');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'provider_type')) {
                $table->dropColumn('provider_type');
            }

            if (Schema::hasColumn('projects', 'brief_file')) {
                $table->dropColumn('brief_file');
            }

            if (Schema::hasColumn('projects', 'benefits')) {
                $table->dropColumn('benefits');
            }
        });
    }
};
