<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Buat VIEW certificates yang memetakan dari quiz_attempts
        // Sehingga controller teman bisa search tanpa perubahan kode
        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                CREATE OR REPLACE VIEW certificates AS
                SELECT
                    qa.id,
                    qa.user_id,
                    q.course_id,
                    qa.score,
                    qa.blockchain_hash,
                    qa.blockchain_id,
                    qa.tx_id,
                    qa.completed_at,
                    qa.is_verified,
                    qa.created_at,
                    qa.updated_at
                FROM quiz_attempts qa
                LEFT JOIN quizzes q ON qa.quiz_id = q.id
                WHERE qa.is_verified = 1
                  AND qa.blockchain_hash IS NOT NULL
            ");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("DROP VIEW IF EXISTS certificates");
        }
    }
};
