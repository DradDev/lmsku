<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Skill;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $activeCourses = Course::with(['materials', 'quizzes', 'students', 'skills', 'tags', 'category'])
            ->where('user_id', Auth::id())
            ->active()
            ->latest()
            ->get();

        $bankCourses = Course::with(['materials', 'quizzes', 'students', 'skills', 'tags', 'category'])
            ->where('user_id', Auth::id())
            ->archived()
            ->latest()
            ->get();

        return view('lecturer.courses.index', compact('activeCourses', 'bankCourses'));
    }

    public function create(): View
    {
        $mainSkills = Skill::with(['children' => function ($query) {
            $query->orderBy('name');
        }])
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $tags = Tag::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('lecturer.courses.create', compact('mainSkills', 'tags', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'level' => ['required', 'in:Beginner,Intermediate,Advanced'],
            'duration_weeks' => ['required', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'certificate_threshold' => ['nullable', 'integer', 'min:0', 'max:100'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'skill_ids' => ['nullable', 'array'],
            'skill_ids.*' => ['exists:skills,id'],
            'main_skill_id' => ['nullable', 'exists:skills,id'],

            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
        ]);

        $thumbnailPath = null;

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('course-thumbnails', 'public');
        }

        $course = Course::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'level' => $validated['level'],
            'duration_weeks' => $validated['duration_weeks'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'certificate_threshold' => $validated['certificate_threshold'] ?? 60,
            'category_id' => $validated['category_id'] ?? null,
            'thumbnail' => $thumbnailPath,
            'progress' => 0,
            'user_id' => Auth::id(),
        ]);

        $this->syncCourseSkillsAndTags($course, $request);

        return redirect()
            ->route('lecturer.courses.show', $course->id)
            ->with('success', 'Course berhasil dibuat. Sekarang tambahkan material dan quiz.');
    }

    public function show(Course $course): View
    {
        abort_unless(
            $course->user_id === Auth::id(),
            403,
            'Kamu tidak memiliki akses ke course ini.'
        );

        $course->load([
            'materials',
            'quizzes.questions',
            'assignments',
            'students',
            'user',
            'skills',
            'tags',
            'category',
            'enrollments.user',
        ]);

        $materials = $course->materials;
        $quizzes = $course->quizzes;
        $assignments = $course->assignments;
        $students = $course->students;
        $enrollments = $course->enrollments;
        $categories = Category::orderBy('name')->get();

        return view('lecturer.courses.show', compact(
            'course',
            'materials',
            'quizzes',
            'assignments',
            'students',
            'enrollments',
            'categories'
        ));
    }

    public function edit(Course $course): View
    {
        abort_unless($course->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke course ini.');

        $course->load(['skills', 'tags', 'category']);

        $mainSkills = Skill::with(['children' => function ($query) {
            $query->orderBy('name');
        }])
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $tags = Tag::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('lecturer.courses.edit', compact('course', 'mainSkills', 'tags', 'categories'));
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        abort_unless($course->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke course ini.');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'level' => ['required', 'in:Beginner,Intermediate,Advanced'],
            'duration_weeks' => ['required', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'certificate_threshold' => ['nullable', 'integer', 'min:0', 'max:100'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'skill_ids' => ['nullable', 'array'],
            'skill_ids.*' => ['exists:skills,id'],
            'main_skill_id' => ['nullable', 'exists:skills,id'],

            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'level' => $validated['level'],
            'duration_weeks' => $validated['duration_weeks'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'certificate_threshold' => $validated['certificate_threshold'] ?? 60,
            'category_id' => $validated['category_id'] ?? null,
        ];

        if ($request->hasFile('thumbnail')) {
            if (!empty($course->thumbnail) && Storage::disk('public')->exists($course->thumbnail)) {
                Storage::disk('public')->delete($course->thumbnail);
            }

            $updateData['thumbnail'] = $request->file('thumbnail')->store('course-thumbnails', 'public');
        }

        $course->update($updateData);

        $this->syncCourseSkillsAndTags($course, $request);

        return redirect()
            ->route('lecturer.courses.show', $course->id)
            ->with('success', 'Informasi course berhasil diperbarui.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        abort_unless($course->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke course ini.');

        $course->delete();

        return redirect()
            ->route('lecturer.courses.index')
            ->with('success', 'Course berhasil dihapus.');
    }

    private function syncCourseSkillsAndTags(Course $course, Request $request): void
    {
        $skillIds = array_map('intval', $request->input('skill_ids', []));
        $mainSkillId = $request->input('main_skill_id');

        if ($mainSkillId && ! in_array((int) $mainSkillId, $skillIds, true)) {
            $skillIds[] = (int) $mainSkillId;
        }

        $skillSyncData = [];

        foreach ($skillIds as $skillId) {
            $skillSyncData[$skillId] = [
                'weight' => 1.00,
                'is_main' => (int) $skillId === (int) $mainSkillId,
            ];
        }

        $course->skills()->sync($skillSyncData);

        $tagIds = array_map('intval', $request->input('tag_ids', []));
        $tagSyncData = [];

        foreach ($tagIds as $tagId) {
            $tagSyncData[$tagId] = [
                'weight' => 1.00,
            ];
        }

        $course->tags()->sync($tagSyncData);
    }

    public function archive(Course $course): RedirectResponse
    {
        abort_unless($course->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke course ini.');
        $course->update(['is_archived' => !$course->is_archived]);
        $status = $course->is_archived ? 'diarsipkan ke Course Bank' : 'diaktifkan kembali';
        return redirect()->back()->with('success', "Course berhasil {$status}.");
    }

    public function duplicate(Course $course): RedirectResponse
    {
        abort_unless($course->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke course ini.');

        $newCourse = $course->replicate([
            'is_archived',
            'start_date',
            'end_date',
        ]);
        $newCourse->name = $course->name . ' (Copy)';
        $newCourse->is_archived = false;
        $newCourse->start_date = now();
        $newCourse->end_date = null;
        $newCourse->save();

        // Copy skills
        foreach ($course->skills as $skill) {
            $newCourse->skills()->attach($skill->id, [
                'weight' => $skill->pivot->weight ?? 1.0,
                'is_main' => $skill->pivot->is_main ?? false,
            ]);
        }

        // Copy tags
        foreach ($course->tags as $tag) {
            $newCourse->tags()->attach($tag->id, [
                'weight' => $tag->pivot->weight ?? 1.0,
            ]);
        }

        // Copy materials
        foreach ($course->materials as $material) {
            $newMaterial = $material->replicate();
            $newMaterial->course_id = $newCourse->id;
            $newMaterial->save();
        }

        // Copy quizzes and questions
        foreach ($course->quizzes as $quiz) {
            $newQuiz = $quiz->replicate();
            $newQuiz->course_id = $newCourse->id;
            $newQuiz->save();

            foreach ($quiz->questions as $question) {
                $newQuestion = $question->replicate();
                $newQuestion->quiz_id = $newQuiz->id;
                $newQuestion->save();
            }
        }

        return redirect()->route('lecturer.courses.show', $newCourse->id)
            ->with('success', 'Course berhasil diduplikasi dari Bank.');
    }
}