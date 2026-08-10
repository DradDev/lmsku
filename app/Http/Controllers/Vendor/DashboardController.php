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
        $vendorId = Auth::id();

        // Vendor Industry Certified Courses
        $courses = Course::where('user_id', $vendorId)
            ->withCount('students')
            ->latest()
            ->get();

        // Vendor Industry Projects
        $projects = Project::where('created_by', $vendorId)
            ->withCount('participations')
            ->latest()
            ->get();

        $projectIds = $projects->pluck('id')->toArray();

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
            'courses',
            'projects',
            'participations',
            'totalCourses',
            'totalProjects',
            'totalEnrolledStudents',
            'totalProjectStudents'
        ));
    }
}
