<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\MasterCourse;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $lecturerId = Auth::id();

        $masterCourseIds = CourseOffering::where('lecturer_id', $lecturerId)
            ->pluck('master_course_id')
            ->merge(MasterCourse::where('user_id', $lecturerId)->pluck('id'))
            ->unique()
            ->filter();

        $offeringIds = CourseOffering::where('lecturer_id', $lecturerId)
            ->pluck('id');

        $query = Material::with(['materialable'])
            ->where(function ($q) use ($masterCourseIds, $offeringIds) {
                $q->where(function ($sub) use ($masterCourseIds) {
                    $sub->where('materialable_type', MasterCourse::class)
                        ->whereIn('materialable_id', $masterCourseIds);
                })
                ->orWhere(function ($sub) use ($offeringIds) {
                    $sub->where('materialable_type', CourseOffering::class)
                        ->whereIn('materialable_id', $offeringIds);
                });
            });

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->input('filter') === 'master') {
            $query->where('materialable_type', MasterCourse::class);
        } elseif ($request->input('filter') === 'class') {
            $query->where('materialable_type', CourseOffering::class);
        }

        $materials = $query->latest()->paginate(15)->withQueryString();

        $offerings = CourseOffering::with(['masterCourse', 'academicTerm'])
            ->where('lecturer_id', $lecturerId)
            ->get();

        return view('lecturer.materials.index', compact('materials', 'offerings'));
    }

    private function authorizeLecturer(Material $material): void
    {
        $lecturerId = Auth::id();

        if ($material->materialable) {
            if ($material->materialable instanceof MasterCourse) {
                $hasTeachingRole = CourseOffering::where('master_course_id', $material->materialable_id)
                    ->where('lecturer_id', $lecturerId)
                    ->exists();
                if ($hasTeachingRole || (int)$material->materialable->user_id === (int)$lecturerId) {
                    return;
                }
            } elseif ($material->materialable instanceof CourseOffering) {
                if ((int)$material->materialable->lecturer_id === (int)$lecturerId) {
                    return;
                }
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
        if ($material && $material->materialable instanceof CourseOffering) {
            $term = $material->materialable->academicTerm;
            if ($term && !$term->is_active) {
                abort(403, 'Semester untuk kelas ini telah non-aktif / ditutup. Modifikasi materi tidak diizinkan.');
            }
        }
    }

    public function create($course, Request $request)
    {
        $courseObj = is_numeric($course)
            ? (CourseOffering::with('academicTerm')->find($course) ?? MasterCourse::find($course) ?? Course::findOrFail($course))
            : $course;

        $lecturerId = Auth::id();
        $isLecturer = false;

        if ($courseObj instanceof CourseOffering) {
            $isLecturer = (int)$courseObj->lecturer_id === (int)$lecturerId;
            if ($courseObj->academicTerm && !$courseObj->academicTerm->is_active) {
                return redirect()->route('lecturer.courses.show', $courseObj->id)
                    ->with('error', 'Semester untuk kelas ini telah non-aktif / ditutup. Penambahan materi dikunci (Read-Only).');
            }
        } elseif ($courseObj instanceof MasterCourse) {
            $isLecturer = CourseOffering::where('master_course_id', $courseObj->id)->where('lecturer_id', $lecturerId)->exists() || (int)$courseObj->user_id === (int)$lecturerId;
        } else {
            $isLecturer = (int)($courseObj->lecturer_id ?? $courseObj->user_id) === (int)$lecturerId;
        }

        abort_unless($isLecturer, 403, 'Kamu tidak memiliki akses ke course ini.');

        $targetScope = $request->query('scope', 'all');

        return view('lecturer.materials.create', [
            'course' => $courseObj,
            'targetScope' => $targetScope,
        ]);
    }

    public function store(Request $request, $course)
    {
        $courseObj = is_numeric($course)
            ? (CourseOffering::with('academicTerm')->find($course) ?? MasterCourse::find($course) ?? Course::findOrFail($course))
            : $course;

        $lecturerId = Auth::id();
        $isLecturer = false;

        if ($courseObj instanceof CourseOffering) {
            $isLecturer = (int)$courseObj->lecturer_id === (int)$lecturerId;
            if ($courseObj->academicTerm && !$courseObj->academicTerm->is_active) {
                return redirect()->back()
                    ->with('error', 'Semester untuk kelas ini telah non-aktif / ditutup. Penambahan materi ditolak.');
            }
        } elseif ($courseObj instanceof MasterCourse) {
            $isLecturer = CourseOffering::where('master_course_id', $courseObj->id)->where('lecturer_id', $lecturerId)->exists() || (int)$courseObj->user_id === (int)$lecturerId;
        } else {
            $isLecturer = (int)($courseObj->lecturer_id ?? $courseObj->user_id) === (int)$lecturerId;
        }

        abort_unless($isLecturer, 403, 'Kamu tidak memiliki akses ke course ini.');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,ppt,pptx,zip,rar', 'max:20480'],
            'target_scope' => ['nullable', \Illuminate\Validation\Rule::in(['all', 'class'])],
        ]);

        $targetScope = $validated['target_scope'] ?? ($request->input('scope') ?? 'all');
        $filePath = $request->file('file')->store('materials', 'public');

        $masterCourseId = ($courseObj instanceof MasterCourse) ? $courseObj->id : ($courseObj->master_course_id ?? $courseObj->id);
        $masterCourse = MasterCourse::find($masterCourseId);

        if ($targetScope === 'all' && $masterCourse) {
            $material = $masterCourse->materials()->create([
                'title' => $validated['title'],
                'file_path' => $filePath,
            ]);

            if ($masterCourse->offerings) {
                foreach ($masterCourse->offerings as $sibling) {
                    app(\App\Services\CourseProgressService::class)->recalculateAllForCourse($sibling->id);
                }
            }
            $scopeMsg = 'Seluruh Rombel Kelas Kurikulum';
        } else {
            $offering = ($courseObj instanceof CourseOffering) ? $courseObj : $masterCourse->offerings()->where('lecturer_id', $lecturerId)->first();
            
            if ($offering) {
                $material = $offering->materials()->create([
                    'title' => $validated['title'],
                    'file_path' => $filePath,
                ]);
                app(\App\Services\CourseProgressService::class)->recalculateAllForCourse($offering->id);
                $scopeMsg = "khusus " . ($offering->section_name ?: 'Kelas Ini');
            } else {
                $material = $masterCourse->materials()->create([
                    'title' => $validated['title'],
                    'file_path' => $filePath,
                ]);
                $scopeMsg = 'Semua Rombel';
            }
        }

        $redirectId = ($courseObj instanceof CourseOffering) ? $courseObj->id : ($courseObj->id ?? $masterCourseId);

        return redirect()
            ->route('lecturer.courses.show', $redirectId)
            ->with('success', "Materi berhasil diupload secara polymorphic untuk {$scopeMsg}.");
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
        ]);

        $material->title = $validated['title'];

        if ($request->hasFile('file')) {
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }
            $material->file_path = $request->file('file')->store('materials', 'public');
        }

        $material->save();

        $redirectOfferingId = $material->materialable_type === CourseOffering::class ? $material->materialable_id : null;

        if ($redirectOfferingId) {
            return redirect()
                ->route('lecturer.courses.show', $redirectOfferingId)
                ->with('success', 'Materi berhasil diperbarui.');
        }

        return redirect()
            ->route('lecturer.materials.index')
            ->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Material $material)
    {
        $this->authorizeLecturer($material);
        $this->checkTermActive($material);

        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return back()->with('success', 'Materi berhasil dihapus.');
    }
}