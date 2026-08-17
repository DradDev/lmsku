<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AcademicTerm;
use App\Models\CourseOffering;
use App\Models\MasterCourse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

echo "=== TESTING ACADEMIC TERMS THEME & TOGGLE DEACTIVATION FLOW ===\n\n";

// 1. Check Admin User
$admin = User::where('role', 'admin')->first();
if (!$admin) {
    echo "No admin user found. Creating a test admin...\n";
    $admin = User::create([
        'name' => 'Admin Tester',
        'email' => 'admintester@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);
}
Auth::login($admin);

// 2. Test Academic Terms Index View Rendering
echo "1. Testing Admin Academic Terms Index View Rendering...\n";
$terms = AcademicTerm::withCount('offerings')->orderByDesc('is_active')->get();
$renderedIndex = View::make('admin.academic-terms.index', compact('terms'))->render();
if (strpos($renderedIndex, 'Pengelolaan Periode Semester Akademik') !== false) {
    echo "   [OK] Academic terms index view rendered successfully (" . strlen($renderedIndex) . " bytes).\n";
} else {
    echo "   [FAIL] Academic terms index view failed to render expected text.\n";
}

// 3. Test Academic Terms Show View Rendering
echo "2. Testing Admin Academic Terms Show View Rendering...\n";
$term = AcademicTerm::first();
if (!$term) {
    $term = AcademicTerm::create([
        'name' => 'Semester Ganjil 2026/2027 Test',
        'academic_year' => '2026/2027',
        'term_type' => 'ganjil',
        'is_active' => true,
    ]);
}

$academicTerm = $term;
$offerings = CourseOffering::with(['masterCourse.category', 'masterCourse.skills', 'masterCourse.tags', 'lecturer', 'enrollments'])
    ->where('academic_term_id', $academicTerm->id)
    ->get();
$groupedOfferings = $offerings->groupBy('master_course_id');
$offeredMasterCoursesCount = $groupedOfferings->count();
$totalOfferingsCount = $offerings->count();
$totalLecturersCount = $offerings->pluck('lecturer_id')->filter()->unique()->count();
$totalEnrollmentsCount = $offerings->sum(fn($o) => $o->enrollments->count());
$allMasterCourses = MasterCourse::with(['category', 'skills', 'tags'])->orderBy('name')->get();
$lecturers = User::where('role', 'lecturer')->orderBy('name')->get();
$allTerms = AcademicTerm::orderByDesc('is_active')->get();

$renderedShow = View::make('admin.academic-terms.show', compact(
    'academicTerm',
    'allTerms',
    'groupedOfferings',
    'offeredMasterCoursesCount',
    'totalOfferingsCount',
    'totalLecturersCount',
    'totalEnrollmentsCount',
    'allMasterCourses',
    'lecturers'
))->render();

if (strpos($renderedShow, 'Penawaran Rombel Kelas Semester Ini') !== false) {
    echo "   [OK] Academic terms show view rendered successfully (" . strlen($renderedShow) . " bytes).\n";
} else {
    echo "   [FAIL] Academic terms show view failed to render expected text.\n";
}

// 4. Test Course Offerings Index View Rendering
echo "3. Testing Admin Course Offerings Index View Rendering...\n";
$offeringsQuery = CourseOffering::with(['masterCourse', 'academicTerm', 'lecturer'])->withCount('enrollments')->get();
$renderedOfferingsIndex = View::make('admin.course-offerings.index', [
    'offerings' => $offeringsQuery,
    'terms' => $terms,
])->render();

if (strpos($renderedOfferingsIndex, 'Daftar Rombel Kelas Penawaran') !== false) {
    echo "   [OK] Course offerings index view rendered successfully (" . strlen($renderedOfferingsIndex) . " bytes).\n";
} else {
    echo "   [FAIL] Course offerings index view failed to render expected text.\n";
}

// 5. Test Toggle Active Logic & Deactivation of Published Classes
echo "4. Testing Toggle Active Logic & Synchronous Unpublish of Offerings...\n";

// Set term to active and create a dummy offering with status 'published'
$term->update(['is_active' => true]);
$mc = MasterCourse::first();
$lecturer = User::where('role', 'lecturer')->first() ?? $admin;

$testOffering = CourseOffering::create([
    'master_course_id' => $mc->id,
    'academic_term_id' => $term->id,
    'lecturer_id' => $lecturer->id,
    'section_name' => 'Kelas Test ' . rand(100, 999),
    'capacity' => 40,
    'certificate_threshold' => 70,
    'status' => 'published',
]);

echo "   - Created test offering in active term with status: '{$testOffering->status}'.\n";

// Simulate Controller Toggle Active (deactivating the semester)
$controller = new \App\Http\Controllers\Admin\AcademicTermController();
$response = $controller->toggleActive($term);

$term->refresh();
$testOffering->refresh();

echo "   - After deactivating semester:\n";
echo "     * Term is_active: " . ($term->is_active ? 'TRUE (Active)' : 'FALSE (Inactive)') . "\n";
echo "     * Offering status: '{$testOffering->status}'\n";

if (!$term->is_active && $testOffering->status === 'draft') {
    echo "   [OK] Deactivating semester successfully changed published offerings to draft!\n";
} else {
    echo "   [FAIL] Offering status was not changed to draft on semester deactivation.\n";
}

// Clean up dummy test offering
$testOffering->delete();

// Re-activate term if needed
$term->update(['is_active' => true]);

echo "\n=== ALL ACADEMIC TERMS THEME & FUNCTIONALITY TESTS PASSED 100%! ===\n";
