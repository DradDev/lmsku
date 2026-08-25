<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\LearningActivityLog;
use App\Models\Project;
use App\Models\ProjectParticipation;
use App\Models\ProjectStatusHistory;
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
        $mainSkills = Skill::orderBy('name')
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
            'skill_ids' => ['required', 'array', 'min:1'],
            'skill_ids.*' => ['exists:skills,id'],
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

        // Main Skills (Multi-selection)
        $skillsData = [];
        foreach ($validated['skill_ids'] as $sId) {
            $skillsData[$sId] = ['is_main' => true, 'weight' => 1.00];
        }
        $project->skills()->sync($skillsData);

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

        $mainSkills = Skill::orderBy('name')->get();
        $tags = Tag::with('skill')->orderBy('name')->get();
        $projectSkillIds = $project->skills->pluck('id')->toArray();

        return view('vendor.projects.edit', compact('project', 'mainSkills', 'tags', 'projectSkillIds'));
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
            'difficulty_level' => ['required', 'in:Beginner,Intermediate,Advanced'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'max_students' => ['required', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
            'skill_ids' => ['required', 'array', 'min:1'],
            'skill_ids.*' => ['exists:skills,id'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
            'brief_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip', 'max:10240'],
        ]);

        if ($request->hasFile('brief_file')) {
            $path = $request->file('brief_file')->store('project-briefs', 'public');
            $validated['brief_file_url'] = Storage::url($path);
        }

        $validated['is_published'] = $request->boolean('is_published');
        unset($validated['skill_ids'], $validated['tag_ids'], $validated['brief_file']);

        $project->update($validated);

        // Sync main skills
        $skillsData = [];
        foreach ($request->skill_ids as $sId) {
            $skillsData[$sId] = ['is_main' => true, 'weight' => 1.00];
        }
        $project->skills()->sync($skillsData);

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

    public function togglePublish(Project $project): RedirectResponse
    {
        if ($project->created_by !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke project industri ini.');
        }

        $newStatus = !$project->is_published;
        $project->update(['is_published' => $newStatus]);

        $statusLabel = $newStatus ? 'dipublikasikan dan aktif dibuka untuk mahasiswa' : 'diubah menjadi Draft internal';

        return back()->with('success', "Status Project '{$project->title}' berhasil {$statusLabel}.");
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
            'joinedProjects' => function ($query) {
                $query->with(['skills', 'tags', 'user']);
            },
            'enrollments.courseOffering.masterCourse.skills',
            'enrollments.courseOffering.masterCourse.tags',
            'enrollments.course.skills',
            'enrollments.course.tags',
        ]);

        $certificates = Certificate::where('user_id', $student->id)->get();

        $acquiredSkillsMap = [];
        foreach ($student->enrollments as $enrollment) {
            $courseObj = $enrollment->courseOffering?->masterCourse ?? $enrollment->course;
            if (!$courseObj) continue;

            $courseName = $courseObj->name ?? 'Course';
            $isCompleted = $enrollment->status === 'completed' || $enrollment->progress_percent >= 100;
            $hasVerifiedCert = $certificates->contains(function ($cert) use ($enrollment) {
                return $cert->course_offering_id && $cert->course_offering_id === $enrollment->course_offering_id;
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
                if ($hasVerifiedCert) $acquiredSkillsMap[$skill->id]['has_verified_cert'] = true;
                if ($isCompleted) $acquiredSkillsMap[$skill->id]['is_completed'] = true;

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

        return view('vendor.projects.student_portfolio', compact('student', 'acquiredSkills', 'certificates'));
    }

    public function approveCertificate(Project $project, ProjectParticipation $participation): RedirectResponse
    {
        abort_unless(
            $project->created_by === Auth::id() && $participation->project_id === $project->id,
            403,
            'Anda tidak memiliki akses untuk menyetujui sertifikat project ini.'
        );

        $oldStatus = $participation->status;
        $oldProgress = $participation->progress_percent;

        // 1. Update Participation Status to completed 100%
        $participation->update([
            'status' => 'completed',
            'progress_percent' => 100,
            'completed_at' => $participation->completed_at ?? now(),
            'last_activity_at' => now(),
        ]);

        // 2. Create or Update Certificate in 'pending' status for Admin Blockchain Verification
        $certificate = Certificate::firstOrNew([
            'user_id'          => $participation->user_id,
            'certifiable_type' => Project::class,
            'certifiable_id'   => $project->id,
        ]);

        if (!$certificate->exists) {
            $certificate->score = 100;
            $certificate->completed_at = $participation->completed_at ?? now();
            $certificate->status = 'pending';
            $certificate->is_verified = false;
            $certificate->credential_code = $certificate->generateCredentialCode();
            $certificate->save();
        } else {
            if (!$certificate->is_verified) {
                $certificate->status = 'pending';
                $certificate->score = 100;
                $certificate->completed_at = $participation->completed_at ?? now();
                if (empty($certificate->credential_code)) {
                    $certificate->credential_code = $certificate->generateCredentialCode();
                }
                $certificate->save();
            }
        }

        // 3. Log Status History
        ProjectStatusHistory::create([
            'project_id' => $project->id,
            'project_participation_id' => $participation->id,
            'user_id' => Auth::id(),
            'old_status' => $oldStatus,
            'new_status' => 'completed',
            'old_progress_percent' => $oldProgress,
            'new_progress_percent' => 100,
            'note' => 'Pengerjaan disetujui Mitra Vendor Industri. Pengajuan sertifikat disalurkan ke Admin untuk verifikasi integritas & blockchain.',
        ]);

        // 4. Learning Activity Log
        LearningActivityLog::create([
            'user_id' => $participation->user_id,
            'project_id' => $project->id,
            'activity_type' => 'project_approved_by_vendor',
            'activity_value' => 100,
            'metadata' => [
                'participation_id' => $participation->id,
                'vendor_id' => Auth::id(),
                'certificate_id' => $certificate->id,
            ],
            'occurred_at' => now(),
        ]);

        $studentName = $participation->user->name ?? 'Mahasiswa';

        return redirect()
            ->route('vendor.projects.show', $project)
            ->with('success', "Pengerjaan {$studentName} berhasil disetujui! Pengajuan penerbitan sertifikat telah disalurkan ke Admin untuk verifikasi integritas & blockchain.");
    }
}
