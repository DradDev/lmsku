<?php

namespace Database\Seeders;

use App\Models\MasterCourse;
use App\Models\Skill;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class MasterCourseSeeder extends Seeder
{
    public function run(): void
    {
        $skillEmbedded = Skill::where('name', 'Embedded Systems & Robotics')->first();
        $skillSoftware = Skill::where('name', 'Software Engineering')->first();
        $skillNetwork = Skill::where('name', 'Networking & Security')->first();
        $skillAI = Skill::where('name', 'Machine Learning & Artificial Intelligence')->first();

        // 1. Course 1: Software Engineering
        $mc1 = MasterCourse::updateOrCreate(
            ['code' => 'TK-SOF-ADV-001'],
            [
                'name' => 'Praktikum Pemrograman Web Enterprise & Microservices',
                'level' => 'Advanced',
                'certificate_threshold' => 80,
                'description' => "Silabus mata kuliah Pemrograman Web Enterprise. Membahas arsitektur microservices, pembuatan RESTful API, otentikasi JWT/Sanctum, otorisasi Role/Policy, serta deployment aplikasi ke cloud server.\n\nCapaian Pembelajaran (CPMK):\n1. Mampu merancang backend microservices terdistribusi.\n2. Menguasai arsitektur software dan optimasi database enterprise.",
            ]
        );
        if ($skillSoftware) {
            $mc1->skills()->sync([$skillSoftware->id]);
            $tags = Tag::where('skill_id', $skillSoftware->id)->whereIn('name', ['Web Development', 'Software Architecture', 'Cloud Computing', 'DevOps'])->pluck('id')->toArray();
            $mc1->tags()->sync($tags);
        }

        // 2. Course 2: Embedded Systems & Robotics
        $mc2 = MasterCourse::updateOrCreate(
            ['code' => 'TK-EMB-INT-001'],
            [
                'name' => 'Praktikum Sistem Tertanam & Mikroprosesor ESP32',
                'level' => 'Intermediate',
                'certificate_threshold' => 75,
                'description' => "Silabus mata kuliah Praktikum Sistem Tertanam. Membahas arsitektur ESP32, penggunaan periferal GPIO, komunikasi I2C/SPI, pembacaan sensor digital/analog, serta integrasi gateway IoT industrial.\n\nCapaian Pembelajaran (CPMK):\n1. Mampu merancang skematik sistem mikrokontroler.\n2. Menguasai pemrograman sensor dan gateway telemetri.",
            ]
        );
        if ($skillEmbedded) {
            $mc2->skills()->sync([$skillEmbedded->id]);
            $tags = Tag::where('skill_id', $skillEmbedded->id)->whereIn('name', ['Microcontrollers', 'Internet of Things (IoT)', 'Control Systems'])->pluck('id')->toArray();
            $mc2->tags()->sync($tags);
        }

        // 3. Course 3: Networking & Security
        $mc3 = MasterCourse::updateOrCreate(
            ['code' => 'TK-NET-BEG-001'],
            [
                'name' => 'Keamanan Jaringan & Administrasi Server Cloud',
                'level' => 'Beginner',
                'certificate_threshold' => 75,
                'description' => "Silabus mata kuliah Keamanan Jaringan. Membahas fondasi protokol TCP/IP, routing & switching Cisco, konfigurasi Firewall, hardening server Linux, dan mitigasi ancaman cyber.",
            ]
        );
        if ($skillNetwork) {
            $mc3->skills()->sync([$skillNetwork->id]);
            $tags = Tag::where('skill_id', $skillNetwork->id)->whereIn('name', ['Network Administration', 'Network Security', 'Cybersecurity', 'Cloud Computing'])->pluck('id')->toArray();
            $mc3->tags()->sync($tags);
        }

        // 4. Course 4: Machine Learning & Artificial Intelligence
        $mc4 = MasterCourse::updateOrCreate(
            ['code' => 'TK-MAC-ADV-001'],
            [
                'name' => 'Penerapan Machine Learning & Computer Vision',
                'level' => 'Advanced',
                'certificate_threshold' => 80,
                'description' => "Silabus mata kuliah Machine Learning. Membahas algoritma pemrosesan citra digital, Convolutional Neural Networks (CNN), deteksi objek real-time, dan ekstraksi fitur visual.",
            ]
        );
        if ($skillAI) {
            $mc4->skills()->sync([$skillAI->id]);
            $tags = Tag::where('skill_id', $skillAI->id)->whereIn('name', ['Machine Learning', 'Deep Learning', 'Computer Vision'])->pluck('id')->toArray();
            $mc4->tags()->sync($tags);
        }
    }
}
