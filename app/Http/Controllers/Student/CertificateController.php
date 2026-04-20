<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\QuizAttempt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CertificateController extends Controller
{
    public function index(): View
    {
        $student = Auth::user();

        $courses = Course::whereIn('id', function ($query) use ($student) {
                $query->select('course_id')
                    ->from('enrollments')
                    ->where('user_id', $student->id);
            })
            ->with(['user', 'quizzes.questions'])
            ->latest()
            ->get();

        foreach ($courses as $course) {
            $course->can_get_certificate = false;
            $course->certificate_status_text = 'Certificate belum tersedia karena final quiz belum ditentukan.';
            $course->verified_final_attempt = null;
            $course->final_quiz = $course->quizzes->firstWhere('is_final', true);

            if ($course->final_quiz) {
                $approvedQuestions = $course->final_quiz->questions->where('status', 'approved');

                $onlyMultipleChoice = $approvedQuestions->count() > 0 &&
                    $approvedQuestions->every(function ($question) {
                        return $question->question_type === 'multiple_choice';
                    });

                $verifiedAttempt = QuizAttempt::where('user_id', $student->id)
                    ->where('quiz_id', $course->final_quiz->id)
                    ->where('is_verified', true)
                    ->orderByDesc('score')
                    ->first();

                $course->verified_final_attempt = $verifiedAttempt;

                if (!$onlyMultipleChoice) {
                    $course->certificate_status_text = 'Final quiz untuk certificate harus berisi multiple choice saja.';
                } elseif (!$verifiedAttempt) {
                    $course->certificate_status_text = 'Kerjakan final quiz dan tunggu verifikasi admin.';
                } elseif ($verifiedAttempt->score < 70) {
                    $course->certificate_status_text = 'Nilai final quiz minimal 70 untuk membuka certificate.';
                } else {
                    $course->can_get_certificate = true;
                    $course->certificate_status_text = 'Certificate siap dibuka dan diunduh.';
                }
            }
        }

        return view('student.certificates.index', compact('courses'));
    }

    public function show(Course $course): View
    {
        [$student, $finalQuiz, $attempt] = $this->resolveCertificateData($course);

        return view('student.certificate', compact('course', 'student', 'finalQuiz', 'attempt'));
    }

    public function download(Course $course): Response
    {
        [$student, $finalQuiz, $attempt] = $this->resolveCertificateData($course);

        $pdf = Pdf::loadView('student.certificate_pdf', compact('course', 'student', 'finalQuiz', 'attempt'))
            ->setPaper('a4', 'landscape');

        $filename = 'certificate-' . $course->id . '-' . $student->id . '.pdf';

        return $pdf->download($filename);
    }

    private function resolveCertificateData(Course $course): array
    {
        $student = Auth::user();

        $isEnrolled = DB::table('enrollments')
            ->where('user_id', $student->id)
            ->where('course_id', $course->id)
            ->exists();

        abort_unless($isEnrolled, 403, 'Kamu tidak terdaftar di course ini.');

        $course->load(['user', 'quizzes.questions']);

        $finalQuiz = $course->quizzes->firstWhere('is_final', true);

        abort_if(!$finalQuiz, 403, 'Certificate belum tersedia karena final quiz belum ditentukan.');

        $approvedQuestions = $finalQuiz->questions->where('status', 'approved');

        abort_if($approvedQuestions->count() === 0, 403, 'Certificate belum tersedia karena final quiz belum memiliki soal yang disetujui.');

        $hasEssay = $approvedQuestions->contains(function ($question) {
            return $question->question_type !== 'multiple_choice';
        });

        abort_if($hasEssay, 403, 'Final quiz untuk certificate harus berisi soal multiple choice saja.');

        $attempt = QuizAttempt::where('user_id', $student->id)
            ->where('quiz_id', $finalQuiz->id)
            ->where('is_verified', true)
            ->orderByDesc('score')
            ->first();

        abort_if(!$attempt, 403, 'Certificate belum tersedia. Selesaikan final quiz dan tunggu verifikasi admin.');
        abort_if($attempt->score < 70, 403, 'Certificate belum tersedia karena nilai final quiz masih di bawah 70.');

        return [$student, $finalQuiz, $attempt];
    }
}
