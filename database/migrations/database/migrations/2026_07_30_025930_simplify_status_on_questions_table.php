<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("UPDATE questions SET status = 'approved' WHERE status IN ('draft', 'pending', 'rejected')");

        DB::statement("
            ALTER TABLE questions
            MODIFY status ENUM('draft', 'pending', 'approved', 'rejected')
            NOT NULL DEFAULT 'approved'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE questions
            MODIFY status ENUM('draft', 'pending', 'approved', 'rejected')
            NOT NULL DEFAULT 'draft'
        ");
    }
};