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
    public function store(Request $request, Course $course): RedirectResponse
    {
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke course sertifikasi ini.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'quiz_type' => ['required', 'in:daily,weekly,final'],
            'time_limit' => ['required', 'integer', 'min:1'],
            'max_attempts' => ['required', 'integer', 'min:1'],
        ]);

        $validated['course_id'] = $course->id;

        $quiz = Quiz::create($validated);

        return back()->with('success', "Kuis '{$quiz->title}' berhasil dibuat. Silakan tambahkan soal evaluasi.");
    }

    public function show(Quiz $quiz): View
    {
        $course = $quiz->course;
        if ($course && $course->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke kuis ini.');
        }

        $quiz->load(['questions', 'course']);

        return view('vendor.quizzes.show', compact('quiz', 'course'));
    }

    public function storeQuestion(Request $request, Quiz $quiz): RedirectResponse
    {
        $course = $quiz->course;
        if ($course && $course->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke kuis ini.');
        }

        $validated = $request->validate([
            'question' => ['required', 'string'],
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
        $course = $quiz ? $quiz->course : null;

        if ($course && $course->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke soal ini.');
        }

        $question->delete();

        return back()->with('success', 'Soal evaluasi berhasil dihapus.');
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        $course = $quiz->course;
        if ($course && $course->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke kuis ini.');
        }

        $quiz->delete();

        return back()->with('success', 'Kuis kelulusan berhasil dihapus.');
    }
}
