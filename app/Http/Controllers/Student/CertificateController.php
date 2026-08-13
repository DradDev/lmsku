<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\Project;
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

        // 3NF Offerings where student is enrolled
        $enrollments = Enrollment::with(['courseOffering.masterCourse.quizzes.questions', 'courseOffering.lecturer', 'course.quizzes.questions', 'course.user'])
            ->where('user_id', $student->id)
            ->latest()
            ->get();

        $courses = collect();

        foreach ($enrollments as $enrollment) {
            $offering = $enrollment->courseOffering;
            $courseObj = $offering ?? $enrollment->course;

            if (!$courseObj) continue;

            $item = clone $courseObj;
            $item->can_get_certificate = false;
            $item->certificate_status_text = 'Certificate belum tersedia karena final quiz belum ditentukan.';
            $item->verified_final_attempt = null;
            $item->final_quiz = $courseObj->quizzes->firstWhere('quiz_type', 'final');
            $item->credential_code = 'CERT-CRS-' . date('Ym') . '-' . sprintf('%04d', $item->id) . '-' . sprintf('%04d', $student->id);

            if ($item->final_quiz) {
                $approvedQuestions = $item->final_quiz->questions->where('status', 'approved');

                $onlyMultipleChoice = $approvedQuestions->count() > 0 &&
                    $approvedQuestions->every(function ($question) {
                        return $question->question_type === 'multiple_choice';
                    });

                $verifiedAttempt = QuizAttempt::where('user_id', $student->id)
                    ->where('quiz_id', $item->final_quiz->id)
                    ->where('is_verified', true)
                    ->orderByDesc('score')
                    ->first();

                $certificateRecord = Certificate::where('user_id', $student->id)
                    ->where(function ($q) use ($enrollment, $item) {
                        if ($enrollment->course_offering_id) {
                            $q->where('course_offering_id', $enrollment->course_offering_id);
                        } else {
                            $q->where('course_id', $item->id);
                        }
                    })
                    ->first();

                $item->verified_final_attempt = $verifiedAttempt;
                $item->certificate_record = $certificateRecord;

                $threshold = $offering?->certificate_threshold 
                    ?? $offering?->masterCourse?->certificate_threshold 
                    ?? $item->certificate_threshold 
                    ?? 75;

                if (! $onlyMultipleChoice) {
                    $item->certificate_status_text = 'Final quiz untuk certificate harus berisi multiple choice saja.';
                } elseif ($certificateRecord && $certificateRecord->status === 'verified') {
                    $item->can_get_certificate = true;
                    $item->certificate_status_text = 'Certificate sudah diverifikasi Admin dan siap diunduh.';
                } elseif ($certificateRecord && $certificateRecord->status === 'pending') {
                    $item->certificate_status_text = 'Sertifikat sedang dalam proses verifikasi oleh Admin.';
                } elseif (! $verifiedAttempt) {
                    $item->certificate_status_text = 'Kerjakan final quiz dan capai nilai minimal ' . $threshold . '.';
                } elseif ($verifiedAttempt->score < $threshold) {
                    $item->certificate_status_text = 'Nilai final quiz minimal ' . $threshold . ' untuk membuka certificate (Nilai Anda: ' . $verifiedAttempt->score . ').';
                } else {
                    $item->certificate_status_text = 'Sertifikat sedang disiapkan untuk verifikasi Admin.';
                }
            }

            $courses->push($item);
        }

        // Fetch Joined Accepted Projects for Project Certificates
        $projects = $student->joinedProjects()
            ->with(['user', 'skills'])
            ->wherePivot('status', 'accepted')
            ->latest()
            ->get();

        foreach ($projects as $project) {
            $project->credential_code = 'CERT-PRJ-' . ($project->created_at ? $project->created_at->format('Ym') : date('Ym')) . '-' . sprintf('%04d', $project->id) . '-' . sprintf('%04d', $student->id);
        }

        return view('student.certificates.index', compact('courses', 'projects'));
    }

    public function show(string $id): View
    {
        $courseOffering = CourseOffering::find($id);
        $course = $courseOffering ?? Course::findOrFail($id);

        [$student, $finalQuiz, $attempt] = $this->resolveCertificateData($course);

        $certificateRecord = Certificate::where('user_id', $student->id)
            ->where(function ($q) use ($courseOffering, $course) {
                if ($courseOffering) {
                    $q->where('course_offering_id', $courseOffering->id);
                } else {
                    $q->where('course_id', $course->id);
                }
            })->first();

        $credentialCode = $certificateRecord?->credential_code ?? (new Certificate([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'course_offering_id' => $courseOffering?->id,
        ]))->generateCredentialCode();

        return view('student.certificate', compact('course', 'student', 'finalQuiz', 'attempt', 'credentialCode'));
    }

    public function download(string $id): Response
    {
        $courseOffering = CourseOffering::find($id);
        $course = $courseOffering ?? Course::findOrFail($id);

        [$student, $finalQuiz, $attempt] = $this->resolveCertificateData($course);

        $certificateRecord = Certificate::where('user_id', $student->id)
            ->where(function ($q) use ($courseOffering, $course) {
                if ($courseOffering) {
                    $q->where('course_offering_id', $courseOffering->id);
                } else {
                    $q->where('course_id', $course->id);
                }
            })->first();

        $credentialCode = $certificateRecord?->credential_code ?? (new Certificate([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'course_offering_id' => $courseOffering?->id,
        ]))->generateCredentialCode();

        $pdf = Pdf::loadView('student.certificate_pdf', compact('course', 'student', 'finalQuiz', 'attempt', 'credentialCode'))
            ->setPaper('a4', 'landscape');

        $filename = 'certificate-course-' . $course->id . '-' . $student->id . '.pdf';

        return $pdf->download($filename);
    }

    public function showProject(Project $project): View
    {
        $student = Auth::user();

        $isJoined = DB::table('project_participations')
            ->where('user_id', $student->id)
            ->where('project_id', $project->id)
            ->where('status', 'accepted')
            ->exists();

        abort_unless($isJoined, 403, 'Kamu belum diterima atau tidak terdaftar di project ini.');

        $project->load(['user', 'skills']);

        $credentialCode = 'CERT-PRJ-' . ($project->created_at ? $project->created_at->format('Ym') : date('Ym')) . '-' . sprintf('%04d', $project->id) . '-' . sprintf('%04d', $student->id);

        return view('student.certificate_project', compact('project', 'student', 'credentialCode'));
    }

    public function downloadProject(Project $project): Response
    {
        $student = Auth::user();

        $isJoined = DB::table('project_participations')
            ->where('user_id', $student->id)
            ->where('project_id', $project->id)
            ->where('status', 'accepted')
            ->exists();

        abort_unless($isJoined, 403, 'Kamu belum diterima atau tidak terdaftar di project ini.');

        $project->load(['user', 'skills']);

        $credentialCode = 'CERT-PRJ-' . ($project->created_at ? $project->created_at->format('Ym') : date('Ym')) . '-' . sprintf('%04d', $project->id) . '-' . sprintf('%04d', $student->id);

        $pdf = Pdf::loadView('student.certificate_project_pdf', compact('project', 'student', 'credentialCode'))
            ->setPaper('a4', 'landscape');

        $filename = 'certificate-project-' . $project->id . '-' . $student->id . '.pdf';

        return $pdf->download($filename);
    }

    private function resolveCertificateData(object $course): array
    {
        $student = Auth::user();

        $isOffering = $course instanceof CourseOffering;

        if ($isOffering) {
            $isEnrolled = DB::table('enrollments')
                ->where('user_id', $student->id)
                ->where('course_offering_id', $course->id)
                ->exists();
        } else {
            $isEnrolled = DB::table('enrollments')
                ->where('user_id', $student->id)
                ->where('course_id', $course->id)
                ->exists();
        }

        abort_unless($isEnrolled, 403, 'Kamu tidak terdaftar di course ini.');

        $course->load(['quizzes.questions']);
        if ($isOffering) {
            $course->load('lecturer');
        } else {
            $course->load('user');
        }

        $finalQuiz = $course->quizzes->firstWhere('quiz_type', 'final');

        abort_if(!$finalQuiz, 403, 'Certificate belum tersedia karena final quiz belum ditentukan.');

        $approvedQuestions = $finalQuiz->questions->where('status', 'approved');

        abort_if($approvedQuestions->count() === 0, 403, 'Certificate belum tersedia karena final quiz belum memiliki soal yang disetujui.');

        $attempt = QuizAttempt::where('user_id', $student->id)
            ->where('quiz_id', $finalQuiz->id)
            ->orderByDesc('score')
            ->first();

        $threshold = $course->certificate_threshold ?? 60;

        abort_if(!$attempt, 403, 'Certificate belum tersedia. Selesaikan final quiz terlebih dahulu.');
        abort_if($attempt->score < $threshold, 403, 'Certificate belum tersedia karena nilai final quiz masih di bawah ' . $threshold . '.');

        $certificateRecord = Certificate::where('user_id', $student->id)
            ->where(function ($q) use ($isOffering, $course) {
                if ($isOffering) {
                    $q->where('course_offering_id', $course->id);
                } else {
                    $q->where('course_id', $course->id);
                }
            })
            ->first();

        abort_if(
            !$attempt->is_verified && (!$certificateRecord || $certificateRecord->status !== 'verified'),
            403,
            'Sertifikat sedang dalam proses verifikasi Admin. Harap tunggu persetujuan Admin.'
        );

        return [$student, $finalQuiz, $attempt];
    }
}
