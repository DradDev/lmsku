<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    public function index(): View
    {
        $submissions = Submission::query()
            ->with(['assignment.course', 'user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('student.submissions.index', compact('submissions'));
    }

    public function create(Assignment $assignment): View
    {
        $user = Auth::user();

        $isEnrolled = DB::table('enrollments')
            ->where('user_id', $user->id)
            ->where('course_id', $assignment->course_id)
            ->exists();

        abort_unless($isEnrolled, 403, 'Kamu tidak memiliki akses ke assignment ini.');

        $assignment->load('course');

        $existingSubmission = Submission::query()
            ->where('assignment_id', $assignment->id)
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        return view('student.submissions.create', compact('assignment', 'existingSubmission'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'assignment_id' => ['required', 'exists:assignments,id'],
            'file' => ['required', 'file', 'max:20480'],
        ]);

        $user = Auth::user();

        $assignment = Assignment::findOrFail($validated['assignment_id']);

        $isEnrolled = DB::table('enrollments')
            ->where('user_id', $user->id)
            ->where('course_id', $assignment->course_id)
            ->exists();

        abort_unless($isEnrolled, 403, 'Kamu tidak memiliki akses ke assignment ini.');

        $filePath = $request->file('file')->store('submissions', 'public');

        Submission::create([
            'assignment_id' => $assignment->id,
            'user_id' => $user->id,
            'file_path' => $filePath,
        ]);

        return redirect()
            ->route('student.submissions.index')
            ->with('success', 'Submission berhasil diupload.');
    }

    public function show(Submission $submission): View
    {
        abort_unless(
            $submission->user_id === Auth::id(),
            403,
            'Kamu tidak memiliki akses ke submission ini.'
        );

        $submission->load(['assignment.course', 'user']);

        return view('student.submissions.show', compact('submission'));
    }
}
