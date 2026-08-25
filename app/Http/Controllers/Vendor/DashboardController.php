<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\CourseOffering;
use App\Models\MasterCourse;
use App\Models\Material;
use App\Models\Project;
use App\Models\ProjectParticipation;
use App\Models\Quiz;
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
        $materials = Material::where(function ($query) use ($masterCourseIds, $courseIds) {
            $query->where(function ($sub) use ($masterCourseIds) {
                $sub->where('materialable_type', MasterCourse::class)
                    ->whereIn('materialable_id', $masterCourseIds);
            })->orWhere(function ($sub) use ($courseIds) {
                $sub->where('materialable_type', CourseOffering::class)
                    ->whereIn('materialable_id', $courseIds);
            });
        })
        ->with(['materialable'])
        ->latest()
        ->get();

        $quizzes = Quiz::where(function ($q) use ($masterCourseIds, $courseIds) {
            $q->where(function ($sub) use ($masterCourseIds) {
                $sub->where('quizzable_type', \App\Models\MasterCourse::class)
                    ->whereIn('quizzable_id', $masterCourseIds);
            })->orWhere(function ($sub) use ($courseIds) {
                $sub->where('quizzable_type', CourseOffering::class)
                    ->whereIn('quizzable_id', $courseIds);
            });
        })
        ->with(['quizzable'])
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
