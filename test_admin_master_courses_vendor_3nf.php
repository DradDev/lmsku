<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\MasterCourse;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

echo "=== TESTING ADMIN MASTER COURSES VENDOR 3NF DEDUPLICATION & BATCH DETAIL ===\n\n";

// 1. Login as Admin
$admin = User::where('role', 'admin')->first();
Auth::login($admin);
echo "1. Logged in as Admin: {$admin->name} (ID: {$admin->id})\n";

// 2. Query via Admin MasterCourseController
$controller = new \App\Http\Controllers\Admin\MasterCourseController();
$view = $controller->index();
$rendered = $view->render();

$vendorMasterCourses = $view->getData()['vendorMasterCourses'] ?? collect();
echo "2. Found {$vendorMasterCourses->count()} Vendor Master Courses in Controller.\n";

foreach ($vendorMasterCourses as $vmc) {
    echo "   - MasterCourse ID: {$vmc->id} | Name: {$vmc->name} | Code: {$vmc->code} | Batches Count: {$vmc->courses->count()}\n";
    foreach ($vmc->courses as $b) {
        echo "     * Batch: {$b->batch_name} (ID: {$b->id})\n";
    }
}

// 3. Verify that in the rendered HTML, each Vendor Master Course code appears exactly ONCE
$duplicated = false;
foreach ($vendorMasterCourses as $vmc) {
    if ($vmc->code) {
        $countOccurrences = substr_count($rendered, $vmc->code);
        if ($countOccurrences > 1) {
            echo "   [FAIL] Master Course {$vmc->code} appears {$countOccurrences} times in view!\n";
            $duplicated = true;
        } else {
            echo "   [OK] Master Course {$vmc->code} appears uniquely ({$countOccurrences} time) in catalog card!\n";
        }
    }
}

// 4. Test Admin viewing Vendor Course Show (Checking Batches List & Enrolled Students Roster)
echo "\n4. Testing Admin Vendor Course Show (Batch List & Enrolled Students Roster)...\n";
$targetMasterCourse = $vendorMasterCourses->filter(fn($mc) => $mc->courses->count() > 0)->first();
$batch1 = $targetMasterCourse->courses->first();
$batch2 = $targetMasterCourse->courses->skip(1)->first() ?? $batch1;

$student = User::where('role', 'student')->first();
Enrollment::updateOrCreate(
    ['user_id' => $student->id, 'course_offering_id' => $batch1->id],
    ['status' => 'completed', 'progress_percent' => 95]
);

$adminCourseController = new \App\Http\Controllers\Admin\CourseController();
$showView = $adminCourseController->show($batch1);
$renderedShow = $showView->render();

if (strpos($renderedShow, 'Daftar Seluruh Angkatan Batch Terdaftar') !== false &&
    strpos($renderedShow, 'Mahasiswa Terdaftar pada Angkatan') !== false &&
    strpos($renderedShow, $student->name) !== false &&
    strpos($renderedShow, 'Memenuhi Syarat') !== false) {
    echo "   [OK] Admin Vendor Course Show displays all batches, enrolled students, progress, and certification qualification cleanly!\n";
} else {
    echo "   [FAIL] Admin Vendor Course Show content missing expected batch/student details.\n";
    $duplicated = true;
}

if (!$duplicated) {
    echo "\n=== ALL ADMIN MASTER COURSE & BATCH DETAIL TESTS PASSED 100%! ===\n";
} else {
    echo "\n=== SOME TESTS FAILED ===\n";
    exit(1);
}
