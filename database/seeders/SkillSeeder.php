<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $mainSkills = [
            [
                'name' => 'Embedded Systems & Robotics',
                'description' => 'Field of embedded systems, microcontrollers, IoT, robotics, control systems, and hardware.',
            ],
            [
                'name' => 'Networking & Security',
                'description' => 'Field of computer networks, network security, cloud computing, server administration, and cybersecurity.',
            ],
            [
                'name' => 'Software Engineering',
                'description' => 'Field of software engineering, web development, mobile, cloud computing, and software architecture.',
            ],
            [
                'name' => 'Machine Learning & Artificial Intelligence',
                'description' => 'Field of machine learning, deep learning, computer vision, NLP, and data mining.',
            ],
            [
                'name' => 'Multimedia',
                'description' => 'Field of digital image processing, computer graphics, animation, audio/video processing, AR/VR, and game development.',
            ],
        ];

        foreach ($mainSkills as $mainSkill) {
            Skill::updateOrCreate(
                ['name' => $mainSkill['name']],
                [
                    'description' => $mainSkill['description'],
                ]
            );
        }
    }
}