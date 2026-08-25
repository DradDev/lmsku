<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\LearningActivityLog;
use App\Models\MasterCourse;
use App\Models\Material;
use App\Models\QuizAttempt;
use App\Models\Quiz;
use App\Models\User;
use App\Services\CourseProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 3NF CourseOfferings yang dipublish di semester aktif
        $offerings = \App\Models\CourseOffering::with(['masterCourse.skills', 'masterCourse.tags', 'academicTerm', 'lecturer', 'materials', 'quizzes.questions'])
            ->where('status', 'published')
            ->whereHas('academicTerm', function ($query) {
                $query->where('is_active', true);
            })
            ->withCount('enrollments')
            ->latest()
            ->get();

        $enrollments = Enrollment::where('user_id', $user->id)->get();
        $enrolledOfferingIds = $enrollments->pluck('course_offering_id')->filter()->toArray();
        $enrolledCourseIds = $enrolledOfferingIds;

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

        // Vendor Certification Courses (3NF CourseOfferings type=vendor)
        $vendorOfferings = \App\Models\CourseOffering::vendor()
            ->with(['lecturer.institution', 'masterCourse.materials', 'masterCourse.quizzes.questions', 'masterCourse.skills', 'masterCourse.tags'])
            ->where('is_archived', false)
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', 'published');
            })
            ->latest()
            ->get();

        $vendorCourses = $vendorOfferings->isNotEmpty() ? $vendorOfferings : Course::with(['user.institution', 'materials', 'quizzes.questions', 'skills', 'tags', 'masterCourse'])
            ->whereHas('user', function ($query) {
                $query->where('role', 'vendor');
            })
            ->where('is_archived', false)
            ->where(function ($query) {
                $query->whereNull('moderation_status')
                    ->orWhere('moderation_status', 'published');
            })
            ->latest()
            ->get();

        $vendorUserIds = $vendorCourses->map(fn($vc) => $vc->lecturer_id ?? $vc->user_id)->filter()->unique();
        $vendors = User::with('institution')
            ->whereIn('id', $vendorUserIds)
            ->orderBy('name')
            ->get();

        foreach ($vendorCourses as $vc) {
            $userEnrollment = $enrollments->first(function ($e) use ($vc) {
                return $e->course_offering_id == $vc->id;
            });
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

                $threshold = $vc->certificate_threshold ?? $vc->masterCourse?->certificate_threshold ?? 75;
                if ($verifiedAttempt && $verifiedAttempt->score >= $threshold) {
                    $canGetCertificate = true;
                }
            }
            $vc->can_get_certificate = $canGetCertificate;
        }

        $savedMaterials = \App\Models\SavedMaterial::where('user_id', $user->id)
            ->with(['material.masterCourse', 'courseOffering.masterCourse', 'courseOffering.lecturer'])
            ->latest()
            ->get();

        return view('student.courses.index', compact('groupedCourses', 'vendorCourses', 'enrolledCourseIds', 'enrolledOfferingIds', 'authors', 'vendors', 'savedMaterials'));
    }

    public function show(string $id): View
    {
        $user = Auth::user();

        // 1. Resolve CourseOffering (either direct ID or via master_course_id)
        $offering = CourseOffering::with([
            'masterCourse.materials',
            'masterCourse.quizzes.questions',
            'masterCourse.skills',
            'masterCourse.tags',
            'academicTerm',
            'lecturer.institution',
            'materials',
            'quizzes.questions',
        ])->find($id);

        if (! $offering) {
            $offering = CourseOffering::with([
                'masterCourse.materials',
                'masterCourse.quizzes.questions',
                'masterCourse.skills',
                'masterCourse.tags',
                'academicTerm',
                'lecturer.institution',
                'materials',
                'quizzes.questions',
            ])
            ->where('master_course_id', $id)
            ->where('status', 'published')
            ->first();
        }

        abort_unless($offering, 404, 'Kelas atau program kursus tidak ditemukan.');

        $course = $offering; // Magic accessors provide seamless compatibility

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_offering_id', $offering->id)
            ->first();

        if ($enrollment) {
            app(CourseProgressService::class)->recalculate($user->id, $offering->id);
            $enrollment->refresh();
        }

        $this->logActivity('view_course', $offering->id);

        // Combined polymorphic materials from Master Course & Offering
        $masterCourseId = $offering->master_course_id;
        $classMaterials = Material::where(function ($q) use ($offering, $masterCourseId) {
            $q->where(function ($sub) use ($masterCourseId) {
                $sub->where('materialable_type', MasterCourse::class)
                    ->where('materialable_id', $masterCourseId);
            })->orWhere(function ($sub) use ($offering) {
                $sub->where('materialable_type', CourseOffering::class)
                    ->where('materialable_id', $offering->id);
            });
        })->latest()->get();
        $course->setRelation('materials', $classMaterials);

        // Combined polymorphic quizzes from Master Course & Offering
        $classQuizzes = Quiz::where(function ($q) use ($offering, $masterCourseId) {
            $q->where(function ($sub) use ($masterCourseId) {
                $sub->where('quizzable_type', MasterCourse::class)
                    ->where('quizzable_id', $masterCourseId);
            })->orWhere(function ($sub) use ($offering) {
                $sub->where('quizzable_type', CourseOffering::class)
                    ->where('quizzable_id', $offering->id);
            });
        })->with('questions')->latest()->get();
        $course->setRelation('quizzes', $classQuizzes);

        $finalQuiz = $course->quizzes->firstWhere('quiz_type', 'final');
        $verifiedFinalAttempt = null;
        $canDownloadCertificate = false;
        $certificateStatusText = 'Certificate belum tersedia karena final quiz belum ditentukan.';

        if ($finalQuiz && $enrollment) {
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

            $threshold = $offering->certificate_threshold ?? $course->masterCourse?->certificate_threshold ?? 75;

            if (! $onlyMultipleChoice) {
                $certificateStatusText = 'Final quiz untuk certificate harus berisi multiple choice saja.';
            } elseif (! $verifiedFinalAttempt) {
                $certificateStatusText = 'Kerjakan final quiz untuk membuka certificate.';
            } elseif ($verifiedFinalAttempt->score < $threshold) {
                $certificateStatusText = "Nilai final quiz minimal {$threshold}% untuk membuka certificate.";
            } else {
                $canDownloadCertificate = true;
                $certificateStatusText = 'Certificate sudah tersedia untuk diunduh.';
            }
        } elseif (! $enrollment) {
            $certificateStatusText = 'Silakan ambil/daftar program ini untuk mengakses materi dan kuis.';
        }

        $retakeRequest = null;
        if ($finalQuiz && $enrollment) {
            $retakeRequest = \App\Models\QuizRetakeRequest::where('user_id', $user->id)
                ->where('quiz_id', $finalQuiz->id)
                ->latest()
                ->first();
        }

        $isReadOnly = $offering->isExpired() || $offering->is_archived || $offering->status === 'cancelled';

        $savedMaterialIds = \App\Models\SavedMaterial::where('user_id', $user->id)
            ->pluck('material_id')
            ->toArray();

        return view('student.courses.show', compact(
            'course',
            'enrollment',
            'finalQuiz',
            'verifiedFinalAttempt',
            'canDownloadCertificate',
            'certificateStatusText',
            'isReadOnly',
            'retakeRequest',
            'savedMaterialIds'
        ));
    }

    public function enroll(string $id): RedirectResponse
    {
        $user = Auth::user();

        // 1. Resolve CourseOffering (either direct ID or via master_course_id)
        $offering = CourseOffering::with(['masterCourse', 'materials', 'quizzes'])->find($id);

        if (! $offering) {
            $masterOfferings = CourseOffering::where('master_course_id', $id)
                ->where('status', 'published')
                ->where('is_archived', false)
                ->get();

            if ($masterOfferings->isNotEmpty()) {
                $offering = $masterOfferings->first(fn($o) => $o->hasAvailableCapacity()) ?? $masterOfferings->first();
            }
        }

        if (! $offering) {
            return redirect()->back()->with('error', 'Kelas atau program sertifikasi tidak ditemukan.');
        }

        $alreadyEnrolled = Enrollment::where('user_id', $user->id)
            ->where('course_offering_id', $offering->id)
            ->exists();

        if ($alreadyEnrolled) {
            return redirect()
                ->route('student.courses.show', $offering->id)
                ->with('success', 'Kamu sudah terdaftar di program ini.');
        }

        if (! $offering->hasAvailableCapacity()) {
            return redirect()->back()->with('error', 'Pendaftaran gagal: Kuota rombel ' . $offering->section_name . ' sudah penuh.');
        }

        if ($offering->isExpired() || $offering->status === 'draft' || $offering->is_archived) {
            return redirect()->back()->with('error', 'Program ini tidak menerima pendaftaran baru.');
        }

        Enrollment::create([
            'user_id'                  => $user->id,
            'course_offering_id'       => $offering->id,
            'progress_percent'         => 0,
            'completed_material_count' => 0,
            'completed_quiz_count'     => 0,
            'total_material_count'     => $offering->materials->count(),
            'total_quiz_count'         => $offering->quizzes->count(),
            'status'                   => 'not_started',
            'started_at'               => now(),
            'last_activity_at'         => now(),
        ]);

        $this->logActivity('enroll_course', $offering->id);

        return redirect()
            ->route('student.courses.show', $offering->id)
            ->with('success', 'Enrollment berhasil! Selamat belajar.');
    }

    private function logActivity(string $activityType, int $offeringId): void
    {
        LearningActivityLog::create([
            'user_id'            => Auth::id(),
            'course_offering_id' => $offeringId,
            'activity_type'      => $activityType,
            'activity_value'     => 1,
            'created_at'         => now(),
        ]);
    }
}
