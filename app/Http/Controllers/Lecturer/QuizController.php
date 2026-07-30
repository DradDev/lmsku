<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
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

        Quiz::create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'time_limit' => $validated['time_limit'] ?? null,
            'quiz_type' => $validated['quiz_type'],
            'max_attempts' => $validated['max_attempts'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
        ]);

        $typeLabel = match ($validated['quiz_type']) {
            'final' => 'Final Quiz',
            'weekly' => 'Weekly Quiz',
            default => 'Daily Quiz',
        };

        return redirect()
            ->back()
            ->with('success', "{$typeLabel} berhasil dibuat.");
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
