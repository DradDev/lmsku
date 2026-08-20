<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\CourseOffering;
use App\Models\Material;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function store(Request $request, CourseOffering $course): RedirectResponse
    {
        $vendorId = Auth::id();
        $isOwner = $course->lecturer_id === $vendorId ||
                   ($course->masterCourse && $course->masterCourse->user_id === $vendorId) ||
                   Auth::user()->isAdmin();

        if (!$isOwner) {
            abort(403, 'Anda tidak memiliki akses ke course sertifikasi ini.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:pdf,docx,pptx,mp4,zip', 'max:51200'],
        ]);

        $path = $request->file('file')->store('materials', 'public');
        $masterCourseId = $course->master_course_id ?? $course->id;

        // Ambil semua batch milik vendor di bawah kurikulum sertifikasi ini
        $vendorBatches = CourseOffering::where('master_course_id', $masterCourseId)
            ->where(function ($q) use ($vendorId) {
                if (!Auth::user()->isAdmin()) {
                    $q->where('lecturer_id', $vendorId)
                      ->orWhereHas('masterCourse', function ($mc) use ($vendorId) {
                          $mc->where('user_id', $vendorId);
                      });
                }
            })
            ->get();

        if ($vendorBatches->isNotEmpty()) {
            foreach ($vendorBatches as $batch) {
                Material::create([
                    'master_course_id'   => $masterCourseId,
                    'course_offering_id' => $batch->id,
                    'title'              => $validated['title'],
                    'file_path'          => $path,
                ]);
            }
        } else {
            Material::create([
                'master_course_id'   => $masterCourseId,
                'course_offering_id' => $course->id,
                'title'              => $validated['title'],
                'file_path'          => $path,
            ]);
        }

        return back()->with('success', "Materi pembelajaran '{$validated['title']}' berhasil diunggah.");
    }

    public function destroy(Request $request, Material $material): RedirectResponse
    {
        $vendorId = Auth::id();
        $isAuthorized = ($material->courseOffering && $material->courseOffering->lecturer_id === $vendorId) ||
                        ($material->masterCourse && $material->masterCourse->user_id === $vendorId) ||
                        Auth::user()->isAdmin();

        if (!$isAuthorized) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        $currentCourseId = $request->input('course_id') ?? $material->course_offering_id;

        // Jika materi berstatus global (course_offering_id is null) dan dihapus dari batch tertentu:
        // Salin ke batch lain milik vendor agar batch lain tidak kehilangan materi
        if ($material->course_offering_id === null && $currentCourseId) {
            $otherBatches = CourseOffering::where('master_course_id', $material->master_course_id)
                ->where('id', '!=', $currentCourseId)
                ->where(function ($q) use ($vendorId) {
                    if (!Auth::user()->isAdmin()) {
                        $q->where('lecturer_id', $vendorId)
                          ->orWhereHas('masterCourse', function ($mc) use ($vendorId) {
                              $mc->where('user_id', $vendorId);
                          });
                    }
                })
                ->get();

            foreach ($otherBatches as $otherBatch) {
                Material::create([
                    'master_course_id'   => $material->master_course_id,
                    'course_offering_id' => $otherBatch->id,
                    'title'              => $material->title,
                    'file_path'          => $material->file_path,
                ]);
            }
        }

        $filePath = $material->file_path;
        $material->delete();

        // Hapus file fisik dari storage hanya jika sudah tidak digunakan oleh baris material lain
        if (!empty($filePath)) {
            $stillUsed = Material::where('file_path', $filePath)->exists();
            if (!$stillUsed && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }

        return back()->with('success', 'Materi pembelajaran berhasil dihapus dari course ini.');
    }
}
