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
                'questions.*.question_type' => ['required', Rule::in(['essay', 'multiple_choice'])],
                'questions.*.difficulty' => ['required', Rule::in(['easy', 'medium', 'hard'])],
                'questions.*.option_a' => ['nullable', 'string'],
                'questions.*.option_b' => ['nullable', 'string'],
                'questions.*.option_c' => ['nullable', 'string'],
                'questions.*.option_d' => ['nullable', 'string'],
                'questions.*.correct_answer' => ['nullable', Rule::in(['A', 'B', 'C', 'D'])],

                'questions.*.skill_ids' => ['nullable', 'array'],
                'questions.*.skill_ids.*' => ['exists:skills,id'],
                'questions.*.main_skill_id' => ['nullable', 'exists:skills,id'],
            ]));
        } else {
            $validated = $request->validate(array_merge($quizRules, [
                'question' => ['required', 'string'],
                'question_type' => ['required', Rule::in(['essay', 'multiple_choice'])],
                'difficulty' => ['required', Rule::in(['easy', 'medium', 'hard'])],
                'option_a' => ['nullable', 'string'],
                'option_b' => ['nullable', 'string'],
                'option_c' => ['nullable', 'string'],
                'option_d' => ['nullable', 'string'],
                'correct_answer' => ['nullable', Rule::in(['A', 'B', 'C', 'D'])],

                'skill_ids' => ['nullable', 'array'],
                'skill_ids.*' => ['exists:skills,id'],
                'main_skill_id' => ['nullable', 'exists:skills,id'],
            ]));

            $validated['questions'] = [[
                'question' => $validated['question'],
                'question_type' => $validated['question_type'],
                'difficulty' => $validated['difficulty'],
                'option_a' => $validated['option_a'] ?? null,
                'option_b' => $validated['option_b'] ?? null,
                'option_c' => $validated['option_c'] ?? null,
                'option_d' => $validated['option_d'] ?? null,
                'correct_answer' => $validated['correct_answer'] ?? null,

                'skill_ids' => $validated['skill_ids'] ?? [],
                'main_skill_id' => $validated['main_skill_id'] ?? null,
            ]];
        }

        $quiz = Quiz::query()
            ->whereHas('course', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->findOrFail($validated['quiz_id']);

        $createdEssay = 0;
        $createdMultipleChoice = 0;

        foreach ($validated['questions'] as $item) {
            if (($item['question_type'] ?? null) === 'multiple_choice') {
                if (
                    empty($item['option_a']) ||
                    empty($item['option_b']) ||
                    empty($item['option_c']) ||
                    empty($item['option_d']) ||
                    empty($item['correct_answer'])
                ) {
                    return back()
                        ->withErrors(['questions' => 'Semua option dan correct answer wajib diisi untuk multiple choice.'])
                        ->withInput();
                }
            }

            if (($item['question_type'] ?? null) === 'essay') {
                $item['option_a'] = null;
                $item['option_b'] = null;
                $item['option_c'] = null;
                $item['option_d'] = null;
                $item['correct_answer'] = null;
            }

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
                'status' => $item['question_type'] === 'essay' ? 'approved' : 'draft',
            ]);

            $this->syncQuestionSkills(
                $question,
                $item['skill_ids'] ?? [],
                $item['main_skill_id'] ?? null
            );

            if ($item['question_type'] === 'essay') {
                $createdEssay++;
            } else {
                $createdMultipleChoice++;
            }
        }

        $messageParts = [];

        if ($createdEssay > 0) {
            $messageParts[] = $createdEssay . ' essay question langsung aktif';
        }

        if ($createdMultipleChoice > 0) {
            $messageParts[] = $createdMultipleChoice . ' multiple choice disimpan sebagai draft';
        }

        return redirect()
            ->route('lecturer.dashboard', ['tab' => 'questions'])
            ->with('success', implode(' dan ', $messageParts) . '.');
    }

    public function show(Question $question): RedirectResponse
    {
        return redirect()->route('lecturer.dashboard', ['tab' => 'questions']);
    }

    public function edit(Question $question): View
    {
        abort_unless($question->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke question ini.');

        abort_if(
            $question->question_type === 'multiple_choice'
            && in_array($question->status, ['pending', 'approved'], true),
            403,
            'Question dengan status ini tidak dapat diedit.'
        );

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

        abort_if(
            $question->question_type === 'multiple_choice'
            && in_array($question->status, ['pending', 'approved'], true),
            403,
            'Question dengan status ini tidak dapat diperbarui.'
        );

        $type = $request->input('question_type');

        $rules = [
            'quiz_id' => ['required', 'exists:quizzes,id'],
            'question' => ['required', 'string'],
            'question_type' => ['required', Rule::in(['essay', 'multiple_choice'])],
            'difficulty' => ['required', Rule::in(['easy', 'medium', 'hard'])],

            'skill_ids' => ['nullable', 'array'],
            'skill_ids.*' => ['exists:skills,id'],
            'main_skill_id' => ['nullable', 'exists:skills,id'],
        ];

        if ($type === 'multiple_choice') {
            $rules = array_merge($rules, [
                'option_a' => ['required', 'string'],
                'option_b' => ['required', 'string'],
                'option_c' => ['required', 'string'],
                'option_d' => ['required', 'string'],
                'correct_answer' => ['required', Rule::in(['A', 'B', 'C', 'D'])],
            ]);
        }

        $data = $request->validate($rules);

        $skillIds = $data['skill_ids'] ?? [];
        $mainSkillId = $data['main_skill_id'] ?? null;

        unset($data['skill_ids'], $data['main_skill_id']);

        $quiz = Quiz::query()
            ->whereHas('course', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->findOrFail($data['quiz_id']);

        if ($type === 'essay') {
            $data['option_a'] = null;
            $data['option_b'] = null;
            $data['option_c'] = null;
            $data['option_d'] = null;
            $data['correct_answer'] = null;
            $data['status'] = 'approved';
        } else {
            if ($question->status === 'rejected' || $question->question_type === 'essay') {
                $data['status'] = 'draft';
            }
        }

        $data['quiz_id'] = $quiz->id;

        $question->update($data);

        $this->syncQuestionSkills($question, $skillIds, $mainSkillId);

        return redirect()
            ->route('lecturer.dashboard', ['tab' => 'questions'])
            ->with(
                'success',
                $type === 'essay'
                    ? 'Essay question berhasil diperbarui dan tetap aktif untuk student.'
                    : 'Question berhasil diperbarui.'
            );
    }

    public function submit(Question $question): RedirectResponse
    {
        abort_unless($question->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke question ini.');

        if ($question->question_type === 'essay') {
            return redirect()
                ->route('lecturer.dashboard', ['tab' => 'questions'])
                ->with('success', 'Essay question tidak perlu submit ke admin dan sudah langsung aktif.');
        }

        abort_if(
            ! in_array($question->status, ['draft', 'rejected'], true),
            403,
            'Question ini tidak bisa disubmit.'
        );

        $question->update([
            'status' => 'pending',
        ]);

        return redirect()
            ->route('lecturer.dashboard', ['tab' => 'questions'])
            ->with('success', 'Question submitted for admin review.');
    }

    public function destroy(Question $question): RedirectResponse
    {
        abort_unless($question->user_id === Auth::id(), 403, 'Kamu tidak memiliki akses ke question ini.');

        abort_if(
            $question->question_type === 'multiple_choice'
            && in_array($question->status, ['pending', 'approved'], true),
            403,
            'Question dengan status ini tidak dapat dihapus.'
        );

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