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
    public function store(Request $request, $course): RedirectResponse
    {
        $courseObj = is_numeric($course)
            ? (\App\Models\CourseOffering::find($course) ?? Course::findOrFail($course))
            : $course;

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'time_limit' => ['nullable', 'integer', 'min:1'],
            'quiz_type' => ['required', Rule::in(['daily', 'weekly', 'final'])],
            'max_attempts' => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_unlimited' => ['nullable', 'boolean'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'target_scope' => ['nullable', Rule::in(['all', 'class'])],
        ]);

        $isUnlimited = $request->boolean('is_unlimited');
        $maxAttemptsValue = $isUnlimited ? 0 : ($validated['max_attempts'] ?? 1);

        $targetScope = $validated['target_scope'] ?? 'all';
        $masterCourseId = $courseObj->master_course_id ?? $courseObj->id;

        // Hanya boleh 1 quiz final per course/master course
        if ($validated['quiz_type'] === 'final') {
            $existingFinal = Quiz::where('master_course_id', $masterCourseId)
                ->where('quiz_type', 'final')
                ->exists();

            if ($existingFinal) {
                return redirect()
                    ->back()
                    ->withErrors(['quiz_type' => 'Mata kuliah ini sudah memiliki Final Quiz. Hapus atau ubah yang lama terlebih dahulu.'])
                    ->withInput();
            }
        }

        $quiz = Quiz::create([
            'course_id' => $courseObj->id,
            'master_course_id' => $masterCourseId,
            'title' => $validated['title'],
            'time_limit' => $validated['time_limit'] ?? null,
            'quiz_type' => $validated['quiz_type'],
            'max_attempts' => $maxAttemptsValue,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
        ]);

        // Sinkronkan ke offering_quizzes berdasarkan target scope
        if ($courseObj instanceof \App\Models\CourseOffering) {
            if ($targetScope === 'all') {
                $allOfferings = \App\Models\CourseOffering::where('master_course_id', $masterCourseId)
                    ->where('lecturer_id', Auth::id())
                    ->get();

                foreach ($allOfferings as $offeringItem) {
                    \App\Models\OfferingQuiz::updateOrCreate(
                        [
                            'course_offering_id' => $offeringItem->id,
                            'quiz_id' => $quiz->id,
                        ],
                        [
                            'start_date' => $quiz->start_date,
                            'end_date' => $quiz->end_date,
                            'time_limit' => $quiz->time_limit,
                            'max_attempts' => $quiz->max_attempts,
                        ]
                    );
                }
            } else {
                \App\Models\OfferingQuiz::updateOrCreate(
                    [
                        'course_offering_id' => $courseObj->id,
                        'quiz_id' => $quiz->id,
                    ],
                    [
                        'start_date' => $quiz->start_date,
                        'end_date' => $quiz->end_date,
                        'time_limit' => $quiz->time_limit,
                        'max_attempts' => $quiz->max_attempts,
                    ]
                );
            }
        }

        $typeLabel = match ($validated['quiz_type']) {
            'final' => 'Final Quiz',
            'weekly' => 'Weekly Quiz',
            default => 'Daily Quiz',
        };

        $scopeLabel = $targetScope === 'all' ? 'untuk Semua Kelas (Master)' : "khusus untuk {$courseObj->section_name}";

        return redirect()
            ->route('lecturer.dashboard', ['tab' => 'questions', 'quiz_id' => $quiz->id])
            ->with('success', "{$typeLabel} '{$quiz->title}' berhasil dibuat {$scopeLabel}! Silakan buat soal-soal untuk quiz ini.");
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
