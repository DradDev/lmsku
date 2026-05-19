<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::with(['skills', 'tags'])
            ->where('created_by', Auth::id())
            ->latest()
            ->get();

        return view('lecturer.projects.index', compact('projects'));
    }

    public function create(): View
    {
        $mainSkills = Skill::with(['children' => function ($query) {
            $query->orderBy('name');
        }])
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $tags = Tag::orderBy('name')->get();

        return view('lecturer.projects.create', compact('mainSkills', 'tags'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'difficulty_level' => ['required', 'in:Beginner,Intermediate,Advanced'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'max_students' => ['required', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],

            'skill_ids' => ['nullable', 'array'],
            'skill_ids.*' => ['exists:skills,id'],
            'main_skill_id' => ['nullable', 'exists:skills,id'],

            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
        ]);

        $project = Project::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'difficulty_level' => $validated['difficulty_level'],
            'duration_days' => $validated['duration_days'],
            'max_students' => $validated['max_students'],
            'created_by' => Auth::id(),
            'is_published' => $request->boolean('is_published'),
        ]);

        $this->syncProjectSkillsAndTags($project, $request);

        return redirect()
            ->route('lecturer.projects.index')
            ->with('success', 'Project berhasil dibuat.');
    }

    public function edit(Project $project): View
    {
        abort_unless(
            $project->created_by === Auth::id(),
            403,
            'Kamu tidak memiliki akses ke project ini.'
        );

        $mainSkills = Skill::with(['children' => function ($query) {
            $query->orderBy('name');
        }])
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $tags = Tag::orderBy('name')->get();

        $project->load(['skills', 'tags']);

        return view('lecturer.projects.edit', compact('project', 'mainSkills', 'tags'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        abort_unless(
            $project->created_by === Auth::id(),
            403,
            'Kamu tidak memiliki akses ke project ini.'
        );

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'difficulty_level' => ['required', 'in:Beginner,Intermediate,Advanced'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'max_students' => ['required', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],

            'skill_ids' => ['nullable', 'array'],
            'skill_ids.*' => ['exists:skills,id'],
            'main_skill_id' => ['nullable', 'exists:skills,id'],

            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
        ]);

        $project->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'difficulty_level' => $validated['difficulty_level'],
            'duration_days' => $validated['duration_days'],
            'max_students' => ['required', 'integer', 'min:1'],
            'is_published' => $request->boolean('is_published'),
        ]);

        $this->syncProjectSkillsAndTags($project, $request);

        return redirect()
            ->route('lecturer.projects.index')
            ->with('success', 'Project berhasil diperbarui.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        abort_unless(
            $project->created_by === Auth::id(),
            403,
            'Kamu tidak memiliki akses ke project ini.'
        );

        $project->delete();

        return redirect()
            ->route('lecturer.projects.index')
            ->with('success', 'Project berhasil dihapus.');
    }

    private function syncProjectSkillsAndTags(Project $project, Request $request): void
    {
        $skillIds = array_map('intval', $request->input('skill_ids', []));
        $mainSkillId = $request->input('main_skill_id');

        if ($mainSkillId && ! in_array((int) $mainSkillId, $skillIds, true)) {
            $skillIds[] = (int) $mainSkillId;
        }

        $skillSyncData = [];

        foreach ($skillIds as $skillId) {
            $skillSyncData[$skillId] = [
                'weight' => 1.00,
                'is_main' => (int) $skillId === (int) $mainSkillId,
            ];
        }

        $project->skills()->sync($skillSyncData);

        $tagIds = array_map('intval', $request->input('tag_ids', []));
        $tagSyncData = [];

        foreach ($tagIds as $tagId) {
            $tagSyncData[$tagId] = [
                'weight' => 1.00,
            ];
        }

        $project->tags()->sync($tagSyncData);
    }

    public function show(Project $project): View
    {
        abort_unless(
            $project->created_by === Auth::id(),
            403,
            'Kamu tidak memiliki akses ke project ini.'
        );

        $project->load([
            'skills',
            'tags',
            'participations.user',
            'participations.statusHistories.user',
            'statusHistories.user',
            'comments.user'
        ]);

        return view('lecturer.projects.show', compact('project'));
    }
}
