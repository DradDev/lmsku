<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom pendukung Fase 2:
     * - academic_terms: academic_year, term_type
     * - course_offerings: section_name, capacity, status
     */
    public function up(): void
    {
        // Tambah kolom ke academic_terms
        Schema::table('academic_terms', function (Blueprint $table) {
            if (! Schema::hasColumn('academic_terms', 'academic_year')) {
                $table->string('academic_year')->nullable()->after('name'); // e.g. "2025/2026"
            }
            if (! Schema::hasColumn('academic_terms', 'term_type')) {
                $table->enum('term_type', ['ganjil', 'genap'])->default('ganjil')->after('academic_year');
            }
        });

        // Tambah kolom ke course_offerings
        Schema::table('course_offerings', function (Blueprint $table) {
            if (! Schema::hasColumn('course_offerings', 'section_name')) {
                $table->string('section_name')->nullable()->after('lecturer_id'); // e.g. "Kelas A"
            }
            if (! Schema::hasColumn('course_offerings', 'capacity')) {
                $table->integer('capacity')->nullable()->after('section_name'); // Kuota mahasiswa
            }
            if (! Schema::hasColumn('course_offerings', 'status')) {
                $table->enum('status', ['draft', 'published', 'ongoing', 'expired', 'cancelled'])
                    ->default('draft')
                    ->after('certificate_threshold');
            }
        });

        // Tambah unique constraint agar tidak bisa buka Kelas A ganda
        // pada matkul & semester yang sama
        try {
            Schema::table('course_offerings', function (Blueprint $table) {
                $table->unique(
                    ['master_course_id', 'academic_term_id', 'section_name'],
                    'course_offerings_unique_section'
                );
            });
        } catch (\Exception $e) {
            // Index sudah ada, skip
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_offerings', function (Blueprint $table) {
            $table->dropUnique('course_offerings_unique_section');
            $table->dropColumn(['section_name', 'capacity', 'status']);
        });

        Schema::table('academic_terms', function (Blueprint $table) {
            $table->dropColumn(['academic_year', 'term_type']);
        });
    }
};
