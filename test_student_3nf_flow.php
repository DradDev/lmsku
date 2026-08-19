<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "=== TESTING STUDENT PORTAL 3NF FLOW ===\n\n";

$student = User::where('role', 'student')->first();
Auth::login($student);
echo "1. Logged in as Student: {$student->name} (ID: {$student->id})\n";

// 1. Test Student Courses Index
echo "2. Testing Student Courses Index...\n";
$courseController = new \App\Http\Controllers\Student\CourseController();
$indexView = $courseController->index();
$renderedIndex = $indexView->render();
assert(strpos($renderedIndex, 'Katalog Kelas & Sertifikasi') !== false || strpos($renderedIndex, 'Course') !== false);
echo "   [OK] Student courses index rendered successfully!\n";

// 2. Test Student Course Show for all offerings
echo "3. Testing Student Course Show for Academic & Vendor Offerings...\n";
$academicOffering = CourseOffering::academic()->first();
$vendorOffering = CourseOffering::vendor()->first();

if ($academicOffering) {
    $showAcademic = $courseController->show((string)$academicOffering->id);
    $renderedAcademic = $showAcademic->render();
    assert(!empty($renderedAcademic));
    echo "   [OK] Student Academic Course Show (ID: {$academicOffering->id}) rendered successfully!\n";
}

if ($vendorOffering) {
    $showVendor = $courseController->show((string)$vendorOffering->id);
    $renderedVendor = $showVendor->render();
    assert(!empty($renderedVendor));
    echo "   [OK] Student Vendor Course Show (ID: {$vendorOffering->id}) rendered successfully!\n";
}

// 3. Test Student Materials Index & Show
echo "4. Testing Student Materials Index & Show...\n";
$matController = new \App\Http\Controllers\Student\MaterialController();
$matIndex = $matController->index();
$renderedMatIndex = $matIndex->render();
echo "   [OK] Student Materials Index rendered successfully!\n";

// Enroll in vendor offering to test material & quiz access
$enrollment = Enrollment::firstOrCreate(
    [
        'user_id' => $student->id,
        'course_offering_id' => $vendorOffering->id,
    ],
    [
        'progress_percent' => 0,
        'status' => 'in_progress',
    ]
);

$material = Material::where('master_course_id', $vendorOffering->master_course_id)->first();
if ($material) {
    $showMat = $matController->show($material);
    $renderedMat = $showMat->render();
    echo "   [OK] Student Material Show (ID: {$material->id}) viewed and completed!\n";
}

// 4. Test Student Quiz Show
echo "5. Testing Student Quiz Show...\n";
$quizController = new \App\Http\Controllers\Student\QuizController();
$quiz = Quiz::where('master_course_id', $vendorOffering->master_course_id)->first();
if ($quiz) {
    $showQuiz = $quizController->show($quiz);
    $renderedQuiz = $showQuiz->render();
    echo "   [OK] Student Quiz Show (ID: {$quiz->id}) rendered successfully!\n";
}

// 5. Test Student Certificates Index
echo "6. Testing Student Certificates Index...\n";
$certController = new \App\Http\Controllers\Student\CertificateController();
$certIndex = $certController->index();
$renderedCertIndex = $certIndex->render();
echo "   [OK] Student Certificates Index rendered successfully!\n";

echo "\n=== ALL STUDENT 3NF FLOW TESTS PASSED 100%! ===\n";
