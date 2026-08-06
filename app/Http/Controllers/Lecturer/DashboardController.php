<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Skill;


class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->input('tab', 'overview');

        $materials = Material::query()
            ->whereHas('course', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->latest()
            ->get();

        $questions = Question::query()
            ->where('user_id', Auth::id())
            ->with(['quiz.course'])
            ->orderBy('id', 'asc')
            ->get();

        $selectedQuizId = $request->input('quiz_id');

        $quizzes = Quiz::query()
            ->whereHas('course', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->with(['course'])
            ->withCount('questions')
            ->when($selectedQuizId, function ($query) use ($selectedQuizId) {
                $query->orderByRaw("CASE WHEN id = ? THEN 0 ELSE 1 END", [(int) $selectedQuizId]);
            })
            ->latest()
            ->get();

        $mainSkills = Skill::with(['children' => function ($query) {
            $query->orderBy('name');
        }])
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $mainSkills = Skill::with(['children' => function ($query) {
            $query->orderBy('name');
        }])
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $retakeRequests = \App\Models\QuizRetakeRequest::with(['user', 'quiz', 'course'])
            ->whereHas('course', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->latest()
            ->get();

        return view('lecturer.dashboard', compact(
            'tab',
            'materials',
            'questions',
            'quizzes',
            'mainSkills',
            'retakeRequests'
        ));
    }
}
