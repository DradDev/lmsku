<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseOffering;
use App\Models\LearningActivityLog;
use App\Models\MasterCourse;
use App\Models\Material;
use App\Models\SavedMaterial;
use App\Services\CourseProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MaterialController extends Controller
{
    private function getEnrolledOfferingForMaterial(Material $material, $user): ?CourseOffering
    {
        // 1. Jika materi bertipe CourseOffering (Khusus Rombel)
        if ($material->materialable_type === CourseOffering::class) {
            return CourseOffering::where('id', $material->materialable_id)
                ->whereIn('id', function ($q) use ($user) {
                    $q->select('course_offering_id')->from('enrollments')->where('user_id', $user->id);
                })
                ->first();
        }

        // 2. Jika materi bertipe MasterCourse (Induk)
        $masterCourseId = $material->materialable_type === MasterCourse::class
            ? $material->materialable_id
            : ($material->master_course_id ?? optional($material->materialable)->master_course_id);

        if ($masterCourseId) {
            return CourseOffering::where('master_course_id', $masterCourseId)
                ->whereIn('id', function ($q) use ($user) {
                    $q->select('course_offering_id')->from('enrollments')->where('user_id', $user->id);
                })
                ->first();
        }

        return null;
    }

    public function index(): View
    {
        $user = Auth::user();

        $savedMaterials = SavedMaterial::where('user_id', $user->id)
            ->with(['material.masterCourse', 'courseOffering.masterCourse', 'courseOffering.lecturer'])
            ->latest()
            ->get();

        return view('student.materials.index', compact('savedMaterials'));
    }

    public function show(Material $material): View
    {
        $user = Auth::user();
        $enrolledOffering = $this->getEnrolledOfferingForMaterial($material, $user);

        abort_unless($enrolledOffering, 403, 'Kamu tidak memiliki akses ke materi ini.');

        $material->load(['masterCourse']);

        $isSaved = SavedMaterial::where('user_id', $user->id)
            ->where('material_id', $material->id)
            ->exists();

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

        return view('student.materials.show', [
            'material'         => $material,
            'enrolledOffering' => $enrolledOffering,
            'isSaved'          => $isSaved,
        ]);
    }

    public function toggleSave(Material $material): RedirectResponse
    {
        $user = Auth::user();
        $enrolledOffering = $this->getEnrolledOfferingForMaterial($material, $user);

        abort_unless($enrolledOffering, 403, 'Kamu tidak memiliki akses ke materi ini.');

        $saved = SavedMaterial::where('user_id', $user->id)
            ->where('material_id', $material->id)
            ->first();

        if ($saved) {
            $saved->delete();
            return back()->with('success', "Materi '{$material->title}' berhasil dihapus dari materi tersimpan.");
        }

        SavedMaterial::create([
            'user_id'            => $user->id,
            'material_id'        => $material->id,
            'course_offering_id' => $enrolledOffering->id,
        ]);

        return back()->with('success', "Materi '{$material->title}' berhasil disimpan ke perpustakaan belajar kamu.");
    }
}