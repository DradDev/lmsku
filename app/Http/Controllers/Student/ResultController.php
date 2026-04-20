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
            ->where('is_verified', true)
            ->latest()
            ->get();

        return view('student.results.index', compact('results'));
    }

    public function show(QuizAttempt $result)
    {
        abort_unless($result->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke hasil ini.');
        abort_unless($result->is_verified, 403, 'Hasil quiz ini belum diverifikasi admin.');

        $result->load(['quiz.course']);

        return view('student.results.show', compact('result'));
    }
}
