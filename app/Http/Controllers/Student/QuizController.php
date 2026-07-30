<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\LearningActivityLog;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Services\CourseProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function show(Quiz $quiz)
    {
        $user = Auth::user();

        $isEnrolled = DB::table('enrollments')
            ->where('user_id', $user->id)
            ->where('course_id', $quiz->course_id)
            ->exists();

        abort_unless($isEnrolled, 403, 'Kamu tidak memiliki akses ke quiz ini.');

        // Cek apakah quiz masih dalam waktu yang tersedia
        if (! $quiz->isAvailable()) {
            return redirect()
                ->back()
                ->with('error', 'Quiz ini tidak tersedia. ' . 
                    ($quiz->start_date && now()->lt($quiz->start_date) 
                        ? 'Quiz belum dibuka (mulai: ' . $quiz->start_date->format('d M Y H:i') . ').' 
                        : 'Quiz sudah ditutup (berakhir: ' . $quiz->end_date->format('d M Y H:i') . ').'));
        }

        // Cek apakah masih ada sisa attempts
        if (! $quiz->canAttempt($user->id)) {
            return redirect()
                ->back()
                ->with('error', 'Kamu sudah mencapai batas maksimal percobaan (' . $quiz->max_attempts . 'x) untuk quiz ini.');
        }

        $quiz->load(['questions' => function ($query) {
            $query->where('status', 'approved');
        }]);

        $remainingAttempts = $quiz->remainingAttempts($user->id);

        LearningActivityLog::create([
            'user_id' => $user->id,
            'course_id' => $quiz->course_id,
            'quiz_id' => $quiz->id,
            'activity_type' => 'start_quiz',
            'activity_value' => 1,
            'occurred_at' => now(),
        ]);

        return view('student.quiz.show', compact('quiz', 'remainingAttempts'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $user = Auth::user();

        $isEnrolled = DB::table('enrollments')
            ->where('user_id', $user->id)
            ->where('course_id', $quiz->course_id)
            ->exists();

        abort_unless($isEnrolled, 403, 'Kamu tidak memiliki akses ke quiz ini.');

        // Re-check availability and attempts
        if (! $quiz->isAvailable()) {
            return redirect()
                ->back()
                ->with('error', 'Quiz ini sudah tidak tersedia.');
        }

        if (! $quiz->canAttempt($user->id)) {
            return redirect()
                ->back()
                ->with('error', 'Kamu sudah mencapai batas maksimal percobaan untuk quiz ini.');
        }

        $questions = $quiz->questions()
            ->where('status', 'approved')
            ->get();

        if ($questions->count() === 0) {
            return redirect()
                ->back()
                ->with('error', 'Quiz belum memiliki soal.');
        }

        // Validate MC answers
        $validationRules = [];
        foreach ($questions as $question) {
            $validationRules["answers.{$question->id}"] = ['nullable', 'in:A,B,C,D'];
        }

        if (! empty($validationRules)) {
            $request->validate($validationRules);
        }

        $correctCount = 0;

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            'score' => 0,
            'is_verified' => false,
            'completed_at' => now(),
        ]);

        foreach ($questions as $question) {
            $answer = $request->input("answers.{$question->id}");
            $isCorrect = $answer === $question->correct_answer;

            if ($isCorrect) {
                $correctCount++;
            }

            QuizAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $question->id,
                'user_id' => $user->id,
                'selected_option' => $answer,
                'answer_text' => null,
                'is_correct' => $isCorrect,
                'score' => $isCorrect ? 1 : 0,
                'feedback' => null,
            ]);
        }

        $finalScore = $questions->count() > 0
            ? round(($correctCount / $questions->count()) * 100, 2)
            : 0;

        // Full MC = auto-verified
        $attempt->update([
            'score' => $finalScore,
            'is_verified' => true,
        ]);

        app(CourseProgressService::class)->recalculate(
            $user->id,
            $quiz->course_id
        );

        LearningActivityLog::create([
            'user_id' => $user->id,
            'course_id' => $quiz->course_id,
            'quiz_id' => $quiz->id,
            'activity_type' => 'finish_quiz',
            'activity_value' => $finalScore,
            'metadata' => [
                'quiz_attempt_id' => $attempt->id,
                'total_questions' => $questions->count(),
                'correct_count' => $correctCount,
            ],
            'occurred_at' => now(),
        ]);

        return view('student.quiz.result', [
            'score' => $finalScore,
            'quiz' => $quiz,
            'attempt' => $attempt,
            'correctCount' => $correctCount,
            'totalQuestions' => $questions->count(),
        ]);
    }
}
