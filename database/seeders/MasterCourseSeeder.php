<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MasterCourse;
use App\Models\Skill;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class MasterCourseSeeder extends Seeder
{
    public function run(): void
    {
        $catEmbedded = Category::where('name', 'Embedded Systems & Microcontroller')->first();
        $catWeb = Category::where('name', 'Web & Software Engineering')->first();
        $catNetwork = Category::where('name', 'Network Infrastructure & Cybersecurity')->first();

        $skillEmbedded = Skill::where('name', 'Embedded Systems & IoT')->first();
        $skillLaravel = Skill::where('name', 'Laravel Backend Framework')->first();
        $skillNetwork = Skill::where('name', 'Computer Networking')->first();

        // 1. Master Course: Praktikum Sistem Tertanam & Mikroprosesor
        $mcEmbedded = MasterCourse::updateOrCreate(
            ['code' => 'TK-EMS-INT-001'],
            [
                'name' => 'Praktikum Sistem Tertanam & Mikroprosesor',
                'level' => 'Intermediate',
                'certificate_threshold' => 75,
                'category_id' => $catEmbedded ? $catEmbedded->id : null,
                'description' => "Silabus mata kuliah Praktikum Sistem Tertanam. Membahas arsitektur ESP32, penggunaan periferal GPIO, komunikasi I2C/SPI, pembacaan sensor digital/analog, serta integrasi gateway IoT.\n\nCapaian Pembelajaran (CPMK):\n1. Mampu merancang skematik perangkat keras mikroprosesor.\n2. Menguasai pemrograman C/C++ pada ESP32.\n3. Mengimplementasikan pembacaan sensor real-time.",
            ]
        );

        if ($skillEmbedded) {
            $mcEmbedded->skills()->sync([$skillEmbedded->id]);
            $tags = Tag::where('skill_id', $skillEmbedded->id)->pluck('id')->toArray();
            $mcEmbedded->tags()->sync($tags);
        }

        // 2. Master Course: Pengembangan Web Enterprise dengan Laravel
        $mcLaravel = MasterCourse::updateOrCreate(
            ['code' => 'TK-LAB-ADV-001'],
            [
                'name' => 'Pengembangan Web Enterprise dengan Laravel',
                'level' => 'Advanced',
                'certificate_threshold' => 80,
                'category_id' => $catWeb ? $catWeb->id : null,
                'description' => "Silabus mata kuliah Pengembangan Web Enterprise. Membahas arsitektur MVC, konsumsi & pembuatan RESTful API, otentikasi JWT/Sanctum, otorisasi Role/Policy, serta optimasi query Eloquent ORM.\n\nCapaian Pembelajaran (CPMK):\n1. Mampu membangun REST API berstandar enterprise.\n2. Menguasai keamanan web dan otorisasi multi-role.",
            ]
        );

        if ($skillLaravel) {
            $mcLaravel->skills()->sync([$skillLaravel->id]);
            $tags = Tag::where('skill_id', $skillLaravel->id)->pluck('id')->toArray();
            $mcLaravel->tags()->sync($tags);
        }

        // 3. Master Course: Keamanan Jaringan & Infrastruktur Komputer
        $mcNetwork = MasterCourse::updateOrCreate(
            ['code' => 'TK-COM-BEG-001'],
            [
                'name' => 'Keamanan Jaringan & Infrastruktur Komputer',
                'level' => 'Beginner',
                'certificate_threshold' => 75,
                'category_id' => $catNetwork ? $catNetwork->id : null,
                'description' => "Silabus mata kuliah Keamanan Jaringan. Membahas fondasi protokol TCP/IP, routing & switching Cisco, konfigurasi Firewall, dan analisis keamanan jaringan terdistribusi.",
            ]
        );

        if ($skillNetwork) {
            $mcNetwork->skills()->sync([$skillNetwork->id]);
            $tags = Tag::where('skill_id', $skillNetwork->id)->pluck('id')->toArray();
            $mcNetwork->tags()->sync($tags);
        }
    }
}
