<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class DemoLmsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        Schema::disableForeignKeyConstraints();

        DB::table('quiz_answers')->truncate();
        DB::table('quiz_attempts')->truncate();
        DB::table('submissions')->truncate();
        DB::table('assignments')->truncate();
        DB::table('materials')->truncate();
        DB::table('questions')->truncate();
        DB::table('quizzes')->truncate();
        DB::table('enrollments')->truncate();
        DB::table('courses')->truncate();
        DB::table('users')->truncate();

        Schema::enableForeignKeyConstraints();

        Storage::disk('public')->deleteDirectory('demo');

        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin LMSKU',
            'email' => 'admin@lmsku.test',
            'role' => 'admin',
            'email_verified_at' => $now,
            'password' => Hash::make('password'),
            'remember_token' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $lecturerId = DB::table('users')->insertGetId([
            'name' => 'Dr. Budi Santoso',
            'email' => 'lecturer@lmsku.test',
            'role' => 'lecturer',
            'email_verified_at' => $now,
            'password' => Hash::make('password'),
            'remember_token' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $studentId = DB::table('users')->insertGetId([
            'name' => 'Andi Pratama',
            'email' => 'student@lmsku.test',
            'role' => 'student',
            'email_verified_at' => $now,
            'password' => Hash::make('password'),
            'remember_token' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $courses = [
            [
                'name' => 'Web Programming with Laravel',
                'description' => 'Belajar membangun LMS berbasis Laravel mulai dari routing, blade, auth, course, material, assignment, quiz, dan certificate.',
                'level' => 'Intermediate',
                'progress' => 88,
                'duration_weeks' => 12,
                'materials' => [
                    ['title' => '01 - Course Overview.pdf', 'filename' => 'course-overview.pdf', 'content' => 'Silabus, capaian pembelajaran, dan roadmap course Laravel.'],
                    ['title' => '02 - Routing and MVC.pdf', 'filename' => 'routing-mvc.pdf', 'content' => 'Materi route, controller, model, dan blade.'],
                    ['title' => '03 - Authentication and Middleware.pdf', 'filename' => 'auth-middleware.pdf', 'content' => 'Materi login, register, middleware auth, dan role.'],
                ],
                'assignments' => [
                    [
                        'title' => 'Assignment 1 - CRUD Mahasiswa',
                        'description' => 'Buat fitur CRUD mahasiswa lengkap dengan validasi, search, dan pagination.',
                        'deadline_days' => 7,
                        'submission' => [
                            'filename' => 'crud-mahasiswa-andi.pdf',
                            'content' => 'Laporan implementasi CRUD mahasiswa.',
                            'grade' => 87,
                            'feedback' => 'Struktur controller sudah rapi, validasi form baik, pagination sudah berjalan.',
                        ],
                    ],
                    [
                        'title' => 'Assignment 2 - Dashboard Statistik',
                        'description' => 'Buat dashboard lecturer yang menampilkan total course, assignment, dan quiz.',
                        'deadline_days' => 12,
                        'submission' => [
                            'filename' => 'dashboard-statistik-andi.pdf',
                            'content' => 'Dokumentasi dashboard statistik lecturer.',
                            'grade' => 89,
                            'feedback' => 'Tampilan dashboard informatif, query aggregate sudah tepat.',
                        ],
                    ],
                    [
                        'title' => 'Capstone Project - Mini LMS Laravel',
                        'description' => 'Bangun mini LMS lengkap dengan multi-role, material, assignment, quiz, dan certificate.',
                        'deadline_days' => 21,
                        'submission' => [
                            'filename' => 'capstone-mini-lms-andi.pdf',
                            'content' => 'Dokumentasi capstone mini LMS berbasis Laravel.',
                            'grade' => 94,
                            'feedback' => 'Project sangat lengkap, alur lecturer-student-admin sudah konsisten.',
                        ],
                    ],
                ],
                'quiz' => [
                    'title' => 'Quiz 1 - Dasar Laravel',
                    'time_limit' => 30,
                    'questions' => [
                        [
                            'type' => 'multiple_choice',
                            'question' => 'Perintah artisan untuk membuat migration adalah...',
                            'options' => [
                                'A' => 'php artisan new:migration',
                                'B' => 'php artisan make:migration',
                                'C' => 'php artisan db:migration',
                                'D' => 'php artisan migration:create',
                            ],
                            'correct' => 'B',
                            'difficulty' => 'easy',
                        ],
                        [
                            'type' => 'essay',
                            'question' => 'Jelaskan perbedaan route, controller, dan view pada Laravel.',
                            'difficulty' => 'medium',
                        ],
                        [
                            'type' => 'multiple_choice',
                            'question' => 'Blade pada Laravel adalah...',
                            'options' => [
                                'A' => 'ORM',
                                'B' => 'Template engine',
                                'C' => 'Queue manager',
                                'D' => 'CLI scheduler',
                            ],
                            'correct' => 'B',
                            'difficulty' => 'easy',
                        ],
                    ],
                    'student_answers' => [
                        ['selected_option' => 'B'],
                        ['answer_text' => 'Route menerima request, controller mengolah logic, dan view menampilkan hasil ke pengguna.'],
                        ['selected_option' => 'B'],
                    ],
                ],
                'final_quiz' => [
                    'title' => 'Final Quiz - Laravel LMS Certification',
                    'time_limit' => 60,
                    'questions' => [
                        [
                            'question' => 'Method Eloquent untuk mengambil data berdasarkan primary key adalah...',
                            'options' => [
                                'A' => 'single()',
                                'B' => 'find()',
                                'C' => 'one()',
                                'D' => 'whereKeyOnly()',
                            ],
                            'correct' => 'B',
                            'difficulty' => 'easy',
                        ],
                        [
                            'question' => 'Middleware auth digunakan untuk...',
                            'options' => [
                                'A' => 'Menghapus cache',
                                'B' => 'Membatasi akses ke user login',
                                'C' => 'Membuat migration',
                                'D' => 'Mengubah role otomatis',
                            ],
                            'correct' => 'B',
                            'difficulty' => 'easy',
                        ],
                        [
                            'question' => 'Perintah php artisan storage:link berfungsi untuk...',
                            'options' => [
                                'A' => 'Membuat symbolic link storage ke public',
                                'B' => 'Menghubungkan database',
                                'C' => 'Menjalankan queue',
                                'D' => 'Menghapus file lama',
                            ],
                            'correct' => 'A',
                            'difficulty' => 'medium',
                        ],
                        [
                            'question' => 'Validasi email yang benar di Laravel adalah...',
                            'options' => [
                                'A' => 'required|mail',
                                'B' => 'required|email',
                                'C' => 'required|string',
                                'D' => 'required|address',
                            ],
                            'correct' => 'B',
                            'difficulty' => 'easy',
                        ],
                        [
                            'question' => 'Relasi one-to-many antara Course dan Material berarti...',
                            'options' => [
                                'A' => 'Satu material punya banyak course',
                                'B' => 'Satu course punya banyak material',
                                'C' => 'Course dan material tidak berelasi',
                                'D' => 'Satu course hanya punya satu material',
                            ],
                            'correct' => 'B',
                            'difficulty' => 'medium',
                        ],
                    ],
                    'student_answers' => ['B', 'B', 'A', 'B', 'A'],
                ],
            ],

            [
                'name' => 'Database Design and MySQL',
                'description' => 'Kursus desain database, normalisasi, relasi tabel, indexing, dan implementasi schema MySQL untuk aplikasi web.',
                'level' => 'Beginner',
                'progress' => 81,
                'duration_weeks' => 10,
                'materials' => [
                    ['title' => '01 - ERD Fundamentals.pdf', 'filename' => 'erd-fundamentals.pdf', 'content' => 'Materi dasar entitas, atribut, dan relasi.'],
                    ['title' => '02 - Normalization.pdf', 'filename' => 'normalization.pdf', 'content' => 'Normalisasi 1NF sampai 3NF untuk desain database.'],
                    ['title' => '03 - Indexing Strategy.pdf', 'filename' => 'indexing-strategy.pdf', 'content' => 'Konsep index, primary key, foreign key, dan query performance.'],
                ],
                'assignments' => [
                    [
                        'title' => 'Assignment 1 - ERD Sistem Akademik',
                        'description' => 'Rancang ERD untuk sistem akademik dengan mahasiswa, dosen, matkul, dan nilai.',
                        'deadline_days' => 6,
                        'submission' => [
                            'filename' => 'erd-akademik-andi.pdf',
                            'content' => 'ERD sistem akademik dan penjelasan relasi.',
                            'grade' => 85,
                            'feedback' => 'Entitas utama sudah benar, cardinality jelas, tinggal perkuat naming convention.',
                        ],
                    ],
                    [
                        'title' => 'Assignment 2 - Normalisasi Data Penjualan',
                        'description' => 'Lakukan normalisasi data penjualan dari bentuk unnormalized hingga 3NF.',
                        'deadline_days' => 11,
                        'submission' => [
                            'filename' => 'normalisasi-penjualan-andi.pdf',
                            'content' => 'Dokumen normalisasi data penjualan sampai 3NF.',
                            'grade' => 90,
                            'feedback' => 'Analisis dependency sangat baik dan hasil normalisasi rapi.',
                        ],
                    ],
                    [
                        'title' => 'Project - Schema LMS Database',
                        'description' => 'Bangun schema database LMS lengkap dengan users, courses, assignments, submissions, quizzes, dan enrollments.',
                        'deadline_days' => 18,
                        'submission' => [
                            'filename' => 'schema-lms-database-andi.pdf',
                            'content' => 'Dokumentasi schema database LMS dan alasan desain tabel.',
                            'grade' => 93,
                            'feedback' => 'Skema lengkap, relasi jelas, dan sangat siap diimplementasikan ke migration.',
                        ],
                    ],
                ],
                'quiz' => [
                    'title' => 'Quiz 1 - Dasar Database',
                    'time_limit' => 25,
                    'questions' => [
                        [
                            'type' => 'multiple_choice',
                            'question' => 'Primary key berfungsi untuk...',
                            'options' => [
                                'A' => 'Mengurutkan data otomatis',
                                'B' => 'Menghapus duplikasi field',
                                'C' => 'Mengidentifikasi record secara unik',
                                'D' => 'Menggandakan data',
                            ],
                            'correct' => 'C',
                            'difficulty' => 'easy',
                        ],
                        [
                            'type' => 'essay',
                            'question' => 'Jelaskan perbedaan primary key dan foreign key.',
                            'difficulty' => 'medium',
                        ],
                        [
                            'type' => 'multiple_choice',
                            'question' => 'Bentuk normal ketiga dikenal sebagai...',
                            'options' => [
                                'A' => '3NF',
                                'B' => '4NF',
                                'C' => 'BCNF',
                                'D' => '2NF',
                            ],
                            'correct' => 'A',
                            'difficulty' => 'easy',
                        ],
                    ],
                    'student_answers' => [
                        ['selected_option' => 'C'],
                        ['answer_text' => 'Primary key mengidentifikasi record secara unik, foreign key menghubungkan tabel lain.'],
                        ['selected_option' => 'A'],
                    ],
                ],
                'final_quiz' => [
                    'title' => 'Final Quiz - Database Certification',
                    'time_limit' => 50,
                    'questions' => [
                        [
                            'question' => 'Foreign key digunakan untuk...',
                            'options' => [
                                'A' => 'Membuat tabel baru',
                                'B' => 'Menghubungkan antar tabel',
                                'C' => 'Menghapus index',
                                'D' => 'Mengganti database',
                            ],
                            'correct' => 'B',
                            'difficulty' => 'easy',
                        ],
                        [
                            'question' => 'Index paling berguna untuk...',
                            'options' => [
                                'A' => 'Mempercepat query pencarian',
                                'B' => 'Menghapus record',
                                'C' => 'Mengubah tipe data',
                                'D' => 'Mengganti schema',
                            ],
                            'correct' => 'A',
                            'difficulty' => 'easy',
                        ],
                        [
                            'question' => 'Relasi many-to-many biasanya membutuhkan...',
                            'options' => [
                                'A' => 'Tabel pivot',
                                'B' => 'Trigger wajib',
                                'C' => 'Stored procedure wajib',
                                'D' => 'Satu kolom JSON',
                            ],
                            'correct' => 'A',
                            'difficulty' => 'medium',
                        ],
                        [
                            'question' => 'Contoh candidate key adalah...',
                            'options' => [
                                'A' => 'Kolom yang bisa uniquely identify record',
                                'B' => 'Kolom yang selalu null',
                                'C' => 'Kolom tanpa index',
                                'D' => 'Kolom yang tidak dipakai',
                            ],
                            'correct' => 'A',
                            'difficulty' => 'medium',
                        ],
                        [
                            'question' => 'Normalisasi bertujuan utama untuk...',
                            'options' => [
                                'A' => 'Memperbesar ukuran tabel',
                                'B' => 'Mengurangi redundansi data',
                                'C' => 'Menghapus semua relasi',
                                'D' => 'Mematikan index',
                            ],
                            'correct' => 'B',
                            'difficulty' => 'easy',
                        ],
                    ],
                    'student_answers' => ['B', 'A', 'A', 'A', 'C'],
                ],
            ],

            [
                'name' => 'REST API Development with Laravel',
                'description' => 'Kursus membangun REST API dengan Laravel, autentikasi token, resource response, validation, dan testing.',
                'level' => 'Advanced',
                'progress' => 76,
                'duration_weeks' => 8,
                'materials' => [
                    ['title' => '01 - API Architecture.pdf', 'filename' => 'api-architecture.pdf', 'content' => 'Konsep REST, resource, endpoint, dan HTTP method.'],
                    ['title' => '02 - API Validation.pdf', 'filename' => 'api-validation.pdf', 'content' => 'Validasi request, status code, dan JSON response.'],
                    ['title' => '03 - Testing APIs.pdf', 'filename' => 'testing-apis.pdf', 'content' => 'Feature testing untuk endpoint API Laravel.'],
                ],
                'assignments' => [
                    [
                        'title' => 'Assignment 1 - CRUD API Products',
                        'description' => 'Buat REST API product lengkap dengan endpoint index, show, store, update, destroy.',
                        'deadline_days' => 5,
                        'submission' => [
                            'filename' => 'crud-api-products-andi.pdf',
                            'content' => 'Dokumentasi REST API product.',
                            'grade' => 86,
                            'feedback' => 'Endpoint lengkap, response konsisten, tinggal rapikan error handling.',
                        ],
                    ],
                    [
                        'title' => 'Assignment 2 - API Authentication',
                        'description' => 'Implementasikan API authentication menggunakan token dan proteksi route.',
                        'deadline_days' => 10,
                        'submission' => [
                            'filename' => 'api-authentication-andi.pdf',
                            'content' => 'Implementasi token auth dan proteksi route API.',
                            'grade' => 91,
                            'feedback' => 'Auth flow berjalan baik, struktur middleware sudah jelas.',
                        ],
                    ],
                    [
                        'title' => 'Project - API LMS Backend',
                        'description' => 'Bangun backend API untuk LMS meliputi users, courses, quiz, assignment, dan result.',
                        'deadline_days' => 16,
                        'submission' => [
                            'filename' => 'api-lms-backend-andi.pdf',
                            'content' => 'Dokumentasi backend API LMS.',
                            'grade' => 95,
                            'feedback' => 'Desain endpoint matang, response standard, dan dokumentasi sangat rapi.',
                        ],
                    ],
                ],
                'quiz' => [
                    'title' => 'Quiz 1 - REST API Basics',
                    'time_limit' => 25,
                    'questions' => [
                        [
                            'type' => 'multiple_choice',
                            'question' => 'HTTP method untuk update resource secara penuh adalah...',
                            'options' => [
                                'A' => 'GET',
                                'B' => 'POST',
                                'C' => 'PUT',
                                'D' => 'HEAD',
                            ],
                            'correct' => 'C',
                            'difficulty' => 'easy',
                        ],
                        [
                            'type' => 'essay',
                            'question' => 'Jelaskan perbedaan endpoint POST dan PUT pada REST API.',
                            'difficulty' => 'medium',
                        ],
                        [
                            'type' => 'multiple_choice',
                            'question' => 'Status code sukses untuk create resource umumnya...',
                            'options' => [
                                'A' => '200',
                                'B' => '201',
                                'C' => '204',
                                'D' => '500',
                            ],
                            'correct' => 'B',
                            'difficulty' => 'easy',
                        ],
                    ],
                    'student_answers' => [
                        ['selected_option' => 'C'],
                        ['answer_text' => 'POST untuk membuat resource baru, PUT untuk mengganti resource yang sudah ada.'],
                        ['selected_option' => 'B'],
                    ],
                ],
                'final_quiz' => [
                    'title' => 'Final Quiz - API Development Certification',
                    'time_limit' => 55,
                    'questions' => [
                        [
                            'question' => 'Format respons paling umum untuk REST API modern adalah...',
                            'options' => [
                                'A' => 'XML only',
                                'B' => 'CSV',
                                'C' => 'JSON',
                                'D' => 'TXT',
                            ],
                            'correct' => 'C',
                            'difficulty' => 'easy',
                        ],
                        [
                            'question' => 'Status code unauthorized adalah...',
                            'options' => [
                                'A' => '200',
                                'B' => '401',
                                'C' => '302',
                                'D' => '500',
                            ],
                            'correct' => 'B',
                            'difficulty' => 'easy',
                        ],
                        [
                            'question' => 'Endpoint GET /products biasanya digunakan untuk...',
                            'options' => [
                                'A' => 'Menghapus product',
                                'B' => 'Mengambil daftar product',
                                'C' => 'Membuat product',
                                'D' => 'Login user',
                            ],
                            'correct' => 'B',
                            'difficulty' => 'easy',
                        ],
                        [
                            'question' => 'Validation request pada Laravel biasanya diletakkan di...',
                            'options' => [
                                'A' => 'View saja',
                                'B' => 'Controller atau Form Request',
                                'C' => 'Migration',
                                'D' => 'Seeder',
                            ],
                            'correct' => 'B',
                            'difficulty' => 'medium',
                        ],
                        [
                            'question' => 'Status code not found adalah...',
                            'options' => [
                                'A' => '301',
                                'B' => '403',
                                'C' => '404',
                                'D' => '422',
                            ],
                            'correct' => 'C',
                            'difficulty' => 'easy',
                        ],
                    ],
                    'student_answers' => ['C', 'B', 'B', 'B', 'A'],
                ],
            ],

            [
                'name' => 'Blade UI and Component Design',
                'description' => 'Kursus membangun antarmuka Laravel Blade yang rapi, reusable, dan mudah di-maintain dengan komponen.',
                'level' => 'Beginner',
                'progress' => 92,
                'duration_weeks' => 6,
                'materials' => [
                    ['title' => '01 - Blade Basics.pdf', 'filename' => 'blade-basics.pdf', 'content' => 'Dasar syntax blade, section, yield, include, dan component.'],
                    ['title' => '02 - Reusable Components.pdf', 'filename' => 'reusable-components.pdf', 'content' => 'Membuat komponen layout, alert, card, dan form element.'],
                    ['title' => '03 - UI Consistency.pdf', 'filename' => 'ui-consistency.pdf', 'content' => 'Panduan desain konsisten untuk dashboard LMS.'],
                ],
                'assignments' => [
                    [
                        'title' => 'Assignment 1 - Blade Layouting',
                        'description' => 'Buat layout master blade dengan sidebar, navbar, dan content section.',
                        'deadline_days' => 4,
                        'submission' => [
                            'filename' => 'blade-layouting-andi.pdf',
                            'content' => 'Implementasi layout master blade.',
                            'grade' => 88,
                            'feedback' => 'Layout bersih dan reusable, sectioning sudah tepat.',
                        ],
                    ],
                    [
                        'title' => 'Assignment 2 - Dashboard UI Revamp',
                        'description' => 'Rapikan UI dashboard student dan lecturer agar lebih konsisten.',
                        'deadline_days' => 9,
                        'submission' => [
                            'filename' => 'dashboard-ui-revamp-andi.pdf',
                            'content' => 'Dokumentasi perbaikan UI dashboard.',
                            'grade' => 90,
                            'feedback' => 'Peningkatan hierarchy dan spacing sudah sangat terasa.',
                        ],
                    ],
                    [
                        'title' => 'Project - Component Based LMS Interface',
                        'description' => 'Bangun antarmuka LMS dengan pendekatan reusable blade component.',
                        'deadline_days' => 14,
                        'submission' => [
                            'filename' => 'component-based-lms-interface-andi.pdf',
                            'content' => 'Dokumentasi UI LMS berbasis reusable blade component.',
                            'grade' => 93,
                            'feedback' => 'Komponen rapi, konsisten, dan siap untuk scale-up.',
                        ],
                    ],
                ],
                'quiz' => [
                    'title' => 'Quiz 1 - Blade Basics',
                    'time_limit' => 20,
                    'questions' => [
                        [
                            'type' => 'multiple_choice',
                            'question' => 'Directive untuk menampilkan isi section pada layout adalah...',
                            'options' => [
                                'A' => '@slot',
                                'B' => '@yield',
                                'C' => '@push',
                                'D' => '@stack',
                            ],
                            'correct' => 'B',
                            'difficulty' => 'easy',
                        ],
                        [
                            'type' => 'essay',
                            'question' => 'Apa manfaat komponen blade reusable pada aplikasi besar?',
                            'difficulty' => 'medium',
                        ],
                        [
                            'type' => 'multiple_choice',
                            'question' => 'Untuk mewarisi layout blade digunakan...',
                            'options' => [
                                'A' => '@extends',
                                'B' => '@include',
                                'C' => '@sectiononly',
                                'D' => '@layout',
                            ],
                            'correct' => 'A',
                            'difficulty' => 'easy',
                        ],
                    ],
                    'student_answers' => [
                        ['selected_option' => 'B'],
                        ['answer_text' => 'Komponen reusable membantu menjaga konsistensi UI, mengurangi duplikasi, dan memudahkan maintenance.'],
                        ['selected_option' => 'A'],
                    ],
                ],
                'final_quiz' => [
                    'title' => 'Final Quiz - Blade UI Certification',
                    'time_limit' => 40,
                    'questions' => [
                        [
                            'question' => '@include biasanya digunakan untuk...',
                            'options' => [
                                'A' => 'Menyisipkan partial view',
                                'B' => 'Menjalankan migration',
                                'C' => 'Menghapus cache',
                                'D' => 'Membuat route',
                            ],
                            'correct' => 'A',
                            'difficulty' => 'easy',
                        ],
                        [
                            'question' => 'Keuntungan utama blade component adalah...',
                            'options' => [
                                'A' => 'Membuat query lebih cepat',
                                'B' => 'UI lebih reusable dan konsisten',
                                'C' => 'Menghapus kebutuhan CSS',
                                'D' => 'Mengganti database',
                            ],
                            'correct' => 'B',
                            'difficulty' => 'easy',
                        ],
                        [
                            'question' => 'Directive untuk mendefinisikan section adalah...',
                            'options' => [
                                'A' => '@yield',
                                'B' => '@section',
                                'C' => '@foreach',
                                'D' => '@csrf',
                            ],
                            'correct' => 'B',
                            'difficulty' => 'easy',
                        ],
                        [
                            'question' => 'Partial sidebar paling cocok disimpan sebagai...',
                            'options' => [
                                'A' => 'Migration',
                                'B' => 'Seed data',
                                'C' => 'Include/component blade',
                                'D' => 'Queue job',
                            ],
                            'correct' => 'C',
                            'difficulty' => 'medium',
                        ],
                        [
                            'question' => 'Tujuan konsistensi UI pada LMS adalah...',
                            'options' => [
                                'A' => 'Membingungkan user',
                                'B' => 'Membuat pengalaman pengguna lebih jelas',
                                'C' => 'Mengurangi fitur',
                                'D' => 'Menghapus navigasi',
                            ],
                            'correct' => 'B',
                            'difficulty' => 'easy',
                        ],
                    ],
                    'student_answers' => ['A', 'B', 'B', 'C', 'B'],
                ],
            ],
        ];

        foreach ($courses as $courseData) {
            $this->seedCoursePackage($courseData, $lecturerId, $studentId, $now);
        }
    }

    private function seedCoursePackage(array $courseData, int $lecturerId, int $studentId, $now): void
    {
        $courseId = DB::table('courses')->insertGetId([
            'name' => $courseData['name'],
            'description' => $courseData['description'],
            'user_id' => $lecturerId,
            'level' => $courseData['level'],
            'progress' => $courseData['progress'],
            'duration_weeks' => $courseData['duration_weeks'],
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('enrollments')->insert([
            'user_id' => $studentId,
            'course_id' => $courseId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        foreach ($courseData['materials'] as $material) {
            $path = 'demo/materials/' . $this->slug($courseData['name']) . '/' . $material['filename'];

            Storage::disk('public')->put($path, $material['content']);

            DB::table('materials')->insert([
                'course_id' => $courseId,
                'title' => $material['title'],
                'file_path' => $path,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        foreach ($courseData['assignments'] as $assignment) {
            $assignmentId = DB::table('assignments')->insertGetId([
                'course_id' => $courseId,
                'title' => $assignment['title'],
                'description' => $assignment['description'],
                'deadline' => Carbon::now()->addDays($assignment['deadline_days'])->toDateString(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $submissionPath = 'demo/submissions/' . $this->slug($courseData['name']) . '/' . $assignment['submission']['filename'];

            Storage::disk('public')->put($submissionPath, $assignment['submission']['content']);

            DB::table('submissions')->insert([
                'assignment_id' => $assignmentId,
                'user_id' => $studentId,
                'file_path' => $submissionPath,
                'grade' => $assignment['submission']['grade'],
                'feedback' => $assignment['submission']['feedback'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->seedRegularQuiz(
            $courseId,
            $lecturerId,
            $studentId,
            $courseData['quiz'],
            $courseData['name'],
            $now
        );

        $this->seedFinalQuiz(
            $courseId,
            $lecturerId,
            $studentId,
            $courseData['final_quiz'],
            $courseData['name'],
            $now
        );
    }

    private function seedRegularQuiz(
        int $courseId,
        int $lecturerId,
        int $studentId,
        array $quizData,
        string $courseName,
        $now
    ): void {
        $quizId = DB::table('quizzes')->insertGetId([
            'course_id' => $courseId,
            'title' => $quizData['title'],
            'time_limit' => $quizData['time_limit'],
            'is_final' => false,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $questionIds = [];
        $questions = $quizData['questions'];

        foreach ($questions as $question) {
            $questionIds[] = DB::table('questions')->insertGetId([
                'quiz_id' => $quizId,
                'user_id' => $lecturerId,
                'question_type' => $question['type'],
                'question' => $question['question'],
                'option_a' => $question['options']['A'] ?? null,
                'option_b' => $question['options']['B'] ?? null,
                'option_c' => $question['options']['C'] ?? null,
                'option_d' => $question['options']['D'] ?? null,
                'correct_answer' => $question['correct'] ?? null,
                'status' => 'approved',
                'difficulty' => $question['difficulty'] ?? 'medium',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $attemptId = DB::table('quiz_attempts')->insertGetId([
            'quiz_id' => $quizId,
            'user_id' => $studentId,
            'score' => 0,
            'is_verified' => true,
            'blockchain_hash' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $answersToInsert = [];
        $gradableCount = 0;
        $correctCount = 0;

        foreach ($questions as $index => $question) {
            $studentAnswer = $quizData['student_answers'][$index];
            $selectedOption = $studentAnswer['selected_option'] ?? null;
            $answerText = $studentAnswer['answer_text'] ?? null;

            $isCorrect = null;
            $score = null;

            if ($question['type'] === 'multiple_choice') {
                $gradableCount++;
                $isCorrect = $selectedOption === $question['correct'];
                $score = $isCorrect ? 1 : 0;

                if ($isCorrect) {
                    $correctCount++;
                }
            }

            $answersToInsert[] = [
                'quiz_attempt_id' => $attemptId,
                'question_id' => $questionIds[$index],
                'user_id' => $studentId,
                'selected_option' => $selectedOption,
                'answer_text' => $answerText,
                'is_correct' => $isCorrect,
                'score' => $score,
                'feedback' => $question['type'] === 'essay'
                    ? 'Jawaban essay sudah diisi dan siap direview lecturer.'
                    : ($isCorrect ? 'Benar.' : 'Kurang tepat.'),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('quiz_answers')->insert($answersToInsert);

        $percentage = $gradableCount > 0
            ? (int) round(($correctCount / $gradableCount) * 100)
            : 0;

        DB::table('quiz_attempts')
            ->where('id', $attemptId)
            ->update([
                'score' => $percentage,
                'blockchain_hash' => hash('sha256', $courseName . '|regular|' . $studentId . '|' . $quizId . '|' . $percentage),
                'updated_at' => $now,
            ]);
    }

    private function seedFinalQuiz(
        int $courseId,
        int $lecturerId,
        int $studentId,
        array $quizData,
        string $courseName,
        $now
    ): void {
        $quizId = DB::table('quizzes')->insertGetId([
            'course_id' => $courseId,
            'title' => $quizData['title'],
            'time_limit' => $quizData['time_limit'],
            'is_final' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $questionIds = [];
        foreach ($quizData['questions'] as $question) {
            $questionIds[] = DB::table('questions')->insertGetId([
                'quiz_id' => $quizId,
                'user_id' => $lecturerId,
                'question_type' => 'multiple_choice',
                'question' => $question['question'],
                'option_a' => $question['options']['A'] ?? null,
                'option_b' => $question['options']['B'] ?? null,
                'option_c' => $question['options']['C'] ?? null,
                'option_d' => $question['options']['D'] ?? null,
                'correct_answer' => $question['correct'],
                'status' => 'approved',
                'difficulty' => $question['difficulty'] ?? 'medium',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $attemptId = DB::table('quiz_attempts')->insertGetId([
            'quiz_id' => $quizId,
            'user_id' => $studentId,
            'score' => 0,
            'is_verified' => true,
            'blockchain_hash' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $answersToInsert = [];
        $correctCount = 0;
        $total = count($quizData['questions']);

        foreach ($quizData['questions'] as $index => $question) {
            $selected = $quizData['student_answers'][$index];
            $isCorrect = $selected === $question['correct'];

            if ($isCorrect) {
                $correctCount++;
            }

            $answersToInsert[] = [
                'quiz_attempt_id' => $attemptId,
                'question_id' => $questionIds[$index],
                'user_id' => $studentId,
                'selected_option' => $selected,
                'answer_text' => null,
                'is_correct' => $isCorrect,
                'score' => $isCorrect ? 1 : 0,
                'feedback' => $isCorrect ? 'Benar.' : 'Jawaban kurang tepat.',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('quiz_answers')->insert($answersToInsert);

        $percentage = $total > 0
            ? (int) round(($correctCount / $total) * 100)
            : 0;

        DB::table('quiz_attempts')
            ->where('id', $attemptId)
            ->update([
                'score' => $percentage,
                'blockchain_hash' => hash('sha256', $courseName . '|final|' . $studentId . '|' . $quizId . '|' . $percentage),
                'updated_at' => $now,
            ]);
    }

    private function slug(string $value): string
    {
        return str($value)->lower()->replace(' ', '-')->replace('&', 'and')->value();
    }
}