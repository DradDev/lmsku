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
        $mcEmbedded = MasterCourse::where('code', 'TK-EMB-INT-001')->first();
        $mcSoftware = MasterCourse::where('code', 'TK-SOF-ADV-001')->first();

        $courseEmbedded = Course::where('master_course_id', $mcEmbedded ? $mcEmbedded->id : 0)->first();
        $courseSoftware = Course::where('master_course_id', $mcSoftware ? $mcSoftware->id : 0)->first();

        // 1. Modul Materi untuk Praktikum Sistem Tertanam
        if ($mcEmbedded) {
            Material::updateOrCreate(
                ['title' => 'Modul 01 - Pengenalan Arsitektur ESP32 & Pin GPIO'],
                [
                    'master_course_id' => $mcEmbedded->id,
                    'course_id' => $courseEmbedded ? $courseEmbedded->id : null,
                    'file_path' => 'materials/modul_01_esp32_gpio.pdf',
                ]
            );

            Material::updateOrCreate(
                ['title' => 'Modul 02 - Komunikasi Protokol I2C & Sensor Pembacaan Data'],
                [
                    'master_course_id' => $mcEmbedded->id,
                    'course_id' => $courseEmbedded ? $courseEmbedded->id : null,
                    'file_path' => 'materials/modul_02_i2c_sensors.pdf',
                ]
            );

            // Kuis Modul 1
            $quizModul1 = Quiz::updateOrCreate(
                ['title' => 'Kuis Evaluasi Modul 01: Arsitektur Mikroprosesor'],
                [
                    'master_course_id' => $mcEmbedded->id,
                    'course_id' => $courseEmbedded ? $courseEmbedded->id : null,
                    'time_limit' => 15,
                    'quiz_type' => 'daily',
                    'max_attempts' => 3,
                ]
            );

            Question::updateOrCreate(
                ['quiz_id' => $quizModul1->id, 'question' => 'Berapa jumlah pin GPIO default pada SoC ESP32-WROOM-32?'],
                [
                    'user_id' => $lecturer ? $lecturer->id : null,
                    'question_type' => 'multiple_choice',
                    'option_a' => '16 Pin',
                    'option_b' => '34 Pin',
                    'option_c' => '48 Pin',
                    'option_d' => '64 Pin',
                    'correct_answer' => 'b',
                    'status' => 'approved',
                    'difficulty' => 'easy',
                ]
            );

            Question::updateOrCreate(
                ['quiz_id' => $quizModul1->id, 'question' => 'Protokol komunikasi mana yang menggunakan dua jalur sinyal SDA dan SCL?'],
                [
                    'user_id' => $lecturer ? $lecturer->id : null,
                    'question_type' => 'multiple_choice',
                    'option_a' => 'SPI',
                    'option_b' => 'UART',
                    'option_c' => 'I2C',
                    'option_d' => 'CAN Bus',
                    'correct_answer' => 'c',
                    'status' => 'approved',
                    'difficulty' => 'medium',
                ]
            );

            // UAS Final Quiz
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

            Question::updateOrCreate(
                ['quiz_id' => $finalQuizEmbedded->id, 'question' => 'Manakah dari berikut yang merupakan keuntungan utama arsitektur Dual-Core pada ESP32?'],
                [
                    'user_id' => $lecturer ? $lecturer->id : null,
                    'question_type' => 'multiple_choice',
                    'option_a' => 'Menurunkan konsumsi daya menjadi nol',
                    'option_b' => 'Memisahkan tugas stack komunikasi nirkabel dan logika program utama secara paralel',
                    'option_c' => 'Menghilangkan kebutuhan pin masukan analog',
                    'option_d' => 'Meningkatkan tegangan operasional dari 3.3V ke 12V',
                    'correct_answer' => 'b',
                    'status' => 'approved',
                    'difficulty' => 'hard',
                ]
            );
        }

        // 2. Modul Materi untuk Pengembangan Web Enterprise dengan Laravel
        if ($mcSoftware) {
            Material::updateOrCreate(
                ['title' => 'Modul 01 - Arsitektur MVC & RESTful API Service'],
                [
                    'master_course_id' => $mcSoftware->id,
                    'course_id' => $courseSoftware ? $courseSoftware->id : null,
                    'file_path' => 'materials/modul_01_laravel_mvc_api.pdf',
                ]
            );

            $finalQuizLaravel = Quiz::updateOrCreate(
                ['title' => 'Final Quiz UAS: Sertifikasi Laravel Enterprise Backend'],
                [
                    'master_course_id' => $mcSoftware->id,
                    'course_id' => $courseSoftware ? $courseSoftware->id : null,
                    'time_limit' => 45,
                    'quiz_type' => 'final',
                    'max_attempts' => 2,
                ]
            );

            Question::updateOrCreate(
                ['quiz_id' => $finalQuizLaravel->id, 'question' => 'Method mana pada Eloquent ORM yang digunakan untuk menghindari masalah N+1 Query Problem?'],
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
        }
    }
}
