<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcademicTermController extends Controller
{
    public function index(): View
    {
        $terms = AcademicTerm::withCount('offerings')
            ->orderByDesc('is_active')
            ->orderByDesc('created_at')
            ->get();

        foreach ($terms as $term) {
            $offerings = \App\Models\CourseOffering::with('enrollments')
                ->where('academic_term_id', $term->id)
                ->get();
            
            $term->master_courses_count = $offerings->pluck('master_course_id')->unique()->count();
            $term->lecturers_count = $offerings->pluck('lecturer_id')->filter()->unique()->count();
            $term->enrollments_count = $offerings->sum(fn($o) => $o->enrollments->count());
        }

        return view('admin.academic-terms.index', compact('terms'));
    }

    public function show(AcademicTerm $academicTerm): View
    {
        $academicTerm->loadCount('offerings');

        $offerings = \App\Models\CourseOffering::with(['masterCourse.category', 'masterCourse.skills', 'masterCourse.tags', 'lecturer', 'enrollments'])
            ->where('academic_term_id', $academicTerm->id)
            ->get();

        $groupedOfferings = $offerings->groupBy('master_course_id');
        $offeredMasterCoursesCount = $groupedOfferings->count();
        $totalOfferingsCount = $offerings->count();
        $totalLecturersCount = $offerings->pluck('lecturer_id')->filter()->unique()->count();
        $totalEnrollmentsCount = $offerings->sum(fn($o) => $o->enrollments->count());

        $allMasterCourses = \App\Models\MasterCourse::with(['category', 'skills', 'tags'])->orderBy('name')->get();
        
        $lecturers = \App\Models\User::where('role', 'lecturer')
            ->orderBy('name')
            ->get();

        foreach ($lecturers as $lec) {
            $lec->assigned_classes_count = \App\Models\CourseOffering::where('academic_term_id', $academicTerm->id)
                ->where('lecturer_id', $lec->id)
                ->count();
        }

        $allTerms = AcademicTerm::orderByDesc('is_active')->orderByDesc('id')->get();

        return view('admin.academic-terms.show', compact(
            'academicTerm',
            'allTerms',
            'groupedOfferings',
            'offeredMasterCoursesCount',
            'totalOfferingsCount',
            'totalLecturersCount',
            'totalEnrollmentsCount',
            'allMasterCourses',
            'lecturers'
        ));
    }

    public function create(): View
    {
        return view('admin.academic-terms.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'academic_year' => ['nullable', 'string', 'max:50'],
            'term_type' => ['required', 'in:ganjil,genap'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // Jika semester ini diaktifkan, nonaktifkan semester lain
        if ($validated['is_active']) {
            AcademicTerm::where('is_active', true)->update(['is_active' => false]);
        }

        AcademicTerm::create($validated);

        return redirect()
            ->route('admin.academic-terms.index')
            ->with('success', 'Periode Semester berhasil ditambahkan.');
    }

    public function edit(AcademicTerm $academicTerm): View
    {
        return view('admin.academic-terms.edit', compact('academicTerm'));
    }

    public function update(Request $request, AcademicTerm $academicTerm): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'academic_year' => ['nullable', 'string', 'max:50'],
            'term_type' => ['required', 'in:ganjil,genap'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // Jika semester ini diaktifkan, nonaktifkan semester lain
        if ($validated['is_active']) {
            AcademicTerm::where('is_active', true)
                ->where('id', '!=', $academicTerm->id)
                ->update(['is_active' => false]);
        }

        $academicTerm->update($validated);

        return redirect()
            ->route('admin.academic-terms.index')
            ->with('success', 'Periode Semester berhasil diperbarui.');
    }

    public function toggleActive(AcademicTerm $academicTerm): RedirectResponse
    {
        if ($academicTerm->is_active) {
            // Nonaktifkan semester ini
            $academicTerm->update(['is_active' => false]);
            $message = "Semester '{$academicTerm->name}' telah dinonaktifkan.";
        } else {
            // Aktifkan semester ini, nonaktifkan semester lain
            AcademicTerm::where('is_active', true)->update(['is_active' => false]);
            $academicTerm->update(['is_active' => true]);
            $message = "Semester '{$academicTerm->name}' telah diaktifkan.";
        }

        return redirect()
            ->route('admin.academic-terms.index')
            ->with('success', $message);
    }

    public function destroy(AcademicTerm $academicTerm): RedirectResponse
    {
        if ($academicTerm->offerings()->count() > 0) {
            return redirect()
                ->route('admin.academic-terms.index')
                ->with('error', 'Tidak dapat menghapus semester yang sudah memiliki kelas penawaran.');
        }

        $academicTerm->delete();

        return redirect()
            ->route('admin.academic-terms.index')
            ->with('success', 'Periode Semester berhasil dihapus.');
    }

    public function storeOffering(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'master_course_id' => ['required', 'exists:master_courses,id'],
            'academic_term_id' => ['required', 'exists:academic_terms,id'],
            'section_name' => ['required', 'string', 'max:50'],
            'lecturer_id' => ['required', 'exists:users,id'],
            'capacity' => ['required', 'integer', 'min:1'],
            'certificate_threshold' => ['required', 'integer', 'min:1', 'max:100'],
            'status' => ['required', 'in:draft,published,cancelled'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $exists = \App\Models\CourseOffering::where('master_course_id', $validated['master_course_id'])
            ->where('academic_term_id', $validated['academic_term_id'])
            ->where('section_name', $validated['section_name'])
            ->exists();

        if ($exists) {
            return redirect()
                ->route('admin.academic-terms.show', $validated['academic_term_id'])
                ->with('error', "Rombel kelas '{$validated['section_name']}' sudah ada untuk mata kuliah ini pada semester tersebut.");
        }

        // Inherit dates from academic term if not provided
        $term = AcademicTerm::find($validated['academic_term_id']);
        if ($term) {
            $validated['start_date'] = $validated['start_date'] ?? $term->start_date?->format('Y-m-d');
            $validated['end_date'] = $validated['end_date'] ?? $term->end_date?->format('Y-m-d');
        }

        \App\Models\CourseOffering::create($validated);

        return redirect()
            ->route('admin.academic-terms.show', $validated['academic_term_id'])
            ->with('success', "Rombel kelas '{$validated['section_name']}' berhasil dibuka pada semester ini.");
    }

    public function updateOffering(Request $request, \App\Models\CourseOffering $offering): RedirectResponse
    {
        $validated = $request->validate([
            'section_name' => ['required', 'string', 'max:50'],
            'lecturer_id' => ['required', 'exists:users,id'],
            'capacity' => ['required', 'integer', 'min:1'],
            'certificate_threshold' => ['required', 'integer', 'min:1', 'max:100'],
            'status' => ['required', 'in:draft,published,cancelled'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $offering->update($validated);

        return redirect()
            ->route('admin.academic-terms.show', $offering->academic_term_id)
            ->with('success', "Rombel kelas '{$offering->section_name}' berhasil diperbarui.");
    }

    public function destroyOffering(\App\Models\CourseOffering $offering): RedirectResponse
    {
        $termId = $offering->academic_term_id;
        $sectionName = $offering->section_name;

        if ($offering->enrollments()->count() > 0) {
            $offering->update(['status' => 'cancelled']);
            $message = "Rombel kelas '{$sectionName}' telah dibatalkan (memiliki pendaftaran mahasiswa).";
        } else {
            $offering->delete();
            $message = "Rombel kelas '{$sectionName}' berhasil dihapus.";
        }

        return redirect()
            ->route('admin.academic-terms.show', $termId)
            ->with('success', $message);
    }
}
