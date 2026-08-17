<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;

echo "=== TESTING STUDENT PROFILE AVATAR UPLOAD & FRAME FLOW ===\n\n";

// 1. Create a test student
$student = User::create([
    'name' => 'Avatar Test Student',
    'email' => 'avatar.' . time() . '@undip.ac.id',
    'password' => bcrypt('password'),
    'role' => 'student',
    'registration_status' => 'approved',
]);

Auth::login($student);
$controller = new ProfileController();

// 2. Test: Edit View Rendering
echo "1. Testing Profile Edit View Rendering...\n";
$view = $controller->edit();
$html = $view->render();

assert(str_contains($html, 'Foto Profil / Avatar'), "View must contain Foto Profil label!");
assert(str_contains($html, 'avatar-live-preview'), "View must contain avatar-live-preview element!");
assert(str_contains($html, 'sidebar-avatar-preview'), "View must contain sidebar-avatar-preview element!");
assert(str_contains($html, 'previewAvatar'), "View must contain previewAvatar JavaScript function!");
echo "   [OK] Profile edit view rendered with live frame and preview scripts!\n\n";

// 3. Test: Uploading Avatar File
echo "2. Testing Avatar Upload Request...\n";
Storage::fake('public');

$fakeFile = UploadedFile::fake()->image('my_photo.jpg', 400, 400);

$request = Request::create(route('profile.update'), 'PATCH', [
    'name' => 'Avatar Test Student Updated',
    'email' => $student->email,
], [], [
    'avatar' => $fakeFile,
]);

$response = $controller->update($request);

$student->refresh();

assert(!empty($student->avatar), "Avatar column must be saved in database!");
Storage::disk('public')->assertExists($student->avatar);
assert(!empty($student->avatar_url), "Avatar URL accessor must not be empty!");
assert(str_contains($student->avatar_url, 'storage/' . $student->avatar), "Avatar URL must map to public storage!");
echo "   [OK] Avatar uploaded, stored in storage/app/public/avatars, and mapped to avatar_url: '{$student->avatar_url}'!\n\n";

// 4. Test: Removing Avatar
echo "3. Testing Avatar Removal...\n";
$oldAvatarPath = $student->avatar;

$removeRequest = Request::create(route('profile.update'), 'PATCH', [
    'name' => 'Avatar Test Student Updated',
    'email' => $student->email,
    'remove_avatar' => '1',
]);

$controller->update($removeRequest);
$student->refresh();

assert(empty($student->avatar), "Avatar column must be null after removal!");
Storage::disk('public')->assertMissing($oldAvatarPath);
echo "   [OK] Avatar removed successfully and database reset to initials!\n\n";

// Clean up test user
$student->delete();

echo "=== ALL AVATAR UPLOAD & FRAME FLOW TESTS PASSED 100%! ===\n";
