<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ResultController extends Controller
{
    public function index(): View
    {
        $results = QuizAttempt::query()
            ->with(['user', 'quiz.course'])
            ->latest()
            ->get();

        $totalResults = QuizAttempt::count();
        $verifiedCount = QuizAttempt::where('is_verified', true)->count();
        $pendingCount = QuizAttempt::where('is_verified', false)->count();
        $averageScore = round((float) QuizAttempt::avg('score'), 2);

        return view('admin.results.index', compact(
            'results',
            'totalResults',
            'verifiedCount',
            'pendingCount',
            'averageScore'
        ));
    }

    public function show(QuizAttempt $result): View
    {
        $result->load([
            'user',
            'quiz.course',
            'answers.question',
        ]);

        return view('admin.results.show', compact('result'));
    }

    public function verify(QuizAttempt $result): RedirectResponse
    {
        if ($result->is_verified) {
            return redirect()
                ->route('admin.results.show', $result->id)
                ->with('success', 'Result ini sudah diverifikasi sebelumnya.');
        }

        $result->update([
            'is_verified' => true,
            'blockchain_hash' => hash(
                'sha256',
                $result->id . '|' .
                $result->user_id . '|' .
                $result->quiz_id . '|' .
                $result->score . '|' .
                now()->timestamp
            ),
        ]);

        return redirect()
            ->route('admin.results.show', $result->id)
            ->with('success', 'Result berhasil diverifikasi.');
    }
}
