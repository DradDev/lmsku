<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\CourseOffering;
use App\Models\Material;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->input('tab', 'overview');
        $lecturerId = Auth::id();

        // Ambil rombel & mata kuliah milik Dosen
        $offerings = CourseOffering::where('lecturer_id', $lecturerId)
            ->with(['masterCourse', 'academicTerm', 'enrollments'])
            ->latest()
            ->get();

        $masterCourseIds = $offerings->pluck('master_course_id')->filter()->unique()->toArray();
        $offeringIds = $offerings->pluck('id')->toArray();

        $rawMaterials = Material::query()
            ->where(function ($query) use ($masterCourseIds, $offeringIds) {
                $query->whereIn('master_course_id', $masterCourseIds)
                    ->orWhereIn('course_offering_id', $offeringIds);
            })
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

        $quizzes = Quiz::query()
            ->whereIn('master_course_id', $masterCourseIds)
            ->with(['masterCourse'])
            ->withCount('questions')
            ->latest()
            ->get();

        $totalStudents = $offerings->flatMap->enrollments->unique('user_id')->count();

        return view('lecturer.dashboard', compact(
            'tab',
            'offerings',
            'materials',
            'quizzes',
            'totalStudents'
        ));
    }
}
