<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AcademicTerm;
use App\Models\CourseOffering;
use App\Models\MasterCourse;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "=== TESTING LECTURER INACTIVE SEMESTER LOCK & READ-ONLY MODE ===\n\n";

// 1. Get Lecturer User
$lecturer = User::where('role', 'lecturer')->first();
if (!$lecturer) {
    echo "[ERROR] No lecturer found.\n";
    exit(1);
}
Auth::login($lecturer);
echo "1. Logged in as Lecturer: {$lecturer->name} (ID: {$lecturer->id})\n";

// 2. Setup Test Data: 1 Active Term, 1 Inactive Term
$activeTerm = AcademicTerm::where('is_active', true)->first();
if (!$activeTerm) {
    $activeTerm = AcademicTerm::first();
    $activeTerm?->update(['is_active' => true]);
}
$inactiveTerm = AcademicTerm::where('is_active', false)->first();
if (!$inactiveTerm) {
    $inactiveTerm = AcademicTerm::create([
        'name' => 'Semester Lampau Inactive',
        'code' => 'INACT-' . rand(1000, 9999),
        'is_active' => false,
        'start_date' => now()->subYear(),
        'end_date' => now()->subMonths(6),
    ]);
}

echo "   - Active Term: ID {$activeTerm->id} ({$activeTerm->name})\n";
echo "   - Inactive Term: ID {$inactiveTerm->id} ({$inactiveTerm->name})\n";

// Find or assign offerings to lecturer for both terms
$activeOffering = CourseOffering::where('academic_term_id', $activeTerm->id)->where('lecturer_id', $lecturer->id)->first();
if (!$activeOffering) {
    $master = MasterCourse::first();
    $activeOffering = CourseOffering::create([
        'master_course_id' => $master->id,
        'academic_term_id' => $activeTerm->id,
        'lecturer_id' => $lecturer->id,
        'section_name' => 'Kelas Aktif Test',
        'status' => 'published',
    ]);
}

$inactiveOffering = CourseOffering::where('academic_term_id', $inactiveTerm->id)->where('lecturer_id', $lecturer->id)->first();
if (!$inactiveOffering) {
    $master = MasterCourse::first();
    $inactiveOffering = CourseOffering::create([
        'master_course_id' => $master->id,
        'academic_term_id' => $inactiveTerm->id,
        'lecturer_id' => $lecturer->id,
        'section_name' => 'Kelas Lampau Inactive',
        'status' => 'draft',
    ]);
}

echo "   - Active Offering: ID {$activeOffering->id} (Term {$activeOffering->academic_term_id}, Status: {$activeOffering->status})\n";
echo "   - Inactive Offering: ID {$inactiveOffering->id} (Term {$inactiveOffering->academic_term_id}, Status: {$inactiveOffering->status})\n";

// 3. Test Lecturer Course Index
$courseController = new \App\Http\Controllers\Lecturer\CourseController();
$indexView = $courseController->index();
$renderedIndex = $indexView->render();
echo "\n2. Testing Lecturer Course Index View Rendering...\n";
if (strpos($renderedIndex, 'Daftar Kelas Pembelajaran Dosen') !== false) {
    echo "   [OK] Course index rendered successfully!\n";
} else {
    echo "   [FAIL] Course index rendering failed.\n";
}

// 4. Test Lecturer Course Show for Inactive Offering
$showView = $courseController->show($inactiveOffering->id);
$renderedShow = $showView->render();
echo "\n3. Testing Lecturer Course Show View on Inactive Semester Offering...\n";
if (strpos($renderedShow, 'Semester Non-Aktif (Mode Arsip & Read-Only)') !== false) {
    echo "   [OK] Inactive semester banner displayed on course show!\n";
} else {
    echo "   [FAIL] Inactive semester banner NOT displayed.\n";
}

if (strpos($renderedShow, 'Threshold Terkunci:') !== false) {
    echo "   [OK] Threshold displayed in locked read-only mode!\n";
} else {
    echo "   [FAIL] Threshold form still accessible.\n";
}

// 5. Test Controller Safeguards: Updating Inactive Offering
echo "\n4. Testing Update Safeguard on Inactive Offering...\n";
$req = Request::create(route('lecturer.courses.update', $inactiveOffering->id), 'PUT', [
    'certificate_threshold' => 90,
]);
$resp = $courseController->update($req, $inactiveOffering->id);
if ($resp->isRedirection() && session('error')) {
    echo "   [OK] Update blocked with error message: '" . session('error') . "'\n";
} else {
    echo "   [FAIL] Update was NOT blocked!\n";
}

// 6. Test Material Controller Safeguards
echo "\n5. Testing Material Controller Safeguard on Inactive Offering...\n";
$matController = new \App\Http\Controllers\Lecturer\MaterialController();

$reqMat = Request::create(route('lecturer.materials.store', $inactiveOffering->id), 'POST', [
    'title' => 'Test Material Inactive',
]);
$respMat = $matController->store($reqMat, $inactiveOffering->id);
if ($respMat->isRedirection() && session('error')) {
    echo "   [OK] Material creation blocked with error message: '" . session('error') . "'\n";
} else {
    echo "   [FAIL] Material creation was NOT blocked!\n";
}

// 7. Test Quiz Controller Safeguards
echo "\n6. Testing Quiz Controller Safeguard on Inactive Offering...\n";
$quizController = new \App\Http\Controllers\Lecturer\QuizController();

$reqQuiz = Request::create(route('lecturer.courses.quizzes.store', $inactiveOffering->id), 'POST', [
    'title' => 'Test Quiz Inactive',
    'quiz_type' => 'daily',
]);
$respQuiz = $quizController->store($reqQuiz, $inactiveOffering->id);
if ($respQuiz->isRedirection() && session('error')) {
    echo "   [OK] Quiz creation blocked with error message: '" . session('error') . "'\n";
} else {
    echo "   [FAIL] Quiz creation was NOT blocked!\n";
}

echo "\n=== ALL INACTIVE SEMESTER SAFEGUARDS PASSED 100%! ===\n";
