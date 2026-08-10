<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectParticipation;
use App\Models\Skill;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $allProjects = Project::with(['skills', 'tags', 'participations'])
            ->where('created_by', Auth::id())
            ->latest()
            ->get();

        $activeProjects = $allProjects->where('is_published', true)->values();
        $bankProjects = $allProjects->where('is_published', false)->values();

        return view('vendor.projects.index', compact('allProjects', 'activeProjects', 'bankProjects'));
    }

    public function create(): View
    {
        $mainSkills = Skill::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $tags = Tag::with('skill')
            ->orderBy('name')
            ->get();

        return view('vendor.projects.create', compact('mainSkills', 'tags'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'benefits' => ['nullable', 'string'],
            'difficulty_level' => ['required', 'in:Beginner,Intermediate,Advanced'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'max_students' => ['required', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
            'main_skill_id' => ['required', 'exists:skills,id'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
            'brief_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip', 'max:10240'],
        ]);

        $briefUrl = null;
        if ($request->hasFile('brief_file')) {
            $path = $request->file('brief_file')->store('project-briefs', 'public');
            $briefUrl = Storage::url($path);
        }

        $project = Project::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'benefits' => $validated['benefits'] ?? null,
            'difficulty_level' => $validated['difficulty_level'],
            'duration_days' => $validated['duration_days'],
            'max_students' => $validated['max_students'],
            'is_published' => $request->boolean('is_published', true),
            'brief_file_url' => $briefUrl,
            'created_by' => Auth::id(),
            'provider_type' => 'external',
        ]);

        // Main Skill
        $project->skills()->attach($validated['main_skill_id'], ['is_main' => true]);

        // Specialty Tags
        if (!empty($validated['tag_ids'])) {
            $project->tags()->sync($validated['tag_ids']);
        }

        return redirect()
            ->route('vendor.projects.show', $project)
            ->with('success', 'Project Industri Mitra berhasil dipublikasikan.');
    }

    public function show(Project $project): View
    {
        if ($project->created_by !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke project industri ini.');
        }

        $project->load([
            'skills',
            'tags',
            'participations.user',
            'participations.statusHistories.user',
            'comments.user',
        ]);

        $mainSkill = $project->skills->firstWhere('pivot.is_main', true) ?? $project->skills->first();
        $participationsMap = $project->participations->keyBy('user_id');

        $recommendedStudents = User::where('role', 'student')
            ->with(['skillProfiles.skill', 'interestProfiles.tag', 'completedProjects'])
            ->get()
            ->map(function ($student) use ($project, $participationsMap) {
                $student->match_score = $student->calculateTalentMatchScore($project);
                $part = $participationsMap->get($student->id);
                $student->invitation_status = $part ? $part->status : null;
                $student->is_already_invited = $part !== null && in_array($part->status, ['invited', 'in_progress', 'development', 'review', 'completed']);
                return $student;
            })
            ->sortByDesc('match_score')
            ->take(3)
            ->values();

        return view('vendor.projects.show', compact('project', 'mainSkill', 'recommendedStudents'));
    }

    public function edit(Project $project): View
    {
        if ($project->created_by !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke project industri ini.');
        }

        $mainSkills = Skill::whereNull('parent_id')->orderBy('name')->get();
        $tags = Tag::with('skill')->orderBy('name')->get();
        $projectMainSkillId = $project->skills->firstWhere('pivot.is_main', true)?->id;

        return view('vendor.projects.edit', compact('project', 'mainSkills', 'tags', 'projectMainSkillId'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        if ($project->created_by !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke project industri ini.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'benefits' => ['nullable', 'string'],
            'difficulty_level' => ['required', 'in:easy,medium,hard'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'max_students' => ['required', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
            'main_skill_id' => ['required', 'exists:skills,id'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
            'brief_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip', 'max:10240'],
        ]);

        if ($request->hasFile('brief_file')) {
            $path = $request->file('brief_file')->store('project-briefs', 'public');
            $validated['brief_file_url'] = Storage::url($path);
        }

        $validated['is_published'] = $request->boolean('is_published');
        unset($validated['main_skill_id'], $validated['tag_ids'], $validated['brief_file']);

        $project->update($validated);

        // Sync main skill
        $project->skills()->sync([$request->main_skill_id => ['is_main' => true]]);

        // Sync tags
        if (isset($request->tag_ids)) {
            $project->tags()->sync($request->tag_ids);
        }

        return redirect()
            ->route('vendor.projects.show', $project)
            ->with('success', 'Project Industri Mitra berhasil diperbarui.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        if ($project->created_by !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke project industri ini.');
        }

        $project->delete();

        return redirect()
            ->route('vendor.projects.index')
            ->with('success', 'Project Industri Mitra berhasil dihapus.');
    }

    public function talentPool(Project $project): View
    {
        if ($project->created_by !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke project industri ini.');
        }

        $project->load(['skills', 'tags', 'participations']);
        $mainSkill = $project->skills->firstWhere('pivot.is_main', true) ?? $project->skills->first();
        $participationsMap = $project->participations->keyBy('user_id');

        $recommendedStudents = User::where('role', 'student')
            ->with(['skillProfiles.skill', 'interestProfiles.tag', 'completedProjects'])
            ->get()
            ->map(function ($student) use ($project, $participationsMap) {
                $student->match_score = $student->calculateTalentMatchScore($project);
                $part = $participationsMap->get($student->id);
                $student->invitation_status = $part ? $part->status : null;
                $student->is_already_invited = $part !== null && in_array($part->status, ['invited', 'in_progress', 'development', 'review', 'completed']);
                return $student;
            })
            ->sortByDesc('match_score')
            ->values();

        return view('vendor.projects.talent_pool', compact('project', 'mainSkill', 'recommendedStudents'));
    }

    public function inviteTalent(Project $project, User $user): RedirectResponse
    {
        if ($project->created_by !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke project industri ini.');
        }

        if ($user->role !== 'student') {
            return back()->with('error', 'Hanya mahasiswa yang dapat diundang.');
        }

        $existing = ProjectParticipation::where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            if ($existing->status === 'declined') {
                $existing->update([
                    'status' => 'invited',
                    'started_at' => now(),
                ]);
            } else {
                return back()->with('error', 'Mahasiswa ini sudah diundang atau telah terdaftar pada project ini.');
            }
        } else {
            ProjectParticipation::create([
                'user_id' => $user->id,
                'project_id' => $project->id,
                'status' => 'invited',
                'progress_percent' => 0,
                'started_at' => now(),
            ]);
        }

        return back()->with('success', "Berhasil mengirimkan Undangan Project Industri kepada {$user->name}.");
    }

    public function studentPortfolio(User $student): View
    {
        $student->load([
            'skillProfiles.skill',
            'interestProfiles.tag',
            'joinedProjects' => function ($query) {
                $query->with(['skills', 'creator']);
            }
        ]);

        return view('vendor.projects.student_portfolio', compact('student'));
    }
}
