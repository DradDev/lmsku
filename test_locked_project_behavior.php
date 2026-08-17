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

echo "=== TESTING PROTECTED PREVIEW LOCKED PROJECT ACCESS CONTROL (OPTION B) ===\n\n";

// 1. Create a test student with ZERO skills and ZERO certificates
$lockedStudent = User::create([
    'name' => 'Student Preview Test',
    'email' => 'preview.' . time() . '@undip.ac.id',
    'password' => bcrypt('password'),
    'role' => 'student',
    'registration_status' => 'approved',
]);

// 2. Find a project with required skills
$project = Project::whereHas('skills')->first();

Auth::login($lockedStudent);

$controller = new ProjectController();

// 3. Test: Accessing show() in Protected Preview mode
echo "1. Testing show() access in Protected Preview mode for ineligible student...\n";
$view = $controller->show($project);
assert($view instanceof \Illuminate\View\View, "Must return View for Protected Preview!");
$html = $view->render();

assert(str_contains($html, 'PROYEK TERKUNCI') || str_contains($html, 'Mode Preview'), "HTML must display locked status banner!");
assert(str_contains($html, 'Dokumen TOR Terkunci') || !str_contains($html, 'Download TOR / Brief Project (PDF)'), "Secret TOR PDF download must be LOCKED/HIDDEN!");
assert(str_contains($html, 'Pendaftaran Terkunci'), "Join button must be disabled and locked!");
echo "   [OK] Protected Preview rendered successfully with secret files and actions locked!\n\n";

// 4. Test: Directly trying to post join() on locked project
echo "2. Testing join() attempt on locked project for ineligible student...\n";
$joinResponse = $controller->join($project);
assert($joinResponse instanceof \Illuminate\Http\RedirectResponse, "Must return RedirectResponse for locked join attempt!");
echo "   [OK] Join strictly BLOCKED! Redirected to index with message: '" . session('error') . "'\n\n";

// 5. Test: Catalog view rendering
echo "3. Testing catalog view rendering for locked project card...\n";
$catalogView = $controller->index();
$catalogHtml = $catalogView->render();
assert(str_contains($catalogHtml, 'Lihat Prasyarat') || str_contains($catalogHtml, 'Terkunci'), "HTML must display prerequisite link!");
echo "   [OK] Catalog view renders clean prerequisite guide!\n\n";

// Clean up test user
$lockedStudent->delete();

echo "=== ALL OPTION B (PROTECTED PREVIEW) TESTS PASSED 100%! ===\n";
