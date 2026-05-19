<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            // Bidang utama
            'Jaringan / Network',
            'Embedded System',
            'Software',
            'Multimedia',
            'ML / AI',
            'Blockchain',

            // Tag pendukung
            'Web Development',
            'Backend',
            'Frontend',
            'Database',
            'Data Science',
            'Artificial Intelligence',
            'Internet of Things',
            'Cyber Security',
            'UI/UX',
            'Project Based',
            'Beginner Friendly',
            'Intermediate',
            'Advanced',
            'Career Ready',
            'Hands-on Practice',
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(['name' => $tag]);
        }
    }
}