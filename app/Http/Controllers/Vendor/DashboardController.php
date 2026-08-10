<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Project;
use App\Models\ProjectParticipation;
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
        $materials = \App\Models\Material::whereIn('course_id', $courseIds)
            ->with('course')
            ->latest()
            ->get();

        $quizzes = \App\Models\Quiz::whereIn('course_id', $courseIds)
            ->with(['course', 'questions'])
            ->withCount('questions')
            ->latest()
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
            'participations',
            'totalCourses',
            'totalProjects',
            'totalEnrolledStudents',
            'totalProjectStudents'
        ));
    }
}
