<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AcademicTermSeeder::class,
            CategorySeeder::class,
            SkillTagSeeder::class,
            MasterCourseSeeder::class,
            CourseOfferingSeeder::class,
            MaterialQuizSeeder::class,
            ProjectSeeder::class,
            EnrollmentCertificateSeeder::class,
        ]);
    }
}