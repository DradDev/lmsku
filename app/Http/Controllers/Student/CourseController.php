<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $courses = Course::whereIn('id', function ($query) use ($user) {
                $query->select('course_id')
                    ->from('enrollments')
                    ->where('user_id', $user->id);
            })
            ->with(['materials', 'quizzes.questions', 'user'])
            ->latest()
            ->get();

        foreach ($courses as $course) {
            $course->progress = 0;
            $course->is_completed = false;
            $course->can_get_certificate = false;

            $finalQuiz = $course->quizzes->firstWhere('is_final', true);

            if ($finalQuiz) {
                $approvedQuestions = $finalQuiz->questions->where('status', 'approved');

                $onlyMultipleChoice = $approvedQuestions->count() > 0 &&
                    $approvedQuestions->every(function ($question) {
                        return $question->question_type === 'multiple_choice';
                    });

                $verifiedAttempt = QuizAttempt::where('user_id', $user->id)
                    ->where('quiz_id', $finalQuiz->id)
                    ->where('is_verified', true)
                    ->orderByDesc('score')
                    ->first();

                if ($verifiedAttempt && $verifiedAttempt->score >= 70 && $onlyMultipleChoice) {
                    $course->can_get_certificate = true;
                }
            }
        }

        return view('student.courses.index', compact('courses'));
    }

    public function show(Course $course)
    {
        $user = Auth::user();

        $isEnrolled = DB::table('enrollments')
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        abort_unless($isEnrolled, 403, 'Kamu tidak terdaftar di course ini.');

        $course->load(['materials', 'quizzes.questions', 'user']);

        $finalQuiz = $course->quizzes->firstWhere('is_final', true);
        $verifiedFinalAttempt = null;
        $canDownloadCertificate = false;
        $certificateStatusText = 'Certificate belum tersedia karena final quiz belum ditentukan.';

        if ($finalQuiz) {
            $approvedQuestions = $finalQuiz->questions->where('status', 'approved');

            $onlyMultipleChoice = $approvedQuestions->count() > 0 &&
                $approvedQuestions->every(function ($question) {
                    return $question->question_type === 'multiple_choice';
                });

            $verifiedFinalAttempt = QuizAttempt::where('user_id', $user->id)
                ->where('quiz_id', $finalQuiz->id)
                ->where('is_verified', true)
                ->orderByDesc('score')
                ->first();

            if (!$onlyMultipleChoice) {
                $certificateStatusText = 'Final quiz untuk certificate harus berisi multiple choice saja.';
            } elseif (!$verifiedFinalAttempt) {
                $certificateStatusText = 'Kerjakan final quiz dan tunggu verifikasi admin untuk membuka certificate.';
            } elseif ($verifiedFinalAttempt->score < 70) {
                $certificateStatusText = 'Nilai final quiz minimal 70 untuk membuka certificate.';
            } else {
                $canDownloadCertificate = true;
                $certificateStatusText = 'Certificate sudah tersedia untuk diunduh.';
            }
        }

        return view('student.courses.show', compact(
            'course',
            'finalQuiz',
            'verifiedFinalAttempt',
            'canDownloadCertificate',
            'certificateStatusText'
        ));
    }
}
