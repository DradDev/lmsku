<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
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
        $courses = Course::with(['materials', 'quizzes', 'students', 'skills', 'tags'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('lecturer.courses.index', compact('courses'));
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

        return view('lecturer.courses.create', compact('mainSkills', 'tags'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'level' => ['required', 'in:Beginner,Intermediate,Advanced'],
            'duration_weeks' => ['required', 'integer', 'min:1'],

            'skill_ids' => ['nullable', 'array'],
            'skill_ids.*' => ['exists:skills,id'],
            'main_skill_id' => ['nullable', 'exists:skills,id'],

            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
        ]);

        $course = Course::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'level' => $validated['level'],
            'duration_weeks' => $validated['duration_weeks'],
            'progress' => 0,
            'user_id' => Auth::id(),
        ]);

        $skillSyncData = [];

        foreach ($request->input('skill_ids', []) as $skillId) {
            $skillSyncData[$skillId] = [
                'weight' => 1.00,
                'is_main' => (int) $skillId === (int) $request->input('main_skill_id'),
            ];
        }

        $course->skills()->sync($skillSyncData);

        $tagSyncData = [];

        foreach ($request->input('tag_ids', []) as $tagId) {
            $tagSyncData[$tagId] = [
                'weight' => 1.00,
            ];
        }

        $course->tags()->sync($tagSyncData);

        return redirect()
            ->route('lecturer.courses.index')
            ->with('success', 'Course berhasil dibuat.');
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
            'enrollments.user',
        ]);

        $materials = $course->materials;
        $quizzes = $course->quizzes;
        $assignments = $course->assignments;
        $students = $course->students;
        $enrollments = $course->enrollments;

        return view('lecturer.courses.show', compact(
            'course',
            'materials',
            'quizzes',
            'assignments',
            'students',
            'enrollments'
        ));
    }

    public function edit(Course $course): View
    {
        abort_unless($course->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke course ini.');

        $mainSkills = Skill::with(['children' => function ($query) {
            $query->orderBy('name');
        }])
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $tags = Tag::orderBy('name')->get();

        $course->load(['skills', 'tags']);

        return view('lecturer.courses.edit', compact('course', 'mainSkills', 'tags'));
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        abort_unless($course->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke course ini.');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            'skill_ids' => ['nullable', 'array'],
            'skill_ids.*' => ['exists:skills,id'],

            'main_skill_id' => ['nullable', 'exists:skills,id'],

            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
        ]);

        $course->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        $this->syncCourseSkillsAndTags($course, $request);

        return redirect()
            ->route('lecturer.courses.index')
            ->with('success', 'Course berhasil diperbarui.');
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
}
