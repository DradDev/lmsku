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
        $lecturerId = Auth::id();

        // Ambil master_course_id dan offering id milik Dosen
        $offerings = \App\Models\CourseOffering::where('lecturer_id', $lecturerId)->get();
        $masterCourseIds = $offerings->pluck('master_course_id')->filter()->unique()->toArray();
        $offeringIds = $offerings->pluck('id')->toArray();

        $materials = Material::query()
            ->where(function ($query) use ($masterCourseIds, $offeringIds) {
                $query->whereIn('master_course_id', $masterCourseIds)
                    ->orWhereIn('course_offering_id', $offeringIds);
            })
            ->latest()
            ->get();

        $questions = Question::query()
            ->where('user_id', $lecturerId)
            ->with(['quiz.masterCourse'])
            ->orderBy('id', 'asc')
            ->get();

        $selectedQuizId = $request->input('quiz_id');

        $quizzes = Quiz::query()
            ->whereIn('master_course_id', $masterCourseIds)
            ->with(['masterCourse'])
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

        $retakeRequests = \App\Models\QuizRetakeRequest::with(['user', 'quiz.masterCourse'])
            ->whereHas('quiz.masterCourse.offerings', function ($query) {
                $query->where('lecturer_id', Auth::id());
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
