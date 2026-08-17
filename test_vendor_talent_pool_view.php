<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Project;
use App\Http\Controllers\Vendor\ProjectController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

echo "=== TESTING VENDOR TALENT POOL AND PORTFOLIO VIEW RENDERING ===\n\n";

$project = Project::first();
$vendor = User::find($project->created_by) ?? User::where('role', 'vendor')->first();
$student = User::where('role', 'student')->first();

if ($project && $vendor) {
    $project->created_by = $vendor->id;
    $project->save();
}

if (!$vendor || !$student || !$project) {
    echo "Required data missing for test!\n";
    exit(1);
}

Auth::login($vendor);
$errors = session('errors', new \Illuminate\Support\ViewErrorBag());
view()->share('errors', $errors);
$controller = new ProjectController();

// 1. Test Vendor Talent Pool View
echo "1. Rendering vendor talentPool view...\n";
$view = $controller->talentPool($project);
$rendered = $view->render();
assert(strlen($rendered) > 0, "Talent pool view must render content!");
assert(!str_contains($rendered, '🔥') && !str_contains($rendered, '🎯'), "Talent pool view must not contain emojis!");
echo "   [OK] Vendor talentPool view rendered cleanly (" . strlen($rendered) . " bytes)\n\n";

// 2. Test Vendor Project Show View (with top candidate match widget)
echo "2. Rendering vendor project show view...\n";
$showView = $controller->show($project);
$showRendered = $showView->render();
assert(strlen($showRendered) > 0, "Project show view must render content!");
echo "   [OK] Vendor project show view rendered cleanly (" . strlen($showRendered) . " bytes)\n\n";

// 3. Test Vendor Student Portfolio View
echo "3. Rendering vendor student portfolio view...\n";
$portView = $controller->studentPortfolio($student);
$portRendered = $portView->render();
assert(strlen($portRendered) > 0, "Student portfolio view must render content!");
echo "   [OK] Vendor student portfolio view rendered cleanly (" . strlen($portRendered) . " bytes)\n\n";

echo "=== ALL VENDOR VIEW RENDERING TESTS PASSED 100%! ===\n";
