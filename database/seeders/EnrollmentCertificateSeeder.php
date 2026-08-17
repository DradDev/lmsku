<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\MasterCourse;
use App\Models\Project;
use App\Models\ProjectParticipation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EnrollmentCertificateSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::where('role', 'student')->first();
        $admin = User::where('role', 'admin')->first();

        $mcEmbedded = MasterCourse::where('code', 'TK-EMB-INT-001')->first();
        $mcSoftware = MasterCourse::where('code', 'TK-SOF-ADV-001')->first();

        // 1. Enrollment & Sertifikat Mahasiswa untuk Course Embedded Systems
        if ($student && $mcEmbedded) {
            $offeringEmbedded = CourseOffering::where('master_course_id', $mcEmbedded->id)->first();
            $courseEmbedded = Course::where('master_course_id', $mcEmbedded->id)->first();

            if ($offeringEmbedded) {
                Enrollment::updateOrCreate(
                    [
                        'user_id' => $student->id,
                        'course_offering_id' => $offeringEmbedded->id,
                    ],
                    [
                        'course_id' => $courseEmbedded ? $courseEmbedded->id : null,
                        'progress_percent' => 100,
                        'completed_material_count' => 2,
                        'completed_quiz_count' => 2,
                        'total_material_count' => 2,
                        'total_quiz_count' => 2,
                        'status' => 'completed',
                        'started_at' => now()->subDays(30),
                        'completed_at' => now()->subDays(2),
                        'last_activity_at' => now()->subDays(2),
                    ]
                );

                $hash1 = '0x' . hash('sha256', 'LMSKU-CERT-EMB-' . $student->id . '-' . time());
                Certificate::updateOrCreate(
                    [
                        'user_id' => $student->id,
                        'course_offering_id' => $offeringEmbedded->id,
                    ],
                    [
                        'course_id' => $courseEmbedded ? $courseEmbedded->id : null,
                        'score' => 92,
                        'blockchain_hash' => $hash1,
                        'blockchain_id' => 'BLK-' . strtoupper(Str::random(10)),
                        'tx_id' => '0x' . Str::random(64),
                        'completed_at' => now()->subDays(2),
                        'is_verified' => true,
                        'status' => 'verified',
                        'verified_at' => now()->subDays(2),
                        'verified_by' => $admin ? $admin->id : null,
                    ]
                );
            }
        }

        // 2. Enrollment Mahasiswa untuk Course Software Engineering (In Progress)
        if ($student && $mcSoftware) {
            $offeringSoftware = CourseOffering::where('master_course_id', $mcSoftware->id)->first();
            $courseSoftware = Course::where('master_course_id', $mcSoftware->id)->first();

            if ($offeringSoftware) {
                Enrollment::updateOrCreate(
                    [
                        'user_id' => $student->id,
                        'course_offering_id' => $offeringSoftware->id,
                    ],
                    [
                        'course_id' => $courseSoftware ? $courseSoftware->id : null,
                        'progress_percent' => 50,
                        'completed_material_count' => 1,
                        'completed_quiz_count' => 1,
                        'total_material_count' => 2,
                        'total_quiz_count' => 2,
                        'status' => 'in_progress',
                        'started_at' => now()->subDays(10),
                        'last_activity_at' => now()->subDays(1),
                    ]
                );
            }
        }

        // 3. SEEDING PARTISIPASI & SERTIFIKAT PROJECT DENGAN 3 KONDISI REALISTIS
        $projects = Project::orderBy('id')->get();
        $p1 = $projects->get(0); // Proyek 1: Vendor PT Telkom (IoT Gateway)
        $p2 = $projects->get(1); // Proyek 2: Kampus LMS (Microservices API)
        $p3 = $projects->get(2); // Proyek 3: Vendor Cyber Security (SOC Audit)

        if ($student) {
            // KONDISI 1: SUDAH MENDAPAT BLOCKCHAIN HASH (VERIFIED & ACCEPTED)
            if ($p1) {
                $part1 = ProjectParticipation::updateOrCreate(
                    [
                        'user_id' => $student->id,
                        'project_id' => $p1->id,
                    ],
                    [
                        'status' => 'completed',
                        'progress_percent' => 100,
                        'started_at' => now()->subDays(45),
                        'completed_at' => now()->subDays(5),
                        'last_activity_at' => now()->subDays(5),
                    ]
                );

                $cert1 = new Certificate([
                    'user_id' => $student->id,
                    'project_id' => $p1->id,
                    'completed_at' => now()->subDays(5),
                ]);
                $code1 = $cert1->generateCredentialCode();

                $rawData1 = [
                    'completed_at' => now()->subDays(5)->toISOString(),
                    'credential_code' => $code1,
                    'project_id' => (int) $p1->id,
                    'score' => 100.0,
                    'user_id' => (int) $student->id,
                ];
                ksort($rawData1);
                $hash1 = '0x' . hash('sha256', json_encode($rawData1) . '|' . $code1 . '|' . now()->subDays(5)->toISOString());

                Certificate::updateOrCreate(
                    [
                        'user_id' => $student->id,
                        'project_id' => $p1->id,
                    ],
                    [
                        'score' => 100,
                        'credential_code' => $code1,
                        'blockchain_hash' => $hash1,
                        'blockchain_id' => 'BC-PRJ-' . strtoupper(substr(hash('sha256', $code1), 0, 8)),
                        'tx_id' => '0x' . substr(hash('sha256', 'tx_prj_' . $hash1), 0, 40),
                        'completed_at' => now()->subDays(5),
                        'is_verified' => true,
                        'status' => 'verified',
                        'verified_at' => now()->subDays(4),
                        'verified_by' => $admin ? $admin->id : null,
                    ]
                );
            }

            // KONDISI 2: PENDING (DISETUJUI PEMBUAT, MENUNGGU VERIFIKASI ADMIN & BLOCKCHAIN HASH)
            if ($p2) {
                $part2 = ProjectParticipation::updateOrCreate(
                    [
                        'user_id' => $student->id,
                        'project_id' => $p2->id,
                    ],
                    [
                        'status' => 'completed',
                        'progress_percent' => 100,
                        'started_at' => now()->subDays(30),
                        'completed_at' => now()->subDays(1),
                        'last_activity_at' => now()->subDays(1),
                    ]
                );

                $cert2 = new Certificate([
                    'user_id' => $student->id,
                    'project_id' => $p2->id,
                    'completed_at' => now()->subDays(1),
                ]);
                $code2 = $cert2->generateCredentialCode();

                Certificate::updateOrCreate(
                    [
                        'user_id' => $student->id,
                        'project_id' => $p2->id,
                    ],
                    [
                        'score' => 100,
                        'credential_code' => $code2,
                        'blockchain_hash' => null,
                        'blockchain_id' => null,
                        'tx_id' => null,
                        'completed_at' => now()->subDays(1),
                        'is_verified' => false,
                        'status' => 'pending',
                        'verified_at' => null,
                        'verified_by' => null,
                    ]
                );
            }

            // KONDISI 3: IN PROGRESS / BELUM DIAJUKAN (SEDANG DIKERJAKAN MAHASISWA)
            if ($p3) {
                ProjectParticipation::updateOrCreate(
                    [
                        'user_id' => $student->id,
                        'project_id' => $p3->id,
                    ],
                    [
                        'status' => 'in_progress',
                        'progress_percent' => 50,
                        'started_at' => now()->subDays(10),
                        'completed_at' => null,
                        'last_activity_at' => now()->subDays(2),
                    ]
                );
                // Belum ada record Certificate
                Certificate::where('user_id', $student->id)->where('project_id', $p3->id)->delete();
            }
        }
    }
}
