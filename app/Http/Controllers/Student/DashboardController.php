<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\QuizAttempt;
use App\Models\Quiz;
use App\Models\QuizAnswer;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $courses = Course::whereIn('id', function ($query) use ($user) {
                $query->select('course_id')
                    ->from('enrollments')
                    ->where('user_id', $user->id);
            })
            ->with(['materials', 'user', 'quizzes.questions'])
            ->latest()
            ->get();

        $courseIds = $courses->pluck('id');

        $availableQuizzes = Quiz::with(['course'])
            ->whereIn('course_id', $courseIds)
            ->whereHas('questions', function ($query) {
                $query->where('status', 'approved');
            })
            ->withCount([
                'questions as approved_questions_count' => function ($query) {
                    $query->where('status', 'approved');
                }
            ])
            ->latest()
            ->get();

        $completed = 0;

        foreach ($courses as $course) {
            $totalMaterials = $course->materials->count();

            $course->progress = $totalMaterials > 0 ? 100 : 0;
            $course->is_completed = $course->progress >= 100;
            $course->can_get_certificate = false;

            $finalQuiz = $course->quizzes->firstWhere('quiz_type', 'final');

            if ($finalQuiz) {
                $verifiedAttempt = QuizAttempt::where('user_id', $user->id)
                    ->where('quiz_id', $finalQuiz->id)
                    ->where('is_verified', true)
                    ->orderByDesc('score')
                    ->first();

                if ($verifiedAttempt && $verifiedAttempt->score >= 70) {
                    $course->can_get_certificate = true;
                }
            }

            if ($course->can_get_certificate) {
                $completed++;
            }
        }

        $totalCourses = $courses->count();
        $inProgress = max($totalCourses - $completed, 0);

        $latestQuiz = QuizAttempt::with(['quiz.course'])
            ->where('user_id', $user->id)
            ->where('is_verified', true)
            ->latest()
            ->first();
        
        $latestQuizResults = QuizAttempt::with(['quiz.course'])
            ->where('user_id', $user->id)
            ->where('is_verified', true)
            ->latest()
            ->take(3)
            ->get();

        $pendingQuiz = QuizAttempt::with(['quiz.course'])
            ->where('user_id', $user->id)
            ->where('is_verified', false)
            ->latest()
            ->first();

        return view('student.dashboard', compact(
            'courses',
            'availableQuizzes',
            'totalCourses',
            'inProgress',
            'completed',
            'latestQuiz',
            'latestQuizResults',
            'pendingQuiz'
        ));
    }
}
