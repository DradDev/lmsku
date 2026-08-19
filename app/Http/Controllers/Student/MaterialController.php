<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseOffering;
use App\Models\LearningActivityLog;
use App\Models\Material;
use App\Services\CourseProgressService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MaterialController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $enrolledMasterCourseIds = CourseOffering::whereIn('id', function ($q) use ($user) {
            $q->select('course_offering_id')->from('enrollments')->where('user_id', $user->id);
        })->pluck('master_course_id')->filter()->unique();

        $materials = Material::query()
            ->with(['masterCourse'])
            ->whereIn('master_course_id', $enrolledMasterCourseIds)
            ->latest()
            ->get();

        return view('student.materials.index', compact('materials'));
    }

    public function show(Material $material): View
    {
        $user = Auth::user();

        $enrolledOffering = CourseOffering::where('master_course_id', $material->master_course_id)
            ->whereIn('id', function ($q) use ($user) {
                $q->select('course_offering_id')->from('enrollments')->where('user_id', $user->id);
            })
            ->first();

        abort_unless($enrolledOffering, 403, 'Kamu tidak memiliki akses ke materi ini.');

        $material->load(['masterCourse']);

        app(CourseProgressService::class)->markMaterialAsCompleted(
            userId: $user->id,
            offeringId: $enrolledOffering->id,
            materialId: $material->id
        );

        LearningActivityLog::create([
            'user_id'            => $user->id,
            'course_offering_id' => $enrolledOffering->id,
            'material_id'        => $material->id,
            'activity_type'      => 'view_material',
            'activity_value'     => 1,
            'created_at'         => now(),
        ]);

        return view('student.materials.show', compact('material'));
    }
}
