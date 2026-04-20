<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::with(['materials', 'quizzes', 'students'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('lecturer.courses.index', compact('courses'));
    }

    public function create(): View
    {
        return view('lecturer.courses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        Course::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'user_id' => Auth::id(),
        ]);

        return redirect()
            ->route('lecturer.courses.index')
            ->with('success', 'Course berhasil dibuat.');
    }

    public function show(Course $course): View
    {
        abort_unless($course->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke course ini.');

        $course->load([
            'materials',
            'quizzes',
            'quizzes.questions',
            'assignments',
            'students',
            'user',
        ]);

        $materials = $course->materials;
        $quizzes = $course->quizzes;
        $assignments = $course->assignments;
        $students = $course->students;

        return view('lecturer.courses.show', compact(
            'course',
            'materials',
            'quizzes',
            'assignments',
            'students'
        ));
    }

    public function edit(Course $course): View
    {
        abort_unless($course->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke course ini.');

        return view('lecturer.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        abort_unless($course->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke course ini.');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $course->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

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
}
