<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

        $offerings = \App\Models\CourseOffering::with(['masterCourse.skills', 'masterCourse.tags', 'lecturer', 'enrollments'])
            ->where('academic_term_id', $academicTerm->id)
            ->get();

        $groupedOfferings = $offerings->groupBy('master_course_id');
        $offeredMasterCoursesCount = $groupedOfferings->count();
        $totalOfferingsCount = $offerings->count();
        $totalLecturersCount = $offerings->pluck('lecturer_id')->filter()->unique()->count();
        $totalEnrollmentsCount = $offerings->sum(fn($o) => $o->enrollments->count());

        $allMasterCourses = \App\Models\MasterCourse::where(function ($q) {
            $q->whereNull('user_id')
              ->orWhereHas('user', fn($u) => $u->where('role', '!=', 'vendor'));
        })->with(['skills', 'tags'])->orderBy('name')->get();
        
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

        // Jika semester ini diaktifkan, nonaktifkan semester lain dan ubah status offering-nya ke draft
        if ($validated['is_active']) {
            $otherTerms = AcademicTerm::where('is_active', true)->pluck('id');
            AcademicTerm::where('is_active', true)->update(['is_active' => false]);
            \App\Models\CourseOffering::whereIn('academic_term_id', $otherTerms)
                ->where('status', '!=', 'cancelled')
                ->update(['status' => 'draft']);
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

        // Jika semester ini dinonaktifkan, ubah seluruh kelas penawaran di dalamnya menjadi draft
        if (!$validated['is_active'] && $academicTerm->is_active) {
            \App\Models\CourseOffering::where('academic_term_id', $academicTerm->id)
                ->where('status', '!=', 'cancelled')
                ->update(['status' => 'draft']);
        } elseif ($validated['is_active']) {
            // Jika semester ini diaktifkan, nonaktifkan semester lain dan ubah kelasnya ke draft
            $otherTerms = AcademicTerm::where('is_active', true)->where('id', '!=', $academicTerm->id)->pluck('id');
            AcademicTerm::where('is_active', true)
                ->where('id', '!=', $academicTerm->id)
                ->update(['is_active' => false]);
            \App\Models\CourseOffering::whereIn('academic_term_id', $otherTerms)
                ->where('status', '!=', 'cancelled')
                ->update(['status' => 'draft']);

            // Aktifkan/publish seluruh kelas pada semester yang diaktifkan
            \App\Models\CourseOffering::where('academic_term_id', $academicTerm->id)
                ->where('status', '!=', 'cancelled')
                ->update(['status' => 'published']);
        }

        $academicTerm->update($validated);

        return redirect()
            ->route('admin.academic-terms.index')
            ->with('success', 'Periode Semester berhasil diperbarui.');
    }

    public function toggleActive(AcademicTerm $academicTerm): RedirectResponse
    {
        if ($academicTerm->is_active) {
            // Nonaktifkan semester ini dan ubah semua kelas penawaran menjadi draft
            $academicTerm->update(['is_active' => false]);
            \App\Models\CourseOffering::where('academic_term_id', $academicTerm->id)
                ->where('status', '!=', 'cancelled')
                ->update(['status' => 'draft']);

            $message = "Semester '{$academicTerm->name}' telah dinonaktifkan dan seluruh kelas di semester ini otomatis dialihkan menjadi Draft.";
        } else {
            // Nonaktifkan semester lain dan ubah kelas di semester lain menjadi draft
            $otherTerms = AcademicTerm::where('is_active', true)->pluck('id');
            AcademicTerm::where('is_active', true)->update(['is_active' => false]);
            \App\Models\CourseOffering::whereIn('academic_term_id', $otherTerms)
                ->where('status', '!=', 'cancelled')
                ->update(['status' => 'draft']);

            // Aktifkan semester ini dan aktifkan/publish seluruh kelas di semester ini
            $academicTerm->update(['is_active' => true]);
            \App\Models\CourseOffering::where('academic_term_id', $academicTerm->id)
                ->where('status', '!=', 'cancelled')
                ->update(['status' => 'published']);

            $message = "Semester '{$academicTerm->name}' telah diaktifkan dan seluruh kelas di semester ini otomatis berstatus Published.";
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
            'section_name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('course_offerings')->where(function ($query) use ($offering) {
                    return $query->where('master_course_id', $offering->master_course_id)
                        ->where('academic_term_id', $offering->academic_term_id);
                })->ignore($offering->id),
            ],
            'lecturer_id' => ['required', 'exists:users,id'],
            'capacity' => ['required', 'integer', 'min:1'],
            'certificate_threshold' => ['required', 'integer', 'min:1', 'max:100'],
            'status' => ['required', 'in:draft,published,cancelled'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ], [
            'section_name.unique' => "Nama rombel '{$request->section_name}' sudah digunakan pada mata kuliah dan semester yang sama.",
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
        $enrolledCount = $offering->enrollments()->count();

        if ($enrolledCount > 0) {
            return redirect()
                ->route('admin.academic-terms.show', $termId)
                ->with('error', "Rombel kelas '{$sectionName}' tidak dapat dihapus karena sudah memiliki {$enrolledCount} mahasiswa yang terdaftar.");
        }

        $offering->delete();

        return redirect()
            ->route('admin.academic-terms.show', $termId)
            ->with('success', "Rombel kelas '{$sectionName}' berhasil dihapus.");
    }
}
