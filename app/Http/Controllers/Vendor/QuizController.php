<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function store(Request $request, $course): RedirectResponse
    {
        $courseObj = is_numeric($course) ? Course::findOrFail($course) : $course;

        $ownerId = $courseObj->lecturer_id ?? ($courseObj->user_id ?? $courseObj->masterCourse?->user_id);
        if ($ownerId !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke course sertifikasi ini.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'quiz_type' => ['required', 'in:daily,weekly,final'],
            'time_limit' => ['nullable', 'integer', 'min:1'],
            'max_attempts' => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_unlimited' => ['nullable', 'boolean'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $isUnlimited = $request->boolean('is_unlimited');
        $maxAttemptsValue = $isUnlimited ? 0 : ($validated['max_attempts'] ?? 1);

        $quiz = Quiz::firstOrCreate(
            [
                'master_course_id' => $courseObj->master_course_id ?? $courseObj->id,
                'title'            => $validated['title'],
            ],
            [
                'quiz_type'    => $validated['quiz_type'],
                'time_limit'   => $validated['time_limit'] ?? null,
                'max_attempts' => $maxAttemptsValue,
                'start_date'   => !empty($validated['start_date']) ? $validated['start_date'] : null,
                'end_date'     => !empty($validated['end_date']) ? $validated['end_date'] : null,
            ]
        );

        return back()->with('success', "Kuis '{$quiz->title}' berhasil dibuat. Silakan tambahkan soal evaluasi.");
    }

    public function update(Request $request, $course, Quiz $quiz): RedirectResponse
    {
        $courseObj = is_numeric($course) ? Course::findOrFail($course) : $course;

        $ownerId = $courseObj->lecturer_id ?? ($courseObj->user_id ?? $courseObj->masterCourse?->user_id);
        if ($ownerId !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke course ini.');
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

        return back()->with('success', "Waktu rilis dan deadline Kuis '{$quiz->title}' berhasil diperbarui!");
    }

    public function show(Quiz $quiz): View
    {
        $ownerId = $quiz->masterCourse?->user_id ?? ($quiz->course?->lecturer_id ?? $quiz->course?->user_id);
        if ($ownerId && $ownerId !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke kuis ini.');
        }

        $course = $quiz->course;
        $quiz->load(['questions', 'course']);

        return view('vendor.quizzes.show', compact('quiz', 'course'));
    }

    public function storeQuestion(Request $request, Quiz $quiz): RedirectResponse
    {
        $ownerId = $quiz->masterCourse?->user_id ?? ($quiz->course?->lecturer_id ?? $quiz->course?->user_id);
        if ($ownerId && $ownerId !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke kuis ini.');
        }

        $validated = $request->validate([
            'question' => ['required', 'string'],
            'difficulty' => ['nullable', 'in:easy,medium,hard'],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['required', 'string'],
            'option_d' => ['required', 'string'],
            'correct_answer' => ['required', 'in:A,B,C,D'],
        ]);

        Question::create([
            'quiz_id' => $quiz->id,
            'user_id' => Auth::id(),
            'question_type' => 'multiple_choice',
            'difficulty' => $validated['difficulty'] ?? 'medium',
            'question' => $validated['question'],
            'option_a' => $validated['option_a'],
            'option_b' => $validated['option_b'],
            'option_c' => $validated['option_c'],
            'option_d' => $validated['option_d'],
            'correct_answer' => $validated['correct_answer'],
        ]);

        return back()->with('success', 'Soal evaluasi berhasil ditambahkan ke kuis.');
    }

    public function destroyQuestion(Question $question): RedirectResponse
    {
        $quiz = $question->quiz;
        $vendorId = Auth::id();
        $isAuthorized = ($quiz && $quiz->course && $quiz->course->user_id === $vendorId) ||
                        ($quiz && $quiz->masterCourse && $quiz->masterCourse->user_id === $vendorId);

        if (!$isAuthorized && !Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke soal ini.');
        }

        $question->delete();

        return back()->with('success', 'Soal evaluasi berhasil dihapus.');
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        $vendorId = Auth::id();
        $isAuthorized = ($quiz->course && $quiz->course->user_id === $vendorId) ||
                        ($quiz->masterCourse && $quiz->masterCourse->user_id === $vendorId);

        if (!$isAuthorized && !Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke kuis ini.');
        }

        $quiz->delete();

        return back()->with('success', 'Kuis kelulusan berhasil dihapus.');
    }

    public function approveRetake(\App\Models\QuizRetakeRequest $retakeRequest): RedirectResponse
    {
        $vendorId = Auth::id();
        $quiz = $retakeRequest->quiz;
        $offering = $retakeRequest->courseOffering;

        $isAuthorized = ($offering && ($offering->lecturer_id === $vendorId || $offering->user_id === $vendorId)) ||
                        ($quiz && $quiz->masterCourse && $quiz->masterCourse->user_id === $vendorId) ||
                        Auth::user()->isAdmin();

        abort_unless($isAuthorized, 403, 'Akses ditolak.');

        $retakeRequest->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', "Permintaan retake kuis mahasiswa '{$retakeRequest->user->name}' berhasil disetujui!");
    }

    public function rejectRetake(\App\Models\QuizRetakeRequest $retakeRequest): RedirectResponse
    {
        $vendorId = Auth::id();
        $quiz = $retakeRequest->quiz;
        $offering = $retakeRequest->courseOffering;

        $isAuthorized = ($offering && ($offering->lecturer_id === $vendorId || $offering->user_id === $vendorId)) ||
                        ($quiz && $quiz->masterCourse && $quiz->masterCourse->user_id === $vendorId) ||
                        Auth::user()->isAdmin();

        abort_unless($isAuthorized, 403, 'Akses ditolak.');

        $retakeRequest->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', "Permintaan retake kuis mahasiswa '{$retakeRequest->user->name}' ditolak.");
    }

    public function bulkApproveRetake(\App\Models\CourseOffering $course): RedirectResponse
    {
        $vendorId = Auth::id();
        $ownerId = $course->lecturer_id ?? ($course->user_id ?? $course->masterCourse?->user_id);
        abort_unless($ownerId === $vendorId || Auth::user()->isAdmin(), 403, 'Akses ditolak.');

        $quizzes = $course->masterCourse ? $course->masterCourse->quizzes : $course->quizzes;
        $quizIds = $quizzes ? $quizzes->pluck('id') : collect();

        $count = \App\Models\QuizRetakeRequest::whereIn('quiz_id', $quizIds)
            ->where('status', 'pending')
            ->where(function ($q) use ($course) {
                $q->where('course_offering_id', $course->id)
                  ->orWhere(function ($sub) use ($course) {
                      $sub->whereNull('course_offering_id')
                          ->whereIn('user_id', $course->enrollments->pluck('user_id'));
                  });
            })
            ->update([
                'status'      => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

        return redirect()
            ->back()
            ->with('success', "Berhasil menyetujui seluruh permintaan retake ({$count} mahasiswa) pada angkatan ini!");
    }

    public function retakeRequests(\App\Models\CourseOffering $course): \Illuminate\View\View
    {
        $vendorId = Auth::id();
        $ownerId = $course->lecturer_id ?? ($course->user_id ?? $course->masterCourse?->user_id);
        abort_unless($ownerId === $vendorId || Auth::user()->isAdmin(), 403, 'Akses ditolak.');

        $course->load(['masterCourse', 'enrollments.user']);
        $quizzes = $course->masterCourse ? $course->masterCourse->quizzes : $course->quizzes;
        $quizIds = $quizzes ? $quizzes->pluck('id') : collect();

        $retakeRequests = \App\Models\QuizRetakeRequest::with(['user', 'quiz', 'courseOffering'])
            ->whereIn('quiz_id', $quizIds)
            ->where(function ($q) use ($course) {
                $q->where('course_offering_id', $course->id)
                  ->orWhere(function ($sub) use ($course) {
                      $sub->whereNull('course_offering_id')
                          ->whereIn('user_id', $course->enrollments->pluck('user_id'));
                  });
            })
            ->latest()
            ->get();

        return view('vendor.courses.retakes', compact('course', 'quizzes', 'retakeRequests'));
    }
}
