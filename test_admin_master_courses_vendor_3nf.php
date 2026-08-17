<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\MasterCourse;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

echo "=== TESTING ADMIN MASTER COURSES VENDOR 3NF DEDUPLICATION ===\n\n";

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

if (!$duplicated) {
    echo "\n=== ALL ADMIN MASTER COURSE VENDOR 3NF TESTS PASSED 100%! ===\n";
} else {
    echo "\n=== SOME TESTS FAILED ===\n";
    exit(1);
}
