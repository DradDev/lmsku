<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
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

        $materials = Material::query()
            ->with(['course.user'])
            ->whereHas('course', function ($query) use ($user) {
                $query->whereIn('id', function ($subQuery) use ($user) {
                    $subQuery->select('course_id')
                        ->from('enrollments')
                        ->where('user_id', $user->id);
                });
            })
            ->latest()
            ->get();

        return view('student.materials.index', compact('materials'));
    }

    public function show(Material $material): View
    {
        $user = Auth::user();

        $isEnrolled = DB::table('enrollments')
            ->where('user_id', $user->id)
            ->where('course_id', $material->course_id)
            ->exists();

        abort_unless($isEnrolled, 403, 'Kamu tidak memiliki akses ke materi ini.');

        $material->load(['course.user']);

        app(CourseProgressService::class)->markMaterialAsCompleted(
            userId: $user->id,
            courseId: $material->course_id,
            materialId: $material->id
        );

        LearningActivityLog::create([
            'user_id' => $user->id,
            'course_id' => $material->course_id,
            'material_id' => $material->id,
            'activity_type' => 'view_material',
            'activity_value' => 1,
            'occurred_at' => now(),
        ]);

        return view('student.materials.show', compact('material'));
    }
}
