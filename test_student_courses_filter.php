<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Http\Controllers\Student\CourseController;
use Illuminate\Support\Facades\Auth;

echo "=== TESTING STUDENT COURSES PAGE WITH VENDOR DROPDOWN FILTER ===\n\n";

$student = User::where('role', 'student')->first();
if (!$student) {
    $student = User::create([
        'name' => 'Test Student Filter',
        'email' => 'student.filter.test@undip.ac.id',
        'password' => bcrypt('password'),
        'role' => 'student',
        'registration_status' => 'approved',
    ]);
}

Auth::login($student);

$controller = new CourseController();
$view = $controller->index();

echo "1. Controller executed successfully!\n";
echo "   View Name: " . $view->name() . "\n";
echo "   Data Keys: " . implode(', ', array_keys($view->getData())) . "\n";
echo "   Vendors count: " . $view->getData()['vendors']->count() . "\n";
echo "   Authors (Lecturers) count: " . $view->getData()['authors']->count() . "\n";
echo "   Grouped Courses (Internal): " . $view->getData()['groupedCourses']->count() . "\n";
echo "   Vendor Courses (External): " . $view->getData()['vendorCourses']->count() . "\n";

echo "\n2. Rendering Blade template to test HTML...\n";
$html = $view->render();
assert(str_contains($html, 'source-filter-select'), "HTML must contain 'source-filter-select'");
assert(str_contains($html, 'author-filter-select'), "HTML must contain 'author-filter-select'");
assert(str_contains($html, 'Mitra Industri / Vendor (Eksternal)'), "HTML must contain optgroup for external vendors");
echo "   [OK] Rendered HTML contains complete dropdown filters for internal & external courses!\n\n";

echo "=== STUDENT COURSES FILTER TEST PASSED 100%! ===\n";
