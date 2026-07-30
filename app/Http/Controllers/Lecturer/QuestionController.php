<?php

namespace App\Http\Controllers\Lecturer;

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
        return redirect()->route('lecturer.dashboard', ['tab' => 'questions']);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('lecturer.dashboard', ['tab' => 'questions']);
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
        } else {
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

            $validated['questions'] = [[
                'question' => $validated['question'],
                'question_type' => $validated['question_type'],
                'difficulty' => $validated['difficulty'],
                'option_a' => $validated['option_a'],
                'option_b' => $validated['option_b'],
                'option_c' => $validated['option_c'],
                'option_d' => $validated['option_d'],
                'correct_answer' => $validated['correct_answer'],

                'skill_ids' => $validated['skill_ids'] ?? [],
                'main_skill_id' => $validated['main_skill_id'] ?? null,
            ]];
        }

        $quiz = Quiz::query()
            ->whereHas('course', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->findOrFail($validated['quiz_id']);

        $createdMultipleChoice = 0;

        foreach ($validated['questions'] as $item) {

            $question = Question::create([
                'quiz_id' => $quiz->id,
                'user_id' => Auth::id(),
                'question' => $item['question'],
                'question_type' => $item['question_type'],
                'difficulty' => $item['difficulty'],
                'option_a' => $item['option_a'] ?? null,
                'option_b' => $item['option_b'] ?? null,
                'option_c' => $item['option_c'] ?? null,
                'option_d' => $item['option_d'] ?? null,
                'correct_answer' => $item['correct_answer'] ?? null,
                'status' => 'approved',
            ]);

            $this->syncQuestionSkills(
                $question,
                $item['skill_ids'] ?? [],
                $item['main_skill_id'] ?? null
            );

            $createdMultipleChoice++;
        }

        return redirect()
            ->route('lecturer.dashboard', ['tab' => 'questions'])
            ->with('success', $createdMultipleChoice . ' multiple choice question berhasil dibuat dan langsung aktif.');
    }

    public function show(Question $question): RedirectResponse
    {
        return redirect()->route('lecturer.dashboard', ['tab' => 'questions']);
    }

    public function edit(Question $question): View
    {
        abort_unless($question->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke question ini.');

        $quizzes = Quiz::query()
            ->whereHas('course', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->with('course')
            ->latest()
            ->get();

        $mainSkills = Skill::with(['children' => function ($query) {
                $query->orderBy('name');
            }])
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $question->load('skills');

        return view('lecturer.questions.edit', compact('question', 'quizzes', 'mainSkills'));
    }

    public function update(Request $request, Question $question): RedirectResponse
    {
        abort_unless($question->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke question ini.');


        $rules = [
            'quiz_id' => ['required', 'exists:quizzes,id'],
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
        ];

        $data = $request->validate($rules);

        $skillIds = $data['skill_ids'] ?? [];
        $mainSkillId = $data['main_skill_id'] ?? null;

        unset($data['skill_ids'], $data['main_skill_id']);

        $quiz = Quiz::query()
            ->whereHas('course', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->findOrFail($data['quiz_id']);

        $data['status'] = 'approved';
        $data['quiz_id'] = $quiz->id;

        $question->update($data);

        $this->syncQuestionSkills($question, $skillIds, $mainSkillId);

        return redirect()
            ->route('lecturer.dashboard', ['tab' => 'questions'])
            ->with('success', 'Question berhasil diperbarui dan tetap aktif untuk student.');
    }

    public function destroy(Question $question): RedirectResponse
    {
        abort_unless($question->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke question ini.');

        $question->delete();

        return redirect()
            ->route('lecturer.dashboard', ['tab' => 'questions'])
            ->with('success', 'Question deleted successfully.');
    }

    private function syncQuestionSkills(Question $question, array $skillIds, mixed $mainSkillId = null): void
    {
        $skillIds = array_map('intval', $skillIds);

        if ($mainSkillId && ! in_array((int) $mainSkillId, $skillIds, true)) {
            $skillIds[] = (int) $mainSkillId;
        }

        $syncData = [];

        foreach ($skillIds as $skillId) {
            $syncData[$skillId] = [
                'weight' => 1.00,
            ];
        }

        $question->skills()->sync($syncData);
    }
}