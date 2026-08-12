<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\LearningActivityLog;
use App\Models\Project;
use App\Models\ProjectParticipation;
use App\Models\ProjectStatusHistory;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $student = Auth::user();
        $student->load('skillProfiles');

        $projects = Project::where('is_published', true)
            ->with(['user', 'skills', 'tags'])
            ->withCount('participations')
            ->latest()
            ->get();

        foreach ($projects as $project) {
            $project->eligibility = $this->checkStudentEligibility($student, $project);
        }

        $authors = User::whereIn('id', $projects->pluck('created_by')->filter()->unique())
            ->orderBy('name')
            ->get();

        $joinedProjectIds = ProjectParticipation::where('user_id', $student->id)
            ->whereIn('status', ['in_progress', 'development', 'review', 'completed'])
            ->pluck('project_id')
            ->toArray();

        $invitedParticipations = ProjectParticipation::with(['project', 'project.user', 'project.skills'])
            ->where('user_id', $student->id)
            ->where('status', 'invited')
            ->latest()
            ->get();

        return view('student.projects.index', compact('projects', 'joinedProjectIds', 'authors', 'invitedParticipations'));
    }

    public function myProjects(): View
    {
        $participations = ProjectParticipation::with([
            'project',
            'project.skills',
            'project.tags',
            'project.user',
        ])
            ->where('user_id', Auth::id())
            ->whereIn('status', ['in_progress', 'development', 'review', 'completed'])
            ->latest()
            ->get();

        $invitedParticipations = ProjectParticipation::with([
            'project',
            'project.skills',
            'project.tags',
            'project.user',
        ])
            ->where('user_id', Auth::id())
            ->where('status', 'invited')
            ->latest()
            ->get();

        return view('student.projects.my', compact('participations', 'invitedParticipations'));
    }

    public function invitations(): View
    {
        $invitedParticipations = ProjectParticipation::with([
            'project',
            'project.skills',
            'project.tags',
            'project.user',
        ])
            ->where('user_id', Auth::id())
            ->where('status', 'invited')
            ->latest()
            ->get();

        return view('student.projects.invitations', compact('invitedParticipations'));
    }

    public function acceptInvite(Project $project): RedirectResponse
    {
        $participation = ProjectParticipation::where('user_id', Auth::id())
            ->where('project_id', $project->id)
            ->where('status', 'invited')
            ->firstOrFail();

        $participation->update([
            'status' => 'in_progress',
            'progress_percent' => 0,
            'started_at' => now(),
            'last_activity_at' => now(),
        ]);

        LearningActivityLog::create([
            'user_id' => Auth::id(),
            'project_id' => $project->id,
            'activity_type' => 'accept_invite_project',
            'activity_value' => 1,
            'metadata' => [
                'participation_id' => $participation->id,
            ],
            'occurred_at' => now(),
        ]);

        return redirect()
            ->route('student.projects.my')
            ->with('success', "Selamat! Anda berhasil mengonfirmasi dan bergabung dalam project '{$project->title}'.");
    }

    public function declineInvite(Project $project): RedirectResponse
    {
        $participation = ProjectParticipation::where('user_id', Auth::id())
            ->where('project_id', $project->id)
            ->where('status', 'invited')
            ->firstOrFail();

        $participation->update([
            'status' => 'declined',
            'last_activity_at' => now(),
        ]);

        return redirect()
            ->route('student.projects.index')
            ->with('success', "Undangan project '{$project->title}' telah ditolak.");
    }

    public function portfolio(): View
    {
        $student = Auth::user();
        $student->load([
            'skillProfiles.skill',
            'interestProfiles.tag',
            'joinedProjects' => function ($query) {
                $query->with(['skills', 'user']);
            },
            'enrollments.courseOffering.masterCourse.category',
            'enrollments.courseOffering.academicTerm',
            'enrollments.courseOffering.lecturer',
            'enrollments.course.user',
            'enrollments.course.category',
        ]);

        $certificates = Certificate::with(['courseOffering.masterCourse', 'course', 'project'])
            ->where('user_id', $student->id)
            ->latest()
            ->get();

        return view('student.portfolio', compact('student', 'certificates'));
    }

    public function show(Project $project): View
    {
        abort_unless($project->is_published, 404);

        $student = Auth::user();
        $student->load('skillProfiles');

        $project->load([
            'skills',
            'tags',
            'user',
            'comments' => function ($query) {
                $query->with('user')->latest();
            },
        ]);

        LearningActivityLog::create([
            'user_id' => $student->id,
            'project_id' => $project->id,
            'activity_type' => 'view_project',
            'activity_value' => 1,
            'occurred_at' => now(),
        ]);

        $participation = ProjectParticipation::where('user_id', $student->id)
            ->where('project_id', $project->id)
            ->first();

        $canComment = $participation !== null;
        $eligibility = $this->checkStudentEligibility($student, $project);

        return view('student.projects.show', compact(
            'project',
            'participation',
            'canComment',
            'eligibility'
        ));
    }

    public function join(Project $project): RedirectResponse
    {
        abort_unless($project->is_published, 404);
        $student = Auth::user();
        $student->load('skillProfiles');

        $alreadyJoined = ProjectParticipation::where('user_id', $student->id)
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
                ->with('error', 'Project quota is full.');
        }

        // Strict Check: Certificate Verified + Main Skill Competency
        $eligibility = $this->checkStudentEligibility($student, $project);

        if (!$eligibility['is_eligible']) {
            $reasonMsg = implode(' ', $eligibility['reasons']);
            return redirect()
                ->route('student.projects.show', $project)
                ->with('error', "Pendaftaran ditolak. Anda belum memenuhi kriteria kelayakan project ini: {$reasonMsg}");
        }

        $participation = ProjectParticipation::create([
            'user_id' => $student->id,
            'project_id' => $project->id,
            'status' => 'in_progress',
            'progress_percent' => 0,
            'started_at' => now(),
            'last_activity_at' => now(),
        ]);

        LearningActivityLog::create([
            'user_id' => $student->id,
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
            ->with('success', 'Project successfully joined.');
    }

    public function checkStudentEligibility(User $student, Project $project): array
    {
        $mainSkill = $project->skills->firstWhere('pivot.is_main', true) ?? $project->skills->first();
        $requiredSkillIds = $project->skills->pluck('id')->toArray();

        $hasMainSkill = false;
        if (!empty($requiredSkillIds)) {
            $userSkillIds = $student->skillProfiles->pluck('skill_id')->toArray();
            $hasMainSkill = !empty(array_intersect($requiredSkillIds, $userSkillIds));
        } else {
            $hasMainSkill = true; // No skill requirement
        }

        // Certificate check: Student must have at least 1 verified certificate or verified final quiz attempt
        $hasVerifiedCertificate = \App\Models\Certificate::where('user_id', $student->id)
            ->where('status', 'verified')
            ->exists();

        if (!$hasVerifiedCertificate) {
            $hasVerifiedCertificate = \App\Models\QuizAttempt::where('user_id', $student->id)
                ->where('is_verified', true)
                ->where('score', '>=', 60)
                ->exists();
        }

        $isEligible = $hasMainSkill && $hasVerifiedCertificate;

        $reasons = [];
        if (!$hasMainSkill) {
            $skillNames = $project->skills->pluck('name')->implode(', ');
            $reasons[] = "Belum memiliki Target Main Skill: " . ($skillNames ?: 'General Skill') . ".";
        }

        if (!$hasVerifiedCertificate) {
            $reasons[] = "Belum memiliki Sertifikat Terverifikasi (Lulus Final Quiz / Sertifikat Vendor).";
        }

        return [
            'is_eligible' => $isEligible,
            'has_main_skill' => $hasMainSkill,
            'has_verified_certificate' => $hasVerifiedCertificate,
            'main_skill_name' => $project->skills->pluck('name')->implode(', ') ?: 'General Skill',
            'reasons' => $reasons,
        ];
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
