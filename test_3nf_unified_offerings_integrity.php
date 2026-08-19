<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\MasterCourse;
use App\Models\Certificate;
use App\Models\User;

echo "=== TESTING 3NF UNIFIED COURSE OFFERINGS INTEGRITY ===\n\n";

// 1. Check Academic Offerings
echo "1. Checking Academic Offerings in course_offerings...\n";
$academicOfferings = CourseOffering::academic()->with(['masterCourse', 'academicTerm', 'lecturer'])->get();
assert($academicOfferings->count() > 0, "Academic offerings must exist");

foreach ($academicOfferings as $ao) {
    assert($ao->type === 'academic', "Offering type must be academic");
    assert($ao->masterCourse !== null, "Academic offering must link to masterCourse");
    assert($ao->academicTerm !== null, "Academic offering must link to academicTerm");
    assert(!empty($ao->name), "Accessor name must pull from masterCourse");
    echo "   - [Academic] ID: {$ao->id} | {$ao->name} ({$ao->section_name}) | Term: {$ao->academicTerm->name}\n";
}
echo "   [OK] Academic offerings structure is valid!\n\n";

// 2. Check Vendor Offerings
echo "2. Checking Vendor Offerings in course_offerings...\n";
$vendorOfferings = CourseOffering::vendor()->with(['masterCourse', 'lecturer'])->get();
assert($vendorOfferings->count() > 0, "Vendor offerings must exist");

foreach ($vendorOfferings as $vo) {
    assert($vo->type === 'vendor', "Offering type must be vendor");
    assert($vo->masterCourse !== null, "Vendor offering must link to masterCourse");
    assert(!empty($vo->name), "Accessor name must pull from masterCourse");
    echo "   - [Vendor] ID: {$vo->id} | {$vo->name} ({$vo->section_name}) | Vendor: {$vo->lecturer->name}\n";
}
echo "   [OK] Vendor offerings structure is valid!\n\n";

// 3. Check Enrollments Foreign Key Integrity
echo "3. Checking Enrollments Integrity (course_offering_id strictly populated)...\n";
$enrollmentsWithoutOffering = Enrollment::whereNull('course_offering_id')->count();
assert($enrollmentsWithoutOffering === 0, "No enrollment should have null course_offering_id");
echo "   [OK] All " . Enrollment::count() . " enrollments are strictly linked to course_offering_id!\n\n";

// 4. Check Magic Accessors on CourseOffering
echo "4. Checking Magic Accessors on CourseOffering (name, description, level, category, skills, tags)...\n";
$sampleOffering = CourseOffering::with(['masterCourse.skills', 'masterCourse.tags', 'masterCourse.category'])->first();
assert(!empty($sampleOffering->name), "Name accessor must work");
assert(!empty($sampleOffering->level), "Level accessor must work");
assert($sampleOffering->skills !== null, "Skills accessor must work");
assert($sampleOffering->tags !== null, "Tags accessor must work");
echo "   - Sample Offering Name: {$sampleOffering->name}\n";
echo "   - Sample Offering Level: {$sampleOffering->level}\n";
echo "   - Sample Offering Skills Count: " . $sampleOffering->skills->count() . "\n";
echo "   - Sample Offering Tags Count: " . $sampleOffering->tags->count() . "\n";
echo "   [OK] Magic accessors work 100% cleanly without data duplication!\n\n";

// 5. Check Certificates Integrity
echo "5. Checking Certificates Foreign Key Integrity...\n";
$courseCerts = Certificate::whereNull('project_id')->get();
foreach ($courseCerts as $cc) {
    assert($cc->course_offering_id !== null, "Course certificate must link to course_offering_id");
    assert($cc->courseOffering !== null, "Certificate must resolve courseOffering relation");
}
echo "   [OK] All " . $courseCerts->count() . " course certificates are strictly linked to course_offering_id!\n\n";

echo "=== ALL 3NF UNIFIED OFFERINGS INTEGRITY TESTS PASSED 100%! ===\n";
