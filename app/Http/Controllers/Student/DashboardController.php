<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Submission;
use App\Models\QuizAttempt;
use App\Models\Assignment;
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
            ->with(['materials', 'assignments', 'user', 'quizzes.questions'])
            ->latest()
            ->get();

        $courseIds = $courses->pluck('id');

        $assignments = Assignment::with('course')
            ->whereIn('course_id', $courseIds)
            ->latest()
            ->get();

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
            $totalAssignments = $course->assignments->count();
            $totalItems = $totalMaterials + $totalAssignments;

            $course->progress = $totalItems > 0
                ? round(($totalMaterials / $totalItems) * 100)
                : 0;

            $course->is_completed = $course->progress >= 100;
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

            if ($course->can_get_certificate) {
                $completed++;
            }
        }

        $totalCourses = $courses->count();
        $inProgress = max($totalCourses - $completed, 0);

        $lastSubmission = Submission::where('user_id', $user->id)
            ->latest()
            ->first();

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

        $latestEssayAnswer = QuizAnswer::with(['question.quiz.course', 'attempt'])
            ->where('user_id', $user->id)
            ->whereNotNull('score')
            ->whereHas('question', function ($query) {
                $query->where('question_type', 'essay');
            })
            ->latest('updated_at')
            ->first();

        return view('student.dashboard', compact(
            'courses',
            'assignments',
            'availableQuizzes',
            'totalCourses',
            'inProgress',
            'completed',
            'lastSubmission',
            'latestQuiz',
            'latestQuizResults',
            'pendingQuiz',
            'latestEssayAnswer'
        ));
    }
}
