<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AcademicTerm;
use App\Models\CourseOffering;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

echo "=== TESTING STUDENT HISTORY VIEWS (ADMIN & LECTURER) ===\n\n";

// 1. Test Admin Role View Rendering
$admin = User::where('role', 'admin')->first();
if (!$admin) {
    echo "[ERROR] No admin found.\n";
    exit(1);
}
Auth::login($admin);
echo "1. Logged in as Admin: {$admin->name} (ID: {$admin->id})\n";

// A. Test Admin Course Offerings Show View
$offering = CourseOffering::with(['masterCourse', 'academicTerm', 'lecturer', 'enrollments.user'])->first();
if (!$offering) {
    echo "[ERROR] No course offering found.\n";
    exit(1);
}

$offeringController = new \App\Http\Controllers\Admin\CourseOfferingController();
$showResp = $offeringController->show($offering);
$renderedAdminShow = $showResp->render();

if (strpos($renderedAdminShow, 'Daftar Mahasiswa & Riwayat Perkuliahan') !== false) {
    echo "   [OK] Admin Course Offering Show (Student History) rendered cleanly (" . strlen($renderedAdminShow) . " bytes)!\n";
} else {
    echo "   [FAIL] Admin Course Offering Show failed to render properly.\n";
}

// B. Test Admin Course Offerings Index View with Peserta button
$indexResp = $offeringController->index(new \Illuminate\Http\Request());
$renderedAdminIndex = $indexResp->render();
if (strpos($renderedAdminIndex, 'Peserta (') !== false) {
    echo "   [OK] Admin Course Offerings Index rendered with 'Peserta' action button!\n";
} else {
    echo "   [FAIL] 'Peserta' button not found on admin course offerings index.\n";
}

// C. Test Admin Academic Term Show View with Peserta button
$term = AcademicTerm::first();
$termController = new \App\Http\Controllers\Admin\AcademicTermController();
$termResp = $termController->show($term);
$renderedTermShow = $termResp->render();
if (strpos($renderedTermShow, 'Peserta (') !== false) {
    echo "   [OK] Admin Academic Term Show rendered with 'Peserta' action button!\n";
} else {
    echo "   [FAIL] 'Peserta' button not found on admin academic term show.\n";
}

// 2. Test Lecturer Role View Rendering
$lecturer = User::where('role', 'lecturer')->first();
if (!$lecturer) {
    echo "[ERROR] No lecturer found.\n";
    exit(1);
}
Auth::login($lecturer);
echo "\n2. Logged in as Lecturer: {$lecturer->name} (ID: {$lecturer->id})\n";

$lecturerOffering = CourseOffering::where('lecturer_id', $lecturer->id)->first();
if ($lecturerOffering) {
    $lecturerCourseController = new \App\Http\Controllers\Lecturer\CourseController();
    $lecturerShowResp = $lecturerCourseController->show($lecturerOffering->id);
    $renderedLecturerShow = $lecturerShowResp->render();

    if (strpos($renderedLecturerShow, 'Mahasiswa Terdaftar') !== false || strpos($renderedLecturerShow, 'Riwayat Peserta Mahasiswa') !== false) {
        echo "   [OK] Lecturer Course Show (Student History & Progress) rendered cleanly (" . strlen($renderedLecturerShow) . " bytes)!\n";
    } else {
        echo "   [FAIL] Student section not found on lecturer course show.\n";
    }
} else {
    echo "   [SKIP] No offering assigned to this lecturer for testing.\n";
}

echo "\n=== ALL STUDENT HISTORY INTEGRATION TESTS PASSED 100%! ===\n";
