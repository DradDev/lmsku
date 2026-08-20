<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class QuestionController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('vendor.dashboard', ['tab' => 'quizzes']);
    }

    public function store(Request $request): RedirectResponse
    {
        $quizRules = [
            'quiz_id' => ['required', 'exists:quizzes,id'],
        ];

        if ($request->has('questions')) {
            $validated = $request->validate(array_merge($quizRules, [
                'questions' => ['required', 'array', 'min:1'],
                'questions.*.question' => ['required', 'string'],
                'questions.*.question_type' => ['required', Rule::in(['multiple_choice'])],
                'questions.*.difficulty' => ['required', Rule::in(['easy', 'medium', 'hard'])],
                'questions.*.option_a' => ['required', 'string'],
                'questions.*.option_b' => ['required', 'string'],
                'questions.*.option_c' => ['required', 'string'],
                'questions.*.option_d' => ['required', 'string'],
                'questions.*.correct_answer' => ['required', Rule::in(['A', 'B', 'C', 'D'])],
                'questions.*.skill_ids' => ['nullable', 'array'],
                'questions.*.skill_ids.*' => ['exists:skills,id'],
                'questions.*.main_skill_id' => ['nullable', 'exists:skills,id'],
            ]));

            $quiz = Quiz::findOrFail($validated['quiz_id']);
            $countCreated = 0;

            foreach ($validated['questions'] as $qData) {
                $question = Question::create([
                    'quiz_id' => $quiz->id,
                    'user_id' => Auth::id(),
                    'question_type' => 'multiple_choice',
                    'difficulty' => $qData['difficulty'] ?? 'medium',
                    'question' => $qData['question'],
                    'option_a' => $qData['option_a'],
                    'option_b' => $qData['option_b'],
                    'option_c' => $qData['option_c'],
                    'option_d' => $qData['option_d'],
                    'correct_answer' => strtoupper($qData['correct_answer']),
                ]);

                $skillIds = [];
                if (!empty($qData['main_skill_id'])) {
                    $skillIds[] = $qData['main_skill_id'];
                }
                if (!empty($qData['skill_ids']) && is_array($qData['skill_ids'])) {
                    $skillIds = array_merge($skillIds, $qData['skill_ids']);
                }

                if (!empty($skillIds)) {
                    $question->skills()->sync(array_unique($skillIds));
                }

                $countCreated++;
            }

            return redirect()->route('vendor.dashboard', ['tab' => 'quizzes', 'quiz_id' => $quiz->id])
                ->with('success', "Berhasil menambahkan {$countCreated} soal ke kuis '{$quiz->title}'.");
        }

        $validated = $request->validate(array_merge($quizRules, [
            'question' => ['required', 'string'],
            'question_type' => ['required', Rule::in(['multiple_choice'])],
            'difficulty' => ['required', Rule::in(['easy', 'medium', 'hard'])],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['required', 'string'],
            'option_d' => ['required', 'string'],
            'correct_answer' => ['required', Rule::in(['A', 'B', 'C', 'D'])],
            'skill_ids' => ['nullable', 'array'],
            'skill_ids.*' => ['exists:skills,id'],
            'main_skill_id' => ['nullable', 'exists:skills,id'],
        ]));

        $quiz = Quiz::findOrFail($validated['quiz_id']);

        $question = Question::create([
            'quiz_id' => $quiz->id,
            'user_id' => Auth::id(),
            'question_type' => 'multiple_choice',
            'difficulty' => $validated['difficulty'] ?? 'medium',
            'question' => $validated['question'],
            'option_a' => $validated['option_a'],
            'option_b' => $validated['option_b'],
            'option_c' => $validated['option_c'],
            'option_d' => $validated['option_d'],
            'correct_answer' => strtoupper($validated['correct_answer']),
        ]);

        $skillIds = [];
        if (!empty($validated['main_skill_id'])) {
            $skillIds[] = $validated['main_skill_id'];
        }
        if (!empty($validated['skill_ids']) && is_array($validated['skill_ids'])) {
            $skillIds = array_merge($skillIds, $validated['skill_ids']);
        }

        if (!empty($skillIds)) {
            $question->skills()->sync(array_unique($skillIds));
        }

        return redirect()->route('vendor.dashboard', ['tab' => 'quizzes', 'quiz_id' => $quiz->id])
            ->with('success', 'Soal berhasil ditambahkan ke kuis.');
    }

    public function edit(Question $question): View
    {
        $quizzes = Quiz::whereHas('course', function ($query) {
            $query->where('user_id', Auth::id());
        })->get();

        $mainSkills = Skill::with('tags')
            ->orderBy('name')
            ->get();

        return view('vendor.questions.edit', compact('question', 'quizzes', 'mainSkills'));
    }

    public function update(Request $request, Question $question): RedirectResponse
    {
        $validated = $request->validate([
            'quiz_id' => ['required', 'exists:quizzes,id'],
            'question' => ['required', 'string'],
            'difficulty' => ['required', Rule::in(['easy', 'medium', 'hard'])],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['required', 'string'],
            'option_d' => ['required', 'string'],
            'correct_answer' => ['required', Rule::in(['A', 'B', 'C', 'D'])],
            'skill_ids' => ['nullable', 'array'],
            'skill_ids.*' => ['exists:skills,id'],
            'main_skill_id' => ['nullable', 'exists:skills,id'],
        ]);

        $question->update([
            'quiz_id' => $validated['quiz_id'],
            'difficulty' => $validated['difficulty'],
            'question' => $validated['question'],
            'option_a' => $validated['option_a'],
            'option_b' => $validated['option_b'],
            'option_c' => $validated['option_c'],
            'option_d' => $validated['option_d'],
            'correct_answer' => strtoupper($validated['correct_answer']),
        ]);

        $skillIds = [];
        if (!empty($validated['main_skill_id'])) {
            $skillIds[] = $validated['main_skill_id'];
        }
        if (!empty($validated['skill_ids']) && is_array($validated['skill_ids'])) {
            $skillIds = array_merge($skillIds, $validated['skill_ids']);
        }

        $question->skills()->sync(array_unique($skillIds));

        return redirect()->route('vendor.dashboard', ['tab' => 'quizzes', 'quiz_id' => $question->quiz_id])
            ->with('success', 'Soal berhasil diperbarui.');
    }
}
