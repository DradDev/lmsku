<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    private function authorizeLecturer(Material $material): void
    {
        $lecturerId = Auth::id();

        // 1. Check via course_offering_id
        if ($material->course_offering_id) {
            $offering = CourseOffering::find($material->course_offering_id);
            if ($offering && (int)$offering->lecturer_id === (int)$lecturerId) {
                return;
            }
        }

        // 2. Check via master_course_id (is lecturer assigned to any offering of this master course?)
        if ($material->master_course_id) {
            $isAssigned = CourseOffering::where('master_course_id', $material->master_course_id)
                ->where('lecturer_id', $lecturerId)
                ->exists();
            if ($isAssigned) {
                return;
            }
        }

        // 3. Check via course_id
        if ($material->course_id) {
            $offering = CourseOffering::find($material->course_id);
            if ($offering && (int)$offering->lecturer_id === (int)$lecturerId) {
                return;
            }

            $legacyCourse = Course::find($material->course_id);
            if ($legacyCourse && (int)$legacyCourse->user_id === (int)$lecturerId) {
                return;
            }
        }

        abort(403, 'Kamu tidak memiliki akses ke materi ini.');
    }

    public function create($course)
    {
        $courseObj = is_numeric($course)
            ? (CourseOffering::find($course) ?? Course::findOrFail($course))
            : $course;

        $lecturerId = Auth::id();
        $isLecturer = false;

        if ($courseObj instanceof CourseOffering) {
            $isLecturer = (int)$courseObj->lecturer_id === (int)$lecturerId;
        } else {
            $isLecturer = (int)$courseObj->user_id === (int)$lecturerId;
        }

        abort_unless($isLecturer, 403, 'Kamu tidak memiliki akses ke course ini.');

        return view('lecturer.materials.create', ['course' => $courseObj]);
    }

    public function store(Request $request, $course)
    {
        $courseObj = is_numeric($course)
            ? (CourseOffering::find($course) ?? Course::findOrFail($course))
            : $course;

        $lecturerId = Auth::id();
        $isLecturer = false;

        if ($courseObj instanceof CourseOffering) {
            $isLecturer = (int)$courseObj->lecturer_id === (int)$lecturerId;
        } else {
            $isLecturer = (int)$courseObj->user_id === (int)$lecturerId;
        }

        abort_unless($isLecturer, 403, 'Kamu tidak memiliki akses ke course ini.');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,ppt,pptx,zip,rar', 'max:20480'],
            'target_scope' => ['nullable', \Illuminate\Validation\Rule::in(['all', 'class'])],
        ]);

        $targetScope = $validated['target_scope'] ?? 'all';
        $filePath = $request->file('file')->store('materials', 'public');

        $masterCourseId = $courseObj->master_course_id ?? $courseObj->id;
        $offeringId = ($courseObj instanceof CourseOffering) ? $courseObj->id : null;

        Material::create([
            'course_id' => $courseObj->id,
            'master_course_id' => $masterCourseId,
            'course_offering_id' => $targetScope === 'class' ? $offeringId : null,
            'title' => $validated['title'],
            'file_path' => $filePath,
        ]);

        $scopeMsg = $targetScope === 'all'
            ? 'Pustaka Induk (Semua Kelas)'
            : "khusus " . ($courseObj->section_name ?: 'Kelas Ini');

        return redirect()
            ->route('lecturer.courses.show', $courseObj->id)
            ->with('success', "Materi berhasil diupload untuk {$scopeMsg}.");
    }

    public function show(Material $material)
    {
        $this->authorizeLecturer($material);

        return view('lecturer.materials.show', compact('material'));
    }

    public function edit(Material $material)
    {
        $this->authorizeLecturer($material);

        return view('lecturer.materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        $this->authorizeLecturer($material);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,zip,rar', 'max:20480'],
            'target_scope' => ['nullable', \Illuminate\Validation\Rule::in(['all', 'class'])],
        ]);

        $updateData = [
            'title' => $validated['title'],
        ];

        if ($request->has('target_scope')) {
            $targetScope = $validated['target_scope'];
            if ($targetScope === 'all') {
                $updateData['course_offering_id'] = null;
            } elseif ($material->course_id) {
                $updateData['course_offering_id'] = $material->course_id;
            }
        }

        if ($request->hasFile('file')) {
            if (!empty($material->file_path) && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }

            $updateData['file_path'] = $request->file('file')->store('materials', 'public');
        }

        $material->update($updateData);

        $redirectId = $material->course_offering_id ?? $material->course_id;

        return redirect()
            ->route('lecturer.courses.show', $redirectId)
            ->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Material $material)
    {
        $this->authorizeLecturer($material);

        $redirectId = $material->course_offering_id ?? $material->course_id;

        if (!empty($material->file_path) && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()
            ->route('lecturer.courses.show', $redirectId)
            ->with('success', 'Materi berhasil dihapus.');
    }
}