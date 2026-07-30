<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LearningActivityLog;
use App\Models\QuizAttempt;
use App\Services\CourseProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $courses = Course::with(['materials', 'quizzes.questions', 'user'])
            ->active()
            ->withCount('students')
            ->latest()
            ->get();

        $enrollments = Enrollment::where('user_id', $user->id)
            ->get()
            ->keyBy('course_id');

        $enrolledCourseIds = $enrollments->keys()->toArray();

        foreach ($courses as $course) {
            $enrollment = $enrollments->get($course->id);

            $course->progress = $enrollment?->progress_percent ?? 0;
            $course->is_completed = $enrollment?->status === 'completed';
            $course->enrollment_status = $enrollment?->status;
            $course->can_get_certificate = false;

            $finalQuiz = $course->quizzes->firstWhere('quiz_type', 'final');

            if ($finalQuiz && in_array($course->id, $enrolledCourseIds)) {
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

        return view('student.courses.index', compact('courses', 'enrolledCourseIds'));
    }

    public function show(Course $course)
    {
        $user = Auth::user();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        abort_unless($enrollment, 403, 'Kamu tidak terdaftar di course ini.');

        app(CourseProgressService::class)->recalculate($user->id, $course->id);

        $enrollment->refresh();

        $course->load(['materials', 'quizzes.questions', 'user']);

        $this->logActivity(
            activityType: 'view_course',
            courseId: $course->id
        );

        $finalQuiz = $course->quizzes->firstWhere('quiz_type', 'final');
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

            if (! $onlyMultipleChoice) {
                $certificateStatusText = 'Final quiz untuk certificate harus berisi multiple choice saja.';
            } elseif (! $verifiedFinalAttempt) {
                $certificateStatusText = 'Kerjakan final quiz dan tunggu verifikasi admin untuk membuka certificate.';
            } elseif ($verifiedFinalAttempt->score < 70) {
                $certificateStatusText = 'Nilai final quiz minimal 70 untuk membuka certificate.';
            } else {
                $canDownloadCertificate = true;
                $certificateStatusText = 'Certificate sudah tersedia untuk diunduh.';
            }
        }

        $isReadOnly = $course->isExpired() || $course->is_archived;

        return view('student.courses.show', compact(
            'course',
            'enrollment',
            'finalQuiz',
            'verifiedFinalAttempt',
            'canDownloadCertificate',
            'certificateStatusText',
            'isReadOnly'
        ));
    }

    public function enroll(Course $course): RedirectResponse
    {
        $user = Auth::user();

        $alreadyEnrolled = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        if ($alreadyEnrolled) {
            return redirect()
                ->route('student.courses.show', $course)
                ->with('success', 'Kamu sudah terdaftar di course ini.');
        }

        if ($course->isExpired() || $course->is_archived) {
            return redirect()->back()->with('error', 'Course ini tidak tersedia untuk pendaftaran baru karena sudah ditutup atau diarsipkan.');
        }

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'progress_percent' => 0,
            'completed_material_count' => 0,
            'completed_quiz_count' => 0,
            'total_material_count' => $course->materials()->count(),
            'total_quiz_count' => $course->quizzes()->count(),
            'status' => 'not_started',
            'started_at' => now(),
            'last_activity_at' => now(),
        ]);

        $this->logActivity(
            activityType: 'enroll_course',
            courseId: $course->id
        );

        app(CourseProgressService::class)->recalculate($user->id, $course->id);

        return redirect()
            ->route('student.courses.show', $course)
            ->with('success', 'Course berhasil diambil.');
    }

    private function logActivity(
        string $activityType,
        ?int $courseId = null,
        ?int $materialId = null,
        ?int $quizId = null,
        float|int $activityValue = 1,
        ?array $metadata = null
    ): void {
        LearningActivityLog::create([
            'user_id' => Auth::id(),
            'course_id' => $courseId,
            'material_id' => $materialId,
            'quiz_id' => $quizId,
            'activity_type' => $activityType,
            'activity_value' => $activityValue,
            'metadata' => $metadata,
            'occurred_at' => now(),
        ]);
    }
}
