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

        $allCourses = Course::with(['materials', 'quizzes', 'students', 'category'])
            ->where('user_id', $vendorId)
            ->latest()
            ->get();

        $activeCourses = $allCourses->where('is_archived', false)->values();
        $bankCourses = $allCourses->where('is_archived', true)->values();

        return view('vendor.courses.index', compact('allCourses', 'activeCourses', 'bankCourses'));
    }

    public function toggleArchive(Course $course): RedirectResponse
    {
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke course ini.');
        }

        $newStatus = !$course->is_archived;
        $course->update(['is_archived' => $newStatus]);

        $statusLabel = $newStatus ? 'diarsipkan (Draft Bank)' : 'diaktifkan dan dibuka kembali';

        return back()->with('success', "Status Course '{$course->name}' berhasil {$statusLabel}.");
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

        // Create or find parent MasterCourse for Vendor (Strict 3NF)
        $masterCourse = MasterCourse::firstOrCreate(
            [
                'name' => $validated['name'],
                'user_id' => Auth::id(),
            ],
            [
                'code' => 'VMC-' . strtoupper(Str::random(6)),
                'description' => $validated['description'],
                'level' => $validated['level'],
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
            ->with('success', 'Course Sertifikasi Industri & Kurikulum Induk berhasil dipublikasikan.');
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
                    'category_id' => $course->category_id,
                ]
            );
            $course->update(['master_course_id' => $masterCourse->id]);
        }

        $course->load([
            'category',
            'materials',
            'quizzes.questions',
            'students',
            'skills',
            'tags',
            'masterCourse.materials',
            'masterCourse.quizzes',
        ]);

        // Combined materials & quizzes (From batch OR inherited from master course)
        $materials = $course->materials->merge($course->masterCourse->materials ?? collect())->unique('id');
        $quizzes = $course->quizzes->merge($course->masterCourse->quizzes ?? collect())->unique('id');
        $students = $course->students;
        $completedStudentCount = $course->enrollments()->where('status', 'completed')->count();

        // Other active batches under the same master course
        $otherBatches = Course::where('master_course_id', $course->master_course_id)
            ->where('id', '!=', $course->id)
            ->orderByDesc('created_at')
            ->get();

        return view('vendor.courses.show', compact('course', 'materials', 'quizzes', 'students', 'completedStudentCount', 'otherBatches'));
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

        $course->update($validated);

        // Also update parent MasterCourse if exists
        if ($course->master_course_id) {
            $masterCourse = MasterCourse::find($course->master_course_id);
            if ($masterCourse) {
                $masterCourse->update([
                    'name' => $validated['name'],
                    'description' => $validated['description'],
                    'level' => $validated['level'],
                    'category_id' => $validated['category_id'],
                ]);
            }
        }

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
            ->with('success', 'Course Sertifikasi Industri berhasil diperbarui.');
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
        ]);

        // Ensure MasterCourse exists
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
            'is_archived' => false,
        ]);

        // Sync skills & tags from parent course
        $skillsData = [];
        foreach ($course->skills as $s) {
            $skillsData[$s->id] = ['is_main' => $s->pivot->is_main ?? true, 'weight' => $s->pivot->weight ?? 1.00];
        }
        $newBatch->skills()->sync($skillsData);
        $newBatch->tags()->sync($course->tags->pluck('id')->toArray());

        return redirect()
            ->route('vendor.courses.show', $newBatch)
            ->with('success', '🚀 Angkatan ' . $newBatch->batch_name . ' berhasil dirilis! Seluruh materi dan kuis dari kurikulum induk otomatis diwariskan.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke course sertifikasi ini.');
        }

        $course->delete();

        return redirect()
            ->route('vendor.courses.index')
            ->with('success', 'Course Sertifikasi Industri berhasil dihapus.');
    }
}
