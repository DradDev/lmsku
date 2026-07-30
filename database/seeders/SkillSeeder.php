<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Skill Utama / Pedoman Besar
        |--------------------------------------------------------------------------
        */

        $mainSkills = [
            [
                'name' => 'AI/ML',
                'description' => 'Bidang kemampuan yang berhubungan dengan machine learning, artificial intelligence, data science, dan pemrosesan data.',
            ],
            [
                'name' => 'Blockchain',
                'description' => 'Bidang kemampuan yang berhubungan dengan blockchain, smart contract, Web3, cryptocurrency, dan decentralized application.',
            ],
            [
                'name' => 'Embedded System',
                'description' => 'Bidang kemampuan yang berhubungan dengan sistem tertanam, microcontroller, sensor, IoT, dan perangkat keras terprogram.',
            ],
            [
                'name' => 'Network',
                'description' => 'Bidang kemampuan yang berhubungan dengan jaringan komputer, komunikasi data, routing, dan keamanan jaringan.',
            ],
            [
                'name' => 'Multimedia',
                'description' => 'Bidang kemampuan yang berhubungan dengan desain, UI/UX, animasi, video, audio, grafis, dan konten digital.',
            ],
            [
                'name' => 'Software Development',
                'description' => 'Bidang kemampuan yang berhubungan dengan pengembangan aplikasi, web, backend, frontend, database, dan software engineering.',
            ],
        ];

        // Rename existing skills if they exist (backward compatibility)
        $renames = [
            'ML / AI' => 'AI/ML',
            'Jaringan / Network' => 'Network',
            'Software' => 'Software Development',
        ];

        foreach ($renames as $oldName => $newName) {
            $skill = Skill::where('name', $oldName)->first();
            if ($skill) {
                $skill->update(['name' => $newName]);
            }
        }

        foreach ($mainSkills as $mainSkill) {
            Skill::updateOrCreate(
                ['name' => $mainSkill['name']],
                [
                    'description' => $mainSkill['description'],
                    'parent_id' => null,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Skill Turunan
        |--------------------------------------------------------------------------
        */

        $childSkills = [
            'Network' => [
                ['name' => 'TCP/IP', 'description' => 'Dasar komunikasi jaringan menggunakan protokol TCP/IP.'],
                ['name' => 'Routing', 'description' => 'Kemampuan memahami dan mengatur routing jaringan.'],
                ['name' => 'Network Security', 'description' => 'Kemampuan memahami keamanan jaringan komputer.'],
                ['name' => 'Wireless Network', 'description' => 'Kemampuan memahami jaringan nirkabel.'],
            ],

            'Embedded System' => [
                ['name' => 'Arduino', 'description' => 'Kemampuan membuat sistem berbasis Arduino.'],
                ['name' => 'Microcontroller', 'description' => 'Kemampuan memahami dan memprogram microcontroller.'],
                ['name' => 'IoT', 'description' => 'Kemampuan menghubungkan perangkat fisik ke internet.'],
                ['name' => 'Sensor Integration', 'description' => 'Kemampuan menghubungkan sensor dengan sistem embedded.'],
            ],

            'Software Development' => [
                ['name' => 'Laravel', 'description' => 'Kemampuan membangun aplikasi web menggunakan Laravel.'],
                ['name' => 'PHP', 'description' => 'Kemampuan pemrograman PHP.'],
                ['name' => 'MySQL', 'description' => 'Kemampuan mengelola database MySQL.'],
                ['name' => 'Database Design', 'description' => 'Kemampuan merancang struktur database.'],
                ['name' => 'API Integration', 'description' => 'Kemampuan integrasi API.'],
                ['name' => 'Backend Development', 'description' => 'Kemampuan membangun backend aplikasi.'],
                ['name' => 'Frontend Development', 'description' => 'Kemampuan membangun tampilan frontend aplikasi.'],
                ['name' => 'Software Engineering', 'description' => 'Kemampuan memahami proses rekayasa perangkat lunak.'],
            ],

            'Multimedia' => [
                ['name' => 'UI/UX Design', 'description' => 'Kemampuan merancang pengalaman dan antarmuka pengguna.'],
                ['name' => 'Graphic Design', 'description' => 'Kemampuan membuat desain grafis.'],
                ['name' => 'Video Editing', 'description' => 'Kemampuan mengedit video.'],
                ['name' => 'Animation', 'description' => 'Kemampuan membuat animasi digital.'],
                ['name' => '3D Design', 'description' => 'Kemampuan membuat model atau aset 3D.'],
            ],

            'AI/ML' => [
                ['name' => 'Python', 'description' => 'Kemampuan pemrograman Python.'],
                ['name' => 'Machine Learning', 'description' => 'Kemampuan membangun model machine learning.'],
                ['name' => 'Data Preprocessing', 'description' => 'Kemampuan membersihkan dan menyiapkan data.'],
                ['name' => 'Data Science', 'description' => 'Kemampuan menganalisis data untuk menghasilkan insight.'],
                ['name' => 'Model Evaluation', 'description' => 'Kemampuan mengevaluasi performa model AI.'],
                ['name' => 'Recommendation System', 'description' => 'Kemampuan membangun sistem rekomendasi.'],
            ],

            'Blockchain' => [
                ['name' => 'Smart Contract', 'description' => 'Kemampuan membuat kontrak pintar di blockchain.'],
                ['name' => 'Web3', 'description' => 'Kemampuan membangun aplikasi berbasis Web3.'],
                ['name' => 'Solidity', 'description' => 'Kemampuan pemrograman smart contract menggunakan Solidity.'],
                ['name' => 'Decentralized Application', 'description' => 'Kemampuan membangun aplikasi terdesentralisasi.'],
            ],
        ];

        foreach ($childSkills as $parentName => $children) {
            $parent = Skill::where('name', $parentName)->first();

            if (! $parent) {
                continue;
            }

            foreach ($children as $child) {
                Skill::updateOrCreate(
                    ['name' => $child['name']],
                    [
                        'description' => $child['description'],
                        'parent_id' => $parent->id,
                    ]
                );
            }
        }
    }
}