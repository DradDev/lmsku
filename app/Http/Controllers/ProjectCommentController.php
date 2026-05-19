<?php

namespace App\Http\Controllers;

use App\Models\LearningActivityLog;
use App\Models\Project;
use App\Models\ProjectComment;
use App\Models\ProjectParticipation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectCommentController extends Controller
{
    public function store(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
            'comment_type' => ['nullable', 'in:comment,feedback,status_note'],
        ]);

        $user = Auth::user();

        $participation = ProjectParticipation::where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->first();

        $isProjectOwner = (int) $project->created_by === (int) $user->id;
        $isParticipant = $participation !== null;

        abort_unless(
            $isProjectOwner || $isParticipant,
            403,
            'Kamu tidak memiliki akses untuk memberi komentar pada project ini.'
        );

        $comment = ProjectComment::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'project_participation_id' => $participation?->id,
            'comment' => $validated['comment'],
            'comment_type' => $validated['comment_type'] ?? 'comment',
        ]);

        LearningActivityLog::create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'activity_type' => 'comment_project',
            'activity_value' => 1,
            'metadata' => [
                'comment_id' => $comment->id,
                'comment_type' => $comment->comment_type,
            ],
            'occurred_at' => now(),
        ]);

        return back()->with('success', 'Komentar berhasil dikirim.');
    }
}
