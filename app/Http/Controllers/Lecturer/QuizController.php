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
            ? (\App\Models\CourseOffering::with('academicTerm')->find($course) ?? Course::findOrFail($course))
            : $course;

        if ($courseObj instanceof \App\Models\CourseOffering && $courseObj->academicTerm && !$courseObj->academicTerm->is_active) {
            return redirect()->back()
                ->with('error', 'Semester untuk kelas ini telah non-aktif / ditutup. Pembuatan kuis ditolak (Read-Only).');
        }

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
            'master_course_id' => $masterCourseId,
            'title' => $validated['title'],
            'time_limit' => $validated['time_limit'] ?? null,
            'quiz_type' => $validated['quiz_type'],
            'max_attempts' => $maxAttemptsValue,
            'start_date' => !empty($validated['start_date']) ? $validated['start_date'] : null,
            'end_date' => !empty($validated['end_date']) ? $validated['end_date'] : null,
        ]);

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

    public function update(Request $request, $course, Quiz $quiz): RedirectResponse
    {
        $courseObj = is_numeric($course)
            ? (\App\Models\CourseOffering::with('academicTerm')->find($course) ?? Course::findOrFail($course))
            : $course;

        $lecturerId = $courseObj->lecturer_id ?? ($courseObj->user_id ?? null);
        if ($lecturerId !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Kamu tidak memiliki akses ke course ini.');
        }

        if ($courseObj instanceof \App\Models\CourseOffering && $courseObj->academicTerm && !$courseObj->academicTerm->is_active) {
            return redirect()->back()
                ->with('error', 'Semester untuk kelas ini telah non-aktif / ditutup. Perubahan kuis ditolak (Read-Only).');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'time_limit' => ['nullable', 'integer', 'min:1'],
            'max_attempts' => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_unlimited' => ['nullable', 'boolean'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $isUnlimited = $request->boolean('is_unlimited');
        $maxAttemptsValue = $isUnlimited ? 0 : ($validated['max_attempts'] ?? 1);

        $quiz->update([
            'title' => $validated['title'],
            'time_limit' => $validated['time_limit'] ?? null,
            'max_attempts' => $maxAttemptsValue,
            'start_date' => !empty($validated['start_date']) ? $validated['start_date'] : null,
            'end_date' => !empty($validated['end_date']) ? $validated['end_date'] : null,
        ]);

        return redirect()
            ->back()
            ->with('success', "Waktu rilis dan deadline Kuis '{$quiz->title}' berhasil diperbarui!");
    }

    public function approveRetake(QuizRetakeRequest $retakeRequest): RedirectResponse
    {
        $courseObj = $retakeRequest->course;
        $lecturerId = $courseObj->lecturer_id ?? ($courseObj->user_id ?? null);
        abort_unless($lecturerId === Auth::id() || Auth::user()->isAdmin(), 403, 'Akses ditolak.');

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
        $courseObj = $retakeRequest->course;
        $lecturerId = $courseObj->lecturer_id ?? ($courseObj->user_id ?? null);
        abort_unless($lecturerId === Auth::id() || Auth::user()->isAdmin(), 403, 'Akses ditolak.');

        $retakeRequest->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', "Permintaan retake kuis mahasiswa '{$retakeRequest->user->name}' ditolak.");
    }

    public function destroy($course, Quiz $quiz): RedirectResponse
    {
        $courseObj = is_numeric($course)
            ? (\App\Models\CourseOffering::with('academicTerm')->find($course) ?? Course::findOrFail($course))
            : $course;

        $lecturerId = $courseObj->lecturer_id ?? ($courseObj->user_id ?? null);
        abort_unless($lecturerId === Auth::id() || Auth::user()->isAdmin(), 403, 'Kamu tidak memiliki akses ke course ini.');

        if ($courseObj instanceof \App\Models\CourseOffering && $courseObj->academicTerm && !$courseObj->academicTerm->is_active) {
            return redirect()->back()
                ->with('error', 'Semester untuk kelas ini telah non-aktif / ditutup. Penghapusan kuis ditolak (Read-Only).');
        }

        \App\Models\OfferingQuiz::where('quiz_id', $quiz->id)->delete();
        $quiz->delete();

        return redirect()
            ->back()
            ->with('success', 'Quiz berhasil dihapus.');
    }

    public function bulkApproveRetake($course): RedirectResponse
    {
        $courseObj = is_numeric($course)
            ? (\App\Models\CourseOffering::find($course) ?? Course::findOrFail($course))
            : $course;

        $lecturerId = $courseObj->lecturer_id ?? ($courseObj->user_id ?? null);
        abort_unless($lecturerId === Auth::id() || Auth::user()->isAdmin(), 403, 'Akses ditolak.');

        $offeringId = $courseObj instanceof \App\Models\CourseOffering ? $courseObj->id : null;
        $quizzes = $courseObj->masterCourse ? $courseObj->masterCourse->quizzes : $courseObj->quizzes;
        $quizIds = $quizzes ? $quizzes->pluck('id') : collect();

        $query = \App\Models\QuizRetakeRequest::whereIn('quiz_id', $quizIds)
            ->where('status', 'pending');

        if ($offeringId) {
            $query->where(function ($q) use ($offeringId, $courseObj) {
                $q->where('course_offering_id', $offeringId)
                  ->orWhere(function ($sub) use ($courseObj) {
                      $sub->whereNull('course_offering_id')
                          ->whereIn('user_id', $courseObj->enrollments->pluck('user_id'));
                  });
            });
        }

        $count = $query->update([
            'status'      => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', "Berhasil menyetujui seluruh permintaan retake ({$count} mahasiswa) sekaligus!");
    }
}
