<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE questions MODIFY option_a VARCHAR(255) NULL");
            DB::statement("ALTER TABLE questions MODIFY option_b VARCHAR(255) NULL");
            DB::statement("ALTER TABLE questions MODIFY option_c VARCHAR(255) NULL");
            DB::statement("ALTER TABLE questions MODIFY option_d VARCHAR(255) NULL");
            DB::statement("ALTER TABLE questions MODIFY correct_answer VARCHAR(10) NULL");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("UPDATE questions SET option_a = '' WHERE option_a IS NULL");
            DB::statement("UPDATE questions SET option_b = '' WHERE option_b IS NULL");
            DB::statement("UPDATE questions SET option_c = '' WHERE option_c IS NULL");
            DB::statement("UPDATE questions SET option_d = '' WHERE option_d IS NULL");
            DB::statement("UPDATE questions SET correct_answer = '' WHERE correct_answer IS NULL");

            DB::statement("ALTER TABLE questions MODIFY option_a VARCHAR(255) NOT NULL");
            DB::statement("ALTER TABLE questions MODIFY option_b VARCHAR(255) NOT NULL");
            DB::statement("ALTER TABLE questions MODIFY option_c VARCHAR(255) NOT NULL");
            DB::statement("ALTER TABLE questions MODIFY option_d VARCHAR(255) NOT NULL");
            DB::statement("ALTER TABLE questions MODIFY correct_answer VARCHAR(10) NOT NULL");
        }
    }
};
