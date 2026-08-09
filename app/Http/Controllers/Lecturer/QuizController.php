<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizRetakeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class QuizController extends Controller
{
    public function store(Request $request, Course $course): RedirectResponse
    {
        abort_unless($course->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke course ini.');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'time_limit' => ['nullable', 'integer', 'min:1'],
            'quiz_type' => ['required', Rule::in(['daily', 'weekly', 'final'])],
            'max_attempts' => ['required', 'integer', 'min:1', 'max:100'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        // Hanya boleh 1 quiz final per course
        if ($validated['quiz_type'] === 'final') {
            $existingFinal = Quiz::where('course_id', $course->id)
                ->where('quiz_type', 'final')
                ->exists();

            if ($existingFinal) {
                return redirect()
                    ->back()
                    ->withErrors(['quiz_type' => 'Course ini sudah memiliki Final Quiz. Hapus atau ubah yang lama terlebih dahulu.'])
                    ->withInput();
            }
        }

        $masterCourseId = $course->master_course_id ?? \App\Models\MasterCourse::where('name', $course->name)->value('id');

        $quiz = Quiz::create([
            'course_id' => $course->id,
            'master_course_id' => $masterCourseId,
            'title' => $validated['title'],
            'time_limit' => $validated['time_limit'] ?? null,
            'quiz_type' => $validated['quiz_type'],
            'max_attempts' => $validated['max_attempts'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
        ]);

        if ($masterCourseId) {
            $offeringIds = \App\Models\CourseOffering::where('master_course_id', $masterCourseId)->pluck('id');
            foreach ($offeringIds as $offeringId) {
                \App\Models\OfferingQuiz::firstOrCreate([
                    'course_offering_id' => $offeringId,
                    'quiz_id' => $quiz->id,
                ], [
                    'start_date' => $quiz->start_date,
                    'end_date' => $quiz->end_date,
                    'time_limit' => $quiz->time_limit,
                    'max_attempts' => $quiz->max_attempts,
                ]);
            }
        }

        $typeLabel = match ($validated['quiz_type']) {
            'final' => 'Final Quiz',
            'weekly' => 'Weekly Quiz',
            default => 'Daily Quiz',
        };

        return redirect()
            ->route('lecturer.dashboard', ['tab' => 'questions', 'quiz_id' => $quiz->id])
            ->with('success', "{$typeLabel} '{$quiz->title}' berhasil dibuat! Silakan buat soal-soal untuk quiz ini di bawah ini.");
    }

    public function update(Request $request, Course $course, Quiz $quiz): RedirectResponse
    {
        abort_unless($course->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke course ini.');
        abort_unless($quiz->course_id === $course->id, 403, 'Quiz tidak valid.');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'time_limit' => ['nullable', 'integer', 'min:1'],
            'max_attempts' => ['required', 'integer', 'min:1', 'max:100'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $quiz->update([
            'title' => $validated['title'],
            'time_limit' => $validated['time_limit'] ?? null,
            'max_attempts' => $validated['max_attempts'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
        ]);

        return redirect()
            ->back()
            ->with('success', "Waktu dan pengaturan Quiz '{$quiz->title}' berhasil diperbarui!");
    }

    public function approveRetake(QuizRetakeRequest $retakeRequest): RedirectResponse
    {
        abort_unless($retakeRequest->course->user_id === Auth::id(), 403, 'Akses ditolak.');

        $retakeRequest->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', "Permintaan retake kuis mahasiswa '{$retakeRequest->user->name}' berhasil disetujui!");
    }

    public function rejectRetake(QuizRetakeRequest $retakeRequest): RedirectResponse
    {
        abort_unless($retakeRequest->course->user_id === Auth::id(), 403, 'Akses ditolak.');

        $retakeRequest->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', "Permintaan retake kuis mahasiswa '{$retakeRequest->user->name}' ditolak.");
    }

    public function destroy(Course $course, Quiz $quiz): RedirectResponse
    {
        abort_unless($course->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke course ini.');
        abort_unless($quiz->course_id === $course->id, 403, 'Quiz tidak valid.');

        $quiz->delete();

        return redirect()
            ->back()
            ->with('success', 'Quiz berhasil dihapus.');
    }
}
