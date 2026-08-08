<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Material;
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
        $lecturerId = Auth::id();

        // 3NF CourseOfferings yang ditugaskan ke Dosen
        $offerings = \App\Models\CourseOffering::with(['masterCourse', 'academicTerm', 'materials', 'quizzes', 'enrollments'])
            ->where('lecturer_id', $lecturerId)
            ->latest()
            ->get();

        // Fallback ke legacy Courses jika ada
        $legacyCourses = Course::with(['materials', 'quizzes', 'students', 'skills', 'tags', 'category'])
            ->where('user_id', $lecturerId)
            ->latest()
            ->get();

        // Pisahkan menjadi Active vs Bank (Archived/Expired)
        $activeOfferings = $offerings->filter(fn($o) => $o->status !== 'expired' && $o->status !== 'cancelled' && !$o->is_archived);
        $bankOfferings = $offerings->filter(fn($o) => $o->status === 'expired' || $o->status === 'cancelled' || $o->is_archived);

        $activeCourses = $activeOfferings->count() > 0 ? $activeOfferings : $legacyCourses->filter(fn($c) => !$c->is_archived);
        $bankCourses = $bankOfferings->count() > 0 ? $bankOfferings : $legacyCourses->filter(fn($c) => $c->is_archived);

        return view('lecturer.courses.index', compact('activeCourses', 'bankCourses', 'offerings'));
    }

    public function create(): View
    {
        $mainSkills = Skill::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $tags = Tag::with('skill')->orderBy('name')->get();
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
            'material_file' => ['required', 'file', 'mimes:pdf,doc,docx,ppt,pptx,zip,rar', 'max:20480'],

            'skill_ids' => ['nullable', 'array'],
            'skill_ids.*' => ['exists:skills,id'],
            'main_skill_id' => ['nullable', 'exists:skills,id'],

            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
        ], [
            'material_file.required' => 'Materi pembelajaran (Learning Material) wajib diunggah saat membuat course baru.',
        ]);

        $course = Course::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'level' => $validated['level'],
            'duration_weeks' => $validated['duration_weeks'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'certificate_threshold' => $validated['certificate_threshold'] ?? 60,
            'category_id' => $validated['category_id'] ?? null,
            'progress' => 0,
            'user_id' => Auth::id(),
        ]);

        if ($request->hasFile('material_file')) {
            $filePath = $request->file('material_file')->store('materials', 'public');
            Material::create([
                'course_id' => $course->id,
                'master_course_id' => $course->master_course_id ?? $course->id,
                'title' => $course->name . ' - Learning Material',
                'file_path' => $filePath,
            ]);
        }

        $this->syncCourseSkillsAndTags($course, $request);

        return redirect()
            ->route('lecturer.courses.show', $course->id)
            ->with('success', 'Course berhasil disimpan dengan materi pembelajaran.');
    }

    public function show(string $id): View
    {
        $lecturerId = Auth::id();

        // 1. Coba cari di CourseOffering (3NF)
        $offering = \App\Models\CourseOffering::with([
            'masterCourse.category',
            'academicTerm',
            'materials',
            'quizzes.questions',
            'enrollments.user',
            'certificates',
        ])
        ->where('lecturer_id', $lecturerId)
        ->find($id);

        if ($offering) {
            $course = $offering; // Magic accessors handle backward compatibility!
            $materials = $offering->materials;
            $quizzes = $offering->quizzes;
            $students = $offering->enrollments->map(fn($e) => $e->user)->filter();
            $enrollments = $offering->enrollments;
            $categories = Category::orderBy('name')->get();
            $retakeRequests = \App\Models\QuizRetakeRequest::with(['user', 'quiz'])
                ->whereIn('quiz_id', $quizzes->pluck('id'))
                ->latest()
                ->get();

            return view('lecturer.courses.show', compact(
                'course',
                'materials',
                'quizzes',
                'students',
                'enrollments',
                'categories',
                'retakeRequests'
            ));
        }

        // 2. Fallback ke legacy Course
        $course = Course::with([
            'materials',
            'quizzes.questions',
            'students',
            'user',
            'skills',
            'tags',
            'category',
            'enrollments.user',
        ])
        ->where('user_id', $lecturerId)
        ->findOrFail($id);

        $materials = $course->materials;
        $quizzes = $course->quizzes;
        $students = $course->students;
        $enrollments = $course->enrollments;
        $categories = Category::orderBy('name')->get();
        $retakeRequests = \App\Models\QuizRetakeRequest::with(['user', 'quiz'])
            ->where('course_id', $course->id)
            ->latest()
            ->get();

        return view('lecturer.courses.show', compact(
            'course',
            'materials',
            'quizzes',
            'students',
            'enrollments',
            'categories',
            'retakeRequests'
        ));
    }

    public function edit(Course $course): View
    {
        abort_unless($course->user_id === Auth::id(), 403, 'You do not have access to this course.');

        $course->load(['skills', 'tags', 'category']);

        $mainSkills = Skill::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $tags = Tag::with('skill')->orderBy('name')->get();
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
            'material_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,zip,rar', 'max:20480'],

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

        $course->update($updateData);

        if ($request->hasFile('material_file')) {
            $filePath = $request->file('material_file')->store('materials', 'public');
            Material::create([
                'course_id' => $course->id,
                'title' => $course->name . ' - Learning Material',
                'file_path' => $filePath,
            ]);
        }

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

        $newIsArchived = !$course->is_archived;
        $updateData = ['is_archived' => $newIsArchived];

        if (!$newIsArchived) {
            // Update start_date to today when reactivating from Course Bank
            $updateData['start_date'] = now();
            // Reset expired end_date if it was in the past so the course stays active
            if ($course->end_date && \Carbon\Carbon::parse($course->end_date)->lt(now()->startOfDay())) {
                $updateData['end_date'] = null;
            }
        }

        $course->update($updateData);

        $status = $newIsArchived ? 'diarsipkan ke Course Bank' : 'diaktifkan kembali dengan tanggal mulai hari ini';
        return redirect()->back()->with('success', "Course \"{$course->name}\" berhasil {$status}. Seluruh data materi, soal, dan mahasiswa tetap tersimpan utuh.");
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