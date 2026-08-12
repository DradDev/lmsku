<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterCourse;
use App\Models\Category;
use App\Models\Skill;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Models\Course;

class MasterCourseController extends Controller
{
    public function index(): View
    {
        $masterCourses = MasterCourse::with(['category', 'skills', 'tags', 'materials', 'quizzes'])
            ->withCount('offerings')
            ->orderBy('name')
            ->get();

        $vendorCourses = Course::with(['user', 'category', 'skills', 'tags', 'materials', 'quizzes', 'masterCourse'])
            ->whereHas('user', function ($query) {
                $query->where('role', 'vendor');
            })
            ->orderByDesc('created_at')
            ->get();

        return view('admin.master-courses.index', compact('masterCourses', 'vendorCourses'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.master-courses.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:master_courses,code'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'level' => ['required', 'in:Beginner,Intermediate,Advanced'],
            'certificate_threshold' => ['required', 'integer', 'min:1', 'max:100'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        MasterCourse::create($validated);

        return redirect()
            ->route('admin.master-courses.index')
            ->with('success', 'Master Course berhasil ditambahkan.');
    }

    public function edit(MasterCourse $masterCourse): View
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.master-courses.edit', compact('masterCourse', 'categories'));
    }

    public function update(Request $request, MasterCourse $masterCourse): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:master_courses,code,' . $masterCourse->id],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'level' => ['required', 'in:Beginner,Intermediate,Advanced'],
            'certificate_threshold' => ['required', 'integer', 'min:1', 'max:100'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        $masterCourse->update($validated);

        return redirect()
            ->route('admin.master-courses.show', $masterCourse)
            ->with('success', 'Master Course berhasil diperbarui.');
    }

    public function syncCompetencies(Request $request, MasterCourse $masterCourse): RedirectResponse
    {
        $validated = $request->validate([
            'skill_ids' => ['nullable', 'array'],
            'skill_ids.*' => ['exists:skills,id'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
        ]);

        $masterCourse->skills()->sync($validated['skill_ids'] ?? []);
        $masterCourse->tags()->sync($validated['tag_ids'] ?? []);

        return redirect()
            ->route('admin.master-courses.show', $masterCourse)
            ->with('success', 'Skill & Tag Target Kompetensi berhasil diperbarui.');
    }

    public function show(MasterCourse $masterCourse, Request $request): View
    {
        $masterCourse->load(['category', 'materials', 'quizzes', 'skills', 'tags']);

        // Semesters (Academic Terms)
        $academicTerms = \App\Models\AcademicTerm::orderByDesc('is_active')
            ->orderByDesc('id')
            ->get();

        // Course Offerings for this Master Course
        $courseOfferings = \App\Models\CourseOffering::with(['lecturer', 'academicTerm'])
            ->where('master_course_id', $masterCourse->id)
            ->get();

        // Selected term for filtered offering section
        $selectedTermId = $request->query('term_id', $academicTerms->firstWhere('is_active', true)?->id ?? $academicTerms->first()?->id);
        $selectedTerm = $academicTerms->firstWhere('id', $selectedTermId);

        $selectedOfferings = $courseOfferings->where('academic_term_id', $selectedTermId);

        $totalSemesters = $academicTerms->count();
        $totalOfferings = $courseOfferings->count();
        $materials = $masterCourse->materials;
        $quizzes = $masterCourse->quizzes;
        $categories = Category::orderBy('name')->get();

        // All Skills and Tags for competency assignment
        $allSkills = Skill::orderBy('name')->get();
        $allTags = Tag::with('skill')->orderBy('name')->get();

        $activeTab = $request->query('tab', 'hierarchy');

        $lecturers = \App\Models\User::where('role', 'lecturer')->orderBy('name')->get();

        return view('admin.master-courses.show', compact(
            'masterCourse',
            'academicTerms',
            'courseOfferings',
            'selectedTerm',
            'selectedOfferings',
            'totalSemesters',
            'totalOfferings',
            'materials',
            'quizzes',
            'categories',
            'allSkills',
            'allTags',
            'activeTab',
            'lecturers'
        ));
    }

    public function destroy(MasterCourse $masterCourse): RedirectResponse
    {
        if ($masterCourse->offerings()->count() > 0) {
            return redirect()
                ->route('admin.master-courses.index')
                ->with('error', 'Tidak dapat menghapus Master Course yang sudah memiliki kelas penawaran.');
        }

        $masterCourse->delete();

        return redirect()
            ->route('admin.master-courses.index')
            ->with('success', 'Master Course berhasil dihapus.');
    }
}
