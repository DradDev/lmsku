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

        // Vendor Certification Courses
        $vendorCourses = Course::with(['user', 'category', 'materials', 'quizzes.questions', 'skills', 'tags', 'masterCourse'])
            ->whereHas('user', function ($query) {
                $query->where('role', 'vendor');
            })
            ->where('is_archived', false)
            ->latest()
            ->get();

        foreach ($vendorCourses as $vc) {
            $userEnrollment = $enrollments->firstWhere('course_id', $vc->id);
            $vc->is_enrolled = (bool) $userEnrollment;
            $vc->progress = $userEnrollment?->progress_percent ?? 0;
            $vc->is_completed = $userEnrollment?->status === 'completed';

            // Check certificate eligibility
            $finalQuiz = $vc->quizzes->firstWhere('quiz_type', 'final') ?? $vc->masterCourse?->quizzes->firstWhere('quiz_type', 'final');
            $canGetCertificate = false;

            if ($finalQuiz && $vc->is_enrolled) {
                $verifiedAttempt = QuizAttempt::where('user_id', $user->id)
                    ->where('quiz_id', $finalQuiz->id)
                    ->where('is_verified', true)
                    ->orderByDesc('score')
                    ->first();

                $threshold = $vc->certificate_threshold ?? 75;
                if ($verifiedAttempt && $verifiedAttempt->score >= $threshold) {
                    $canGetCertificate = true;
                }
            }
            $vc->can_get_certificate = $canGetCertificate;
        }

        return view('student.courses.index', compact('groupedCourses', 'vendorCourses', 'enrolledCourseIds', 'enrolledOfferingIds', 'authors'));
    }

    public function show(string $id)
    {
        $user = Auth::user();

        // 1. Coba di Course Vendor (Explicit Vendor Check)
        $vendorCourse = Course::with(['materials', 'quizzes.questions', 'user', 'masterCourse.materials', 'masterCourse.quizzes'])
            ->where('id', $id)
            ->whereHas('user', function ($query) {
                $query->where('role', 'vendor');
            })
            ->first();

        if ($vendorCourse) {
            $course = $vendorCourse;
            $enrollment = Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->first();

            abort_unless($enrollment, 403, 'Kamu tidak terdaftar di course sertifikasi vendor ini.');

            app(CourseProgressService::class)->recalculate($user->id, $course->id);

            $enrollment->refresh();

            $this->logActivity(
                activityType: 'view_course',
                courseId: $course->id
            );

            // Combined 3NF materials & quizzes
            if ($course->masterCourse) {
                $combinedMaterials = $course->materials->merge($course->masterCourse->materials ?? collect())->unique('id');
                $combinedQuizzes = $course->quizzes->merge($course->masterCourse->quizzes ?? collect())->unique('id');
                $course->setRelation('materials', $combinedMaterials);
                $course->setRelation('quizzes', $combinedQuizzes);
            }

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

                $threshold = $course->certificate_threshold ?? 75;

                if (! $onlyMultipleChoice) {
                    $certificateStatusText = 'Final quiz untuk certificate harus berisi multiple choice saja.';
                } elseif (! $verifiedFinalAttempt) {
                    $certificateStatusText = 'Kerjakan final quiz untuk membuka certificate.';
                } elseif ($verifiedFinalAttempt->score < $threshold) {
                    $certificateStatusText = "Nilai final quiz minimal {$threshold}% untuk membuka certificate.";
                } else {
                    $canDownloadCertificate = true;
                    $certificateStatusText = 'Certificate Sertifikasi Industri sudah tersedia untuk diunduh.';
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

        // 2. Coba di CourseOffering (3NF Academic)
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

        // 3. Fallback ke legacy Course
        $course = Course::findOrFail($id);

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        abort_unless($enrollment, 403, 'Kamu tidak terdaftar di course sertifikasi ini.');

        app(CourseProgressService::class)->recalculate($user->id, $course->id);

        $enrollment->refresh();

        $this->logActivity(
            activityType: 'view_course',
            courseId: $course->id
        );

        // Combined 3NF materials & quizzes
        if ($course->masterCourse) {
            $combinedMaterials = $course->materials->merge($course->masterCourse->materials ?? collect())->unique('id');
            $combinedQuizzes = $course->quizzes->merge($course->masterCourse->quizzes ?? collect())->unique('id');
            $course->setRelation('materials', $combinedMaterials);
            $course->setRelation('quizzes', $combinedQuizzes);
        }

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

            $threshold = $course->certificate_threshold ?? 75;

            if (! $onlyMultipleChoice) {
                $certificateStatusText = 'Final quiz untuk certificate harus berisi multiple choice saja.';
            } elseif (! $verifiedFinalAttempt) {
                $certificateStatusText = 'Kerjakan final quiz untuk membuka certificate.';
            } elseif ($verifiedFinalAttempt->score < $threshold) {
                $certificateStatusText = "Nilai final quiz minimal {$threshold}% untuk membuka certificate.";
            } else {
                $canDownloadCertificate = true;
                $certificateStatusText = 'Certificate Sertifikasi Industri sudah tersedia untuk diunduh.';
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

        // 1. Explicit Vendor Course Enrollment Check
        $vendorCourse = Course::where('id', $id)
            ->whereHas('user', fn($q) => $q->where('role', 'vendor'))
            ->first();

        if ($vendorCourse) {
            $alreadyEnrolled = Enrollment::where('user_id', $user->id)
                ->where('course_id', $vendorCourse->id)
                ->exists();

            if ($alreadyEnrolled) {
                return redirect()
                    ->route('student.courses.show', $vendorCourse->id)
                    ->with('success', 'Kamu sudah terdaftar di course sertifikasi vendor ini.');
            }

            if ($vendorCourse->is_archived) {
                return redirect()->back()->with('error', 'Course sertifikasi vendor ini tidak sedang menerima pendaftaran baru.');
            }

            Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $vendorCourse->id,
                'progress_percent' => 0,
                'completed_material_count' => 0,
                'completed_quiz_count' => 0,
                'total_material_count' => $vendorCourse->materials->count(),
                'total_quiz_count' => $vendorCourse->quizzes->count(),
                'status' => 'not_started',
                'started_at' => now(),
                'last_activity_at' => now(),
            ]);

            $this->logActivity(
                activityType: 'enroll_course',
                courseId: $vendorCourse->id
            );

            return redirect()
                ->route('student.courses.show', $vendorCourse->id)
                ->with('success', 'Enrollment berhasil! Selamat mengikuti Sertifikasi Industri.');
        }

        // 2. Coba enroll di CourseOffering (3NF Academic)
        $offering = \App\Models\CourseOffering::with(['masterCourse', 'materials', 'quizzes'])->find($id);

        // Jika $id bukan CourseOffering ID langsung, periksa apakah $id merupakan master_course_id
        if (! $offering) {
            $masterOfferings = \App\Models\CourseOffering::where('master_course_id', $id)
                ->where('status', 'published')
                ->whereHas('academicTerm', function ($query) {
                    $query->where('is_active', true);
                })
                ->get();

            if ($masterOfferings->count() > 0) {
                // Cari rombel yang masih memiliki kuota
                $offering = $masterOfferings->first(fn($o) => $o->hasAvailableCapacity());

                if (! $offering) {
                    return redirect()
                        ->back()
                        ->with('error', 'Pendaftaran gagal: Seluruh rombel kelas untuk mata kuliah ini sudah memenuhi kuota maksimum (kuota habis).');
                }
            }
        }

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
                return redirect()->back()->with('error', 'Pendaftaran gagal: Rombel ' . $offering->section_name . ' sudah memenuhi kuota maksimum (' . $offering->capacity . ' mahasiswa).');
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
