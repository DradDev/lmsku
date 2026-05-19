<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\LearningActivityLog;
use App\Models\Project;
use App\Models\ProjectParticipation;
use App\Models\ProjectStatusHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::where('is_published', true)
            ->withCount('participations')
            ->latest()
            ->get();

        $joinedProjectIds = ProjectParticipation::where('user_id', Auth::id())
            ->pluck('project_id')
            ->toArray();

        return view('student.projects.index', compact('projects', 'joinedProjectIds'));
    }

    public function show(Project $project): View
    {
        abort_unless($project->is_published, 404);

        $project->load([
            'skills',
            'tags',
            'comments' => function ($query) {
                $query->with('user')->latest();
            },
        ]);

        LearningActivityLog::create([
            'user_id' => Auth::id(),
            'project_id' => $project->id,
            'activity_type' => 'view_project',
            'activity_value' => 1,
            'occurred_at' => now(),
        ]);

        $participation = ProjectParticipation::where('user_id', Auth::id())
            ->where('project_id', $project->id)
            ->first();

        $canComment = $participation !== null;

        return view('student.projects.show', compact(
            'project',
            'participation',
            'canComment'
        ));
    }

    public function join(Project $project): RedirectResponse
    {
        abort_unless($project->is_published, 404);

        $alreadyJoined = ProjectParticipation::where('user_id', Auth::id())
            ->where('project_id', $project->id)
            ->exists();

        if ($alreadyJoined) {
            return redirect()
                ->route('student.projects.my')
                ->with('success', 'Kamu sudah mengambil project ini.');
        }

        $joinedCount = ProjectParticipation::where('project_id', $project->id)
            ->count();

        if ($joinedCount >= ($project->max_students ?? 1)) {
            return redirect()
                ->route('student.projects.show', $project)
                ->with('error', 'Kuota project sudah penuh.');
        }

        $participation = ProjectParticipation::create([
            'user_id' => Auth::id(),
            'project_id' => $project->id,
            'status' => 'in_progress',
            'progress_percent' => 0,
            'started_at' => now(),
            'last_activity_at' => now(),
        ]);

        LearningActivityLog::create([
            'user_id' => Auth::id(),
            'project_id' => $project->id,
            'activity_type' => 'join_project',
            'activity_value' => 1,
            'metadata' => [
                'participation_id' => $participation->id,
            ],
            'occurred_at' => now(),
        ]);

        return redirect()
            ->route('student.projects.show', $project)
            ->with('success', 'Project berhasil diambil.');
    }

    public function myProjects(): View
    {
        $participations = ProjectParticipation::with([
            'project',
            'project.skills',
            'project.tags',
        ])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('student.projects.my', compact('participations'));
    }

    public function complete(Project $project): RedirectResponse
    {
        $participation = ProjectParticipation::where('user_id', Auth::id())
            ->where('project_id', $project->id)
            ->firstOrFail();

        $oldStatus = $participation->status;
        $oldProgress = $participation->progress_percent;

        $participation->update([
            'status' => 'completed',
            'progress_percent' => 100,
            'completed_at' => now(),
            'last_activity_at' => now(),
        ]);

        ProjectStatusHistory::create([
            'project_id' => $project->id,
            'project_participation_id' => $participation->id,
            'user_id' => Auth::id(),
            'old_status' => $oldStatus,
            'new_status' => 'completed',
            'old_progress_percent' => $oldProgress,
            'new_progress_percent' => 100,
            'note' => 'Project ditandai selesai.',
        ]);

        LearningActivityLog::create([
            'user_id' => Auth::id(),
            'project_id' => $project->id,
            'activity_type' => 'complete_project',
            'activity_value' => 1,
            'metadata' => [
                'participation_id' => $participation->id,
            ],
            'occurred_at' => now(),
        ]);

        return redirect()
            ->route('student.projects.my')
            ->with('success', 'Project berhasil ditandai selesai.');
    }

    public function updateProgress(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:in_progress,development,review,completed'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $participation = ProjectParticipation::where('user_id', Auth::id())
            ->where('project_id', $project->id)
            ->firstOrFail();

        $oldStatus = $participation->status;
        $oldProgress = $participation->progress_percent;

        $progressMap = [
            'in_progress' => 25,
            'development' => 50,
            'review' => 75,
            'completed' => 100,
        ];

        $newStatus = $validated['status'];
        $newProgress = $progressMap[$newStatus];

        $participation->update([
            'status' => $newStatus,
            'progress_percent' => $newProgress,
            'completed_at' => $newStatus === 'completed' ? now() : null,
            'last_activity_at' => now(),
        ]);

        ProjectStatusHistory::create([
            'project_id' => $project->id,
            'project_participation_id' => $participation->id,
            'user_id' => Auth::id(),
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'old_progress_percent' => $oldProgress,
            'new_progress_percent' => $newProgress,
            'note' => $validated['note'] ?? null,
        ]);

        LearningActivityLog::create([
            'user_id' => Auth::id(),
            'project_id' => $project->id,
            'activity_type' => 'update_project_progress',
            'activity_value' => $newProgress,
            'metadata' => [
                'participation_id' => $participation->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'note' => $validated['note'] ?? null,
            ],
            'occurred_at' => now(),
        ]);

        return redirect()
            ->route('student.projects.show', $project)
            ->with('success', 'Progress project berhasil diperbarui.');
    }
}
