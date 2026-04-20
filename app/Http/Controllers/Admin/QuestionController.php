<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class QuestionController extends Controller
{
    public function index(): View
    {
        $questions = Question::query()
            ->with(['quiz.course', 'user'])
            ->latest()
            ->get();

        $totalQuestions = Question::count();
        $approvedCount = Question::where('status', 'approved')->count();
        $pendingCount = Question::where('status', 'pending')->count();
        $rejectedCount = Question::where('status', 'rejected')->count();

        return view('admin.questions.index', compact(
            'questions',
            'totalQuestions',
            'approvedCount',
            'pendingCount',
            'rejectedCount'
        ));
    }

    public function show(Question $question): View
    {
        $question->load(['quiz.course', 'user']);

        return view('admin.questions.show', compact('question'));
    }

    public function edit(Question $question): View
    {
        $question->load(['quiz.course', 'user']);

        return view('admin.questions.edit', compact('question'));
    }

    public function update(Request $request, Question $question): RedirectResponse
    {
        $rules = [
            'question' => ['required', 'string'],
            'question_type' => ['required', Rule::in(['essay', 'multiple_choice'])],
            'difficulty' => ['required', Rule::in(['easy', 'medium', 'hard'])],
            'status' => ['required', Rule::in(['pending', 'approved', 'rejected'])],
        ];

        if ($request->input('question_type') === 'multiple_choice') {
            $rules = array_merge($rules, [
                'option_a' => ['required', 'string'],
                'option_b' => ['required', 'string'],
                'option_c' => ['required', 'string'],
                'option_d' => ['required', 'string'],
                'correct_answer' => ['required', Rule::in(['A', 'B', 'C', 'D'])],
            ]);
        }

        $validated = $request->validate($rules);

        if (($validated['question_type'] ?? null) === 'essay') {
            $validated['option_a'] = null;
            $validated['option_b'] = null;
            $validated['option_c'] = null;
            $validated['option_d'] = null;
            $validated['correct_answer'] = null;
        }

        $question->update($validated);

        return redirect()
            ->route('admin.questions.index')
            ->with('success', 'Soal berhasil diperbarui.');
    }

    public function approve(Question $question): RedirectResponse
    {
        $question->update([
            'status' => 'approved',
        ]);

        return redirect()
            ->route('admin.questions.index')
            ->with('success', 'Soal berhasil disetujui.');
    }

    public function reject(Question $question): RedirectResponse
    {
        $question->update([
            'status' => 'rejected',
        ]);

        return redirect()
            ->route('admin.questions.index')
            ->with('success', 'Soal berhasil ditolak.');
    }

    public function destroy(Question $question): RedirectResponse
    {
        $question->delete();

        return redirect()
            ->route('admin.questions.index')
            ->with('success', 'Soal berhasil dihapus.');
    }
}
