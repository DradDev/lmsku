<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin Akademik / Kaprodi
        User::updateOrCreate(
            ['email' => 'admin@lmsku.test'],
            [
                'name' => 'Admin Kaprodi Tekkom',
                'role' => 'admin',
                'registration_status' => 'approved',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'peminatan' => 'Sistem Komputer & IoT',
            ]
        );

        // 2. Dosen Pengampu (Lecturers)
        User::updateOrCreate(
            ['email' => 'lecturer@lmsku.test'],
            [
                'name' => 'Dr. Ir. Budi Santoso, M.T.',
                'role' => 'lecturer',
                'registration_status' => 'approved',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'peminatan' => 'Embedded Systems & Software Architecture',
            ]
        );

        User::updateOrCreate(
            ['email' => 'lecturer2@lmsku.test'],
            [
                'name' => 'Siti Aminah, S.Kom., M.T.',
                'role' => 'lecturer',
                'registration_status' => 'approved',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'peminatan' => 'Jaringan & Keamanan Cyber',
            ]
        );

        // 3. Mahasiswa (Students)
        User::updateOrCreate(
            ['email' => 'student@lmsku.test'],
            [
                'name' => 'Johan Pratama',
                'role' => 'student',
                'registration_status' => 'approved',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'peminatan' => 'Embedded Systems & Software Engineering',
            ]
        );

        User::updateOrCreate(
            ['email' => 'student2@lmsku.test'],
            [
                'name' => 'Rina Amalia',
                'role' => 'student',
                'registration_status' => 'approved',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'peminatan' => 'Web & Mobile Engineering',
            ]
        );

        // 4. Mitra Industri (Vendor)
        User::updateOrCreate(
            ['email' => 'vendor@lmsku.test'],
            [
                'name' => 'PT Telkom Digital Indonesia',
                'role' => 'vendor',
                'registration_status' => 'approved',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'peminatan' => 'IoT & Telecom Solution',
            ]
        );
    }
}
