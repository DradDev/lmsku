<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop view dulu kalau ada
        DB::statement("DROP VIEW IF EXISTS certificates");

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('score')->nullable();
            $table->string('blockchain_hash', 64)->nullable();
            $table->string('blockchain_id')->nullable();
            $table->string('tx_id', 128)->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });

        // Isi dari quiz_attempts yang sudah verified & punya blockchain_hash
        DB::statement("
            INSERT INTO certificates
                (user_id, course_id, score, blockchain_hash, blockchain_id, tx_id, completed_at, is_verified, created_at, updated_at)
            SELECT
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

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};