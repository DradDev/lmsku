<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\Project;
use App\Models\ProjectParticipation;
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

        // 1. Ambil Seluruh Kursus Mahasiswa (3NF CourseOfferings)
        $enrollments = Enrollment::with([
            'courseOffering.masterCourse.quizzes.questions',
            'courseOffering.masterCourse.skills',
            'courseOffering.lecturer.institution',
            'courseOffering.academicTerm',
            'course.masterCourse.quizzes.questions',
            'course.masterCourse.skills',
            'course.user.institution',
        ])
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

            $quizzes = $courseObj->quizzes ?? collect();
            if ($quizzes->isEmpty() && isset($courseObj->masterCourse)) {
                $quizzes = $courseObj->masterCourse->quizzes ?? collect();
            }

            $item->final_quiz = $quizzes->firstWhere('quiz_type', 'final');

            $certificateRecord = Certificate::where('user_id', $student->id)
                ->where('course_offering_id', $enrollment->course_offering_id)
                ->first();

            $item->certificate_record = $certificateRecord;

            $tempCert = new Certificate([
                'user_id'            => $student->id,
                'course_offering_id' => $enrollment->course_offering_id,
                'completed_at'       => $enrollment->updated_at ?? now(),
            ]);
            $item->credential_code = $certificateRecord?->credential_code ?? $tempCert->generateCredentialCode();

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

                $item->verified_final_attempt = $verifiedAttempt;

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
                    $item->certificate_status_text = 'Kerjakan final quiz dan capai nilai minimal ' . $threshold . '%.';
                } elseif ($verifiedAttempt->score < $threshold) {
                    $item->certificate_status_text = 'Nilai final quiz minimal ' . $threshold . '% untuk membuka certificate (Nilai Anda: ' . $verifiedAttempt->score . ').';
                } else {
                    $item->can_get_certificate = true;
                    $item->certificate_status_text = 'Sertifikat lulus evaluasi dan siap diunduh.';
                }
            }

            $courses->push($item);
        }

        // 2. Ambil Seluruh Proyek yang Diikuti Mahasiswa (Internal Dosen & Eksternal Vendor)
        $participations = ProjectParticipation::with([
            'project.creator.institution',
            'project.user.institution',
            'project.skills',
            'project.tags',
        ])
            ->where('user_id', $student->id)
            ->whereIn('status', ['in_progress', 'development', 'review', 'completed'])
            ->latest('updated_at')
            ->get();

        $projects = collect();

        foreach ($participations as $part) {
            $prj = $part->project;
            if (!$prj) continue;

            $item = clone $prj;
            $item->participation = $part;

            $certificateRecord = Certificate::where('user_id', $student->id)
                ->where('project_id', $prj->id)
                ->first();

            $item->certificate_record = $certificateRecord;

            $tempCert = new Certificate([
                'user_id' => $student->id,
                'project_id' => $prj->id,
                'completed_at' => $part->completed_at ?? $part->updated_at ?? now(),
            ]);
            $item->credential_code = $certificateRecord?->credential_code ?? $tempCert->generateCredentialCode();

            // Cek kelayakan sertifikat proyek
            $isVerified = ($certificateRecord && $certificateRecord->status === 'verified' && !empty($certificateRecord->blockchain_hash));
            $isPending = ($certificateRecord && $certificateRecord->status === 'pending') || (!$certificateRecord && $part->status === 'completed');

            if ($isVerified) {
                $item->can_get_certificate = true;
                $item->certificate_status_text = 'Sertifikat Project resmi telah diverifikasi Admin & tercatat di Blockchain.';
                $item->status_badge = 'Verified';
            } elseif ($isPending) {
                $item->can_get_certificate = false;
                $item->certificate_status_text = 'Pengerjaan selesai & disetujui. Menunggu verifikasi integritas & penerbitan hash blockchain oleh Admin.';
                $item->status_badge = 'Pending';
            } elseif ($part->status === 'review') {
                $item->can_get_certificate = false;
                $item->certificate_status_text = 'Proyek sedang dalam tahap evaluasi/review akhir oleh Pembimbing.';
                $item->status_badge = 'Review';
            } else {
                $item->can_get_certificate = false;
                $item->certificate_status_text = 'Progres pengerjaan ' . ($part->progress_percent ?? 0) . '%. Selesaikan proyek hingga 100% untuk membuka sertifikat.';
                $item->status_badge = 'In Progress';
            }

            $projects->push($item);
        }

        return view('student.certificates.index', compact('courses', 'projects'));
    }

    public function show(string $id): View
    {
        $courseOffering = CourseOffering::find($id);
        $course = $courseOffering ?? Course::findOrFail($id);

        [$student, $finalQuiz, $attempt] = $this->resolveCertificateData($course);

        $offeringId = $courseOffering?->id ?? $course->id;

        $certificateRecord = Certificate::where('user_id', $student->id)
            ->where('course_offering_id', $offeringId)
            ->first();

        $credentialCode = $certificateRecord?->credential_code ?? (new Certificate([
            'user_id'            => $student->id,
            'course_offering_id' => $offeringId,
        ]))->generateCredentialCode();

        return view('student.certificate', compact('course', 'student', 'finalQuiz', 'attempt', 'credentialCode'));
    }

    public function download(string $id): Response
    {
        $courseOffering = CourseOffering::find($id);
        $course = $courseOffering ?? Course::findOrFail($id);

        [$student, $finalQuiz, $attempt] = $this->resolveCertificateData($course);

        $offeringId = $courseOffering?->id ?? $course->id;

        $certificateRecord = Certificate::where('user_id', $student->id)
            ->where('course_offering_id', $offeringId)
            ->first();

        $credentialCode = $certificateRecord?->credential_code ?? (new Certificate([
            'user_id'            => $student->id,
            'course_offering_id' => $offeringId,
        ]))->generateCredentialCode();

        $pdf = Pdf::loadView('student.certificate_pdf', compact('course', 'student', 'finalQuiz', 'attempt', 'credentialCode'))
            ->setPaper('a4', 'landscape');

        $filename = 'certificate-course-' . $course->id . '-' . $student->id . '.pdf';

        return $pdf->download($filename);
    }

    public function showProject(Project $project): View
    {
        $student = Auth::user();

        $participation = ProjectParticipation::where('user_id', $student->id)
            ->where('project_id', $project->id)
            ->first();

        $certificateRecord = Certificate::where('user_id', $student->id)
            ->where('project_id', $project->id)
            ->first();

        $isEligible = ($certificateRecord && $certificateRecord->is_verified && !empty($certificateRecord->blockchain_hash));

        abort_unless(
            $isEligible,
            403,
            'Sertifikat project belum dapat diakses. Sertifikat sedang menunggu verifikasi integritas & penerbitan blockchain hash oleh Admin.'
        );

        $project->load(['creator.institution', 'user.institution', 'skills']);

        $credentialCode = $certificateRecord?->credential_code ?? (new Certificate([
            'user_id' => $student->id,
            'project_id' => $project->id,
            'completed_at' => $participation?->completed_at ?? $project->created_at ?? now(),
        ]))->generateCredentialCode();

        return view('student.certificate_project', compact('project', 'student', 'credentialCode', 'participation', 'certificateRecord'));
    }

    public function downloadProject(Project $project): Response
    {
        $student = Auth::user();

        $participation = ProjectParticipation::where('user_id', $student->id)
            ->where('project_id', $project->id)
            ->first();

        $certificateRecord = Certificate::where('user_id', $student->id)
            ->where('project_id', $project->id)
            ->first();

        $isEligible = ($certificateRecord && $certificateRecord->is_verified && !empty($certificateRecord->blockchain_hash));

        abort_unless(
            $isEligible,
            403,
            'Sertifikat project belum dapat diakses. Sertifikat sedang menunggu verifikasi integritas & penerbitan blockchain hash oleh Admin.'
        );

        $project->load(['creator.institution', 'user.institution', 'skills']);

        $credentialCode = $certificateRecord?->credential_code ?? (new Certificate([
            'user_id' => $student->id,
            'project_id' => $project->id,
            'completed_at' => $participation?->completed_at ?? $project->created_at ?? now(),
        ]))->generateCredentialCode();

        $pdf = Pdf::loadView('student.certificate_project_pdf', compact('project', 'student', 'credentialCode', 'participation', 'certificateRecord'))
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
            $course->load(['lecturer.institution', 'masterCourse']);
        } else {
            $course->load(['user.institution']);
        }

        $quizzes = $course->quizzes ?? collect();
        if ($quizzes->isEmpty() && isset($course->masterCourse)) {
            $quizzes = $course->masterCourse->quizzes ?? collect();
        }

        $finalQuiz = $quizzes->firstWhere('quiz_type', 'final');

        abort_if(!$finalQuiz, 403, 'Certificate belum tersedia karena final quiz belum ditentukan.');

        $approvedQuestions = $finalQuiz->questions->where('status', 'approved');

        abort_if($approvedQuestions->count() === 0, 403, 'Certificate belum tersedia karena final quiz belum memiliki soal yang disetujui.');

        $attempt = QuizAttempt::where('user_id', $student->id)
            ->where('quiz_id', $finalQuiz->id)
            ->orderByDesc('score')
            ->first();

        $threshold = $course->certificate_threshold ?? ($course->masterCourse?->certificate_threshold ?? 60);

        abort_if(!$attempt, 403, 'Certificate belum tersedia. Selesaikan final quiz terlebih dahulu.');
        abort_if($attempt->score < $threshold, 403, 'Certificate belum tersedia karena nilai final quiz masih di bawah ' . $threshold . '%.');

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
