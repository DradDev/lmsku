<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Institution;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\DB;

echo "=== TESTING REGISTRATION SYSTEM & INSTITUTION ENTITIES ===\n\n";

DB::beginTransaction();

try {
    $controller = new RegisteredUserController();

    // 1. TEST STUDENT REGISTRATION
    echo "1. Testing Student Registration with Peminatan...\n";
    $studentReq = Request::create('/register', 'POST', [
        'name' => 'Test Student Undip',
        'email' => 'student.test_' . time() . '@undip.ac.id',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'student',
        'peminatan' => ['Software Engineering', 'Machine Learning & Artificial Intelligence'],
    ]);

    $resp = $controller->store($studentReq);
    $createdStudent = User::where('email', $studentReq->email)->first();
    assert($createdStudent !== null, 'Student must be created');
    assert($createdStudent->role === 'student', 'Role must be student');
    assert(str_contains($createdStudent->peminatan, 'Software Engineering'), 'Peminatan must match');
    assert($createdStudent->registration_status === 'pending', 'Status must be pending');
    echo "   [OK] Student registered successfully with ID: {$createdStudent->id}, Peminatan: '{$createdStudent->peminatan}'\n\n";

    // 2. TEST LECTURER REGISTRATION
    echo "2. Testing Lecturer Registration...\n";
    $lecturerReq = Request::create('/register', 'POST', [
        'name' => 'Dr. Test Lecturer',
        'email' => 'lecturer.test_' . time() . '@undip.ac.id',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'lecturer',
    ]);

    $resp = $controller->store($lecturerReq);
    $createdLecturer = User::where('email', $lecturerReq->email)->first();
    assert($createdLecturer !== null, 'Lecturer must be created');
    assert($createdLecturer->role === 'lecturer', 'Role must be lecturer');
    assert($createdLecturer->registration_status === 'pending', 'Status must be pending');
    echo "   [OK] Lecturer registered successfully with ID: {$createdLecturer->id}\n\n";

    // 3. TEST VENDOR REGISTRATION: NEW COMPANY (PT Telkom Indonesia)
    echo "3. Testing Vendor Registration with NEW Company ('PT Telkom Indonesia')...\n";
    $vendorCompanyReq1 = Request::create('/register', 'POST', [
        'name' => 'PIC Telkom 1',
        'email' => 'telkom1_' . time() . '@telkom.co.id',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'vendor',
        'vendor_type' => 'company',
        'institution_mode' => 'new',
        'new_institution_name' => 'PT Telkom Indonesia',
        'new_institution_code' => 'TLKM',
    ]);

    $resp = $controller->store($vendorCompanyReq1);
    $createdVendor1 = User::with('institution')->where('email', $vendorCompanyReq1->email)->first();
    assert($createdVendor1 !== null, 'Vendor 1 must be created');
    assert($createdVendor1->institution !== null, 'Vendor 1 must be linked to institution');
    assert($createdVendor1->institution->name === 'PT Telkom Indonesia', 'Institution name must match');
    assert($createdVendor1->institution->code === 'TLKM', 'Institution code must be TLKM');
    echo "   [OK] Vendor 1 created with Institution: '{$createdVendor1->institution->name}', Code: '{$createdVendor1->institution->code}'\n\n";

    // 4. TEST VENDOR REGISTRATION: EXISTING COMPANY (Another user joins PT Telkom)
    echo "4. Testing Vendor Registration joining EXISTING Company...\n";
    $vendorCompanyReq2 = Request::create('/register', 'POST', [
        'name' => 'PIC Telkom 2',
        'email' => 'telkom2_' . time() . '@telkom.co.id',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'vendor',
        'vendor_type' => 'company',
        'institution_mode' => 'existing',
        'institution_id' => $createdVendor1->institution_id,
    ]);

    $resp = $controller->store($vendorCompanyReq2);
    $createdVendor2 = User::with('institution')->where('email', $vendorCompanyReq2->email)->first();
    assert($createdVendor2 !== null, 'Vendor 2 must be created');
    assert($createdVendor2->institution_id === $createdVendor1->institution_id, 'Both vendors must share the SAME institution ID');
    echo "   [OK] Vendor 2 correctly linked to existing Institution ID: {$createdVendor2->institution_id} (Code: {$createdVendor2->institution->code})\n\n";

    // 5. TEST VENDOR REGISTRATION: NEW COMPANY COLLISION TEST (Auto-sanitization & Code generation)
    echo "5. Testing Vendor Registration with similar company name ('PT Telkom Akses')...\n";
    $vendorCompanyReq3 = Request::create('/register', 'POST', [
        'name' => 'PIC Telkom Akses',
        'email' => 'telkomakses_' . time() . '@telkom.co.id',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'vendor',
        'vendor_type' => 'company',
        'institution_mode' => 'new',
        'new_institution_name' => 'PT Telkom Akses',
        'new_institution_code' => '', // Auto-generate
    ]);

    $resp = $controller->store($vendorCompanyReq3);
    $createdVendor3 = User::with('institution')->where('email', $vendorCompanyReq3->email)->first();
    assert($createdVendor3 !== null, 'Vendor 3 must be created');
    assert($createdVendor3->institution->code !== 'TLKM', 'Must not collide with existing TLKM');
    echo "   [OK] Vendor 3 auto-generated non-colliding Code: '{$createdVendor3->institution->code}' for '{$createdVendor3->institution->name}'\n\n";

    // 6. TEST VENDOR REGISTRATION: INDIVIDUAL (Perorangan)
    echo "6. Testing Vendor Registration as INDIVIDUAL (Perorangan / Tanpa PT)...\n";
    $vendorIndivReq = Request::create('/register', 'POST', [
        'name' => 'Budi Santoso',
        'email' => 'budisantoso_' . time() . '@gmail.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'vendor',
        'vendor_type' => 'individual',
    ]);

    $resp = $controller->store($vendorIndivReq);
    $createdVendorIndiv = User::where('email', $vendorIndivReq->email)->first();
    assert($createdVendorIndiv !== null, 'Individual vendor must be created');
    assert($createdVendorIndiv->institution_type === 'individual', 'Institution type must be individual');
    assert($createdVendorIndiv->institution_id === null, 'Individual vendor must have null institution_id');
    echo "   [OK] Individual Vendor registered successfully with ID: {$createdVendorIndiv->id}, Type: '{$createdVendorIndiv->institution_type}', Institution ID: NULL\n\n";

    echo "=== ALL 6 REGISTRATION TESTS PASSED 100% WITH ZERO ERRORS! ===\n";

} catch (\Exception $e) {
    echo "ERROR DURING TEST: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
} finally {
    DB::rollBack();
    echo "Database rollback completed (clean state preserved).\n";
}
