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
        $rawMaterials = Material::whereIn('master_course_id', $masterCourseIds)
            ->orWhereIn('course_offering_id', $courseIds)
            ->with(['masterCourse', 'courseOffering'])
            ->latest()
            ->get();

        $materials = $rawMaterials->groupBy(function ($m) {
            return ($m->master_course_id ?? 0) . '_' . trim(strtolower($m->title));
        })->map(function ($group) {
            $first = $group->first();
            $first->assigned_offerings = $group->pluck('courseOffering')->filter();
            $first->is_all_classes = $group->contains(fn($m) => is_null($m->course_offering_id));
            $first->related_ids = $group->pluck('id')->toArray();
            return $first;
        })->values();

        $quizzes = Quiz::whereIn('master_course_id', $masterCourseIds)
            ->with(['masterCourse'])
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
