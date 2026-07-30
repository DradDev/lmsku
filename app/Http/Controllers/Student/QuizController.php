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

        $quiz->load(['questions' => function ($query) {
            $query->where('status', 'approved');
        }]);

        LearningActivityLog::create([
            'user_id' => $user->id,
            'course_id' => $quiz->course_id,
            'quiz_id' => $quiz->id,
            'activity_type' => 'start_quiz',
            'activity_value' => 1,
            'occurred_at' => now(),
        ]);

        return view('student.quiz.show', compact('quiz'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $user = Auth::user();

        $isEnrolled = DB::table('enrollments')
            ->where('user_id', $user->id)
            ->where('course_id', $quiz->course_id)
            ->exists();

        abort_unless($isEnrolled, 403, 'Kamu tidak memiliki akses ke quiz ini.');

        $questions = $quiz->questions()
            ->where('status', 'approved')
            ->get();

        if ($questions->count() === 0) {
            return redirect()
                ->back()
                ->with('error', 'Quiz belum memiliki soal yang disetujui admin.');
        }

        $validationRules = [];
        $validationMessages = [];

        foreach ($questions as $question) {
            if ($question->question_type === 'essay') {
                $validationRules["answers.{$question->id}"] = ['required', 'string'];
                $validationMessages["answers.{$question->id}.required"] = 'Jawaban essay wajib diisi.';
            } elseif ($question->question_type === 'multiple_choice') {
                $validationRules["answers.{$question->id}"] = ['nullable', 'in:A,B,C,D,E'];
            }
        }

        if (! empty($validationRules)) {
            $request->validate($validationRules, $validationMessages);
        }

        $multipleChoiceQuestions = $questions->filter(function ($question) {
            return $question->question_type === 'multiple_choice';
        });

        $correctCount = 0;

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            'score' => 0,
            'is_verified' => false,
        ]);

        foreach ($questions as $question) {
            $answer = $request->input("answers.{$question->id}");

            if ($question->question_type === 'multiple_choice') {
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
            } else {
                QuizAnswer::create([
                    'quiz_attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'user_id' => $user->id,
                    'selected_option' => null,
                    'answer_text' => $answer,
                    'is_correct' => null,
                    'score' => null,
                    'feedback' => null,
                ]);
            }
        }

        $finalScore = $multipleChoiceQuestions->count() > 0
            ? round(($correctCount / $multipleChoiceQuestions->count()) * 100, 2)
            : 0;

        $attempt->update([
            'score' => $finalScore,
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
                'multiple_choice_count' => $multipleChoiceQuestions->count(),
                'correct_count' => $correctCount,
            ],
            'occurred_at' => now(),
        ]);
        $hasEssay = $questions->contains(function ($q) {
            return $q->question_type === 'essay';
        });

        // Kalau pure multiple choice, langsung auto-verify
        if (!$hasEssay) {
            $attempt->update(['is_verified' => true]);
        }

        return view('student.quiz.result', [
            'score'    => $finalScore,
            'quiz'     => $quiz,
            'attempt'  => $attempt,
            'hasEssay' => $hasEssay,
        ]);
    }
}
