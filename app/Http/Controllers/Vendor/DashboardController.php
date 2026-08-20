<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\MasterCourse;
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

        // Vendor Master Courses & Batches
        $masterCourses = MasterCourse::where('user_id', $vendorId)->get();
        $masterCourseIds = $masterCourses->pluck('id')->toArray();

        $courses = CourseOffering::where('lecturer_id', $vendorId)
            ->orWhereIn('master_course_id', $masterCourseIds)
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
        $materials = Material::whereIn('master_course_id', $masterCourseIds)
            ->orWhereIn('course_offering_id', $courseIds)
            ->with('masterCourse')
            ->latest()
            ->get();

        $selectedQuizId = $request->input('quiz_id');

        $quizzes = Quiz::whereIn('master_course_id', $masterCourseIds)
            ->with(['masterCourse', 'questions'])
            ->withCount('questions')
            ->when($selectedQuizId, function ($query) use ($selectedQuizId) {
                $query->orderByRaw("CASE WHEN id = ? THEN 0 ELSE 1 END", [(int) $selectedQuizId]);
            })
            ->latest()
            ->get();

        $questions = Question::where('user_id', $vendorId)
            ->with(['quiz.masterCourse', 'skills'])
            ->orderBy('id', 'asc')
            ->get();

        $groupedQuestions = $questions->groupBy('quiz_id');

        $mainSkills = Skill::with('tags')
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
