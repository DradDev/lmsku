<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            // blockchain_id = "quiz_attempt_42" — ledger key
            $table->string('blockchain_id')->nullable()->after('blockchain_hash');
            // tx_id sudah ada dari migration sebelumnya, skip jika error
        });
    }

    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropColumn('blockchain_id');
        });
    }
};
