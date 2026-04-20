<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempt;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with results and questions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get input from request
        $tab = $request->input('tab', 'results');
        $resultStatus = $request->input('result_status', 'all');
        $questionStatus = $request->input('question_status', 'all');
        $search = trim($request->input('search', ''));

        // Query to get results
        $resultsQuery = QuizAttempt::with(['user', 'quiz.course'])->latest();

        // Filter by result status
        if ($resultStatus === 'verified') {
            $resultsQuery->where('is_verified', true);
        } elseif ($resultStatus === 'unverified') {
            $resultsQuery->where('is_verified', false);
        }

        // Search filter
        if ($search !== '') {
            $resultsQuery->where(function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('quiz.course', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Get results
        $results = $resultsQuery->get();

        // Query to get questions
        $questionsQuery = Question::with(['quiz.course', 'user'])->latest();

        // Filter by question status
        if ($questionStatus !== 'all') {
            $questionsQuery->where('status', $questionStatus);
        }

        // Search filter for questions
        if ($search !== '') {
            $questionsQuery->where(function ($query) use ($search) {
                $query->where('question', 'like', "%{$search}%")
                    ->orWhereHas('quiz', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    })
                    ->orWhereHas('quiz.course', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Get questions
        $questions = $questionsQuery->get();

        // Stats for results
        $resultStats = [
            'total' => QuizAttempt::count(),
            'verified' => QuizAttempt::where('is_verified', true)->count(),
            'unverified' => QuizAttempt::where('is_verified', false)->count(),
            'average_score' => round((float) QuizAttempt::avg('score')),
        ];

        // Stats for questions
        $questionStats = [
            'total' => Question::count(),
            'approved' => Question::where('status', 'approved')->count(),
            'pending' => Question::where('status', 'pending')->count(),
            'rejected' => Question::where('status', 'rejected')->count(),
        ];

        // Return view with all data
        return view('admin.dashboard', compact(
            'tab',
            'search',
            'resultStatus',
            'questionStatus',
            'results',
            'questions',
            'resultStats',
            'questionStats'
        ));
    }

    /**
     * Verify a result for the given quiz attempt.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyResult($id)
    {
        $attempt = QuizAttempt::findOrFail($id);

        if (!$attempt->is_verified) {
            $attempt->update([
                'is_verified' => true,
                'blockchain_hash' => '0x' . Str::upper(
                    substr(
                        hash('sha256', $attempt->id . '|' . $attempt->user_id . '|' . now()),
                        0,
                        16
                    )
                ),
            ]);
        }

        return redirect()
            ->route('admin.dashboard', ['tab' => 'results'])
            ->with('success', 'Hasil mahasiswa berhasil diverifikasi.');
    }
}