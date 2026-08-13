<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class SkillTagSeeder extends Seeder
{
    public function run(): void
    {
        $taxonomy = [
            'Embedded Systems & IoT' => [
                'description' => 'Keahlian merancang dan memprogram perangkat sistem tertanam dan sensor IoT.',
                'tags' => ['Microcontroller', 'ESP32', 'GPIO & I2C Protocol', 'Sensors & Actuators'],
            ],
            'Laravel Backend Framework' => [
                'description' => 'Keahlian merancang backend enterprise, RESTful API, dan MVC dengan Laravel.',
                'tags' => ['REST API', 'Eloquent ORM', 'Authentication & Middleware', 'MVC Architecture'],
            ],
            'Computer Networking' => [
                'description' => 'Keahlian mengonfigurasi jaringan komputer, routing, switching, dan firewall.',
                'tags' => ['TCP/IP Protocol', 'Cisco Routing', 'Firewall & Security', 'VLAN Configuration'],
            ],
            'Python Data Science' => [
                'description' => 'Keahlian analisis data, machine learning, dan pemrograman Python.',
                'tags' => ['Machine Learning', 'Pandas & NumPy', 'Neural Networks', 'Data Visualization'],
            ],
        ];

        foreach ($taxonomy as $skillName => $data) {
            $skill = Skill::updateOrCreate(
                ['name' => $skillName],
                ['description' => $data['description']]
            );

            foreach ($data['tags'] as $tagName) {
                Tag::updateOrCreate(
                    [
                        'name' => $tagName,
                        'skill_id' => $skill->id,
                    ],
                    []
                );
            }
        }
    }
}
