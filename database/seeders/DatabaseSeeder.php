<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DemoLmsSeeder::class,
        ]);
        
        $this->call([
    SkillSeeder::class,
    TagSeeder::class,
]);
    }
    
}