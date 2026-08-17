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
        $catNetwork = Category::where('name', 'Network Infrastructure & Cybersecurity')->first();

        $skillEmbedded = Skill::where('name', 'Embedded Systems & Robotics')->first();
        $skillSoftware = Skill::where('name', 'Software Engineering')->first();
        $skillNetwork = Skill::where('name', 'Networking & Security')->first();

        // 1. Proyek Industri 1: Vendor PT Telkom (Embedded Systems & Robotics)
        $p1 = Project::updateOrCreate(
            ['title' => 'Smart Factory IoT Sensor Gateway & Environmental Telemetry'],
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
            $tags = Tag::where('skill_id', $skillEmbedded->id)->whereIn('name', ['Microcontrollers', 'Internet of Things (IoT)', 'Edge Computing'])->pluck('id')->toArray();
            $p1->tags()->sync($tags);
        }

        // 2. Proyek Industri 2: Internal Campus LMS (Software Engineering)
        $p2 = Project::updateOrCreate(
            ['title' => 'High-Availability Microservices RESTful API Platform'],
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
        if ($skillSoftware) {
            $p2->skills()->sync([$skillSoftware->id]);
            $tags = Tag::where('skill_id', $skillSoftware->id)->whereIn('name', ['Web Development', 'Software Architecture', 'Cloud Computing'])->pluck('id')->toArray();
            $p2->tags()->sync($tags);
        }

        // 3. Proyek Industri 3: Vendor Cyber Security (Networking & Security)
        $p3 = Project::updateOrCreate(
            ['title' => 'Enterprise Network Security Audit & SOC Monitoring System'],
            [
                'description' => 'Proyek simulasi pengujian penetrasi (Penetration Testing), pengerasan firewall server Linux, dan implementasi Security Operations Center (SOC) monitoring.',
                'difficulty_level' => 'Advanced',
                'duration_days' => 75,
                'max_students' => 3,
                'created_by' => $vendor ? $vendor->id : 1,
                'provider_type' => 'external',
                'benefits' => 'Sertifikat Magang Cyber Security, Insentif Proyek, dan Sertifikasi Industri.',
                'is_published' => true,
                'category_id' => $catNetwork ? $catNetwork->id : null,
            ]
        );
        if ($skillNetwork) {
            $p3->skills()->sync([$skillNetwork->id]);
            $tags = Tag::where('skill_id', $skillNetwork->id)->whereIn('name', ['Network Security', 'Cybersecurity', 'DevSecOps'])->pluck('id')->toArray();
            $p3->tags()->sync($tags);
        }
    }
}
