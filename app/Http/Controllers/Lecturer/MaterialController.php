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

        // 1. Check via master_course_id
        if ($material->master_course_id) {
            $hasTeachingRole = CourseOffering::where('master_course_id', $material->master_course_id)
                ->where('lecturer_id', $lecturerId)
                ->exists();

            if ($hasTeachingRole) {
                return;
            }
        }

        // 2. Check via course_offering_id
        if ($material->course_offering_id) {
            $offering = CourseOffering::find($material->course_offering_id);
            if ($offering && (int)$offering->lecturer_id === (int)$lecturerId) {
                return;
            }
        }

        abort(403, 'Kamu tidak memiliki akses ke materi ini.');
    }

    private function checkTermActive(?Material $material = null, $courseObj = null): void
    {
        if ($courseObj instanceof CourseOffering) {
            $term = $courseObj->academicTerm;
            if ($term && !$term->is_active) {
                abort(403, 'Semester untuk kelas ini telah non-aktif / ditutup. Modifikasi materi tidak diizinkan.');
            }
        }
        if ($material) {
            $offeringId = $material->course_offering_id;
            if ($offeringId) {
                $offering = CourseOffering::with('academicTerm')->find($offeringId);
                if ($offering && $offering->academicTerm && !$offering->academicTerm->is_active) {
                    abort(403, 'Semester untuk kelas ini telah non-aktif / ditutup. Modifikasi materi tidak diizinkan.');
                }
            }
        }
    }

    public function create($course)
    {
        $courseObj = is_numeric($course)
            ? (CourseOffering::with('academicTerm')->find($course) ?? Course::findOrFail($course))
            : $course;

        $lecturerId = Auth::id();
        $isLecturer = false;

        if ($courseObj instanceof CourseOffering) {
            $isLecturer = (int)$courseObj->lecturer_id === (int)$lecturerId;
            if ($courseObj->academicTerm && !$courseObj->academicTerm->is_active) {
                return redirect()->route('lecturer.courses.show', $courseObj->id)
                    ->with('error', 'Semester untuk kelas ini telah non-aktif / ditutup. Penambahan materi dikunci (Read-Only).');
            }
        } else {
            $isLecturer = (int)($courseObj->lecturer_id ?? $courseObj->user_id) === (int)$lecturerId;
        }

        abort_unless($isLecturer, 403, 'Kamu tidak memiliki akses ke course ini.');

        return view('lecturer.materials.create', ['course' => $courseObj]);
    }

    public function store(Request $request, $course)
    {
        $courseObj = is_numeric($course)
            ? (CourseOffering::with('academicTerm')->find($course) ?? Course::findOrFail($course))
            : $course;

        $lecturerId = Auth::id();
        $isLecturer = false;

        if ($courseObj instanceof CourseOffering) {
            $isLecturer = (int)$courseObj->lecturer_id === (int)$lecturerId;
            if ($courseObj->academicTerm && !$courseObj->academicTerm->is_active) {
                return redirect()->back()
                    ->with('error', 'Semester untuk kelas ini telah non-aktif / ditutup. Penambahan materi ditolak.');
            }
        } else {
            $isLecturer = (int)($courseObj->lecturer_id ?? $courseObj->user_id) === (int)$lecturerId;
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

        if ($targetScope === 'all' && $courseObj instanceof CourseOffering) {
            $siblingOfferings = CourseOffering::where('lecturer_id', $lecturerId)
                ->where('master_course_id', $masterCourseId)
                ->where('academic_term_id', $courseObj->academic_term_id)
                ->get();

            if ($siblingOfferings->isNotEmpty()) {
                foreach ($siblingOfferings as $sibling) {
                    Material::create([
                        'master_course_id' => $masterCourseId,
                        'course_offering_id' => $sibling->id,
                        'title' => $validated['title'],
                        'file_path' => $filePath,
                    ]);
                    app(\App\Services\CourseProgressService::class)->recalculateAllForCourse($sibling->id);
                }
            } else {
                Material::create([
                    'master_course_id' => $masterCourseId,
                    'course_offering_id' => $offeringId,
                    'title' => $validated['title'],
                    'file_path' => $filePath,
                ]);
                app(\App\Services\CourseProgressService::class)->recalculateAllForCourse($courseObj->id);
            }
        } else {
            Material::create([
                'master_course_id' => $masterCourseId,
                'course_offering_id' => $offeringId,
                'title' => $validated['title'],
                'file_path' => $filePath,
            ]);
            if ($offeringId) {
                app(\App\Services\CourseProgressService::class)->recalculateAllForCourse($offeringId);
            }
        }

        $scopeMsg = $targetScope === 'all'
            ? 'Semua Kelas Rombel'
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
        $this->checkTermActive($material);

        return view('lecturer.materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        $this->authorizeLecturer($material);
        $this->checkTermActive($material);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,zip,rar', 'max:20480'],
            'target_scope' => ['nullable', \Illuminate\Validation\Rule::in(['all', 'class'])],
        ]);

        $updateData = [
            'title' => $validated['title'],
        ];

        if (isset($validated['target_scope'])) {
            if ($validated['target_scope'] === 'all') {
                $updateData['course_offering_id'] = null;
            } elseif (!empty($request->course_offering_id)) {
                $updateData['course_offering_id'] = $request->course_offering_id;
            }
        }

        if ($request->hasFile('file')) {
            if (!empty($material->file_path) && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }

            $updateData['file_path'] = $request->file('file')->store('materials', 'public');
        }

        $material->update($updateData);

        $offering = CourseOffering::where('master_course_id', $material->master_course_id)
            ->where('lecturer_id', Auth::id())
            ->first();
        $redirectId = $material->course_offering_id ?? ($offering?->id ?? 1);

        return redirect()
            ->route('lecturer.courses.show', $redirectId)
            ->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Request $request, Material $material)
    {
        $this->authorizeLecturer($material);
        $this->checkTermActive($material);

        $lecturerId = Auth::id();
        $offering = CourseOffering::where('master_course_id', $material->master_course_id)
            ->where('lecturer_id', $lecturerId)
            ->first();

        $currentCourseId = $request->input('course_id') ?? ($material->course_offering_id ?? $offering?->id);

        // Jika materi berstatus global (course_offering_id is null) dan dihapus dari kelas tertentu:
        // Amankan materi untuk kelas-kelas rombel lain (sibling) milik dosen sebelum materi ini dihapus dari kelas aktif
        if ($material->course_offering_id === null && $currentCourseId) {
            $currentOffering = CourseOffering::find($currentCourseId);
            if ($currentOffering) {
                $otherOfferings = CourseOffering::where('lecturer_id', $lecturerId)
                    ->where('master_course_id', $material->master_course_id)
                    ->where('id', '!=', $currentCourseId)
                    ->get();

                foreach ($otherOfferings as $otherOff) {
                    Material::create([
                        'master_course_id'   => $material->master_course_id,
                        'course_offering_id' => $otherOff->id,
                        'title'              => $material->title,
                        'file_path'          => $material->file_path,
                    ]);
                    app(\App\Services\CourseProgressService::class)->recalculateAllForCourse($otherOff->id);
                }
            }
        }

        $filePath = $material->file_path;
        $material->delete();

        // Hapus file fisik dari storage hanya jika sudah tidak ada baris materi lain yang menggunakannya
        if (!empty($filePath)) {
            $stillUsed = Material::where('file_path', $filePath)->exists();
            if (!$stillUsed && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }

        if ($currentCourseId) {
            app(\App\Services\CourseProgressService::class)->recalculateAllForCourse($currentCourseId);
        }

        return redirect()
            ->route('lecturer.courses.show', $currentCourseId ?? 1)
            ->with('success', 'Materi berhasil dihapus dari kelas ini.');
    }
}