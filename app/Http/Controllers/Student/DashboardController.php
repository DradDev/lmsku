<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\ProjectParticipation;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Ambil seluruh kursus aktif mahasiswa (mendukung 3NF CourseOffering & Legacy Course)
        $enrollments = Enrollment::with([
            'courseOffering.masterCourse.materials',
            'courseOffering.masterCourse.quizzes.questions',
            'courseOffering.lecturer.institution',
            'courseOffering.academicTerm',
            'course.materials',
            'course.user.institution',
            'course.quizzes.questions',
        ])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $courses = collect();
        $allQuizIds = collect();

        // Eager load all verified attempts for this user to avoid N+1 query in loop
        $userVerifiedAttempts = QuizAttempt::where('user_id', $user->id)
            ->where('is_verified', true)
            ->get()
            ->groupBy('quiz_id');

        foreach ($enrollments as $enrollment) {
            $offering = $enrollment->courseOffering;
            $courseObj = $offering ?? $enrollment->course;

            if (!$courseObj) {
                continue;
            }

            $item = clone $courseObj;
            $item->enrollment_id = $enrollment->id;
            $item->progress = $enrollment->is_completed ? 100 : ($enrollment->progress_percent ?? 0);
            $item->is_completed = $enrollment->is_completed;
            $item->can_get_certificate = false;

            $quizzes = $courseObj instanceof CourseOffering ? $courseObj->all_quizzes : ($courseObj->quizzes ?? collect());
            $allQuizIds = $allQuizIds->merge($quizzes->pluck('id'));

            $finalQuiz = $quizzes->firstWhere('quiz_type', 'final');
            if ($finalQuiz) {
                $threshold = $offering?->certificate_threshold 
                    ?? $offering?->masterCourse?->certificate_threshold 
                    ?? $item->certificate_threshold 
                    ?? 70;

                $verifiedAttempt = $userVerifiedAttempts->get($finalQuiz->id)?->sortByDesc('score')->first();

                if ($verifiedAttempt && $verifiedAttempt->score >= $threshold) {
                    $item->can_get_certificate = true;
                }
            }

            $courses->push($item);
        }

        $totalCourses = $courses->count();
        $completed = $courses->where('can_get_certificate', true)->count();
        $inProgress = max($totalCourses - $completed, 0);

        // 2. Kuis yang Tersedia untuk Mahasiswa
        $availableQuizzes = Quiz::with(['quizzable'])
            ->whereIn('id', $allQuizIds->unique())
            ->whereHas('questions', function ($query) {
                $query->where('status', 'approved');
            })
            ->withCount([
                'questions as approved_questions_count' => function ($query) {
                    $query->where('status', 'approved');
                }
            ])
            ->latest()
            ->take(4)
            ->get();

        // 3. Riwayat Hasil Kuis Terakhir
        $latestQuiz = QuizAttempt::with(['quiz.quizzable'])
            ->where('user_id', $user->id)
            ->where('is_verified', true)
            ->latest()
            ->first();

        $latestQuizResults = QuizAttempt::with(['quiz.quizzable'])
            ->where('user_id', $user->id)
            ->where('is_verified', true)
            ->latest()
            ->take(3)
            ->get();

        $pendingQuiz = QuizAttempt::with(['quiz.quizzable'])
            ->where('user_id', $user->id)
            ->where('is_verified', false)
            ->latest()
            ->first();

        // 4. Proyek & Portofolio Aktif Mahasiswa (Pengganti Grafik Dummy)
        $activeParticipations = ProjectParticipation::with([
            'project.creator.institution',
            'project.skills',
        ])
            ->where('user_id', $user->id)
            ->whereIn('status', ['in_progress', 'development', 'review', 'completed'])
            ->latest('updated_at')
            ->take(3)
            ->get();

        $pendingInvitationsCount = ProjectParticipation::where('user_id', $user->id)
            ->where('status', 'invited')
            ->count();

        $totalJoinedProjectsCount = ProjectParticipation::where('user_id', $user->id)
            ->whereIn('status', ['in_progress', 'development', 'review', 'completed'])
            ->count();

        $savedMaterials = \App\Models\SavedMaterial::where('user_id', $user->id)
            ->whereHas('material')
            ->with(['material.masterCourse', 'courseOffering.masterCourse'])
            ->latest()
            ->take(3)
            ->get();

        return view('student.dashboard', compact(
            'courses',
            'availableQuizzes',
            'totalCourses',
            'inProgress',
            'completed',
            'latestQuiz',
            'latestQuizResults',
            'pendingQuiz',
            'activeParticipations',
            'pendingInvitationsCount',
            'totalJoinedProjectsCount',
            'savedMaterials'
        ));
    }
}
