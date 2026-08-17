<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE project_participations
            MODIFY status ENUM('invited', 'in_progress', 'development', 'review', 'completed', 'declined')
            NOT NULL DEFAULT 'in_progress'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE project_participations
            MODIFY status ENUM('in_progress', 'development', 'review', 'completed')
            NOT NULL DEFAULT 'in_progress'
        ");
    }
};
