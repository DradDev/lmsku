<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\LearningActivityLog;
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

        $this->authorizeStudentEnrollment($user->id, $assignment->course_id);

        $assignment->load('course');

        $existingSubmission = Submission::query()
            ->where('assignment_id', $assignment->id)
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        return view('student.submissions.create', compact(
            'assignment',
            'existingSubmission'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'assignment_id' => ['required', 'exists:assignments,id'],
            'file' => ['required', 'file', 'max:20480'],
        ]);

        $user = Auth::user();

        $assignment = Assignment::query()
            ->with('course')
            ->findOrFail($validated['assignment_id']);

        $this->authorizeStudentEnrollment($user->id, $assignment->course_id);

        $filePath = $request->file('file')->store('submissions', 'public');

        $submission = Submission::create([
            'assignment_id' => $assignment->id,
            'user_id' => $user->id,
            'file_path' => $filePath,
        ]);

        LearningActivityLog::create([
            'user_id' => $user->id,
            'course_id' => $assignment->course_id,
            'activity_type' => 'submit_assignment',
            'activity_value' => 1,
            'metadata' => [
                'assignment_id' => $assignment->id,
                'submission_id' => $submission->id,
                'file_path' => $filePath,
            ],
            'occurred_at' => now(),
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

    private function authorizeStudentEnrollment(int $userId, int $courseId): void
    {
        $isEnrolled = DB::table('enrollments')
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();

        abort_unless(
            $isEnrolled,
            403,
            'Kamu tidak memiliki akses ke assignment ini.'
        );
    }
}