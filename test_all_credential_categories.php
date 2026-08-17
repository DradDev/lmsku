<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AcademicTerm;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\Institution;
use App\Models\MasterCourse;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== TESTING 4 CATEGORIES OF CREDENTIAL GENERATION ===\n\n";

DB::beginTransaction();

try {
    // 0. SETUP DUMMY DATA
    $student = User::firstOrCreate(
        ['email' => 'student.testing.cred@undip.ac.id'],
        ['name' => 'Johan Pratama', 'password' => bcrypt('password'), 'role' => 'student', 'registration_status' => 'approved']
    );

    $lecturer = User::firstOrCreate(
        ['email' => 'lecturer.testing.cred@undip.ac.id'],
        ['name' => 'Dr. Ir. Dosen Pengampu', 'password' => bcrypt('password'), 'role' => 'lecturer', 'registration_status' => 'approved']
    );

    $institutionTelkom = Institution::firstOrCreate(
        ['code' => 'TLKM'],
        ['name' => 'PT Telkom Indonesia', 'slug' => 'telkom-indonesia', 'type' => 'company']
    );

    $vendorCompanyUser = User::firstOrCreate(
        ['email' => 'vendor.telkom.cred@telkom.co.id'],
        ['name' => 'Mentor Telkom IoT', 'password' => bcrypt('password'), 'role' => 'vendor', 'institution_id' => $institutionTelkom->id, 'institution_type' => 'company', 'registration_status' => 'approved']
    );

    $vendorIndividualUser = User::firstOrCreate(
        ['email' => 'budi.santoso.indiv@gmail.com'],
        ['name' => 'Budi Santoso', 'password' => bcrypt('password'), 'role' => 'vendor', 'institution_id' => null, 'institution_type' => 'individual', 'registration_status' => 'approved']
    );

    $skillSE = Skill::firstOrCreate(['name' => 'Software Engineering']);
    $skillAI = Skill::firstOrCreate(['name' => 'Machine Learning & Artificial Intelligence']);
    $skillEMB = Skill::firstOrCreate(['name' => 'Embedded Systems & Robotics']);
    $skillNET = Skill::firstOrCreate(['name' => 'Networking & Security']);

    $termGanjil = AcademicTerm::firstOrCreate(
        ['name' => '2026/2027 Ganjil'],
        ['start_date' => '2026-08-01', 'end_date' => '2026-12-31', 'is_active' => true]
    );

    // =========================================================================
    // 1. TEST ACADEMIC COURSE CERTIFICATE (Teknik Komputer)
    // =========================================================================
    echo "1. Testing Kategori 1: Academic Course Certificate (Teknik Komputer)...\n";
    $masterCourse = MasterCourse::firstOrCreate(
        ['code' => 'TK-SE-001'],
        ['name' => 'Software Engineering Fundamentals', 'level' => 'Beginner']
    );

    $offering = CourseOffering::create([
        'master_course_id' => $masterCourse->id,
        'academic_term_id' => $termGanjil->id,
        'lecturer_id' => $lecturer->id,
    ]);

    $certAcademic = new Certificate([
        'user_id' => $student->id,
        'course_offering_id' => $offering->id,
        'completed_at' => \Carbon\Carbon::parse('2026-09-15'),
    ]);

    $codeAcademic = $certAcademic->generateCredentialCode();
    echo "   [OUTPUT]: {$codeAcademic}\n";
    $expectedAcademic = "CERT/TK-SE-001/20261/" . sprintf('%04d', $student->id);
    assert($codeAcademic === $expectedAcademic, "Academic code must match '{$expectedAcademic}', got '{$codeAcademic}'");
    echo "   [OK] Academic Course Credential Verified!\n\n";

    // =========================================================================
    // 2. TEST LECTURER PROJECT (Internal Kampus / Capstone)
    // =========================================================================
    echo "2. Testing Kategori 2: Lecturer Project Certificate (Internal Kampus)...\n";
    $prjLecturer = Project::create([
        'title' => 'Smart Factory IoT Embedded System',
        'difficulty_level' => 'Intermediate',
        'duration_days' => 30,
        'created_by' => $lecturer->id,
        'provider_type' => 'internal',
        'is_published' => true,
    ]);
    $prjLecturer->skills()->sync([$skillEMB->id => ['is_main' => true, 'weight' => 1.0], $skillAI->id => ['is_main' => true, 'weight' => 1.0]]);

    $certLecturerPrj = new Certificate([
        'user_id' => $student->id,
        'project_id' => $prjLecturer->id,
        'completed_at' => \Carbon\Carbon::parse('2026-10-20'),
    ]);

    $codeLecturerPrj = $certLecturerPrj->generateCredentialCode();
    echo "   [OUTPUT]: {$codeLecturerPrj}\n";
    // Month 10 is Ganjil -> 20261, Skills EMB & AI -> EMB-AI
    $expectedLecturerPrj = "CERT/TK-PRJ-EMB-AI-" . sprintf('%04d', $prjLecturer->id) . "/20261/" . sprintf('%04d', $student->id);
    assert($codeLecturerPrj === $expectedLecturerPrj, "Lecturer Project code must match '{$expectedLecturerPrj}', got '{$codeLecturerPrj}'");
    echo "   [OK] Lecturer Project Credential Verified!\n\n";

    // =========================================================================
    // 3. TEST VENDOR PROJECT (Mitra Perusahaan & Perorangan)
    // =========================================================================
    echo "3. Testing Kategori 3: Vendor Project Certificate (Industry External)...\n";
    // 3a. Perusahaan (PT Telkom)
    $prjTelkom = Project::create([
        'title' => 'Big Data Analytics Engine for Enterprise',
        'difficulty_level' => 'Advanced',
        'duration_days' => 45,
        'created_by' => $vendorCompanyUser->id,
        'provider_type' => 'external',
        'is_published' => true,
    ]);
    $prjTelkom->skills()->sync([$skillSE->id => ['is_main' => true, 'weight' => 1.0], $skillAI->id => ['is_main' => true, 'weight' => 1.0]]);

    $certVendorCompanyPrj = new Certificate([
        'user_id' => $student->id,
        'project_id' => $prjTelkom->id,
        'completed_at' => \Carbon\Carbon::parse('2026-08-16'),
    ]);

    $codeVendorCompanyPrj = $certVendorCompanyPrj->generateCredentialCode();
    echo "   [OUTPUT PT]: {$codeVendorCompanyPrj}\n";
    $expectedVendorCompanyPrj = "CERT/IND-PRJ-TLKM-SE-AI-" . sprintf('%04d', $prjTelkom->id) . "/202608/" . sprintf('%04d', $student->id);
    assert($codeVendorCompanyPrj === $expectedVendorCompanyPrj, "Vendor Company Project code must match '{$expectedVendorCompanyPrj}', got '{$codeVendorCompanyPrj}'");

    // 3b. Perorangan (Budi Santoso)
    $prjBudi = Project::create([
        'title' => 'Network Defense & Penetration Testing',
        'difficulty_level' => 'Intermediate',
        'duration_days' => 20,
        'created_by' => $vendorIndividualUser->id,
        'provider_type' => 'external',
        'is_published' => true,
    ]);
    $prjBudi->skills()->sync([$skillNET->id => ['is_main' => true, 'weight' => 1.0], $skillEMB->id => ['is_main' => true, 'weight' => 1.0]]);

    $certVendorIndivPrj = new Certificate([
        'user_id' => $student->id,
        'project_id' => $prjBudi->id,
        'completed_at' => \Carbon\Carbon::parse('2026-08-16'),
    ]);

    $codeVendorIndivPrj = $certVendorIndivPrj->generateCredentialCode();
    echo "   [OUTPUT PERORANGAN]: {$codeVendorIndivPrj}\n";
    $expectedBudiCode = Certificate::getVendorCode($vendorIndividualUser);
    $expectedSkillTag = Certificate::extractSkillsTag($prjBudi->skills);
    $expectedVendorIndivPrj = "CERT/IND-PRJ-{$expectedBudiCode}-{$expectedSkillTag}-" . sprintf('%04d', $prjBudi->id) . "/202608/" . sprintf('%04d', $student->id);
    assert($codeVendorIndivPrj === $expectedVendorIndivPrj, "Vendor Individual Project code must match '{$expectedVendorIndivPrj}', got '{$codeVendorIndivPrj}'");
    echo "   [OK] Vendor Project Credential Verified!\n\n";

    // =========================================================================
    // 4. TEST VENDOR COURSE (Industry Course)
    // =========================================================================
    echo "4. Testing Kategori 4: Vendor Course Certificate (Industry Course)...\n";
    $courseVendor = Course::create([
        'name' => 'Fullstack Cloud Architecture Masterclass',
        'description' => 'Industrial course on cloud design',
        'user_id' => $vendorCompanyUser->id,
        'level' => 'Advanced',
        'duration_weeks' => 8,
    ]);
    $courseVendor->skills()->sync([$skillNET->id => ['is_main' => true, 'weight' => 1.0], $skillSE->id => ['is_main' => true, 'weight' => 1.0]]);

    $certVendorCourse = new Certificate([
        'user_id' => $student->id,
        'course_id' => $courseVendor->id,
        'completed_at' => \Carbon\Carbon::parse('2026-08-16'),
    ]);

    $codeVendorCourse = $certVendorCourse->generateCredentialCode();
    echo "   [OUTPUT]: {$codeVendorCourse}\n";
    $expectedVendorCourse = "CERT/IND-CRS-TLKM-NET-SE-" . sprintf('%04d', $courseVendor->id) . "/202608/" . sprintf('%04d', $student->id);
    assert($codeVendorCourse === $expectedVendorCourse, "Vendor Course code must match '{$expectedVendorCourse}', got '{$codeVendorCourse}'");
    echo "   [OK] Vendor Course Credential Verified!\n\n";

    echo "=== ALL 4 CREDENTIAL CATEGORIES GENERATION TESTS PASSED 100% WITH ZERO ERRORS! ===\n";

} catch (\Exception $e) {
    echo "ERROR DURING TEST: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
} finally {
    DB::rollBack();
    echo "Database rollback completed (clean state preserved).\n";
}
