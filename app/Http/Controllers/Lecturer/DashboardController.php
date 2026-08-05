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
            ->latest()
            ->get();

        $quizzes = Quiz::query()
            ->whereHas('course', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->with(['course'])
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

        $totalCourses = \App\Models\Course::where('user_id', Auth::id())->count();

        $totalStudents = \App\Models\Enrollment::whereHas('course', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->distinct('user_id')
            ->count('user_id');

        return view('lecturer.dashboard', compact(
            'tab',
            'materials',
            'questions',
            'quizzes',
            'mainSkills',
            'totalCourses',
            'totalStudents'
        ));
    }
}
