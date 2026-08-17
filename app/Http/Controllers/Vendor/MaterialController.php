<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Material;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function store(Request $request, Course $course): RedirectResponse
    {
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke course sertifikasi ini.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:pdf,docx,pptx,mp4,zip', 'max:51200'],
        ]);

        $path = $request->file('file')->store('materials', 'public');

        Material::create([
            'course_id' => $course->id,
            'master_course_id' => $course->master_course_id,
            'title' => $validated['title'],
            'file_path' => $path,
        ]);

        return back()->with('success', "Materi pembelajaran '{$validated['title']}' berhasil diunggah ke kurikulum induk.");
    }

    public function destroy(Material $material): RedirectResponse
    {
        $vendorId = Auth::id();
        $isAuthorized = ($material->course && $material->course->user_id === $vendorId) ||
                        ($material->masterCourse && $material->masterCourse->user_id === $vendorId);

        if (!$isAuthorized && !Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return back()->with('success', 'Materi pembelajaran kurikulum berhasil dihapus.');
    }
}
