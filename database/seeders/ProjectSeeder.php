<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $vendor = User::where('role', 'vendor')->first();
        $admin = User::where('role', 'admin')->first();

        $catEmbedded = Category::where('name', 'Embedded Systems & Microcontroller')->first();
        $catWeb = Category::where('name', 'Web & Software Engineering')->first();

        $skillEmbedded = Skill::where('name', 'Embedded Systems & IoT')->first();
        $skillLaravel = Skill::where('name', 'Laravel Backend Framework')->first();

        // 1. Proyek Industri dari Vendor (PT Telkom - External)
        $p1 = Project::updateOrCreate(
            ['title' => 'Smart Industrial Environmental Monitoring Node (ESP32 Gateway)'],
            [
                'description' => 'Mitra industri PT Telkom Digital Indonesia membuka pendaftaran proyek pengembangan simpul sensor pemantau kualitas lingkungan pabrik berbasis ESP32, protokol I2C, dan pemancar telemetry gateway.',
                'difficulty_level' => 'Intermediate',
                'duration_days' => 60,
                'max_students' => 5,
                'created_by' => $vendor ? $vendor->id : ($admin ? $admin->id : 1),
                'provider_type' => 'external',
                'benefits' => 'Sertifikat Magang Industri, Uang Saku Bulanan, dan Pendampingan Mentor.',
                'is_published' => true,
                'category_id' => $catEmbedded ? $catEmbedded->id : null,
            ]
        );

        if ($skillEmbedded) {
            $p1->skills()->sync([$skillEmbedded->id]);
            $tags = Tag::where('skill_id', $skillEmbedded->id)->pluck('id')->toArray();
            $p1->tags()->sync($tags);
        }

        // 2. Proyek Industri Internal Campus LMS (Internal)
        $p2 = Project::updateOrCreate(
            ['title' => 'Enterprise RESTful API Microservices Hub'],
            [
                'description' => 'Proyek pengembangan layanan backend microservices enterprise menggunakan Laravel, JWT Authentication, dan optimasi query database terdistribusi.',
                'difficulty_level' => 'Advanced',
                'duration_days' => 90,
                'max_students' => 4,
                'created_by' => $admin ? $admin->id : 1,
                'provider_type' => 'internal',
                'benefits' => 'Sertifikat Portofolio Kampus, Kredit SKKM Akademik, dan Rekomendasi Karir.',
                'is_published' => true,
                'category_id' => $catWeb ? $catWeb->id : null,
            ]
        );

        if ($skillLaravel) {
            $p2->skills()->sync([$skillLaravel->id]);
            $tags = Tag::where('skill_id', $skillLaravel->id)->pluck('id')->toArray();
            $p2->tags()->sync($tags);
        }
    }
}
