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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama kursus
            $table->text('description')->nullable(); // Deskripsi kursus
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi dengan pengguna (lecturer)
            $table->enum('level', ['Beginner', 'Intermediate', 'Advanced']); // Level kursus
            $table->integer('progress')->default(0); // Progres mahasiswa dalam kursus (misal persentase)
            $table->integer('duration_weeks'); // Durasi kursus dalam minggu
            $table->timestamps(); // Tanggal created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
