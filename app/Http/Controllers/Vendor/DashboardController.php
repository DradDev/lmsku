<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Material;
use App\Models\Project;
use App\Models\ProjectParticipation;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->input('tab', 'overview');
        $vendorId = Auth::id();

        // Vendor Industry Certified Courses
        $courses = Course::where('user_id', $vendorId)
            ->withCount(['students', 'materials', 'quizzes'])
            ->latest()
            ->get();

        $courseIds = $courses->pluck('id')->toArray();

        // Vendor Industry Projects
        $projects = Project::where('created_by', $vendorId)
            ->withCount('participations')
            ->latest()
            ->get();

        $projectIds = $projects->pluck('id')->toArray();

        // Materials & Quizzes created by Vendor
        $materials = Material::whereIn('course_id', $courseIds)
            ->with('course')
            ->latest()
            ->get();

        $selectedQuizId = $request->input('quiz_id');

        $quizzes = Quiz::whereIn('course_id', $courseIds)
            ->with(['course', 'questions'])
            ->withCount('questions')
            ->when($selectedQuizId, function ($query) use ($selectedQuizId) {
                $query->orderByRaw("CASE WHEN id = ? THEN 0 ELSE 1 END", [(int) $selectedQuizId]);
            })
            ->latest()
            ->get();

        $questions = Question::where('user_id', $vendorId)
            ->with(['quiz.course', 'skills'])
            ->orderBy('id', 'asc')
            ->get();

        $groupedQuestions = $questions->groupBy('quiz_id');

        $mainSkills = Skill::with(['children' => function ($query) {
            $query->orderBy('name');
        }])
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        // Participations in Vendor Projects
        $participations = ProjectParticipation::whereIn('project_id', $projectIds)
            ->with(['user', 'project'])
            ->latest()
            ->take(5)
            ->get();

        $totalCourses = $courses->count();
        $totalProjects = $projects->count();
        $totalEnrolledStudents = $courses->sum('students_count');
        $totalProjectStudents = $projects->sum('participations_count');

        return view('vendor.dashboard', compact(
            'tab',
            'courses',
            'projects',
            'materials',
            'quizzes',
            'questions',
            'groupedQuestions',
            'mainSkills',
            'participations',
            'totalCourses',
            'totalProjects',
            'totalEnrolledStudents',
            'totalProjectStudents'
        ));
    }
}
