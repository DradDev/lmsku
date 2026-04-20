<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function store(Request $request, Course $course): RedirectResponse
    {
        abort_unless($course->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke course ini.');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'time_limit' => ['nullable', 'integer', 'min:1'],
            'is_final' => ['nullable', 'boolean'],
        ]);

        $isFinal = (bool) ($validated['is_final'] ?? false);

        if ($isFinal) {
            Quiz::where('course_id', $course->id)->update([
                'is_final' => false,
            ]);
        }

        Quiz::create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'time_limit' => $validated['time_limit'] ?? null,
            'is_final' => $isFinal,
        ]);

        return redirect()
            ->back()
            ->with('success', $isFinal ? 'Final quiz berhasil dibuat.' : 'Quiz berhasil dibuat.');
    }

    public function makeFinal(Course $course, Quiz $quiz): RedirectResponse
    {
        abort_unless($course->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke course ini.');
        abort_unless($quiz->course_id === $course->id, 403, 'Quiz tidak valid.');

        Quiz::where('course_id', $course->id)->update([
            'is_final' => false,
        ]);

        $quiz->update([
            'is_final' => true,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Quiz berhasil dijadikan final quiz.');
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
