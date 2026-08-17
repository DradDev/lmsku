<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\MasterCourse;
use App\Models\Skill;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $vendorId = Auth::id();

        // 1. Pastikan seluruh legacy course milik vendor terhubung ke MasterCourse
        $legacyWithoutMaster = Course::where('user_id', $vendorId)
            ->whereNull('master_course_id')
            ->get();

        foreach ($legacyWithoutMaster as $lc) {
            $mc = MasterCourse::firstOrCreate(
                [
                    'name' => $lc->name,
                    'user_id' => $vendorId,
                ],
                [
                    'code' => 'VMC-' . strtoupper(Str::random(6)),
                    'description' => $lc->description,
                    'level' => $lc->level ?? 'Beginner',
                    'certificate_threshold' => $lc->certificate_threshold ?? 75,
                    'category_id' => $lc->category_id,
                ]
            );
            $lc->update(['master_course_id' => $mc->id]);
        }

        // 2. Ambil MasterCourse milik Vendor beserta angkatan batch dan relasinya
        $masterCourses = MasterCourse::with([
            'category',
            'skills',
            'tags',
            'materials',
            'quizzes',
            'courses.enrollments',
        ])
        ->where('user_id', $vendorId)
        ->latest()
        ->get();

        // Ambil data courses untuk fallback & kalkulasi
        $allBatches = Course::with(['materials', 'quizzes', 'enrollments', 'category'])
            ->where('user_id', $vendorId)
            ->latest()
            ->get();

        $activeMasterCourses = $masterCourses->filter(function ($mc) {
            // Aktif jika memiliki setidaknya 1 batch yang tidak diarsipkan
            return $mc->courses->where('is_archived', false)->count() > 0 || $mc->courses->isEmpty();
        })->values();

        $archivedMasterCourses = $masterCourses->filter(function ($mc) {
            return $mc->courses->isNotEmpty() && $mc->courses->every(fn($c) => (bool)$c->is_archived);
        })->values();

        return view('vendor.courses.index', compact(
            'masterCourses',
            'activeMasterCourses',
            'archivedMasterCourses',
            'allBatches'
        ));
    }

    public function toggleArchive(Course $course): RedirectResponse
    {
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke course ini.');
        }

        $newStatus = !$course->is_archived;
        $course->update(['is_archived' => $newStatus]);

        $statusLabel = $newStatus ? 'diarsipkan (Draft Bank)' : 'diaktifkan dan dibuka kembali';

        return back()->with('success', "Status Angkatan '{$course->batch_name}' pada '{$course->name}' berhasil {$statusLabel}.");
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $skills = Skill::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('vendor.courses.create', compact('categories', 'skills', 'tags'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'batch_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'level' => ['required', 'in:Beginner,Intermediate,Advanced'],
            'duration_weeks' => ['nullable', 'integer', 'min:1'],
            'certificate_threshold' => ['required', 'integer', 'min:0', 'max:100'],
            'is_archived' => ['nullable', 'boolean'],
            'skill_ids' => ['required', 'array', 'min:1'],
            'skill_ids.*' => ['exists:skills,id'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
        ]);

        // 1. Create or find parent MasterCourse for Vendor (Strict 3NF)
        $masterCourse = MasterCourse::firstOrCreate(
            [
                'name' => $validated['name'],
                'user_id' => Auth::id(),
            ],
            [
                'code' => 'VMC-' . strtoupper(Str::random(6)),
                'description' => $validated['description'],
                'level' => $validated['level'],
                'certificate_threshold' => $validated['certificate_threshold'],
                'category_id' => $validated['category_id'],
            ]
        );

        $skillsData = [];
        $masterSkillsData = [];
        foreach ($validated['skill_ids'] as $sId) {
            $skillsData[$sId] = ['is_main' => true, 'weight' => 1.00];
            $masterSkillsData[$sId] = ['is_main' => true];
        }
        $masterCourse->skills()->sync($masterSkillsData);
        if (!empty($validated['tag_ids'])) {
            $masterCourse->tags()->sync($validated['tag_ids']);
        }

        // 2. Buat Inaugural Batch (Angkatan Perdana)
        $validated['user_id'] = Auth::id();
        $validated['master_course_id'] = $masterCourse->id;
        $validated['progress'] = 0;
        $validated['duration_weeks'] = $validated['duration_weeks'] ?? 4;
        $validated['batch_name'] = $validated['batch_name'] ?? 'Batch 1 - 2026';

        $course = Course::create($validated);
        $course->skills()->sync($skillsData);

        if (!empty($validated['tag_ids'])) {
            $course->tags()->sync($validated['tag_ids']);
        }

        return redirect()
            ->route('vendor.courses.show', $course)
            ->with('success', 'Program Sertifikasi Industri & Angkatan ' . $course->batch_name . ' berhasil dipublikasikan.');
    }

    public function show(Course $course): View
    {
        if (Auth::user()->role !== 'admin' && $course->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke course sertifikasi ini.');
        }

        // Ensure MasterCourse link exists
        if (!$course->master_course_id) {
            $masterCourse = MasterCourse::firstOrCreate(
                [
                    'name' => $course->name,
                    'user_id' => $course->user_id,
                ],
                [
                    'code' => 'VMC-' . strtoupper(Str::random(6)),
                    'description' => $course->description,
                    'level' => $course->level,
                    'certificate_threshold' => $course->certificate_threshold ?? 75,
                    'category_id' => $course->category_id,
                ]
            );
            $course->update(['master_course_id' => $masterCourse->id]);
        }

        $course->load([
            'category',
            'materials',
            'quizzes.questions',
            'enrollments.user',
            'students',
            'skills',
            'tags',
            'masterCourse.materials',
            'masterCourse.quizzes.questions',
            'masterCourse.skills',
            'masterCourse.tags',
        ]);

        // Materi & Kuis terpusat dari MasterCourse & seluruh batch di bawah kurikulum ini
        $masterCourse = $course->masterCourse;
        $materials = $masterCourse
            ? \App\Models\Material::where('master_course_id', $masterCourse->id)->orWhere('course_id', $course->id)->get()
            : $course->materials;

        $quizzes = $masterCourse
            ? \App\Models\Quiz::with('questions')->where('master_course_id', $masterCourse->id)->orWhere('course_id', $course->id)->get()
            : $course->quizzes;

        $students = $course->students;
        $completedStudentCount = $course->enrollments()->where('status', 'completed')->count();

        // Seluruh angkatan batch yang ada pada kurikulum induk ini
        $allBatches = Course::where('master_course_id', $course->master_course_id)
            ->withCount('enrollments')
            ->orderBy('created_at')
            ->get();

        $otherBatches = $allBatches->where('id', '!=', $course->id);

        $categories = Category::orderBy('name')->get();
        $skills = Skill::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('vendor.courses.show', compact(
            'course',
            'masterCourse',
            'materials',
            'quizzes',
            'students',
            'completedStudentCount',
            'allBatches',
            'otherBatches',
            'categories',
            'skills',
            'tags'
        ));
    }

    public function edit(Course $course): View
    {
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke course sertifikasi ini.');
        }

        $categories = Category::orderBy('name')->get();
        $skills = Skill::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('vendor.courses.edit', compact('course', 'categories', 'skills', 'tags'));
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke course sertifikasi ini.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'level' => ['required', 'in:Beginner,Intermediate,Advanced'],
            'skill_ids' => ['required', 'array', 'min:1'],
            'skill_ids.*' => ['exists:skills,id'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
            'batch_name' => ['nullable', 'string', 'max:255'],
            'duration_weeks' => ['nullable', 'integer', 'min:1'],
            'certificate_threshold' => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_archived' => ['nullable', 'boolean'],
        ]);

        // 1. Update MasterCourse
        if ($course->master_course_id) {
            $masterCourse = MasterCourse::find($course->master_course_id);
            if ($masterCourse) {
                $masterCourse->update([
                    'name' => $validated['name'],
                    'description' => $validated['description'],
                    'level' => $validated['level'],
                    'category_id' => $validated['category_id'],
                ]);

                $masterSkillsData = [];
                foreach ($validated['skill_ids'] as $sId) {
                    $masterSkillsData[$sId] = ['is_main' => true];
                }
                $masterCourse->skills()->sync($masterSkillsData);

                if (isset($validated['tag_ids'])) {
                    $masterCourse->tags()->sync($validated['tag_ids']);
                }

                // Sinkronkan metadata master ke seluruh batch turunan
                Course::where('master_course_id', $masterCourse->id)->update([
                    'name' => $validated['name'],
                    'description' => $validated['description'],
                    'level' => $validated['level'],
                    'category_id' => $validated['category_id'],
                ]);
            }
        }

        // 2. Update field batch spesifik jika ada
        $batchUpdate = [
            'name' => $validated['name'],
            'description' => $validated['description'],
            'level' => $validated['level'],
            'category_id' => $validated['category_id'],
        ];

        if (!empty($validated['batch_name'])) {
            $batchUpdate['batch_name'] = $validated['batch_name'];
        }
        if (isset($validated['duration_weeks'])) {
            $batchUpdate['duration_weeks'] = $validated['duration_weeks'];
        }
        if (isset($validated['certificate_threshold'])) {
            $batchUpdate['certificate_threshold'] = $validated['certificate_threshold'];
        }
        if (isset($validated['is_archived'])) {
            $batchUpdate['is_archived'] = $validated['is_archived'];
        }

        $course->update($batchUpdate);

        $skillsData = [];
        foreach ($validated['skill_ids'] as $sId) {
            $skillsData[$sId] = ['is_main' => true, 'weight' => 1.00];
        }
        $course->skills()->sync($skillsData);

        if (isset($validated['tag_ids'])) {
            $course->tags()->sync($validated['tag_ids']);
        }

        return redirect()
            ->route('vendor.courses.show', $course)
            ->with('success', 'Informasi Program Sertifikasi Industri berhasil diperbarui.');
    }

    public function updateBatch(Request $request, Course $course): RedirectResponse
    {
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke batch ini.');
        }

        $validated = $request->validate([
            'batch_name' => ['required', 'string', 'max:255'],
            'certificate_threshold' => ['required', 'integer', 'min:0', 'max:100'],
            'duration_weeks' => ['nullable', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_archived' => ['nullable', 'boolean'],
        ]);

        if ($request->has('is_archived')) {
            $validated['is_archived'] = (bool)$request->is_archived;
        }

        $course->update($validated);

        return redirect()
            ->route('vendor.courses.show', $course)
            ->with('success', "Pengaturan Angkatan '{$course->batch_name}' berhasil diperbarui.");
    }

    public function launchBatch(Request $request, Course $course): RedirectResponse
    {
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke course sertifikasi ini.');
        }

        $validated = $request->validate([
            'batch_name' => ['required', 'string', 'max:255'],
            'certificate_threshold' => ['required', 'integer', 'min:0', 'max:100'],
            'duration_weeks' => ['nullable', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        // Pastikan MasterCourse ada
        if (!$course->master_course_id) {
            $masterCourse = MasterCourse::firstOrCreate(
                [
                    'name' => $course->name,
                    'user_id' => Auth::id(),
                ],
                [
                    'code' => 'VMC-' . strtoupper(Str::random(6)),
                    'description' => $course->description,
                    'level' => $course->level,
                    'certificate_threshold' => $course->certificate_threshold ?? 75,
                    'category_id' => $course->category_id,
                ]
            );
            $course->update(['master_course_id' => $masterCourse->id]);
        }

        $newBatch = Course::create([
            'name' => $course->name,
            'batch_name' => $validated['batch_name'],
            'description' => $course->description,
            'user_id' => Auth::id(),
            'master_course_id' => $course->master_course_id,
            'level' => $course->level,
            'progress' => 0,
            'duration_weeks' => $validated['duration_weeks'] ?? $course->duration_weeks ?? 4,
            'certificate_threshold' => $validated['certificate_threshold'],
            'category_id' => $course->category_id,
            'start_date' => !empty($validated['start_date']) ? $validated['start_date'] : null,
            'end_date' => !empty($validated['end_date']) ? $validated['end_date'] : null,
            'is_archived' => false,
        ]);

        // Wariskan relasi skills & tags
        $skillsData = [];
        foreach ($course->skills as $s) {
            $skillsData[$s->id] = ['is_main' => $s->pivot->is_main ?? true, 'weight' => $s->pivot->weight ?? 1.00];
        }
        $newBatch->skills()->sync($skillsData);
        $newBatch->tags()->sync($course->tags->pluck('id')->toArray());

        return redirect()
            ->route('vendor.courses.show', $newBatch)
            ->with('success', 'Angkatan ' . $newBatch->batch_name . ' berhasil diluncurkan! Seluruh materi dan bank kuis otomatis diwariskan.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke course sertifikasi ini.');
        }

        if ($course->enrollments()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus angkatan yang sudah memiliki mahasiswa terdaftar. Silakan gunakan fitur arsip.');
        }

        $course->delete();

        return redirect()
            ->route('vendor.courses.index')
            ->with('success', 'Angkatan Course Sertifikasi berhasil dihapus.');
    }
}
