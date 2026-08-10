<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE project_participations
                MODIFY status ENUM('in_progress', 'development', 'review', 'completed')
                NOT NULL DEFAULT 'in_progress'
            ");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE project_participations
                MODIFY status ENUM('in_progress', 'completed')
                NOT NULL DEFAULT 'in_progress'
            ");
        }
    }
};
