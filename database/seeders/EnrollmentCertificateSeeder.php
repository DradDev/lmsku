<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\MasterCourse;
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
    }
}
