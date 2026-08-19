<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuizAnswerController extends Controller
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

        $essayAnswers = QuizAnswer::query()
            ->with(['user', 'question', 'attempt'])
            ->whereHas('attempt', function ($query) use ($quiz) {
                $query->where('quiz_id', $quiz->id);
            })
            ->whereHas('question', function ($query) {
                $query->where('question_type', 'essay');
            })
            ->latest()
            ->get();

        return view('lecturer.quiz.essay_answers', compact('course', 'quiz', 'essayAnswers'));
    }

    public function grade(Request $request, Course $course, Quiz $quiz, QuizAnswer $quizAnswer): RedirectResponse
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

        $quizAnswer->load(['attempt.answers.question', 'question']);

        abort_unless(
            $quizAnswer->attempt && $quizAnswer->attempt->quiz_id === $quiz->id,
            403,
            'Jawaban quiz tidak valid.'
        );

        abort_unless(
            $quizAnswer->question && $quizAnswer->question->question_type === 'essay',
            403,
            'Hanya jawaban essay yang bisa dinilai manual.'
        );

        $validated = $request->validate([
            'score' => ['required', 'numeric', 'min:0', 'max:100'],
            'feedback' => ['nullable', 'string'],
        ]);

        $quizAnswer->update([
            'score' => $validated['score'],
            'feedback' => $validated['feedback'] ?? null,
        ]);

        $attempt = $quizAnswer->attempt->fresh(['answers.question']);
        $answers = $attempt->answers;
        $totalQuestions = $answers->count();

        $points = 0;

        foreach ($answers as $answer) {
            if (!$answer->question) {
                continue;
            }

            if ($answer->question->question_type === 'multiple_choice') {
                $points += (int) ($answer->score ?? 0);
            } else {
                $points += is_null($answer->score) ? 0 : ((float) $answer->score / 100);
            }
        }

        $finalScore = $totalQuestions > 0
            ? round(($points / $totalQuestions) * 100, 2)
            : 0;

        $allEssayGraded = $answers
            ->filter(function ($answer) {
                return $answer->question && $answer->question->question_type === 'essay';
            })
            ->every(function ($answer) {
                return !is_null($answer->score);
            });

        $attempt->update([
            'score' => $finalScore,
            'is_verified' => $allEssayGraded,
        ]);

        // Akumulasi otomatis profil kompetensi skill mahasiswa saat essay dinilai
        \Illuminate\Support\Facades\Artisan::call('ai:calculate-user-skill-profiles', [
            '--user_id' => $attempt->user_id,
        ]);

        return back()->with('success', 'Jawaban essay berhasil dinilai.');
    }
}
