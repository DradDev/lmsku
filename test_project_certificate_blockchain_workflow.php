<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Certificate;
use App\Models\Project;
use App\Models\ProjectParticipation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

echo "=== TESTING PROJECT CERTIFICATE & BLOCKCHAIN VERIFICATION WORKFLOW ===\n\n";

// 1. Run Seeder
echo "1. Seeding demo multi-state certificates...\n";
(new \Database\Seeders\EnrollmentCertificateSeeder())->run();

$student = User::where('role', 'student')->first();
$admin = User::where('role', 'admin')->first();
$vendor = User::where('role', 'vendor')->first();
$lecturer = User::where('role', 'lecturer')->first();

assert($student !== null, "Student user must exist");
assert($admin !== null, "Admin user must exist");

$projects = Project::orderBy('id')->get();
$p1 = $projects->get(0); // Verified
$p2 = $projects->get(1); // Pending
$p3 = $projects->get(2); // In Progress

echo "   - Student: {$student->name} (ID: {$student->id})\n";
echo "   - Project 1 (Target Verified): {$p1->title} (ID: {$p1->id})\n";
echo "   - Project 2 (Target Pending): {$p2->title} (ID: {$p2->id})\n";
echo "   - Project 3 (Target In Progress): {$p3->title} (ID: {$p3->id})\n\n";

// 2. Test Student Certificate Index View
echo "2. Testing Student Certificate Index Tab Rendering...\n";
Auth::login($student);
$certController = app(\App\Http\Controllers\Student\CertificateController::class);
$view = $certController->index();
$projectsData = $view->getData()['projects'];

$item1 = $projectsData->firstWhere('id', $p1->id);
$item2 = $projectsData->firstWhere('id', $p2->id);
$item3 = $projectsData->firstWhere('id', $p3->id);

assert($item1->can_get_certificate === true, "Project 1 must be downloadable");
assert($item1->status_badge === 'Verified', "Project 1 status badge must be 'Verified'");
echo "   [OK] Project 1 is 'Verified' with download access enabled!\n";

assert($item2->can_get_certificate === false, "Project 2 must NOT be downloadable yet (Pending)");
assert($item2->status_badge === 'Pending', "Project 2 status badge must be 'Pending'");
echo "   [OK] Project 2 is 'Pending' with download access locked!\n";

assert($item3->can_get_certificate === false, "Project 3 must NOT be downloadable (In Progress)");
assert($item3->status_badge === 'In Progress', "Project 3 status badge must be 'In Progress'");
echo "   [OK] Project 3 is 'In Progress' with download access locked!\n\n";

// 3. Test Student Guard on Pending Certificate
echo "3. Testing Student Guard against unauthorized download on pending certificate...\n";
try {
    $certController->showProject($p2);
    echo "   [FAIL] Expected 403 on pending project certificate!\n";
    exit(1);
} catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
    assert($e->getStatusCode() === 403, "Expected 403 status code");
    echo "   [OK] Access correctly rejected with 403: '{$e->getMessage()}'\n";
}

// 4. Test Student Access on Verified Certificate
echo "4. Testing Student Access on Verified Certificate...\n";
$viewProject1 = $certController->showProject($p1);
assert($viewProject1->getName() === 'student.certificate_project', "Must render certificate_project view");
$rendered = $viewProject1->render();
assert(str_contains($rendered, 'Blockchain Cryptographic Ledger Verification'), "Must contain blockchain verification block");
echo "   [OK] Verified Certificate web view rendered with Blockchain verification proof!\n\n";

// 5. Test Lecturer / Author Approval Flow
echo "5. Testing Lecturer/Author Approval Flow for Project 2...\n";
$creatorP2 = $p2->creator ?? $admin;
Auth::login($creatorP2);
$part2 = ProjectParticipation::where('user_id', $student->id)->where('project_id', $p2->id)->first();
$lecController = app(\App\Http\Controllers\Lecturer\ProjectController::class);
$redirectApproval = $lecController->approveCertificate($p2, $part2);
$cert2 = Certificate::where('user_id', $student->id)->where('project_id', $p2->id)->first();

assert($cert2 !== null, "Certificate record must exist");
assert($cert2->status === 'pending', "Certificate must be in pending status");
assert($cert2->is_verified === false, "Certificate must not be verified yet");
echo "   [OK] Project 2 successfully approved by creator and dispatched to Admin in 'pending' status!\n\n";

// 6. Test Admin Verification & Blockchain Hashing Flow
echo "6. Testing Admin Integrity Verification & Blockchain Hash Generation...\n";
Auth::login($admin);
$adminResultController = app(\App\Http\Controllers\Admin\ResultController::class);

// Admin index
$adminIndexView = $adminResultController->index(new \Illuminate\Http\Request(['tab' => 'project']));
assert($adminIndexView->getName() === 'admin.results.index', "Must render admin.results.index");
echo "   [OK] Admin Results page Tab Project loaded successfully!\n";

// Admin verify Project 2
$redirectVerify = $adminResultController->verifyProject($part2);
$cert2->refresh();
$part2->refresh();

assert($cert2->is_verified === true, "Certificate must be verified");
assert($cert2->status === 'verified', "Certificate status must be 'verified'");
assert(!empty($cert2->blockchain_hash), "Certificate must have blockchain_hash");
assert(!empty($cert2->blockchain_id), "Certificate must have blockchain_id");
assert(!empty($cert2->tx_id), "Certificate must have tx_id");
assert($cert2->verified_by === $admin->id, "Certificate verified_by must match admin ID");

echo "   [OK] Certificate 2 verified by Admin!\n";
echo "        * Blockchain ID: {$cert2->blockchain_id}\n";
echo "        * Hash: {$cert2->blockchain_hash}\n";
echo "        * TxID: {$cert2->tx_id}\n";

// 7. Test Admin Check Integrity
echo "7. Testing Admin Tamper-Proof Integrity Check on Blockchain...\n";
$redirectIntegrity = $adminResultController->checkProjectIntegrity($part2);
assert(session('success') !== null, "Integrity check must return success session message");
echo "   [OK] " . session('success') . "\n\n";

// 8. Test Student Access on Newly Verified Certificate 2
echo "8. Testing Student Access on Newly Verified Certificate 2...\n";
Auth::login($student);
$viewProject2 = $certController->showProject($p2);
$rendered2 = $viewProject2->render();
assert(str_contains($rendered2, $cert2->blockchain_hash), "Rendered certificate must contain blockchain hash");
echo "   [OK] Student can now successfully view & download Certificate 2 with Blockchain proof!\n\n";

// 9. Test Student Portfolio Integration
echo "9. Testing Student Portfolio View Rendering...\n";
$studentProjectController = app(\App\Http\Controllers\Student\ProjectController::class);
$portfolioView = $studentProjectController->portfolio();
$portfolioRendered = $portfolioView->render();
assert(str_contains($portfolioRendered, 'Verified Blockchain'), "Portfolio must contain Verified Blockchain badge");
echo "   [OK] Student Digital Portfolio displays verified blockchain project badges!\n\n";

echo "=== ALL PROJECT CERTIFICATE BLOCKCHAIN WORKFLOW TESTS PASSED 100%! ===\n";
