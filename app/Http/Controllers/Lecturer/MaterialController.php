<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function create(Course $course)
    {
        abort_unless($course->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke course ini.');

        return view('lecturer.materials.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        abort_unless($course->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke course ini.');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,ppt,pptx', 'max:20480'],
        ]);

        $filePath = $request->file('file')->store('materials', 'public');

        $masterCourseId = $course->master_course_id ?? \App\Models\MasterCourse::where('name', $course->name)->value('id');

        Material::create([
            'course_id' => $course->id,
            'master_course_id' => $masterCourseId,
            'title' => $validated['title'],
            'file_path' => $filePath,
        ]);

        return redirect()
            ->route('lecturer.courses.show', $course->id)
            ->with('success', 'Materi berhasil diupload.');
    }

    public function show(Material $material)
    {
        abort_unless(
            $material->course && $material->course->user_id === Auth::id(),
            403
        );

        return view('lecturer.materials.show', compact('material'));
    }

    public function edit(Material $material)
    {
        abort_unless(
            $material->course && $material->course->user_id === Auth::id(),
            403
        );

        return view('lecturer.materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        abort_unless(
            $material->course && $material->course->user_id === Auth::id(),
            403
        );

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx', 'max:20480'],
        ]);

        $updateData = [
            'title' => $validated['title'],
        ];

        if ($request->hasFile('file')) {
            if (!empty($material->file_path) && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }

            $updateData['file_path'] = $request->file('file')->store('materials', 'public');
        }

        $material->update($updateData);

        return redirect()
            ->route('lecturer.courses.show', $material->course_id)
            ->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Material $material)
    {
        abort_unless(
            $material->course && $material->course->user_id === Auth::id(),
            403
        );

        $courseId = $material->course_id;

        if (!empty($material->file_path) && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()
            ->route('lecturer.courses.show', $courseId)
            ->with('success', 'Materi berhasil dihapus.');
    }
}