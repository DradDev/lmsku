<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\MasterCourse;
use App\Models\Material;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;

class MaterialQuizSeeder extends Seeder
{
    public function run(): void
    {
        $lecturer = User::where('role', 'lecturer')->first();
        
        $mcSoftware = MasterCourse::where('code', 'TK-SOF-ADV-001')->first();
        $mcEmbedded = MasterCourse::where('code', 'TK-EMB-INT-001')->first();
        $mcNetwork = MasterCourse::where('code', 'TK-NET-BEG-001')->first();

        // 1. Modul Materi & Kuis untuk Course Web Enterprise
        if ($mcSoftware) {
            $courseSoftware = Course::where('master_course_id', $mcSoftware->id)->first();

            Material::updateOrCreate(
                ['title' => 'Modul 01 - Microservices Architecture & RESTful API Service'],
                [
                    'master_course_id' => $mcSoftware->id,
                    'course_id' => $courseSoftware ? $courseSoftware->id : null,
                    'file_path' => 'materials/modul_01_microservices_api.pdf',
                ]
            );

            Material::updateOrCreate(
                ['title' => 'Modul 02 - Otentikasi JWT & Otorisasi Middleware'],
                [
                    'master_course_id' => $mcSoftware->id,
                    'course_id' => $courseSoftware ? $courseSoftware->id : null,
                    'file_path' => 'materials/modul_02_jwt_middleware.pdf',
                ]
            );

            $quizSoftwareModul1 = Quiz::updateOrCreate(
                ['title' => 'Kuis Evaluasi Modul 01: Microservices REST API'],
                [
                    'master_course_id' => $mcSoftware->id,
                    'course_id' => $courseSoftware ? $courseSoftware->id : null,
                    'time_limit' => 15,
                    'quiz_type' => 'daily',
                    'max_attempts' => 3,
                ]
            );

            Question::updateOrCreate(
                ['quiz_id' => $quizSoftwareModul1->id, 'question' => 'Method mana pada Eloquent ORM yang digunakan untuk menghindari masalah N+1 Query Problem?'],
                [
                    'user_id' => $lecturer ? $lecturer->id : null,
                    'question_type' => 'multiple_choice',
                    'option_a' => 'whereHas()',
                    'option_b' => 'with() / Eager Loading',
                    'option_c' => 'join()',
                    'option_d' => 'lazy()',
                    'correct_answer' => 'b',
                    'status' => 'approved',
                    'difficulty' => 'medium',
                ]
            );

            $finalQuizSoftware = Quiz::updateOrCreate(
                ['title' => 'Final Quiz UAS: Sertifikasi Software Engineering & Microservices'],
                [
                    'master_course_id' => $mcSoftware->id,
                    'course_id' => $courseSoftware ? $courseSoftware->id : null,
                    'time_limit' => 45,
                    'quiz_type' => 'final',
                    'max_attempts' => 2,
                ]
            );

            Question::updateOrCreate(
                ['quiz_id' => $finalQuizSoftware->id, 'question' => 'Manakah HTTP Method yang idempotently digunakan untuk memperbarui atau mengganti resource secara utuh?'],
                [
                    'user_id' => $lecturer ? $lecturer->id : null,
                    'question_type' => 'multiple_choice',
                    'option_a' => 'POST',
                    'option_b' => 'PUT',
                    'option_c' => 'PATCH',
                    'option_d' => 'GET',
                    'correct_answer' => 'b',
                    'status' => 'approved',
                    'difficulty' => 'easy',
                ]
            );
        }

        // 2. Modul Materi & Kuis untuk Praktikum Sistem Tertanam
        if ($mcEmbedded) {
            $courseEmbedded = Course::where('master_course_id', $mcEmbedded->id)->first();

            Material::updateOrCreate(
                ['title' => 'Modul 01 - Pengenalan Arsitektur ESP32 & Periferal GPIO'],
                [
                    'master_course_id' => $mcEmbedded->id,
                    'course_id' => $courseEmbedded ? $courseEmbedded->id : null,
                    'file_path' => 'materials/modul_01_esp32_gpio.pdf',
                ]
            );

            $finalQuizEmbedded = Quiz::updateOrCreate(
                ['title' => 'Final Quiz UAS: Ujian Komprehensif Sertifikasi Sistem Tertanam'],
                [
                    'master_course_id' => $mcEmbedded->id,
                    'course_id' => $courseEmbedded ? $courseEmbedded->id : null,
                    'time_limit' => 30,
                    'quiz_type' => 'final',
                    'max_attempts' => 2,
                ]
            );

            Question::updateOrCreate(
                ['quiz_id' => $finalQuizEmbedded->id, 'question' => 'Apakah fungsi utama dari modul ADC (Analog to Digital Converter) pada mikroprosesor?'],
                [
                    'user_id' => $lecturer ? $lecturer->id : null,
                    'question_type' => 'multiple_choice',
                    'option_a' => 'Mengubah sinyal analog dari sensor menjadi nilai digital yang dibaca mikrokontroler',
                    'option_b' => 'Mengatur frekuensi jam prosesor',
                    'option_c' => 'Menyimpan data persisten pada memori flash',
                    'option_d' => 'Mengirim sinyal radio nirkabel WiFi',
                    'correct_answer' => 'a',
                    'status' => 'approved',
                    'difficulty' => 'medium',
                ]
            );
        }

        // 3. Modul Materi & Kuis untuk Keamanan Jaringan
        if ($mcNetwork) {
            $courseNetwork = Course::where('master_course_id', $mcNetwork->id)->first();

            Material::updateOrCreate(
                ['title' => 'Modul 01 - Konfigurasi Firewall & Keamanan Jaringan TCP/IP'],
                [
                    'master_course_id' => $mcNetwork->id,
                    'course_id' => $courseNetwork ? $courseNetwork->id : null,
                    'file_path' => 'materials/modul_01_network_firewall.pdf',
                ]
            );

            $finalQuizNetwork = Quiz::updateOrCreate(
                ['title' => 'Final Quiz UAS: Sertifikasi Administrator Jaringan & Keamanan Cloud'],
                [
                    'master_course_id' => $mcNetwork->id,
                    'course_id' => $courseNetwork ? $courseNetwork->id : null,
                    'time_limit' => 35,
                    'quiz_type' => 'final',
                    'max_attempts' => 2,
                ]
            );

            Question::updateOrCreate(
                ['quiz_id' => $finalQuizNetwork->id, 'question' => 'Port default berapa yang digunakan oleh protokol HTTPS terenkripsi SSL/TLS?'],
                [
                    'user_id' => $lecturer ? $lecturer->id : null,
                    'question_type' => 'multiple_choice',
                    'option_a' => '80',
                    'option_b' => '22',
                    'option_c' => '443',
                    'option_d' => '8080',
                    'correct_answer' => 'c',
                    'status' => 'approved',
                    'difficulty' => 'easy',
                ]
            );
        }
    }
}
