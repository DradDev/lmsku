<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseOffering;
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
        $offerings = CourseOffering::with(['masterCourse', 'academicTerm', 'materials', 'quizzes', 'enrollments'])
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

        $groupedOfferings = $activeOfferings->groupBy('master_course_id');

        $activeCourses = $activeOfferings->count() > 0 ? $activeOfferings : $legacyCourses->filter(fn($c) => !$c->is_archived);
        $bankCourses = $bankOfferings->count() > 0 ? $bankOfferings : $legacyCourses->filter(fn($c) => $c->is_archived);

        return view('lecturer.courses.index', compact('activeCourses', 'bankCourses', 'offerings', 'groupedOfferings'));
    }

    public function show($id): View
    {
        $lecturerId = Auth::id();

        // 1. Coba cari di CourseOffering (3NF)
        $offering = CourseOffering::with([
            'masterCourse.materials',
            'masterCourse.quizzes.questions',
            'masterCourse.category',
            'academicTerm',
            'enrollments.user',
            'quizzes',
        ])
        ->where('lecturer_id', $lecturerId)
        ->find($id);

        if ($offering) {
            $siblingOfferings = CourseOffering::with(['academicTerm', 'enrollments'])
                ->where('lecturer_id', $lecturerId)
                ->where('master_course_id', $offering->master_course_id)
                ->get();

            $course = $offering;
            $materials = $offering->masterCourse->materials ?? collect();
            $quizzes = $offering->quizzes->count() > 0 ? $offering->quizzes : ($offering->masterCourse->quizzes ?? collect());
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
                'retakeRequests',
                'siblingOfferings'
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

    public function edit($id): View
    {
        $lecturerId = Auth::id();

        // Check if CourseOffering 3NF
        $offering = CourseOffering::with(['masterCourse.skills', 'masterCourse.tags', 'materials'])
            ->where('lecturer_id', $lecturerId)
            ->find($id);

        if ($offering) {
            $course = $offering;
            $mainSkills = Skill::whereNull('parent_id')->orderBy('name')->get();
            $tags = Tag::with('skill')->orderBy('name')->get();
            $categories = Category::orderBy('name')->get();

            return view('lecturer.courses.edit', compact('course', 'mainSkills', 'tags', 'categories'));
        }

        // Fallback to legacy Course
        $course = Course::with(['skills', 'tags', 'materials'])->where('user_id', $lecturerId)->findOrFail($id);

        $mainSkills = Skill::whereNull('parent_id')->orderBy('name')->get();
        $tags = Tag::with('skill')->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('lecturer.courses.edit', compact('course', 'mainSkills', 'tags', 'categories'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $lecturerId = Auth::id();

        // 1. Coba update CourseOffering 3NF
        $offering = CourseOffering::where('lecturer_id', $lecturerId)->find($id);
        if ($offering) {
            $validated = $request->validate([
                'certificate_threshold' => ['nullable', 'integer', 'min:0', 'max:100'],
                'capacity' => ['nullable', 'integer', 'min:1'],
                'section_name' => ['nullable', 'string', 'max:100'],
            ]);

            $offering->update([
                'certificate_threshold' => $validated['certificate_threshold'] ?? $offering->certificate_threshold,
                'capacity' => $validated['capacity'] ?? $offering->capacity,
                'section_name' => $validated['section_name'] ?? $offering->section_name,
            ]);

            return redirect()
                ->route('lecturer.courses.show', $offering->id)
                ->with('success', 'Threshold Sertifikat & Pengaturan Kelas berhasil diperbarui.');
        }

        // 2. Fallback update legacy Course
        $course = Course::where('user_id', $lecturerId)->findOrFail($id);

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
            'certificate_threshold' => $validated['certificate_threshold'] ?? 75,
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

    public function archive($id): RedirectResponse
    {
        $lecturerId = Auth::id();

        $offering = CourseOffering::where('lecturer_id', $lecturerId)->find($id);
        if ($offering) {
            $offering->update(['is_archived' => !$offering->is_archived]);
            $statusMsg = $offering->is_archived ? 'diarsip' : 'diaktifkan kembali';
            return back()->with('success', "Status penawaran kelas berhasil {$statusMsg}.");
        }

        $course = Course::where('user_id', $lecturerId)->findOrFail($id);
        $course->update(['is_archived' => !$course->is_archived]);
        $statusMsg = $course->is_archived ? 'diarsip' : 'diaktifkan kembali';

        return back()->with('success', "Status course berhasil {$statusMsg}.");
    }

    public function duplicate($id): RedirectResponse
    {
        $lecturerId = Auth::id();

        $offering = CourseOffering::where('lecturer_id', $lecturerId)->find($id);
        if ($offering) {
            $newOffering = $offering->replicate();
            $newOffering->section_name = $offering->section_name . ' (Copy)';
            $newOffering->created_at = now();
            $newOffering->updated_at = now();
            $newOffering->save();

            return redirect()
                ->route('lecturer.courses.index')
                ->with('success', 'Penawaran kelas berhasil diduplikasi.');
        }

        $original = Course::with(['materials', 'quizzes.questions', 'skills', 'tags'])
            ->where('user_id', $lecturerId)
            ->findOrFail($id);

        $newCourse = $original->replicate();
        $newCourse->name = $original->name . ' (Copy)';
        $newCourse->progress = 0;
        $newCourse->is_archived = false;
        $newCourse->save();

        foreach ($original->materials as $mat) {
            $newMat = $mat->replicate();
            $newMat->course_id = $newCourse->id;
            $newMat->save();
        }

        foreach ($original->quizzes as $quiz) {
            $newQuiz = $quiz->replicate();
            $newQuiz->course_id = $newCourse->id;
            $newQuiz->save();

            foreach ($quiz->questions as $q) {
                $newQ = $q->replicate();
                $newQ->quiz_id = $newQuiz->id;
                $newQ->save();
            }
        }

        return redirect()
            ->route('lecturer.courses.index')
            ->with('success', 'Course berhasil diduplikasi beserta materi dan kuisnya.');
    }

    private function syncCourseSkillsAndTags(Course $course, Request $request): void
    {
        $skillIds = array_map('intval', (array) $request->input('skill_ids', []));
        $mainSkillId = (int) $request->input('main_skill_id', 0);
        $tagIds = array_map('intval', (array) $request->input('tag_ids', []));

        if ($mainSkillId > 0 && !in_array($mainSkillId, $skillIds, true)) {
            $skillIds[] = $mainSkillId;
        }

        $syncSkillsData = [];
        foreach ($skillIds as $skId) {
            $syncSkillsData[$skId] = [
                'weight' => ($skId === $mainSkillId) ? 100 : 50,
                'is_main' => ($skId === $mainSkillId),
            ];
        }

        $course->skills()->sync($syncSkillsData);

        $syncTagsData = [];
        foreach ($tagIds as $tId) {
            $syncTagsData[$tId] = [
                'weight' => 50,
            ];
        }

        $course->tags()->sync($syncTagsData);
    }
}