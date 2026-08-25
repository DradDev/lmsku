<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\CourseOffering;
use App\Models\MasterCourse;
use App\Models\Material;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function store(Request $request, $course): RedirectResponse
    {
        $vendorId = Auth::id();
        $courseObj = is_numeric($course)
            ? (CourseOffering::find($course) ?? MasterCourse::findOrFail($course))
            : $course;

        $isOwner = false;
        if ($courseObj instanceof CourseOffering) {
            $isOwner = $courseObj->lecturer_id === $vendorId ||
                       ($courseObj->masterCourse && $courseObj->masterCourse->user_id === $vendorId) ||
                       Auth::user()->isAdmin();
        } elseif ($courseObj instanceof MasterCourse) {
            $isOwner = $courseObj->user_id === $vendorId || Auth::user()->isAdmin();
        }

        if (!$isOwner) {
            abort(403, 'Anda tidak memiliki akses ke course sertifikasi ini.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:pdf,docx,pptx,mp4,zip,rar', 'max:51200'],
            'target_scope' => ['nullable', \Illuminate\Validation\Rule::in(['all', 'batch'])],
        ]);

        $path = $request->file('file')->store('materials', 'public');
        $targetScope = $validated['target_scope'] ?? $request->input('scope', 'all');

        $masterCourseId = ($courseObj instanceof MasterCourse) ? $courseObj->id : ($courseObj->master_course_id ?? $courseObj->id);
        $masterCourse = MasterCourse::find($masterCourseId);

        if ($targetScope === 'all' && $masterCourse) {
            // Polymorphic upload to MasterCourse (Induk Sertifikasi -> Semua Batch)
            $masterCourse->materials()->create([
                'title' => $validated['title'],
                'file_path' => $path,
            ]);
            $scopeMsg = 'Semua Batch Pelatihan';
        } else {
            // Polymorphic upload to CourseOffering (Khusus Batch Ini)
            $targetOffering = ($courseObj instanceof CourseOffering) ? $courseObj : $masterCourse->offerings()->first();
            if ($targetOffering) {
                $targetOffering->materials()->create([
                    'title' => $validated['title'],
                    'file_path' => $path,
                ]);
                $scopeMsg = 'Batch ' . ($targetOffering->batch_name ?: $targetOffering->id);
            } else {
                $masterCourse->materials()->create([
                    'title' => $validated['title'],
                    'file_path' => $path,
                ]);
                $scopeMsg = 'Semua Batch';
            }
        }

        return back()->with('success', "Materi pembelajaran '{$validated['title']}' berhasil diunggah untuk {$scopeMsg}.");
    }

    public function destroy(Request $request, Material $material): RedirectResponse
    {
        $vendorId = Auth::id();
        $isAuthorized = false;

        if ($material->materialable) {
            if ($material->materialable instanceof MasterCourse) {
                $isAuthorized = $material->materialable->user_id === $vendorId || Auth::user()->isAdmin();
            } elseif ($material->materialable instanceof CourseOffering) {
                $isAuthorized = $material->materialable->lecturer_id === $vendorId ||
                                ($material->materialable->masterCourse && $material->materialable->masterCourse->user_id === $vendorId) ||
                                Auth::user()->isAdmin();
            }
        } else {
            $isAuthorized = ($material->courseOffering && $material->courseOffering->lecturer_id === $vendorId) ||
                            ($material->masterCourse && $material->masterCourse->user_id === $vendorId) ||
                            Auth::user()->isAdmin();
        }

        if (!$isAuthorized) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return back()->with('success', 'Materi pembelajaran berhasil dihapus.');
    }
}