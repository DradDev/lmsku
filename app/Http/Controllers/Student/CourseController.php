<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LearningActivityLog;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Services\CourseProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 3NF CourseOfferings yang dipublish di semester aktif
        $offerings = \App\Models\CourseOffering::with(['masterCourse.category', 'academicTerm', 'lecturer', 'materials', 'quizzes.questions'])
            ->where('status', 'published')
            ->whereHas('academicTerm', function ($query) {
                $query->where('is_active', true);
            })
            ->withCount('enrollments')
            ->latest()
            ->get();

        $enrollments = Enrollment::where('user_id', $user->id)->get();
        $enrolledOfferingIds = $enrollments->pluck('course_offering_id')->filter()->toArray();
        $enrolledCourseIds = $enrollments->pluck('course_id')->filter()->toArray();

        // Group offerings by master_course_id
        $groupedOfferings = $offerings->groupBy('master_course_id');
        $groupedCourses = collect();

        foreach ($groupedOfferings as $masterId => $offeringGroup) {
            $firstOffering = $offeringGroup->first();
            $masterCourse = $firstOffering->masterCourse;

            // Check if user is enrolled in any offering of this master course
            $userEnrollmentInGroup = $enrollments->first(function ($e) use ($offeringGroup) {
                return in_array($e->course_offering_id, $offeringGroup->pluck('id')->toArray());
            });

            $enrolledOffering = $userEnrollmentInGroup
                ? $offeringGroup->firstWhere('id', $userEnrollmentInGroup->course_offering_id)
                : null;

            $activeOffering = $enrolledOffering ?? $firstOffering;

            $progress = $userEnrollmentInGroup?->progress_percent ?? 0;
            $isCompleted = $userEnrollmentInGroup?->status === 'completed';
            $isEnrolled = (bool) $userEnrollmentInGroup;

            $finalQuiz = $activeOffering->quizzes->firstWhere('quiz_type', 'final');
            $canGetCertificate = false;

            if ($finalQuiz && $isEnrolled) {
                $approvedQuestions = $finalQuiz->questions->where('status', 'approved');
                $onlyMultipleChoice = $approvedQuestions->count() > 0 &&
                    $approvedQuestions->every(fn($q) => $q->question_type === 'multiple_choice');

                $verifiedAttempt = QuizAttempt::where('user_id', $user->id)
                    ->where('quiz_id', $finalQuiz->id)
                    ->where('is_verified', true)
                    ->orderByDesc('score')
                    ->first();

                if ($verifiedAttempt && $verifiedAttempt->score >= ($activeOffering->certificate_threshold ?? 70) && $onlyMultipleChoice) {
                    $canGetCertificate = true;
                }
            }

            $groupedCourses->push((object)[
                'master_course' => $masterCourse,
                'offerings' => $offeringGroup,
                'active_offering' => $activeOffering,
                'enrolled_offering' => $enrolledOffering,
                'is_enrolled' => $isEnrolled,
                'progress' => $progress,
                'is_completed' => $isCompleted,
                'can_get_certificate' => $canGetCertificate,
            ]);
        }

        $authors = User::whereIn('id', $offerings->pluck('lecturer_id')->filter()->unique())
            ->orderBy('name')
            ->get();

        return view('student.courses.index', compact('groupedCourses', 'enrolledCourseIds', 'enrolledOfferingIds', 'authors'));
    }

    public function show(string $id)
    {
        $user = Auth::user();

        // 1. Coba di CourseOffering (3NF)
        $offering = \App\Models\CourseOffering::with(['masterCourse', 'academicTerm', 'lecturer', 'materials', 'quizzes.questions'])
            ->find($id);

        if ($offering) {
            $enrollment = Enrollment::where('user_id', $user->id)
                ->where('course_offering_id', $offering->id)
                ->first();

            abort_unless($enrollment, 403, 'Kamu tidak terdaftar di kelas penawaran ini.');

            $course = $offering; // Magic accessors handle backward compatibility!

            $this->logActivity(
                activityType: 'view_course',
                courseId: $offering->master_course_id
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

                $threshold = $offering->certificate_threshold ?? 60;

                if (! $onlyMultipleChoice) {
                    $certificateStatusText = 'Final quiz untuk certificate harus berisi multiple choice saja.';
                } elseif (! $verifiedFinalAttempt) {
                    $certificateStatusText = 'Kerjakan final quiz dan tunggu verifikasi admin untuk membuka certificate.';
                } elseif ($verifiedFinalAttempt->score < $threshold) {
                    $certificateStatusText = "Nilai final quiz minimal {$threshold} untuk membuka certificate.";
                } else {
                    $canDownloadCertificate = true;
                    $certificateStatusText = 'Certificate sudah tersedia untuk diunduh.';
                }
            }

            $retakeRequest = null;
            if ($finalQuiz) {
                $retakeRequest = \App\Models\QuizRetakeRequest::where('user_id', $user->id)
                    ->where('quiz_id', $finalQuiz->id)
                    ->latest()
                    ->first();
            }

            $isReadOnly = $offering->isExpired() || $offering->status === 'cancelled';

            return view('student.courses.show', compact(
                'course',
                'enrollment',
                'finalQuiz',
                'verifiedFinalAttempt',
                'canDownloadCertificate',
                'certificateStatusText',
                'isReadOnly',
                'retakeRequest'
            ));
        }

        // 2. Fallback ke legacy Course
        $course = Course::findOrFail($id);

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

        $retakeRequest = null;
        if ($finalQuiz) {
            $retakeRequest = \App\Models\QuizRetakeRequest::where('user_id', $user->id)
                ->where('quiz_id', $finalQuiz->id)
                ->latest()
                ->first();
        }

        $isReadOnly = $course->isExpired() || $course->is_archived;

        return view('student.courses.show', compact(
            'course',
            'enrollment',
            'finalQuiz',
            'verifiedFinalAttempt',
            'canDownloadCertificate',
            'certificateStatusText',
            'isReadOnly',
            'retakeRequest'
        ));
    }

    public function enroll(string $id): RedirectResponse
    {
        $user = Auth::user();

        // 1. Coba enroll di CourseOffering (3NF)
        $offering = \App\Models\CourseOffering::with(['masterCourse', 'materials', 'quizzes'])->find($id);

        if ($offering) {
            $alreadyEnrolled = Enrollment::where('user_id', $user->id)
                ->where('course_offering_id', $offering->id)
                ->exists();

            if ($alreadyEnrolled) {
                return redirect()
                    ->route('student.courses.show', $offering->id)
                    ->with('success', 'Kamu sudah terdaftar di kelas ini.');
            }

            // CAPACITY CHECK: Kuota Mahasiswa
            if (! $offering->hasAvailableCapacity()) {
                return redirect()->back()->with('error', 'Pendaftaran gagal: Kelas penawaran ini sudah memenuhi kuota maksimum (' . $offering->capacity . ' mahasiswa).');
            }

            if ($offering->isExpired() || $offering->status === 'cancelled') {
                return redirect()->back()->with('error', 'Kelas ini tidak tersedia untuk pendaftaran baru karena sudah ditutup atau dibatalkan.');
            }

            Enrollment::create([
                'user_id' => $user->id,
                'course_offering_id' => $offering->id,
                'course_id' => $offering->master_course_id,
                'progress_percent' => 0,
                'completed_material_count' => 0,
                'completed_quiz_count' => 0,
                'total_material_count' => $offering->materials->count(),
                'total_quiz_count' => $offering->quizzes->count(),
                'status' => 'not_started',
                'started_at' => now(),
                'last_activity_at' => now(),
            ]);

            $this->logActivity(
                activityType: 'enroll_course',
                courseId: $offering->master_course_id
            );

            return redirect()
                ->route('student.courses.show', $offering->id)
                ->with('success', 'Enrollment berhasil! Selamat belajar.');
        }

        // 2. Fallback ke legacy Course
        $course = Course::findOrFail($id);

        $alreadyEnrolled = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        if ($alreadyEnrolled) {
            return redirect()
                ->route('student.courses.show', $course->id)
                ->with('success', 'Kamu sudah terdaftar di course ini.');
        }

        if ($course->isExpired() || $course->is_archived) {
            return redirect()->back()->with('error', 'Course ini tidak tersedia untuk enrollment baru.');
        }

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'progress_percent' => 0,
            'completed_material_count' => 0,
            'completed_quiz_count' => 0,
            'total_material_count' => $course->materials->count(),
            'total_quiz_count' => $course->quizzes->count(),
            'status' => 'not_started',
            'started_at' => now(),
            'last_activity_at' => now(),
        ]);

        $this->logActivity(
            activityType: 'enroll_course',
            courseId: $course->id
        );

        return redirect()
            ->route('student.courses.show', $course->id)
            ->with('success', 'Enrollment berhasil! Selamat belajar.');
    }

    private function logActivity(string $activityType, int $courseId): void
    {
        $validCourseId = Course::where('id', $courseId)->exists() ? $courseId : null;

        LearningActivityLog::create([
            'user_id' => Auth::id(),
            'course_id' => $validCourseId,
            'activity_type' => $activityType,
            'activity_value' => 1,
            'occurred_at' => now(),
        ]);
    }
}
