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
            if (! Schema::hasColumn('courses', 'moderation_status')) {
                $table->enum('moderation_status', ['published', 'suspended', 'revision_requested'])
                    ->default('published')
                    ->after('is_archived');
            }

            if (! Schema::hasColumn('courses', 'moderation_note')) {
                $table->text('moderation_note')->nullable()->after('moderation_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'moderation_status')) {
                $table->dropColumn('moderation_status');
            }
            if (Schema::hasColumn('courses', 'moderation_note')) {
                $table->dropColumn('moderation_note');
            }
        });
    }
};
