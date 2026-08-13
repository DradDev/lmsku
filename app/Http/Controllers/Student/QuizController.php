<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
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
            ->where(function ($query) use ($quiz) {
                $query->where('course_id', $quiz->course_id)
                    ->orWhere('course_id', $quiz->master_course_id)
                    ->orWhereIn('course_offering_id', function ($sub) use ($quiz) {
                        $sub->select('id')->from('course_offerings')
                            ->where('master_course_id', $quiz->master_course_id);
                    });
            })
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
            'course_id' => $quiz->master_course_id ?? $quiz->course_id,
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

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where(function ($query) use ($quiz) {
                $query->where('course_id', $quiz->course_id)
                    ->orWhere('course_id', $quiz->master_course_id)
                    ->orWhereIn('course_offering_id', function ($sub) use ($quiz) {
                        $sub->select('id')->from('course_offerings')
                            ->where('master_course_id', $quiz->master_course_id);
                    });
            })
            ->latest()
            ->first();

        abort_unless($enrollment, 403, 'Kamu tidak memiliki akses ke quiz ini.');

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

        if ($quiz->course_id) {
            app(CourseProgressService::class)->recalculate(
                $user->id,
                $quiz->course_id
            );
        }

        LearningActivityLog::create([
            'user_id' => $user->id,
            'course_id' => $quiz->master_course_id ?? $quiz->course_id,
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

        // Handles Certificate creation for Final Quiz if score >= threshold
        $certificate = null;
        if ($quiz->isFinal()) {
            $offering = $enrollment->courseOffering;
            $threshold = $offering?->certificate_threshold ?? ($quiz->course->certificate_threshold ?? 60);

            if ($finalScore >= $threshold) {
                $certificate = \App\Models\Certificate::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'course_offering_id' => $enrollment->course_offering_id,
                        'course_id' => $quiz->master_course_id ?? $quiz->course_id,
                    ],
                    [
                        'score' => $finalScore,
                        'status' => 'pending',
                        'is_verified' => false,
                        'completed_at' => now(),
                    ]
                );

                if ($finalScore > $certificate->score) {
                    $certificate->update(['score' => $finalScore]);
                }
            }
        }

        // Akumulasi otomatis profil kompetensi skill mahasiswa dari hasil kuis
        if ($quiz->course && $quiz->course->skills) {
            foreach ($quiz->course->skills as $skill) {
                \App\Models\UserSkillProfile::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'skill_id' => $skill->id,
                    ],
                    [
                        'avg_score' => $finalScore,
                        'highest_score' => $finalScore,
                        'last_activity_at' => now(),
                        'last_calculated_at' => now(),
                    ]
                );
            }
        }

        \Illuminate\Support\Facades\Artisan::call('ai:calculate-user-skill-profiles', [
            '--user_id' => $user->id,
        ]);

        return view('student.quiz.result', [
            'score' => $finalScore,
            'quiz' => $quiz,
            'attempt' => $attempt,
            'certificate' => $certificate,
            'correctCount' => $correctCount,
            'totalQuestions' => $questions->count(),
        ]);
    }

    public function requestRetake(Quiz $quiz)
    {
        $user = Auth::user();

        $bestAttempt = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->orderByDesc('score')
            ->first();

        if (!$bestAttempt || $bestAttempt->score >= 70) {
            return redirect()->back()->with('error', 'Permintaan retake hanya berlaku jika Anda sudah mengikuti quiz dan nilai Anda di bawah 70.');
        }

        if ($quiz->canAttempt($user->id)) {
            return redirect()->back()->with('error', 'Anda masih memiliki sisa kesempatan untuk mengerjakan kuis ini.');
        }

        $existingRequest = \App\Models\QuizRetakeRequest::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('status', 'pending')
            ->exists();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'Permintaan retake Anda sudah terkirim dan sedang menunggu persetujuan Author.');
        }

        \App\Models\QuizRetakeRequest::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'course_id' => $quiz->course_id,
            'status' => 'pending',
            'reason' => 'Pengajuan ulang ujian karena nilai di bawah passing threshold (70).',
        ]);

        return redirect()->back()->with('success', 'Permintaan retake kuis berhasil dikirim ke Author. Mohon menunggu persetujuan.');
    }
}
