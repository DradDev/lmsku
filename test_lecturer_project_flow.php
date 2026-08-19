<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Skill;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

echo "=== TESTING LECTURER PROJECT FLOW 3NF ===\n\n";

$lecturer = User::where('role', 'lecturer')->first();
assert($lecturer !== null, "Lecturer user must exist");
Auth::login($lecturer);

\Illuminate\Support\Facades\View::share('errors', new \Illuminate\Support\ViewErrorBag());

echo "1. Logged in as Lecturer: {$lecturer->name} (ID: {$lecturer->id})\n";

$controller = new \App\Http\Controllers\Lecturer\ProjectController();

// 2. Test Index View
echo "2. Testing Lecturer Projects Index (index)... \n";
$indexView = $controller->index();
$htmlIndex = $indexView->render();
assert(!empty($htmlIndex), "Index view must render cleanly");
echo "   [OK] Lecturer Projects Index rendered successfully without SQL errors!\n";

// 3. Test Create View
echo "3. Testing Lecturer Projects Create (create)... \n";
$createView = $controller->create();
$htmlCreate = $createView->render();
assert(!empty($htmlCreate), "Create view must render cleanly");
echo "   [OK] Lecturer Projects Create rendered successfully without SQL errors!\n";

// 4. Test Show & Edit View on existing project
$project = Project::where('created_by', $lecturer->id)->first();
if (!$project) {
    $project = Project::create([
        'title' => 'Audit Test Project 3NF',
        'description' => 'Testing 3NF verification',
        'difficulty_level' => 'Intermediate',
        'duration_days' => 14,
        'max_students' => 5,
        'created_by' => $lecturer->id,
        'is_published' => true,
    ]);
}

echo "4. Testing Lecturer Project Show (ID: {$project->id})...\n";
$showView = $controller->show($project);
$htmlShow = $showView->render();
assert(!empty($htmlShow), "Show view must render cleanly");
echo "   [OK] Lecturer Project Show rendered successfully!\n";

echo "5. Testing Lecturer Project Edit (ID: {$project->id})...\n";
$editView = $controller->edit($project);
$htmlEdit = $editView->render();
assert(!empty($htmlEdit), "Edit view must render cleanly");
echo "   [OK] Lecturer Project Edit rendered successfully!\n";

echo "\n=== ALL LECTURER PROJECT TESTS PASSED 100%! ===\n";
