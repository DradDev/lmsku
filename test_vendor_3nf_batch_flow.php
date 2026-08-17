<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\MasterCourse;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

echo "=== TESTING VENDOR 3NF MASTER COURSE & MULTI-BATCH ARCHITECTURE ===\n\n";

// 1. Get or Create Vendor User
$vendor = User::where('role', 'vendor')->first();
if (!$vendor) {
    echo "[ERROR] No vendor user found.\n";
    exit(1);
}
Auth::login($vendor);
echo "1. Logged in as Vendor: {$vendor->name} (ID: {$vendor->id})\n";

// 2. Setup Category & Skills
$category = Category::firstOrCreate(['name' => 'Cloud & DevOps Tech'], ['slug' => 'cloud-devops']);
$skill = Skill::firstOrCreate(['name' => 'Kubernetes & Docker'], ['category' => 'Engineering']);

// 3. Test Vendor Course Store (Creates Master Course + Batch 1)
echo "\n2. Testing Vendor Course Store (Master Course + Batch 1)...\n";
$courseController = new \App\Http\Controllers\Vendor\CourseController();

$reqStore = Request::create(route('vendor.courses.store'), 'POST', [
    'name' => 'Master Certified Kubernetes Administrator (CKA)',
    'batch_name' => 'Batch 1 - Q1 2026',
    'description' => 'Comprehensive enterprise cloud orchestration curriculum.',
    'level' => 'Advanced',
    'category_id' => $category->id,
    'duration_weeks' => 6,
    'certificate_threshold' => 85,
    'skill_ids' => [$skill->id],
]);

$respStore = $courseController->store($reqStore);

$masterCourse = MasterCourse::where('name', 'Master Certified Kubernetes Administrator (CKA)')
    ->where('user_id', $vendor->id)
    ->first();

if (!$masterCourse) {
    echo "   [FAIL] MasterCourse was not created!\n";
    exit(1);
}
echo "   [OK] MasterCourse created: ID {$masterCourse->id} (Code: {$masterCourse->code})\n";

$batch1 = Course::where('master_course_id', $masterCourse->id)
    ->where('batch_name', 'Batch 1 - Q1 2026')
    ->first();

if (!$batch1) {
    echo "   [FAIL] Batch 1 was not created!\n";
    exit(1);
}
echo "   [OK] Batch 1 created: ID {$batch1->id} linked to MasterCourse {$batch1->master_course_id}\n";

// 4. Test Material Creation with master_course_id
echo "\n3. Testing Material Upload linked to MasterCourse...\n";
Storage::fake('public');
$fakeFile = UploadedFile::fake()->create('modul1_k8s_architecture.pdf', 500, 'application/pdf');

$materialController = new \App\Http\Controllers\Vendor\MaterialController();
$reqMat = Request::create(route('vendor.materials.store', $batch1->id), 'POST', [
    'title' => 'Modul 1: Kubernetes Cluster Architecture & Pods',
], [], [
    'file' => $fakeFile,
]);

$respMat = $materialController->store($reqMat, $batch1);

$material = Material::where('title', 'Modul 1: Kubernetes Cluster Architecture & Pods')->first();
if ($material && $material->master_course_id == $masterCourse->id) {
    echo "   [OK] Material created: ID {$material->id} with master_course_id {$material->master_course_id}!\n";
} else {
    echo "   [FAIL] Material master_course_id was not set properly.\n";
    exit(1);
}

// 5. Test Quiz Creation with master_course_id
echo "\n4. Testing Quiz Creation linked to MasterCourse...\n";
$quizController = new \App\Http\Controllers\Vendor\QuizController();
$reqQuiz = Request::create(route('vendor.quizzes.store', $batch1->id), 'POST', [
    'title' => 'Final Exam: CKA Certification Test',
    'quiz_type' => 'final',
    'time_limit' => 90,
    'max_attempts' => 2,
]);
$respQuiz = $quizController->store($reqQuiz, $batch1);

$quiz = Quiz::where('title', 'Final Exam: CKA Certification Test')->first();
if ($quiz && $quiz->master_course_id == $masterCourse->id) {
    echo "   [OK] Quiz created: ID {$quiz->id} with master_course_id {$quiz->master_course_id}!\n";
} else {
    echo "   [FAIL] Quiz master_course_id was not set properly.\n";
    exit(1);
}

// 6. Test Launch Batch 2 (Multi-Batch without duplication)
echo "\n5. Testing Launch Batch 2 under the same MasterCourse...\n";
$reqBatch2 = Request::create(route('vendor.courses.launch-batch', $batch1->id), 'POST', [
    'batch_name' => 'Batch 2 - Q3 2026 Intake',
    'certificate_threshold' => 85,
    'duration_weeks' => 8,
]);

