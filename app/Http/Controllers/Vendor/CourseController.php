<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Skill;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $validated['user_id'] = Auth::id();
        $validated['progress'] = 0;
        $validated['duration_weeks'] = $validated['duration_weeks'] ?? 4;
        $validated['batch_name'] = $validated['batch_name'] ?? 'Batch 1 - 2026';

        $course = Course::create($validated);

        // Prepare Skill Sync Array (All selected skills are Main Skills for Vendor Course)
        $skillsData = [];
        foreach ($validated['skill_ids'] as $sId) {
            $skillsData[$sId] = ['is_main' => true, 'weight' => 1.00];
        }

        $course->skills()->sync($skillsData);

        if (!empty($validated['tag_ids'])) {
            $course->tags()->sync($validated['tag_ids']);
        }

        return redirect()
            ->route('vendor.courses.show', $course)
            ->with('success', 'Course Sertifikasi Industri berhasil dipublikasikan.');
    }

    public function show(Course $course): View
    {
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke course sertifikasi ini.');
        }

        $course->load([
            'category',
            'materials',
            'quizzes.questions',
            'students',
            'skills',
            'tags',
        ]);

        $students = $course->students;
        $materials = $course->materials;
        $quizzes = $course->quizzes;
        $completedStudentCount = $students->filter(function ($s) {
            return ($s->pivot->progress_percent ?? 0) >= 100;
        })->count();

        return view('vendor.courses.show', compact('course', 'students', 'materials', 'quizzes', 'completedStudentCount'));
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

        // Prepare Skill Sync Array (All selected skills are Main Skills for Vendor Course)
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
