<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CourseOffering;
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

        $completedProjectIds = ProjectParticipation::where('user_id', $student->id)
            ->where('status', 'completed')
            ->pluck('project_id')
            ->toArray();

        $invitedParticipations = ProjectParticipation::with(['project', 'project.user', 'project.skills'])
            ->where('user_id', $student->id)
            ->where('status', 'invited')
            ->latest()
            ->get();

        return view('student.projects.index', compact('projects', 'joinedProjectIds', 'completedProjectIds', 'authors', 'invitedParticipations'));
    }

    public function show(Project $project): View
    {
        $currentUser = Auth::user();
        $student = ($currentUser->role === 'admin' && request('user_id'))
            ? (\App\Models\User::find(request('user_id')) ?? $currentUser)
            : ($currentUser->role === 'admin'
                ? (ProjectParticipation::where('project_id', $project->id)->where('status', 'completed')->first()?->user ?? $currentUser)
                : $currentUser);

        $project->load([
            'user.institution',
            'creator.institution',
            'skills',
            'tags',
            'participations.user',
            'comments.user',
            'comments.replies.user',
        ]);

        $participation = ProjectParticipation::where('user_id', $student->id)
            ->where('project_id', $project->id)
            ->first();

        if (!$participation && $currentUser->role === 'admin') {
            $participation = ProjectParticipation::where('project_id', $project->id)->first();
        }

        $certificate = Certificate::where('user_id', $student->id)
            ->where('certifiable_type', Project::class)
            ->where('certifiable_id', $project->id)
            ->first();

        if (!$certificate && $currentUser->role === 'admin') {
            $certificate = Certificate::where('certifiable_type', Project::class)->where('certifiable_id', $project->id)->first();
        }

        $eligibility = $this->checkStudentEligibility($student, $project);

        $skillIds = $project->skills->pluck('id')->toArray();
        $prerequisiteCourses = \App\Models\MasterCourse::with(['skills'])
            ->whereHas('skills', function ($q) use ($skillIds) {
                $q->whereIn('skills.id', $skillIds);
            })
            ->get();

        $statusHistories = ProjectStatusHistory::with('user')
            ->where('project_id', $project->id)
            ->where(function ($q) use ($participation) {
                if ($participation) {
                    $q->where('project_participation_id', $participation->id);
                } else {
                    $q->whereNull('project_participation_id');
                }
            })
            ->latest()
            ->get();

        return view('student.projects.show', compact('project', 'participation', 'certificate', 'eligibility', 'statusHistories', 'prerequisiteCourses'));
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
            'interestProfiles.tag.skill',
            'joinedProjects' => function ($query) {
                $query->with(['skills', 'tags', 'user']);
            },
            'enrollments.courseOffering.masterCourse.skills',
            'enrollments.courseOffering.masterCourse.tags',
            'enrollments.courseOffering.academicTerm',
            'enrollments.courseOffering.lecturer',
            'enrollments.course.skills',
            'enrollments.course.tags',
        ]);

        $certificates = Certificate::with([
            'certifiable',
        ])
            ->where('user_id', $student->id)
            ->latest()
            ->get();

        // 1. Build Course-Based Acquired Multi-Skill Competency Matrix
        $acquiredSkillsMap = [];

        foreach ($student->enrollments as $enrollment) {
            $masterCourse = $enrollment->courseOffering?->masterCourse;
            $courseObj = $masterCourse ?? $enrollment->course;

            if (!$courseObj) {
                continue;
            }

            $courseName = $masterCourse->name ?? ($enrollment->course->name ?? 'Course');
            $isCompleted = $enrollment->status === 'completed' || $enrollment->progress_percent >= 100;
            $hasVerifiedCert = $certificates->contains(function ($cert) use ($enrollment) {
                return $cert->certifiable_type === CourseOffering::class && (int)$cert->certifiable_id === (int)$enrollment->course_offering_id;
            });

            foreach ($courseObj->skills as $skill) {
                if (!isset($acquiredSkillsMap[$skill->id])) {
                    $acquiredSkillsMap[$skill->id] = [
                        'skill' => $skill,
                        'courses' => [],
                        'tags' => collect(),
                        'has_verified_cert' => false,
                        'is_completed' => false,
                    ];
                }

                $acquiredSkillsMap[$skill->id]['courses'][] = $courseName;
                if ($hasVerifiedCert) {
                    $acquiredSkillsMap[$skill->id]['has_verified_cert'] = true;
                }
                if ($isCompleted) {
                    $acquiredSkillsMap[$skill->id]['is_completed'] = true;
                }

                foreach ($courseObj->tags as $tag) {
                    if ($tag->skill_id === $skill->id || !$tag->skill_id) {
                        if (!$acquiredSkillsMap[$skill->id]['tags']->contains('id', $tag->id)) {
                            $acquiredSkillsMap[$skill->id]['tags']->push($tag);
                        }
                    }
                }
            }
        }

        $acquiredSkills = collect($acquiredSkillsMap);

        // 2. Build Student Interest & Preference Profile (Minat)
        $interestTags = $student->interestProfiles->map(function ($ip) {
            return [
                'tag' => $ip->tag,
                'skill_name' => $ip->tag?->skill?->name ?? 'General Category',
            ];
        });

        return view('student.portfolio', compact('student', 'certificates', 'acquiredSkills', 'interestTags'));
    }

    public function checkStudentEligibility(User $student, Project $project): array
    {
        $requiredSkills = $project->skills;
        $requiredSkillIds = $requiredSkills->pluck('id')->toArray();

        // Collect all skill IDs student acquired from enrolled courses
        $student->loadMissing([
            'enrollments.courseOffering.masterCourse.skills',
            'enrollments.course.skills',
        ]);

        $studentAcquiredSkillIds = [];
        foreach ($student->enrollments as $enrollment) {
            $courseObj = $enrollment->courseOffering?->masterCourse ?? $enrollment->course;
            if ($courseObj) {
                foreach ($courseObj->skills as $s) {
                    $studentAcquiredSkillIds[] = $s->id;
                }
            }
        }
        $studentAcquiredSkillIds = array_unique($studentAcquiredSkillIds);

        $hasRequiredSkills = false;
        if (!empty($requiredSkillIds)) {
            $hasRequiredSkills = !empty(array_intersect($requiredSkillIds, $studentAcquiredSkillIds));
        } else {
            $hasRequiredSkills = true; // No skill requirement
        }

        // Certificate check: Student must have at least 1 verified certificate or verified final quiz attempt
        $hasVerifiedCertificate = Certificate::where('user_id', $student->id)
            ->where('status', 'verified')
            ->exists();

        if (!$hasVerifiedCertificate) {
            $hasVerifiedCertificate = \App\Models\QuizAttempt::where('user_id', $student->id)
                ->where('is_verified', true)
                ->where('score', '>=', 60)
                ->exists();
        }

        $isEligible = $hasRequiredSkills && $hasVerifiedCertificate;

        $reasons = [];
        if (!$hasRequiredSkills) {
            $skillNames = $project->skills->pluck('name')->implode(', ');
            $reasons[] = "Belum mengambil Course yang membekali Skill: " . ($skillNames ?: 'General Skill') . ".";
        }

        if (!$hasVerifiedCertificate) {
            $reasons[] = "Belum memiliki Sertifikat Terverifikasi (Lulus Final Quiz / Sertifikat Vendor).";
        }

        return [
            'is_eligible' => $isEligible,
            'has_main_skill' => $hasRequiredSkills,
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

        if ($participation->status === 'completed') {
            return redirect()
                ->route('student.projects.show', $project)
                ->with('info', 'Proyek ini telah selesai. Status pengerjaan tidak dapat diubah lagi.');
        }

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

    public function join(Project $project): RedirectResponse
    {
        $student = Auth::user();

        // 1. Cek publikasi dan kuota
        if (!$project->is_published) {
            return redirect()
                ->route('student.projects.index')
                ->with('error', "Pendaftaran untuk project '{$project->title}' saat ini sedang ditutup.");
        }

        if ($project->is_full) {
            return redirect()
                ->route('student.projects.index')
                ->with('error', "Kuota pendaftaran untuk project '{$project->title}' sudah penuh.");
        }

        // 2. Cek kelayakan kompetensi sebelum join
        $eligibility = $this->checkStudentEligibility($student, $project);
        if (!($eligibility['is_eligible'] ?? false)) {
            $reasonsText = implode(' ', $eligibility['reasons'] ?? []);
            return redirect()
                ->route('student.projects.index')
                ->with('error', "Gagal mengambil proyek: Proyek '{$project->title}' saat ini terkunci. " . $reasonsText);
        }

        // 3. Cek partisipasi yang sudah ada
        $existingParticipation = ProjectParticipation::where('user_id', $student->id)
            ->where('project_id', $project->id)
            ->first();

        if ($existingParticipation) {
            if ($existingParticipation->status === 'completed') {
                return redirect()
                    ->route('student.projects.show', $project)
                    ->with('info', "Anda telah menyelesaikan project '{$project->title}'. Silakan akses sertifikat kelulusan Anda.");
            }

            if ($existingParticipation->status === 'invited') {
                return redirect()
                    ->route('student.projects.my')
                    ->with('info', "Anda memiliki undangan pending untuk project '{$project->title}'. Silakan konfirmasi pada daftar undangan.");
            }

            if (in_array($existingParticipation->status, ['in_progress', 'development', 'review'])) {
                return redirect()
                    ->route('student.projects.show', $project)
                    ->with('info', "Anda sudah terdaftar dan sedang mengerjakan project '{$project->title}'.");
            }
        }

        $participation = ProjectParticipation::create([
            'project_id' => $project->id,
            'user_id' => $student->id,
            'status' => 'in_progress',
            'progress_percent' => 0,
            'started_at' => now(),
            'last_activity_at' => now(),
        ]);

        ProjectStatusHistory::create([
            'project_id' => $project->id,
            'project_participation_id' => $participation->id,
            'user_id' => $student->id,
            'old_status' => null,
            'new_status' => 'in_progress',
            'old_progress_percent' => 0,
            'new_progress_percent' => 0,
            'note' => 'Mahasiswa berhasil mendaftar dan mulai mengerjakan project industri.',
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
            ->route('student.projects.my')
            ->with('success', "Selamat! Anda berhasil mengambil project '{$project->title}'. Silakan mulai pengerjaan tugas & milestone.");
    }
}