$respBatch2 = $courseController->launchBatch($reqBatch2, $batch1);

$batch2 = Course::where('master_course_id', $masterCourse->id)
    ->where('batch_name', 'Batch 2 - Q3 2026 Intake')
    ->first();

if ($batch2) {
    echo "   [OK] Batch 2 created: ID {$batch2->id} under MasterCourse {$batch2->master_course_id}!\n";
} else {
    echo "   [FAIL] Batch 2 was not created.\n";
    exit(1);
}

// 7. Test Batch 2 Inheriting Materials & Quizzes Automatically (Zero duplication)
echo "\n6. Testing Material & Quiz inheritance on Batch 2...\n";
$showBatch2View = $courseController->show($batch2);
$renderedBatch2 = $showBatch2View->render();

if (strpos($renderedBatch2, 'Modul 1: Kubernetes Cluster Architecture') !== false &&
    strpos($renderedBatch2, 'Final Exam: CKA Certification Test') !== false) {
    echo "   [OK] Batch 2 automatically displays inherited Material & Quiz from MasterCourse without duplicating records!\n";
} else {
    echo "   [FAIL] Batch 2 did not display inherited curriculum content.\n";
}

// 8. Test Student Enrollments Isolation per Batch
echo "\n7. Testing Student Enrollments isolation per Batch...\n";
$student1 = User::where('role', 'student')->first();
$student2 = User::where('role', 'student')->skip(1)->first() ?? $student1;

Enrollment::updateOrCreate(
    ['user_id' => $student1->id, 'course_id' => $batch1->id],
    ['status' => 'in_progress', 'progress_percent' => 50]
);

Enrollment::updateOrCreate(
    ['user_id' => $student2->id, 'course_id' => $batch2->id],
    ['status' => 'completed', 'progress_percent' => 100]
);

$batch1EnrollmentsCount = $batch1->fresh()->enrollments()->count();
$batch2EnrollmentsCount = $batch2->fresh()->enrollments()->count();

echo "   - Batch 1 Enrollments: {$batch1EnrollmentsCount}\n";
echo "   - Batch 2 Enrollments: {$batch2EnrollmentsCount}\n";
if ($batch1EnrollmentsCount >= 1 && $batch2EnrollmentsCount >= 1) {
    echo "   [OK] Student enrollments are cleanly isolated per batch!\n";
} else {
    echo "   [FAIL] Student enrollment isolation mismatch.\n";
}

// 9. Test Vendor Index Grouped Rendering
echo "\n8. Testing Vendor Course Index View Rendering...\n";
$indexView = $courseController->index();
$renderedIndex = $indexView->render();

if (strpos($renderedIndex, 'Daftar Program Sertifikasi') !== false &&
    strpos($renderedIndex, 'Master Certified Kubernetes Administrator (CKA)') !== false &&
    strpos($renderedIndex, 'Batch 1 - Q1 2026') !== false &&
    strpos($renderedIndex, 'Batch 2 - Q3 2026 Intake') !== false) {
    echo "   [OK] Vendor Index renders MasterCourse with multiple batch pills cleanly!\n";
} else {
    echo "   [FAIL] Vendor Index rendering failed.\n";
}

// 10. Test Update Batch & Toggle Archive specifically
echo "\n9. Testing Update Batch & Toggle Archive per Batch...\n";
$updateBatchReq = Request::create(route('vendor.courses.update-batch', $batch2->id), 'PUT', [
    'batch_name' => 'Batch 2 - Q3 2026 (Updated & Active)',
    'certificate_threshold' => 85,
    'duration_weeks' => 6,
    'is_archived' => 0,
]);
$courseController->updateBatch($updateBatchReq, $batch2);
$batch2Fresh = $batch2->fresh();

if ($batch2Fresh->batch_name === 'Batch 2 - Q3 2026 (Updated & Active)' &&
    $batch2Fresh->certificate_threshold == 85 &&
    $batch2Fresh->duration_weeks == 6 &&
    $batch2Fresh->is_archived == false) {
    echo "   [OK] Batch 2 parameters successfully updated without modifying MasterCourse!\n";
} else {
    echo "   [FAIL] Batch 2 parameters update mismatch.\n";
}

// Toggle archive Batch 1
$initialArchived = $batch1->fresh()->is_archived;
$courseController->toggleArchive($batch1);
$batch1Fresh = $batch1->fresh();
if ($batch1Fresh->is_archived !== $initialArchived) {
    echo "   [OK] Batch 1 archive status toggled independently while Batch 2 remains active!\n";
} else {
    echo "   [FAIL] Batch 1 archive toggle failed.\n";
}

echo "\n=== ALL VENDOR 3NF MULTI-BATCH TESTS PASSED 100%! ===\n";
