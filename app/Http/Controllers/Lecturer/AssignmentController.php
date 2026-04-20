<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function index(): View
    {
        $assignments = Assignment::query()
            ->with(['course'])
            ->whereHas('course', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->latest()
            ->get();

        return view('lecturer.assignments.index', compact('assignments'));
    }

    public function create(): View
    {
        $courses = Course::query()
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('lecturer.assignments.create', compact('courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'deadline' => ['nullable', 'date'],
        ]);

        $course = Course::query()
            ->where('user_id', Auth::id())
            ->findOrFail($validated['course_id']);

        Assignment::create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'deadline' => $validated['deadline'] ?? null,
        ]);

        return redirect()
            ->route('lecturer.assignments.index')
            ->with('success', 'Assignment berhasil dibuat.');
    }

    public function show(Assignment $assignment): View
    {
        abort_unless(
            $assignment->course && $assignment->course->user_id === Auth::id(),
            403,
            'Kamu tidak memiliki akses ke assignment ini.'
        );

        $assignment->load(['course', 'submissions.user']);

        return view('lecturer.assignments.show', compact('assignment'));
    }

    public function edit(Assignment $assignment): View
    {
        abort_unless(
            $assignment->course && $assignment->course->user_id === Auth::id(),
            403
        );

        $courses = Course::query()
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('lecturer.assignments.edit', compact('assignment', 'courses'));
    }

    public function update(Request $request, Assignment $assignment): RedirectResponse
    {
        abort_unless(
            $assignment->course && $assignment->course->user_id === Auth::id(),
            403
        );

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'deadline' => ['nullable', 'date'],
        ]);

        $course = Course::query()
            ->where('user_id', Auth::id())
            ->findOrFail($validated['course_id']);

        $assignment->update([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'deadline' => $validated['deadline'] ?? null,
        ]);

        return redirect()
            ->route('lecturer.assignments.index')
            ->with('success', 'Assignment berhasil diperbarui.');
    }

    public function gradeSubmission(Request $request, Assignment $assignment, Submission $submission): RedirectResponse
    {
        abort_unless(
            $assignment->course && $assignment->course->user_id === Auth::id(),
            403
        );

        abort_unless($submission->assignment_id === $assignment->id, 404);

        $validated = $request->validate([
            'grade' => ['required', 'numeric', 'min:0', 'max:100'],
            'feedback' => ['nullable', 'string'],
        ]);

        $data = [];

        if (Schema::hasColumn('submissions', 'grade')) {
            $data['grade'] = $validated['grade'];
        } elseif (Schema::hasColumn('submissions', 'score')) {
            $data['score'] = $validated['grade'];
        }

        if (Schema::hasColumn('submissions', 'feedback')) {
            $data['feedback'] = $validated['feedback'] ?? null;
        }

        $submission->update($data);

        return back()->with('success', 'Submission berhasil dinilai.');
    }

    public function destroy(Assignment $assignment): RedirectResponse
    {
        abort_unless(
            $assignment->course && $assignment->course->user_id === Auth::id(),
            403
        );

        $assignment->delete();

        return redirect()
            ->route('lecturer.assignments.index')
            ->with('success', 'Assignment berhasil dihapus.');
    }
}
