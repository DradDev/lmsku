<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $mapping = [
            'Embedded Systems & Robotics' => [
                'Microcontrollers',
                'Internet of Things (IoT)',
                'Robotics',
                'Control Systems',
                'Digital Electronics',
                'Computer Vision',
                'Autonomous Systems',
                'Edge Computing',
            ],
            'Networking & Security' => [
                'Network Administration',
                'Server Administration',
                'Cloud Computing',
                'Virtualization',
                'Network Security',
                'Cybersecurity',
                'Digital Forensics',
                'Cryptography',
                'DevSecOps',
                'Wireless Networking',
            ],
            'Software Engineering' => [
                'Web Development',
                'Mobile Development',
                'Desktop Application Development',
                'Cloud Computing',
                'Software Architecture',
                'DevOps',
                'Database Engineering',
                'Software Testing',
                'UI/UX Design',
            ],
            'Machine Learning & Artificial Intelligence' => [
                'Machine Learning',
                'Deep Learning',
                'Computer Vision',
                'Natural Language Processing (NLP)',
                'Reinforcement Learning',
                'Generative AI',
                'Data Mining',
                'Predictive Analytics',
                'MLOps',
            ],
            'Multimedia' => [
                'Digital Image Processing',
                'Computer Graphics',
                'Animation',
                'Audio Processing',
                'Video Processing',
                'Augmented Reality (AR)',
                'Virtual Reality (VR)',
                'Mixed Reality (MR)',
                'Game Development',
                'Human–Computer Interaction (HCI)',
                'Interactive Media',
            ],
        ];

        foreach ($mapping as $skillName => $tags) {
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