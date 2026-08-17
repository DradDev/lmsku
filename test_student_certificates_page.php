<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Project;
use App\Models\ProjectParticipation;
use App\Models\Certificate;
use App\Http\Controllers\Student\CertificateController;
use Illuminate\Support\Facades\Auth;

echo "=== TESTING STUDENT CERTIFICATES PAGE (COURSES & PROJECTS) ===\n\n";

$student = User::where('role', 'student')->first();
if (!$student) {
    $student = User::create([
        'name' => 'Test Student Certs',
        'email' => 'student.certs.test@undip.ac.id',
        'password' => bcrypt('password'),
        'role' => 'student',
        'registration_status' => 'approved',
    ]);
}

Auth::login($student);

// Let's ensure the student has at least 1 project participation
$project = Project::first();
if ($project) {
    ProjectParticipation::firstOrCreate(
        ['user_id' => $student->id, 'project_id' => $project->id],
        ['status' => 'completed', 'progress_percent' => 100, 'completed_at' => now()]
    );
}

$controller = new CertificateController();
$view = $controller->index();

echo "1. Controller executed successfully!\n";
echo "   View Name: " . $view->name() . "\n";
echo "   Courses count: " . $view->getData()['courses']->count() . "\n";
echo "   Projects count: " . $view->getData()['projects']->count() . "\n";

foreach ($view->getData()['projects'] as $prj) {
    echo "   - Project: {$prj->title} | Credential: {$prj->credential_code} | Can Get Cert: " . ($prj->can_get_certificate ? 'YES' : 'NO') . "\n";
}

echo "\n2. Testing showProject method...\n";
if ($project) {
    $showProjectView = $controller->showProject($project);
    echo "   [OK] showProject view rendered: " . $showProjectView->name() . "\n";
    assert(str_contains($showProjectView->render(), e($project->title)));
}

echo "\n3. Rendering Blade template to verify HTML...\n";
$html = $view->render();
assert(str_contains($html, 'tab-courses'), "HTML must contain 'tab-courses'");
assert(str_contains($html, 'tab-projects'), "HTML must contain 'tab-projects'");
assert(str_contains($html, 'Project Certificates'), "HTML must contain 'Project Certificates'");
echo "   [OK] Rendered HTML contains complete Course & Project certificate tabs!\n\n";

echo "=== STUDENT CERTIFICATES PAGE TEST PASSED 100%! ===\n";
