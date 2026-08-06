<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
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
            $course->final_quiz = $course->quizzes->firstWhere('quiz_type', 'final');
            $course->credential_code = 'CERT-CRS-' . date('Ym') . '-' . sprintf('%04d', $course->id) . '-' . sprintf('%04d', $student->id);

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

                $certificateRecord = \App\Models\Certificate::where('user_id', $student->id)
                    ->where('course_id', $course->id)
                    ->first();

                $course->verified_final_attempt = $verifiedAttempt;
                $course->certificate_record = $certificateRecord;

                $threshold = $course->certificate_threshold ?? 60;

                if (! $onlyMultipleChoice) {
                    $course->certificate_status_text = 'Final quiz untuk certificate harus berisi multiple choice saja.';
                } elseif ($certificateRecord && $certificateRecord->status === 'verified') {
                    $course->can_get_certificate = true;
                    $course->certificate_status_text = 'Certificate sudah diverifikasi Admin dan siap diunduh.';
                } elseif ($certificateRecord && $certificateRecord->status === 'pending') {
                    $course->certificate_status_text = 'Sertifikat sedang dalam proses verifikasi oleh Admin.';
                } elseif (! $verifiedAttempt) {
                    $course->certificate_status_text = 'Kerjakan final quiz dan capai nilai minimal ' . $threshold . '.';
                } elseif ($verifiedAttempt->score < $threshold) {
                    $course->certificate_status_text = 'Nilai final quiz minimal ' . $threshold . ' untuk membuka certificate (Nilai Anda: ' . $verifiedAttempt->score . ').';
                } else {
                    $course->certificate_status_text = 'Sertifikat sedang disiapkan untuk verifikasi Admin.';
                }
            }
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

    public function show(Course $course): View
    {
        [$student, $finalQuiz, $attempt] = $this->resolveCertificateData($course);

        $credentialCode = 'CERT-CRS-' . ($attempt->created_at ? $attempt->created_at->format('Ym') : date('Ym')) . '-' . sprintf('%04d', $course->id) . '-' . sprintf('%04d', $student->id);

        return view('student.certificate', compact('course', 'student', 'finalQuiz', 'attempt', 'credentialCode'));
    }

    public function download(Course $course): Response
    {
        [$student, $finalQuiz, $attempt] = $this->resolveCertificateData($course);

        $credentialCode = 'CERT-CRS-' . ($attempt->created_at ? $attempt->created_at->format('Ym') : date('Ym')) . '-' . sprintf('%04d', $course->id) . '-' . sprintf('%04d', $student->id);

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

    private function resolveCertificateData(Course $course): array
    {
        $student = Auth::user();

        $isEnrolled = DB::table('enrollments')
            ->where('user_id', $student->id)
            ->where('course_id', $course->id)
            ->exists();

        abort_unless($isEnrolled, 403, 'Kamu tidak terdaftar di course ini.');

        $course->load(['user', 'quizzes.questions']);

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

        $certificateRecord = \App\Models\Certificate::where('user_id', $student->id)
            ->where('course_id', $course->id)
            ->first();

        abort_if(
            !$attempt->is_verified && (!$certificateRecord || $certificateRecord->status !== 'verified'),
            403,
            'Sertifikat sedang dalam proses verifikasi Admin. Harap tunggu persetujuan Admin.'
        );

        return [$student, $finalQuiz, $attempt];
    }
}
