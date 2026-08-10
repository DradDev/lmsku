<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseOffering;
use App\Models\MasterCourse;
use App\Models\AcademicTerm;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CourseOfferingController extends Controller
{
    public function index(Request $request): View
    {
        $query = CourseOffering::with(['masterCourse', 'academicTerm', 'lecturer'])
            ->withCount('enrollments');

        // Filter berdasarkan semester (opsional)
        if ($request->filled('academic_term_id')) {
            $query->where('academic_term_id', $request->academic_term_id);
        }

        $offerings = $query->orderBy('created_at', 'desc')->get();

        $terms = AcademicTerm::orderBy('created_at', 'desc')->get();

        return view('admin.course-offerings.index', compact('offerings', 'terms'));
    }

    public function create(Request $request): View
    {
        $masterCourses = MasterCourse::orderBy('name')->get();
        $terms = AcademicTerm::orderBy('created_at', 'desc')->get();
        $lecturers = User::where('role', 'lecturer')->orderBy('name')->get();

        $selectedMasterCourseId = $request->query('master_course_id');
        $selectedAcademicTermId = $request->query('academic_term_id');

        return view('admin.course-offerings.create', compact(
            'masterCourses', 
            'terms', 
            'lecturers', 
            'selectedMasterCourseId', 
            'selectedAcademicTermId'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'master_course_id' => ['required', 'exists:master_courses,id'],
            'academic_term_id' => ['required', 'exists:academic_terms,id'],
            'lecturer_id' => ['required', 'exists:users,id'],
            'section_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('course_offerings')->where(function ($query) use ($request) {
                    return $query->where('master_course_id', $request->master_course_id)
                        ->where('academic_term_id', $request->academic_term_id);
                }),
            ],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'certificate_threshold' => ['required', 'integer', 'min:0', 'max:100'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:draft,published,ongoing,expired,cancelled'],
        ], [
            'section_name.unique' => 'Nama kelas ini sudah ada pada mata kuliah dan semester yang sama.',
        ]);

        CourseOffering::create($validated);

        return redirect()
            ->route('admin.course-offerings.index')
            ->with('success', 'Kelas penawaran berhasil dibuka.');
    }

    public function edit(CourseOffering $courseOffering): View
    {
        $masterCourses = MasterCourse::orderBy('name')->get();
        $terms = AcademicTerm::orderBy('created_at', 'desc')->get();
        $lecturers = User::where('role', 'lecturer')->orderBy('name')->get();

        return view('admin.course-offerings.edit', compact('courseOffering', 'masterCourses', 'terms', 'lecturers'));
    }

    public function update(Request $request, CourseOffering $courseOffering): RedirectResponse
    {
        $validated = $request->validate([
            'master_course_id' => ['required', 'exists:master_courses,id'],
            'academic_term_id' => ['required', 'exists:academic_terms,id'],
            'lecturer_id' => ['required', 'exists:users,id'],
            'section_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('course_offerings')->where(function ($query) use ($request) {
                    return $query->where('master_course_id', $request->master_course_id)
                        ->where('academic_term_id', $request->academic_term_id);
                })->ignore($courseOffering->id),
            ],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'certificate_threshold' => ['required', 'integer', 'min:0', 'max:100'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:draft,published,ongoing,expired,cancelled'],
        ], [
            'section_name.unique' => 'Nama kelas ini sudah ada pada mata kuliah dan semester yang sama.',
        ]);

        $courseOffering->update($validated);

        return redirect()
            ->route('admin.course-offerings.index')
            ->with('success', 'Kelas penawaran berhasil diperbarui.');
    }

    public function destroy(CourseOffering $courseOffering): RedirectResponse
    {
        if ($courseOffering->enrollments()->count() > 0) {
            return redirect()
                ->route('admin.course-offerings.index')
                ->with('error', 'Tidak dapat menghapus kelas yang sudah memiliki mahasiswa terdaftar.');
        }

        $courseOffering->delete();

        return redirect()
            ->route('admin.course-offerings.index')
            ->with('success', 'Kelas penawaran berhasil dihapus.');
    }
}
