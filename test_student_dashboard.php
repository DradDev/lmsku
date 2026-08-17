<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Project;
use App\Models\ProjectParticipation;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Student\DashboardController;

echo "=== TESTING STUDENT DASHBOARD WITH ACTIVE PROJECTS WIDGET ===\n\n";

$student = User::where('role', 'student')->first();
if (!$student) {
    $student = User::create([
        'name' => 'Test Student Dashboard',
        'email' => 'student.dash.test@undip.ac.id',
        'password' => bcrypt('password'),
        'role' => 'student',
        'registration_status' => 'approved',
    ]);
}

Auth::login($student);

$controller = new DashboardController();
$view = $controller->index();

echo "1. Controller executed successfully!\n";
echo "   View Name: " . $view->name() . "\n";
echo "   Data Keys: " . implode(', ', array_keys($view->getData())) . "\n";
echo "   Active Participations count: " . $view->getData()['activeParticipations']->count() . "\n";
echo "   Pending Invitations count: " . $view->getData()['pendingInvitationsCount'] . "\n";

echo "\n2. Rendering Blade template to test syntax & rendering...\n";
$html = $view->render();
assert(str_contains($html, 'Proyek Aktif'), "HTML must contain 'Proyek Aktif'");
assert(!str_contains($html, 'progressChart'), "HTML must NOT contain 'progressChart'");
echo "   [OK] Rendered HTML contains 'Proyek Aktif' widget and clean of dummy chart!\n\n";

echo "=== STUDENT DASHBOARD TEST PASSED 100%! ===\n";
