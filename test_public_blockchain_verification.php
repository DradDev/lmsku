<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Http\Request;

echo "=== TESTING PUBLIC BLOCKCHAIN VERIFICATION ENGINE ===\n\n";

// 1. Refresh Seeder
echo "1. Seeding demo multi-state certificates...\n";
(new \Database\Seeders\EnrollmentCertificateSeeder())->run();

$verifiedProjectCert = Certificate::whereNotNull('project_id')
    ->where('is_verified', true)
    ->whereNotNull('blockchain_hash')
    ->first();

$pendingProjectCert = Certificate::whereNotNull('project_id')
    ->where('is_verified', false)
    ->first();

$verifiedCourseCert = Certificate::whereNotNull('course_offering_id')
    ->where('is_verified', true)
    ->whereNotNull('blockchain_hash')
    ->first();

assert($verifiedProjectCert !== null, "Verified project certificate must exist in seeder");
assert($pendingProjectCert !== null, "Pending project certificate must exist in seeder");
assert($verifiedCourseCert !== null, "Verified course certificate must exist in seeder");

echo "   - Verified Project Cert Hash: {$verifiedProjectCert->blockchain_hash}\n";
echo "   - Verified Project Credential Code: {$verifiedProjectCert->credential_code}\n";
echo "   - Pending Project Credential Code: {$pendingProjectCert->credential_code}\n";
echo "   - Verified Course Cert Hash: {$verifiedCourseCert->blockchain_hash}\n\n";

$controller = app(\App\Http\Controllers\BlockchainVerificationController::class);

// 2. Test POST /validasi-blockchain with Verified Project Blockchain Hash
echo "2. Testing Validation with Verified Project Certificate Blockchain Hash...\n";
$req1 = Request::create('/validasi-blockchain', 'POST', ['hash' => $verifiedProjectCert->blockchain_hash]);
$view1 = $controller->verify($req1);
$data1 = $view1->getData();

assert($data1['status'] === 'valid', "Expected status to be 'valid'");
assert($data1['result'] !== null, "Expected result data to be present");
assert($data1['result']->student_name === $verifiedProjectCert->user->name, "Student name must match");
assert($data1['result']->cert_type === 'Project Certificate', "Cert type must be Project Certificate");
assert($data1['result']->blockchain_hash === $verifiedProjectCert->blockchain_hash, "Hash must match");
echo "   [OK] Project Certificate verified successfully by SHA-256 Hash!\n\n";

// 3. Test POST /validasi-blockchain with Verified Project Credential Code
echo "3. Testing Validation with Verified Project Credential Code...\n";
$req2 = Request::create('/validasi-blockchain', 'POST', ['hash' => $verifiedProjectCert->credential_code]);
$view2 = $controller->verify($req2);
$data2 = $view2->getData();

assert($data2['status'] === 'valid', "Expected status to be 'valid' when searching by credential_code");
assert($data2['result']->credential_code === $verifiedProjectCert->credential_code, "Credential code must match");
echo "   [OK] Project Certificate verified successfully by Credential Code!\n\n";

// 4. Test POST /validasi-blockchain with Verified Course Certificate
echo "4. Testing Validation with Verified Academic Course Certificate Hash...\n";
$req3 = Request::create('/validasi-blockchain', 'POST', ['hash' => $verifiedCourseCert->blockchain_hash]);
$view3 = $controller->verify($req3);
$data3 = $view3->getData();

assert($data3['status'] === 'valid', "Expected status to be 'valid'");
assert($data3['result']->cert_type === 'Course Certificate', "Cert type must be Course Certificate");
echo "   [OK] Course Certificate verified successfully by Hash!\n\n";

// 5. Test GET /validasi-blockchain?hash=... (Auto-verification on direct link / QR Code)
echo "5. Testing Direct URL / QR Code auto-verification (GET /validasi-blockchain?hash=...)...\n";
$req4 = Request::create('/validasi-blockchain?hash=' . urlencode($verifiedProjectCert->blockchain_hash), 'GET');
$view4 = $controller->index($req4);
$data4 = $view4->getData();

assert($data4['status'] === 'valid', "Expected GET auto-verification status to be 'valid'");
echo "   [OK] Direct link / QR-code GET verification succeeded!\n\n";

// 6. Test Pending Certificate (Should be rejected with informative pending status)
echo "6. Testing Validation on Pending Certificate (Not yet admin-verified)...\n";
$req5 = Request::create('/validasi-blockchain', 'POST', ['hash' => $pendingProjectCert->credential_code]);
$view5 = $controller->verify($req5);
$data5 = $view5->getData();

assert($data5['status'] === 'invalid', "Expected pending certificate to be invalid");
assert(str_contains($data5['message'], 'PENDING'), "Expected message to mention PENDING status");
echo "   [OK] Pending certificate correctly identified: '{$data5['message']}'\n\n";

// 7. Test Fake / Non-existent Hash
echo "7. Testing Validation on Fake / Tampered Hash...\n";
$req6 = Request::create('/validasi-blockchain', 'POST', ['hash' => '0xfakehash1234567890abcdef']);
$view6 = $controller->verify($req6);
$data6 = $view6->getData();

assert($data6['status'] === 'invalid', "Expected fake hash to be invalid");
echo "   [OK] Tampered / Fake hash correctly rejected: '{$data6['message']}'\n\n";

// 8. Test Welcome Page View Rendering
echo "8. Testing Welcome Landing Page View Rendering...\n";
$welcomeHtml = view('welcome')->render();
assert(str_contains($welcomeHtml, 'Cek & Validasi Hash Blockchain Sertifikat'), "Welcome page must contain blockchain verification form");
assert(str_contains($welcomeHtml, 'Mitra Industri / Vendor'), "Welcome page must contain Vendor portal card");
assert(str_contains($welcomeHtml, 'Administrator LP3M'), "Welcome page must contain Admin portal card");
echo "   [OK] Welcome landing page renders interactive validator and all portal cards!\n\n";

echo "=== ALL PUBLIC BLOCKCHAIN VERIFICATION TESTS PASSED 100%! ===\n";
