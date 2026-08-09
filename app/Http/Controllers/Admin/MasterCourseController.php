<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterCourse;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MasterCourseController extends Controller
{
    public function index(): View
    {
        $masterCourses = MasterCourse::with('category')
            ->withCount('offerings')
            ->orderBy('name')
            ->get();

        return view('admin.master-courses.index', compact('masterCourses'));
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
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        $masterCourse->update($validated);

        return redirect()
            ->route('admin.master-courses.index')
            ->with('success', 'Master Course berhasil diperbarui.');
    }

    public function show(MasterCourse $masterCourse, Request $request): View
    {
        $masterCourse->load('category');

        // Semesters (Academic Terms) linked to offerings or all terms
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

        return view('admin.master-courses.show', compact(
            'masterCourse',
            'academicTerms',
            'courseOfferings',
            'selectedTerm',
            'selectedOfferings',
            'totalSemesters',
            'totalOfferings'
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
