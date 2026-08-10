<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Course::with(['user', 'category', 'materials', 'quizzes', 'students'])
            ->withCount(['materials', 'quizzes', 'students']);

        // Filter Provider Type (Vendor vs Lecturer)
        if ($request->filled('provider_type')) {
            if ($request->provider_type === 'vendor') {
                $query->whereHas('user', function ($q) {
                    $q->where('role', 'vendor');
                });
            } elseif ($request->provider_type === 'lecturer') {
                $query->whereHas('user', function ($q) {
                    $q->where('role', 'lecturer');
                });
            }
        }

        // Filter Status (Active vs Archived)
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_archived', false);
            } elseif ($request->status === 'archived') {
                $query->where('is_archived', true);
            }
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $courses = $query->latest()->get();

        // Statistics
        $totalCourses = Course::count();
        $vendorCourses = Course::whereHas('user', fn($q) => $q->where('role', 'vendor'))->count();
        $lecturerCourses = Course::whereHas('user', fn($q) => $q->where('role', 'lecturer'))->count();
        $activeCourses = Course::where('is_archived', false)->count();

        return view('admin.courses.index', compact(
            'courses',
            'totalCourses',
            'vendorCourses',
            'lecturerCourses',
            'activeCourses'
        ));
    }

    public function show(Course $course): View
    {
        $course->load(['user', 'category', 'materials', 'quizzes.questions', 'students', 'skills']);

        return view('admin.courses.show', compact('course'));
    }

    public function toggleArchive(Course $course): RedirectResponse
    {
        $newStatus = !$course->is_archived;
        $course->update(['is_archived' => $newStatus]);

        $statusLabel = $newStatus ? 'diarsipkan (Draft Internal Admin)' : 'diaktifkan kembali';

        return back()->with('success', "Status Course '{$course->name}' berhasil {$statusLabel} oleh Admin.");
    }

    public function destroy(Course $course): RedirectResponse
    {
        $name = $course->name;
        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('success', "Course '{$name}' berhasil dihapus dari sistem oleh Admin.");
    }
}
