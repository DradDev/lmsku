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
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.academic-terms.index', compact('terms'));
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
}
