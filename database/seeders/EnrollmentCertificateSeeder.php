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

        $mcEmbedded = MasterCourse::where('code', 'TK-EMS-INT-001')->first();
        $offeringEmbedded = CourseOffering::where('master_course_id', $mcEmbedded ? $mcEmbedded->id : 0)->first();
        $courseEmbedded = Course::where('master_course_id', $mcEmbedded ? $mcEmbedded->id : 0)->first();

        if ($student && $offeringEmbedded) {
            // 1. Enrollment Mhs ke Praktikum Sistem Tertanam
            $enrollment = Enrollment::updateOrCreate(
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

            // 2. Certificate Digital Verifikasi Blockchain
            $hash = '0x' . hash('sha256', 'LMSKU-CERT-' . $student->id . '-' . $offeringEmbedded->id . '-' . time());

            Certificate::updateOrCreate(
                [
                    'user_id' => $student->id,
                    'course_offering_id' => $offeringEmbedded->id,
                ],
                [
                    'course_id' => $courseEmbedded ? $courseEmbedded->id : null,
                    'score' => 92,
                    'blockchain_hash' => $hash,
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
}
