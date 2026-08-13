<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Web & Software Engineering', 'description' => 'Kurikulum pemrograman web modern, arsitektur MVC, REST API, dan software engineering.'],
            ['name' => 'Embedded Systems & Microcontroller', 'description' => 'Kurikulum sistem tertanam, pemrograman mikroprosesor, sensor, dan teknologi IoT.'],
            ['name' => 'Network Infrastructure & Cybersecurity', 'description' => 'Kurikulum infrastruktur jaringan komputer, routing, switching, dan keamanan cyber.'],
            ['name' => 'Artificial Intelligence & Data Science', 'description' => 'Kurikulum kecerdasan buatan, pemrosesan data, machine learning, dan neural networks.'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['name' => $cat['name']],
                [
                    'slug' => Str::slug($cat['name']),
                    'description' => $cat['description'],
                ]
            );
        }
    }
}
