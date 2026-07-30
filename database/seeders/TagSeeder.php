<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Tags per Skill (10 tags per skill utama)
        |--------------------------------------------------------------------------
        */

        $skillTagMapping = [
            'AI/ML' => [
                'Python',
                'Machine Learning',
                'Deep Learning',
                'Tensorflow',
                'Pytorch',
                'OpenCV',
                'Computer Vision',
                'NLP',
                'Data Science',
                'Data Mining',
            ],

            'Blockchain' => [
                'Solidity',
                'Ethereum',
                'Web3',
                'Smart Contract',
                'Metamask',
                'Cryptography',
                'Consensus',
                'NFT',
                'DeFi',
                'Hyperledger',
            ],

            'Embedded System' => [
                'Arduino',
                'ESP32',
                'Microcontroller',
                'Raspberry Pi',
                'IoT',
                'Sensor',
                'C Programming',
                'RTOS',
                'PCB Design',
                'Embedded Linux',
            ],

            'Network' => [
                'TCP/IP',
                'Routing',
                'Switching',
                'Mikrotik',
                'Cisco',
                'Linux Server',
                'Cyber Security',
                'Firewall',
                'VPN',
                'Wireshark',
            ],

            'Multimedia' => [
                'UI',
                'UX',
                'Figma',
                'Adobe XD',
                'Animation',
                'Video Editing',
                'Graphic Design',
                '3D Modeling',
                'Photoshop',
                'Illustrator',
            ],

            'Software Development' => [
                'PHP',
                'Laravel',
                'React',
                'NodeJS',
                'API',
                'Database',
                'Java',
                'Spring Boot',
                'Flutter',
                'Docker',
            ],
        ];

        foreach ($skillTagMapping as $skillName => $tags) {
            $skill = Skill::where('name', $skillName)->whereNull('parent_id')->first();

            if (! $skill) {
                continue;
            }

            foreach ($tags as $tagName) {
                Tag::updateOrCreate(
                    ['name' => $tagName],
                    ['skill_id' => $skill->id]
                );
            }
        }
    }
}