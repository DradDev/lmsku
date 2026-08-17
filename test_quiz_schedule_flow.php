<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\MasterCourse;
use App\Models\Quiz;
use App\Models\Enrollment;
use App\Http\Controllers\Lecturer\QuizController as LecturerQuizController;
use App\Http\Controllers\Vendor\QuizController as VendorQuizController;
use App\Http\Controllers\Student\QuizController as StudentQuizController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

echo "=== TESTING QUIZ START DATE & END DATE SCHEDULE FLOW (LECTURER & VENDOR) ===\n\n";

// 1. Setup Lecturer & CourseOffering
$lecturer = User::where('role', 'lecturer')->first();
$vendor = User::where('role', 'vendor')->first();
$student = User::where('role', 'student')->first();

if (!$lecturer || !$vendor || !$student) {
    echo "Required users not found!\n";
    exit(1);
}

// 2. Test Lecturer Quiz Update with Start Date & End Date
echo "1. Testing Lecturer Quiz update with start_date & end_date...\n";
Auth::login($lecturer);

$offering = CourseOffering::where('lecturer_id', $lecturer->id)->first();
if (!$offering) {
    $master = MasterCourse::first();
    $term = \App\Models\AcademicTerm::first();
    $offering = CourseOffering::create([
        'master_course_id' => $master->id,
        'academic_term_id' => $term->id,
        'lecturer_id' => $lecturer->id,
        'section_name' => 'Kelas Test Schedule',
    ]);
}

$quiz = Quiz::create([
    'master_course_id' => $offering->master_course_id,
    'course_id' => $offering->id,
    'title' => 'Test Schedule Quiz Lecturer',
    'quiz_type' => 'daily',
    'max_attempts' => 2,
    'time_limit' => 45,
]);

$lecturerController = new LecturerQuizController();

$startDate = now()->addDays(2)->format('Y-m-d\TH:i');
$endDate = now()->addDays(5)->format('Y-m-d\TH:i');

$updateReq = Request::create(route('lecturer.courses.quizzes.update', [$offering->id, $quiz->id]), 'PUT', [
    'title' => 'Test Schedule Quiz Lecturer Updated',
    'time_limit' => 60,
    'max_attempts' => 3,
    'start_date' => $startDate,
    'end_date' => $endDate,
]);

$response = $lecturerController->update($updateReq, $offering->id, $quiz);
$quiz->refresh();

assert($quiz->start_date !== null, "Quiz start_date must be saved!");
assert($quiz->end_date !== null, "Quiz end_date must be saved!");
assert($quiz->time_limit === 60, "Quiz time_limit must be updated to 60!");
echo "   [OK] Lecturer Quiz updated successfully with start_date: {$quiz->start_date} and end_date: {$quiz->end_date}!\n\n";

// 3. Test Vendor Quiz Update with Start Date & End Date
echo "2. Testing Vendor Quiz update with start_date & end_date...\n";
Auth::login($vendor);

$vendorCourse = Course::where('user_id', $vendor->id)->first();
if (!$vendorCourse) {
    $vendorCourse = Course::create([
        'user_id' => $vendor->id,
        'title' => 'Vendor Certified AI Course',
        'is_published' => true,
    ]);
}

$vendorQuiz = Quiz::create([
    'course_id' => $vendorCourse->id,
    'title' => 'Vendor Test Schedule Quiz',
    'quiz_type' => 'daily',
    'max_attempts' => 1,
]);

$vendorController = new VendorQuizController();
$vendorUpdateReq = Request::create(route('vendor.courses.quizzes.update', [$vendorCourse->id, $vendorQuiz->id]), 'PUT', [
    'title' => 'Vendor Test Schedule Quiz Updated',
    'time_limit' => 30,
    'max_attempts' => 2,
    'start_date' => $startDate,
    'end_date' => $endDate,
]);

$vendorController->update($vendorUpdateReq, $vendorCourse->id, $vendorQuiz);
$vendorQuiz->refresh();

assert($vendorQuiz->start_date !== null, "Vendor quiz start_date must be saved!");
assert($vendorQuiz->end_date !== null, "Vendor quiz end_date must be saved!");
echo "   [OK] Vendor Quiz updated successfully with start_date: {$vendorQuiz->start_date} and end_date: {$vendorQuiz->end_date}!\n\n";

// 4. Test Student Access Enforcement: Future Quiz (Belum Dibuka)
echo "3. Testing Student access enforcement on Future Quiz (start_date in future)...\n";
Auth::login($student);

// Enroll student if not enrolled
Enrollment::firstOrCreate([
    'user_id' => $student->id,
    'course_offering_id' => $offering->id,
    'course_id' => $offering->master_course_id,
]);

$studentQuizController = new StudentQuizController();

assert(!$quiz->isAvailable(), "Quiz with future start_date must not be available!");

$studentResponse = $studentQuizController->show($quiz);
assert($studentResponse instanceof \Illuminate\Http\RedirectResponse, "Must redirect when quiz is not available!");
assert(str_contains(session('error'), 'belum dibuka'), "Error message must state quiz is not opened yet!");
echo "   [OK] Future quiz access blocked for student with message: '" . session('error') . "'\n\n";

// 5. Test Student Access Enforcement: Expired Quiz (end_date in past)
echo "4. Testing Student access enforcement on Expired Quiz (end_date in past)...\n";
$quiz->update([
    'start_date' => now()->subDays(5),
    'end_date' => now()->subDay(),
]);

assert(!$quiz->isAvailable(), "Quiz with past end_date must not be available!");

$studentResponse2 = $studentQuizController->show($quiz);
assert($studentResponse2 instanceof \Illuminate\Http\RedirectResponse, "Must redirect when quiz is expired!");
assert(str_contains(session('error'), 'sudah ditutup'), "Error message must state quiz is closed!");
echo "   [OK] Expired quiz access blocked for student with message: '" . session('error') . "'\n\n";

// 6. Test Active Quiz Access (currently within start_date & end_date)
echo "5. Testing Student access on Active Quiz (ongoing schedule)...\n";
$quiz->update([
    'start_date' => now()->subHour(),
    'end_date' => now()->addDays(2),
]);

assert($quiz->isAvailable(), "Quiz within schedule must be available!");

$studentView = $studentQuizController->show($quiz);
assert($studentView instanceof \Illuminate\View\View, "Must return View when quiz is available!");
echo "   [OK] Active scheduled quiz opens smoothly for student!\n\n";

// Clean up
$quiz->delete();
$vendorQuiz->delete();

echo "=== ALL QUIZ SCHEDULE & AVAILABILITY TESTS PASSED 100%! ===\n";
