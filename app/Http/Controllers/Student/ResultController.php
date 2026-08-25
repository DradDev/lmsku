<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $results = QuizAttempt::with(['quiz.quizzable'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $resultsByCourse = $results->groupBy(function ($result) {
            return $result->quiz->course->name ?? 'Tanpa Course';
        });

        $joinedProjects = $user->joinedProjects()
            ->with(['skills', 'tags', 'user'])
            ->wherePivot('status', 'accepted')
            ->latest()
            ->get();

        return view('student.results.index', compact('results', 'resultsByCourse', 'joinedProjects'));
    }

    public function show(QuizAttempt $result)
    {
        abort_unless($result->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke hasil ini.');

        $result->load(['quiz.quizzable', 'answers.question']);

        return view('student.results.show', compact('result'));
    }
}