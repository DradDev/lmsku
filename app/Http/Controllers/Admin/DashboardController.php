<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $resultStatus = $request->input('result_status', 'all');
        $search = trim($request->input('search', ''));

        // Query to get results — hanya Final Quiz (dasar sertifikat & blockchain)
        $resultsQuery = QuizAttempt::whereHas('quiz', function ($query) {
                $query->where('is_final', true);
            })
            ->with(['user', 'quiz.course'])
            ->latest();

        if ($resultStatus === 'verified') {
            $resultsQuery->where('is_verified', true);
        } elseif ($resultStatus === 'unverified') {
            $resultsQuery->where('is_verified', false);
        }

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

        $results = $resultsQuery->get();

        // Stats for results — hanya Final Quiz
        $finalQuizAttempts = QuizAttempt::whereHas('quiz', function ($query) {
            $query->where('is_final', true);
        });

        $resultStats = [
            'total' => (clone $finalQuizAttempts)->count(),
            'verified' => (clone $finalQuizAttempts)->where('is_verified', true)->count(),
            'unverified' => (clone $finalQuizAttempts)->where('is_verified', false)->count(),
            'average_score' => round((float) (clone $finalQuizAttempts)->avg('score')),
        ];

        return view('admin.dashboard', compact(
            'search',
            'resultStatus',
            'results',
            'resultStats'
        ));
    }

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
            ->route('admin.dashboard')
            ->with('success', 'Hasil mahasiswa berhasil diverifikasi.');
    }
}