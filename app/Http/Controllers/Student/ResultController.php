<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function index()
    {
        $results = QuizAttempt::with(['quiz.course'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $resultsByCourse = $results->groupBy(function ($result) {
            return $result->quiz->course->name ?? 'Tanpa Course';
        });

        return view('student.results.index', compact('results', 'resultsByCourse'));
    }

    public function show(QuizAttempt $result)
    {
        abort_unless($result->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke hasil ini.');

        $result->load(['quiz.course', 'answers.question']);

        return view('student.results.show', compact('result'));
    }
}