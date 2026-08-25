<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizRetakeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function create(Request $request, $course): View
    {
        $courseObj = is_numeric($course)
            ? (\App\Models\CourseOffering::with('academicTerm')->find($course) ?? Course::findOrFail($course))
            : $course;

        if ($courseObj instanceof \App\Models\CourseOffering && $courseObj->academicTerm && !$courseObj->academicTerm->is_active) {
            return redirect()->back()
                ->with('error', 'Semester untuk kelas ini telah non-aktif / ditutup. Pembuatan kuis ditolak (Read-Only).');
        }

        return view('lecturer.quizzes.create', compact('courseObj'));
    }

    public function store(\App\Http\Requests\Lecturer\StoreQuizRequest $request, $course): RedirectResponse
    {
        $validated = $request->validated();
        
        $courseObj = is_numeric($course)
            ? (\App\Models\CourseOffering::find($course) ?? Course::findOrFail($course))
            : $course;

        $targetScope = $validated['target_scope'] ?? 'all';
        $masterCourseId = ($courseObj instanceof \App\Models\MasterCourse)
            ? $courseObj->id
            : ($courseObj->master_course_id ?? $courseObj->id);

        if ($targetScope === 'class' && $courseObj instanceof \App\Models\CourseOffering) {
            $quizzableType = \App\Models\CourseOffering::class;
            $quizzableId = $courseObj->id;
        } else {
            $quizzableType = \App\Models\MasterCourse::class;
            $quizzableId = $masterCourseId;
        }

        $isUnlimited = $request->boolean('is_unlimited');
        $maxAttemptsValue = $isUnlimited ? 0 : ($validated['max_attempts'] ?? 1);

        $quiz = Quiz::create([
            'quizzable_type' => $quizzableType,
            'quizzable_id'   => $quizzableId,
            'title'          => $validated['title'],
            'time_limit'     => $validated['time_limit'] ?? null,
            'quiz_type'      => $validated['quiz_type'],
            'max_attempts'   => $maxAttemptsValue,
            'start_date'     => !empty($validated['start_date']) ? $validated['start_date'] : null,
            'end_date'       => !empty($validated['end_date']) ? $validated['end_date'] : null,
        ]);

        $typeLabel = match ($validated['quiz_type']) {
            'final' => 'Final Quiz',
            'weekly' => 'Weekly Quiz',
            default => 'Daily Quiz',
        };

        $scopeLabel = $targetScope === 'all' ? 'untuk Semua Kelas (Master)' : "khusus untuk {$courseObj->section_name}";

        return redirect()
            ->back()
            ->with('success', "{$typeLabel} '{$quiz->title}' berhasil dibuat {$scopeLabel}! Silakan kelola butir soal untuk kuis ini.");
    }

    public function show($courseOrQuiz, $quizParam = null): View
    {
        $quiz = $quizParam instanceof Quiz 
            ? $quizParam 
            : ($courseOrQuiz instanceof Quiz ? $courseOrQuiz : Quiz::findOrFail(is_numeric($quizParam) ? $quizParam : $courseOrQuiz));

        $courseObj = null;
        if ($quizParam) {
            $courseObj = is_numeric($courseOrQuiz)
                ? (\App\Models\CourseOffering::with('academicTerm')->find($courseOrQuiz) ?? Course::findOrFail($courseOrQuiz))
                : $courseOrQuiz;
        } else {
            $courseObj = $quiz->course ?? ($quiz->quizzable instanceof \App\Models\MasterCourse ? $quiz->quizzable->offerings()->where('lecturer_id', Auth::id())->first() : $quiz->quizzable);
        }

        $lecturerId = $courseObj?->lecturer_id ?? ($courseObj?->user_id ?? ($quiz->course?->user_id ?? null));
        if ($lecturerId && $lecturerId !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Kamu tidak memiliki akses ke kuis ini.');
        }

        $isTermActive = true;
        if ($courseObj instanceof \App\Models\CourseOffering && $courseObj->academicTerm) {
            $isTermActive = (bool) $courseObj->academicTerm->is_active;
        }

        $quiz->load(['questions' => fn($q) => $q->latest()], 'quizzable');
        $course = $courseObj ?? $quiz->course;

        return view('lecturer.quiz.show', compact('quiz', 'course', 'isTermActive'));
    }

    public function storeQuestion(Request $request, Quiz $quiz): RedirectResponse
    {
        $ownerId = $quiz->course?->lecturer_id ?? ($quiz->course?->user_id ?? $quiz->quizzable?->user_id);
        if ($ownerId && $ownerId !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Kamu tidak memiliki akses ke kuis ini.');
        }

        if ($quiz->course && $quiz->course instanceof \App\Models\CourseOffering && $quiz->course->academicTerm && !$quiz->course->academicTerm->is_active) {
            return redirect()->back()
                ->with('error', 'Semester untuk kelas ini telah non-aktif / ditutup. Penambahan soal ditolak (Read-Only).');
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
            'status' => 'approved',
        ]);

        return redirect()->back()->with('success', 'Soal evaluasi berhasil ditambahkan ke kuis.');
    }

    public function update(\App\Http\Requests\Lecturer\UpdateQuizRequest $request, $course, Quiz $quiz): RedirectResponse
    {
        $validated = $request->validated();

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

        $quizTitle = $quiz->title;
        $quiz->delete();

        return redirect()
            ->back()
            ->with('success', "Kuis '{$quizTitle}' berhasil dihapus.");
    }

    public function bulkApproveRetake($course): RedirectResponse
    {
        $courseObj = is_numeric($course)
            ? (\App\Models\CourseOffering::find($course) ?? Course::findOrFail($course))
            : $course;

        $lecturerId = $courseObj->lecturer_id ?? ($courseObj->user_id ?? null);
        abort_unless($lecturerId === Auth::id() || Auth::user()->isAdmin(), 403, 'Akses ditolak.');

        $offeringId = $courseObj instanceof \App\Models\CourseOffering ? $courseObj->id : null;
        $quizzes = $courseObj instanceof \App\Models\CourseOffering ? $courseObj->all_quizzes : ($courseObj->masterCourse ? $courseObj->masterCourse->quizzes : $courseObj->quizzes);
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

    public function retakeRequests($course): \Illuminate\View\View
    {
        $courseObj = is_numeric($course)
            ? (\App\Models\CourseOffering::with(['masterCourse', 'academicTerm', 'enrollments.user'])->find($course) ?? Course::findOrFail($course))
            : $course;

        $lecturerId = $courseObj->lecturer_id ?? ($courseObj->user_id ?? null);
        abort_unless($lecturerId === Auth::id() || Auth::user()->isAdmin(), 403, 'Akses ditolak.');

        $course = $courseObj;
        $offeringId = $courseObj instanceof \App\Models\CourseOffering ? $courseObj->id : null;
        $quizzes = $courseObj instanceof \App\Models\CourseOffering ? $courseObj->all_quizzes : ($courseObj->masterCourse ? $courseObj->masterCourse->quizzes : $courseObj->quizzes);
        $quizIds = $quizzes ? $quizzes->pluck('id') : collect();

        $query = \App\Models\QuizRetakeRequest::with(['user', 'quiz', 'courseOffering'])
            ->whereIn('quiz_id', $quizIds);

        if ($offeringId) {
            $query->where(function ($q) use ($offeringId, $courseObj) {
                $q->where('course_offering_id', $offeringId)
                  ->orWhere(function ($sub) use ($courseObj) {
                      $sub->whereNull('course_offering_id')
                          ->whereIn('user_id', $courseObj->enrollments->pluck('user_id'));
                  });
            });
        }

        $retakeRequests = $query->latest()->get();

        return view('lecturer.courses.retakes', compact('course', 'quizzes', 'retakeRequests'));
    }
}
