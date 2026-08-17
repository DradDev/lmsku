<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Project;
use App\Http\Controllers\Lecturer\ProjectController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

echo "=== TESTING TALENT POOL AND PORTFOLIO VIEW RENDERING ===\n\n";

$lecturer = User::where('role', 'lecturer')->first();
$student = User::where('role', 'student')->first();
$project = Project::where('created_by', $lecturer->id)->first() ?? Project::first();

if (!$lecturer || !$student || !$project) {
    echo "Required data missing for test!\n";
    exit(1);
}

Auth::login($lecturer);
$errors = session('errors', new \Illuminate\Support\ViewErrorBag());
view()->share('errors', $errors);
$controller = new ProjectController();

// 1. Test Talent Pool View
echo "1. Rendering talentPool view...\n";
$req = Request::create(route('lecturer.projects.talent-pool', $project), 'GET');
$view = $controller->talentPool($req, $project);
$rendered = $view->render();
assert(strlen($rendered) > 0, "Talent pool view must render content!");
assert(!str_contains($rendered, '🔥') && !str_contains($rendered, '🎯'), "Talent pool view must not contain emojis!");
echo "   [OK] talentPool view rendered cleanly (" . strlen($rendered) . " bytes)\n\n";

// 2. Test Project Show View (with top candidate match widget)
echo "2. Rendering project show view...\n";
$showView = $controller->show($project);
$showRendered = $showView->render();
assert(strlen($showRendered) > 0, "Project show view must render content!");
echo "   [OK] Project show view rendered cleanly (" . strlen($showRendered) . " bytes)\n\n";

// 3. Test Student Portfolio View
echo "3. Rendering student portfolio view...\n";
$portView = $controller->studentPortfolio($student);
$portRendered = $portView->render();
assert(strlen($portRendered) > 0, "Student portfolio view must render content!");
echo "   [OK] Student portfolio view rendered cleanly (" . strlen($portRendered) . " bytes)\n\n";

echo "=== ALL VIEW RENDERING TESTS PASSED 100%! ===\n";
