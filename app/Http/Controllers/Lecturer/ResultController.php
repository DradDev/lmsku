<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ResultController extends Controller
{
    public function index(Course $course, Quiz $quiz): View
    {
        abort_unless(
            ($course->lecturer_id ?? $course->user_id) === Auth::id(),
            403,
            'Kamu tidak memiliki akses ke course ini.'
        );

        abort_unless(
            $quiz->master_course_id === $course->master_course_id,
            403,
            'Quiz tidak valid.'
        );

        $attempts = QuizAttempt::with('user')
            ->where('quiz_id', $quiz->id)
            ->latest()
            ->get();

        return view('lecturer.results.index', compact('course', 'quiz', 'attempts'));
    }

    public function show(Course $course, Quiz $quiz, QuizAttempt $result): View
    {
        abort_unless(
            ($course->lecturer_id ?? $course->user_id) === Auth::id(),
            403,
            'Kamu tidak memiliki akses ke course ini.'
        );

        abort_unless(
            $quiz->master_course_id === $course->master_course_id,
            403,
            'Quiz tidak valid.'
        );

        abort_unless(
            $result->quiz_id === $quiz->id,
            403,
            'Result tidak valid untuk quiz ini.'
        );

        $result->load(['user', 'quiz.course', 'answers.question']);

        return view('lecturer.results.show', compact('course', 'quiz', 'result'));
    }
}