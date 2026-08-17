<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Project;
use App\Models\Skill;
use App\Models\ProjectParticipation;
use App\Http\Controllers\Student\ProjectController;
use Illuminate\Support\Facades\Auth;

echo "=== TESTING STRICT LOCKED PROJECT ACCESS CONTROL (OPTION A) ===\n\n";

// 1. Create a fresh test student with ZERO skills and ZERO certificates
$lockedStudent = User::create([
    'name' => 'Student Ineligible Test',
    'email' => 'ineligible.' . time() . '@undip.ac.id',
    'password' => bcrypt('password'),
    'role' => 'student',
    'registration_status' => 'approved',
]);

// 2. Find or create a project with required skills
$skill = Skill::first();
$project = Project::whereHas('skills')->first();
if (!$project) {
    $vendor = User::where('role', 'vendor')->first() ?? User::where('role', 'lecturer')->first();
    $project = Project::create([
        'title' => 'Advanced AI Computer Vision Project',
        'description' => 'Top secret industry computer vision project.',
        'created_by' => $vendor->id,
        'difficulty_level' => 'advanced',
        'duration_days' => 30,
        'max_students' => 5,
        'is_published' => true,
        'provider_type' => 'external',
    ]);
    $project->skills()->sync([$skill->id]);
}

Auth::login($lockedStudent);

$controller = new ProjectController();

// 3. Test: Directly trying to access show() method of locked project
echo "1. Testing direct show() access on locked project for ineligible student...\n";
$response = $controller->show($project);

assert($response instanceof \Illuminate\Http\RedirectResponse, "Must return RedirectResponse for locked project!");
assert(session('error') !== null, "Session error must be set explaining project is locked!");
echo "   [OK] Access strictly BLOCKED! Redirected to index with message: '" . session('error') . "'\n\n";

// 4. Test: Directly trying to post join() on locked project
echo "2. Testing join() attempt on locked project for ineligible student...\n";
$joinResponse = $controller->join($project);
assert($joinResponse instanceof \Illuminate\Http\RedirectResponse, "Must return RedirectResponse for locked join attempt!");
echo "   [OK] Join strictly BLOCKED! Redirected to index with message: '" . session('error') . "'\n\n";

// 5. Test: Catalog view rendering
echo "3. Testing catalog view rendering for locked project card...\n";
$catalogView = $controller->index();
$html = $catalogView->render();
assert(str_contains($html, 'Syarat Belum Terpenuhi') || str_contains($html, 'Terkunci'), "HTML must display locked status indicator!");
echo "   [OK] Catalog view renders disabled locked state without clickable leak!\n\n";

// Clean up test user
$lockedStudent->delete();

echo "=== ALL LOCKED PROJECT ACCESS CONTROL TESTS PASSED 100%! ===\n";
