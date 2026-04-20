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
    public function index()
    {
        $materials = Material::with('course')
            ->whereHas('course', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->latest()
            ->get();

        return view('lecturer.materials.index', compact('materials'));
    }

    public function create()
    {
        $courses = Course::where('user_id', Auth::id())
            ->latest()
            ->get();

        if ($courses->isEmpty()) {
            return redirect()
                ->route('lecturer.courses.create')
                ->with('success', 'Buat course terlebih dahulu sebelum upload materi.');
        }

        return view('lecturer.materials.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,ppt,pptx', 'max:20480'],
        ]);

        $course = Course::where('user_id', Auth::id())
            ->findOrFail($validated['course_id']);

        $filePath = $request->file('file')->store('materials', 'public');

        Material::create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'file_path' => $filePath,
        ]);

        return redirect()
            ->route('lecturer.materials.index')
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

        $courses = Course::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('lecturer.materials.edit', compact('material', 'courses'));
    }

    public function update(Request $request, Material $material)
    {
        abort_unless(
            $material->course && $material->course->user_id === Auth::id(),
            403
        );

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx', 'max:20480'],
        ]);

        $course = Course::where('user_id', Auth::id())
            ->findOrFail($validated['course_id']);

        $updateData = [
            'course_id' => $course->id,
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
            ->route('lecturer.materials.index')
            ->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Material $material)
    {
        abort_unless(
            $material->course && $material->course->user_id === Auth::id(),
            403
        );

        if (!empty($material->file_path) && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()
            ->route('lecturer.materials.index')
            ->with('success', 'Materi berhasil dihapus.');
    }
}
