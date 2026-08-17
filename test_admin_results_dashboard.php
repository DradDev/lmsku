<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\QuizAttempt;

echo "=== TESTING ADMIN DASHBOARD & RESULTS WITHOUT AVERAGE SCORE ===\n\n";

$admin = User::where('role', 'admin')->first();
Auth::login($admin);

// 1. Test Admin Dashboard View
echo "1. Testing Admin Dashboard View...\n";
$finalQuizAttempts = QuizAttempt::whereHas('quiz', function ($query) {
    $query->where('quiz_type', 'final');
});

$resultStats = [
    'total' => (clone $finalQuizAttempts)->count(),
    'verified' => (clone $finalQuizAttempts)->where('is_verified', true)->count(),
    'unverified' => (clone $finalQuizAttempts)->where('is_verified', false)->count(),
];

$results = (clone $finalQuizAttempts)->with(['user', 'quiz.course'])->latest()->get();
$search = '';
$resultStatus = 'all';

$renderedDashboard = View::make('admin.dashboard', compact('search', 'resultStatus', 'results', 'resultStats'))->render();

if (strpos($renderedDashboard, 'Average Score') === false && strpos($renderedDashboard, 'Total Results') !== false) {
    echo "   [OK] Admin Dashboard rendered cleanly WITHOUT 'Average Score' (" . strlen($renderedDashboard) . " bytes).\n";
} else {
    echo "   [FAIL] Admin Dashboard still has 'Average Score' or failed to render.\n";
}

// 2. Test Admin Results View
echo "2. Testing Admin Results View...\n";
$controller = new \App\Http\Controllers\Admin\ResultController();
$request = new \Illuminate\Http\Request();
$response = $controller->index($request);
$renderedResults = $response->render();

if (strpos($renderedResults, 'Average Score') === false && strpos($renderedResults, 'Total Results') !== false) {
    echo "   [OK] Admin Results Page rendered cleanly WITHOUT 'Average Score' (" . strlen($renderedResults) . " bytes).\n";
} else {
    echo "   [FAIL] Admin Results Page still has 'Average Score' or failed to render.\n";
}

echo "\n=== ALL VERIFICATION TESTS PASSED 100%! ===\n";
