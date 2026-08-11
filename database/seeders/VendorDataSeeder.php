<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\MasterCourse;
use App\Models\Material;
use App\Models\Project;
use App\Models\ProjectParticipation;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Skill;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VendorDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure Vendor User exists
        $vendor = User::firstOrCreate(
            ['email' => 'vendor@telkom.id'],
            [
                'name' => 'PT Telkom Digital Partner',
                'password' => bcrypt('password'),
                'role' => 'vendor',
            ]
        );

        $student = User::where('role', 'student')->first();
        $category = Category::first() ?? Category::create(['name' => 'Teknologi Informasi & Cloud']);

        // 2. Ensure Skills & Tags
        $skillCloud = Skill::firstOrCreate(['name' => 'Cloud Computing & DevOps'], ['parent_id' => null]);
        $skillWeb = Skill::firstOrCreate(['name' => 'Web Development & API'], ['parent_id' => null]);
        $skillSecurity = Skill::firstOrCreate(['name' => 'Cyber Security & Audit'], ['parent_id' => null]);

        $tagDocker = Tag::firstOrCreate(['name' => 'Docker & Kubernetes'], ['skill_id' => $skillCloud->id]);
        $tagLaravel = Tag::firstOrCreate(['name' => 'Laravel & Microservices'], ['skill_id' => $skillWeb->id]);
        $tagPentest = Tag::firstOrCreate(['name' => 'Pentesting & OWASP'], ['skill_id' => $skillSecurity->id]);

        // 3. Create Vendor Master Courses & Batch Offerings (3NF)
        $masterCourse1 = MasterCourse::firstOrCreate(
            ['name' => 'Sertifikasi Fullstack Microservices & Cloud Enterprise'],
            [
                'code' => 'VMC-TELKOM-01',
                'description' => 'Kurikulum standar industri Telkom untuk arsitektur microservices modern, containerization Docker, dan integrasi cloud.',
                'level' => 'Intermediate',
                'category_id' => $category->id,
                'user_id' => $vendor->id,
            ]
        );

        // Attach skills & tags to Master Course
        $masterCourse1->skills()->syncWithoutDetaching([
            $skillCloud->id => ['is_main' => true],
            $skillWeb->id => ['is_main' => true],
        ]);
        $masterCourse1->tags()->syncWithoutDetaching([$tagDocker->id, $tagLaravel->id]);

        // Add 3NF Master Course Material
        $mat1 = Material::firstOrCreate(
            ['title' => 'Modul 1: Pengenalan Arsitektur Microservices Cloud Telkom'],
            [
                'master_course_id' => $masterCourse1->id,
                'file_path' => 'materials/sample_microservices.pdf',
            ]
        );

        // Add 3NF Master Course Final Quiz
        $quiz1 = Quiz::firstOrCreate(
            ['title' => 'Final Exam Sertifikasi Microservices Cloud Telkom'],
            [
                'master_course_id' => $masterCourse1->id,
                'quiz_type' => 'final',
                'time_limit' => 45,
                'max_attempts' => 3,
            ]
        );

        Question::firstOrCreate(
            ['question' => 'Komponen mana yang berfungsi mengatur lalu lintas data HTTP menuju microservices internal?'],
            [
                'quiz_id' => $quiz1->id,
                'user_id' => $vendor->id,
                'question_type' => 'multiple_choice',
                'option_a' => 'API Gateway',
                'option_b' => 'Load Balancer Only',
                'option_c' => 'Database Primary',
                'option_d' => 'DNS Server',
                'correct_answer' => 'A',
                'status' => 'approved',
                'difficulty' => 'Medium',
            ]
        );

        // Batch 1 Course
        $vendorCourse1 = Course::firstOrCreate(
            [
                'name' => 'Sertifikasi Fullstack Microservices & Cloud Enterprise',
                'batch_name' => 'Batch 1 - 2026',
                'user_id' => $vendor->id,
            ],
            [
                'master_course_id' => $masterCourse1->id,
                'description' => 'Program pelatihan sertifikasi intensif angkatan pertama dengan mentor industri Telkom.',
                'category_id' => $category->id,
                'certificate_threshold' => 80,
                'duration_weeks' => 6,
                'level' => 'Intermediate',
                'is_archived' => false,
                'progress' => 0,
            ]
        );
        $vendorCourse1->skills()->syncWithoutDetaching([
            $skillCloud->id => ['is_main' => true, 'weight' => 1.00],
            $skillWeb->id => ['is_main' => true, 'weight' => 1.00],
        ]);

        // Batch 2 Course
        $vendorCourse2 = Course::firstOrCreate(
            [
                'name' => 'Sertifikasi Fullstack Microservices & Cloud Enterprise',
                'batch_name' => 'Batch 2 - Intake Q3 2026',
                'user_id' => $vendor->id,
            ],
            [
                'master_course_id' => $masterCourse1->id,
                'description' => 'Peluncuran angkatan kedua untuk semester ganjil 2026/2027.',
                'category_id' => $category->id,
                'certificate_threshold' => 80,
                'duration_weeks' => 6,
                'level' => 'Intermediate',
                'is_archived' => false,
                'progress' => 0,
            ]
        );
        $vendorCourse2->skills()->syncWithoutDetaching([
            $skillCloud->id => ['is_main' => true, 'weight' => 1.00],
            $skillWeb->id => ['is_main' => true, 'weight' => 1.00],
        ]);

        // 4. Create Published Vendor Projects
        $vendorProject1 = Project::firstOrCreate(
            ['title' => 'Pengembangan Cloud Payment Gateway Microservices Telkom'],
            [
                'description' => 'Project real-client industri untuk membangun gateway pembayaran berbasis Docker & Laravel Microservices dengan standar keamanan PCI-DSS.',
                'benefits' => 'Insentif Rp 3.500.000 / bulan, Sertifikat Pendamping Portofolio Resmi Telkom Digital, dan Kesempatan Rekrutmen Langsung.',
                'difficulty_level' => 'Intermediate',
                'duration_days' => 30,
                'max_students' => 5,
                'is_published' => true, // Published!
                'created_by' => $vendor->id,
                'provider_type' => 'external',
            ]
        );

        $vendorProject1->skills()->syncWithoutDetaching([
            $skillCloud->id => ['is_main' => true, 'weight' => 1.00],
            $skillWeb->id => ['is_main' => true, 'weight' => 1.00],
        ]);
        $vendorProject1->tags()->syncWithoutDetaching([$tagDocker->id, $tagLaravel->id]);

        $vendorProject2 = Project::firstOrCreate(
            ['title' => 'Audit Keamanan & Penesting Portal Enterprise Telkom'],
            [
                'description' => 'Pemeriksaan celah keamanan (vulnerability assessment) dan pengujian penetrasi pada sistem otentikasi single sign-on enterprise.',
                'benefits' => 'Sertifikat Keahlian Cyber Security Telkom + Letter of Recommendation dari Chief Information Security Officer.',
                'difficulty_level' => 'Advanced',
                'duration_days' => 45,
                'max_students' => 3,
                'is_published' => true, // Published!
                'created_by' => $vendor->id,
                'provider_type' => 'external',
            ]
        );

        $vendorProject2->skills()->syncWithoutDetaching([
            $skillSecurity->id => ['is_main' => true, 'weight' => 1.00],
        ]);
        $vendorProject2->tags()->syncWithoutDetaching([$tagPentest->id]);

        // 5. Send Pending Project Invitation to Student
        if ($student) {
            ProjectParticipation::firstOrCreate(
                [
                    'project_id' => $vendorProject1->id,
                    'user_id' => $student->id,
                ],
                [
                    'status' => 'invited',
                    'progress_percent' => 0,
                    'last_activity_at' => now(),
                ]
            );
        }

        echo "VendorDataSeeder executed successfully!\n";
    }
}
