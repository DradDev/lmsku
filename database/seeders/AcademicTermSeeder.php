<?php

namespace Database\Seeders;

use App\Models\AcademicTerm;
use Illuminate\Database\Seeder;

class AcademicTermSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan semester lama jika ada
        AcademicTerm::query()->update(['is_active' => false]);

        // Semester Genap 2025/2026 (Lampau)
        AcademicTerm::updateOrCreate(
            ['name' => 'Semester Genap 2025/2026'],
            [
                'start_date' => '2026-01-15',
                'end_date' => '2026-06-30',
                'is_active' => false,
            ]
        );

        // Semester Ganjil 2026/2027 (Aktif Saat Ini)
        AcademicTerm::updateOrCreate(
            ['name' => 'Semester Ganjil 2026/2027'],
            [
                'start_date' => '2026-08-01',
                'end_date' => '2026-12-31',
                'is_active' => true,
            ]
        );
    }
}
