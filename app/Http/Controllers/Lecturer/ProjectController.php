<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\LearningActivityLog;
use App\Models\Project;
use App\Models\ProjectParticipation;
use App\Models\ProjectStatusHistory;
use App\Models\Skill;
use App\Models\Tag;
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

        $skillsWithoutCourses = [];
        $mainSkills = Skill::whereNull('parent_id')->get();
        foreach ($mainSkills as $skill) {
            if (!$this->checkSkillHasCourse($skill->id)) {
                $skillsWithoutCourses[] = $skill->id;
            }
        }

        return view('lecturer.projects.index', compact('allProjects', 'activeProjects', 'bankProjects', 'skillsWithoutCourses'));
    }

    public function togglePublish(Project $project): RedirectResponse
    {
        abort_unless(
            $project->created_by === Auth::id(),
            403,
            'Kamu tidak memiliki akses ke project ini.'
        );

        $newStatus = !$project->is_published;
        $project->update([
            'is_published' => $newStatus,
        ]);

        $msg = $newStatus
            ? 'Project berhasil dipublikasikan ke Active Projects.'
            : 'Project dipindahkan ke Project Bank.';

        return redirect()
            ->route('lecturer.projects.index')
            ->with('success', $msg);
    }

    public function create(): View
    {
        $mainSkills = Skill::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $tags = Tag::with('skill')
            ->orderBy('name')
            ->get();

        $skillsWithoutCourses = [];
        foreach ($mainSkills as $skill) {
            if (!$this->checkSkillHasCourse($skill->id)) {
                $skillsWithoutCourses[] = $skill->id;
            }
        }

        return view('lecturer.projects.create', compact('mainSkills', 'tags', 'skillsWithoutCourses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'difficulty_level' => ['required', 'in:Beginner,Intermediate,Advanced'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'max_students' => ['required', 'integer', 'min:1'],
            'provider_type' => ['nullable', 'in:internal,external'],
            'benefits' => ['nullable', 'string', 'max:1000'],
            'brief_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip,rar', 'max:10240'],
            'is_published' => ['nullable', 'boolean'],

            'skill_ids' => ['nullable', 'array'],
            'skill_ids.*' => ['exists:skills,id'],
            'main_skill_id' => ['nullable', 'exists:skills,id'],

            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
        ]);

        $briefPath = null;
        if ($request->hasFile('brief_file')) {
            $briefPath = $request->file('brief_file')->store('project_briefs', 'public');
        }

        $user = Auth::user();
        $providerType = $user->role === 'vendor'
            ? 'external'
            : ($validated['provider_type'] ?? 'internal');

        $project = Project::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'difficulty_level' => $validated['difficulty_level'],
            'duration_days' => $validated['duration_days'],
            'max_students' => $validated['max_students'],
            'created_by' => $user->id,
            'provider_type' => $providerType,
            'brief_file' => $briefPath,
            'benefits' => $validated['benefits'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ]);

        $this->syncProjectSkillsAndTags($project, $request);

        $mainSkillId = $request->input('main_skill_id');
        if ($mainSkillId && !$this->checkSkillHasCourse((int) $mainSkillId)) {
            $skillName = Skill::find($mainSkillId)?->name ?? 'Main Skill';
            return redirect()
                ->route('lecturer.projects.index')
                ->with('success', 'Project successfully created.')
                ->with('warning', "⚠️ Catatan: Belum terdapat Course aktif di sistem yang menguji Main Skill '{$skillName}'. Mahasiswa belum bisa membangun kompetensi untuk mendaftar project ini sebelum Course terkait dibuat. Disarankan untuk membuat Course untuk '{$skillName}'!");
        }

        return redirect()
            ->route('lecturer.projects.index')
            ->with('success', 'Project successfully created.');
    }

    public function edit(Project $project): View
    {
        abort_unless(
            $project->created_by === Auth::id(),
            403,
            'You do not have access to this project.'
        );

        $mainSkills = Skill::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $tags = Tag::with('skill')
            ->orderBy('name')
            ->get();

        $project->load(['skills', 'tags']);

        $skillsWithoutCourses = [];
        foreach ($mainSkills as $skill) {
            if (!$this->checkSkillHasCourse($skill->id)) {
                $skillsWithoutCourses[] = $skill->id;
            }
        }

        return view('lecturer.projects.edit', compact('project', 'mainSkills', 'tags', 'skillsWithoutCourses'));
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
            'provider_type' => ['nullable', 'in:internal,external'],
            'benefits' => ['nullable', 'string', 'max:1000'],
            'brief_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip,rar', 'max:10240'],
            'is_published' => ['nullable', 'boolean'],

            'skill_ids' => ['nullable', 'array'],
            'skill_ids.*' => ['exists:skills,id'],
            'main_skill_id' => ['nullable', 'exists:skills,id'],

            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
        ]);

        $updateData = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'difficulty_level' => $validated['difficulty_level'],
            'duration_days' => $validated['duration_days'],
            'max_students' => $validated['max_students'],
            'benefits' => $validated['benefits'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ];

        if (isset($validated['provider_type'])) {
            $updateData['provider_type'] = $validated['provider_type'];
        }

        if ($request->hasFile('brief_file')) {
            if (!empty($project->brief_file) && Storage::disk('public')->exists($project->brief_file)) {
                Storage::disk('public')->delete($project->brief_file);
            }
            $updateData['brief_file'] = $request->file('brief_file')->store('project_briefs', 'public');
        }

        $project->update($updateData);

        $this->syncProjectSkillsAndTags($project, $request);

        $mainSkillId = $request->input('main_skill_id');
        if ($mainSkillId && !$this->checkSkillHasCourse((int) $mainSkillId)) {
            $skillName = Skill::find($mainSkillId)?->name ?? 'Main Skill';
            return redirect()
                ->route('lecturer.projects.index')
                ->with('success', 'Project berhasil diperbarui.')
                ->with('warning', "⚠️ Catatan: Belum terdapat Course aktif di sistem yang menguji Main Skill '{$skillName}'. Mahasiswa belum bisa membangun kompetensi untuk mendaftar project ini sebelum Course terkait dibuat. Disarankan untuk membuat Course untuk '{$skillName}'!");
        }

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

    private function checkSkillHasCourse(?int $skillId): bool
    {
        if (!$skillId) {
            return true;
        }

        $hasCourse = Course::where('is_archived', false)
            ->where(function ($query) use ($skillId) {
                $query->whereHas('skills', function ($q) use ($skillId) {
                    $q->where('skills.id', $skillId)
                      ->orWhere('skills.parent_id', $skillId);
                })
                ->orWhereHas('tags', function ($q) use ($skillId) {
                    $q->where('tags.skill_id', $skillId);
                })
                ->orWhereHas('quizzes.questions.skills', function ($q) use ($skillId) {
                    $q->where('skills.id', $skillId)
                      ->orWhere('skills.parent_id', $skillId);
                });
            })
            ->exists();

        if ($hasCourse) {
            return true;
        }

        // Fallback: If any active course matches by skill name or main terms
        $skill = Skill::find($skillId);
        if ($skill) {
            $skillWords = explode(' ', str_replace('&', '', $skill->name));
            $firstWord = trim($skillWords[0] ?? '');
            if (!empty($firstWord) && strlen($firstWord) >= 3) {
                $hasNamedCourse = Course::where('is_archived', false)
                    ->where(function ($q) use ($skill, $firstWord) {
                        $q->where('name', 'LIKE', "%{$skill->name}%")
                          ->orWhere('name', 'LIKE', "%{$firstWord}%")
                          ->orWhere('description', 'LIKE', "%{$skill->name}%");
                    })
                    ->exists();

                if ($hasNamedCourse) {
                    return true;
                }
            }
        }

        return false;
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

        $mainSkill = $project->skills->firstWhere('pivot.is_main', true) ?? $project->skills->first();
        $hasCourseForSkill = $mainSkill ? $this->checkSkillHasCourse($mainSkill->id) : true;

        $participationsMap = $project->participations->keyBy('user_id');

        $recommendedStudents = \App\Models\User::where('role', 'student')
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

        return view('lecturer.projects.show', compact('project', 'hasCourseForSkill', 'mainSkill', 'recommendedStudents'));
    }

    public function talentPool(Request $request, Project $project): View
    {
        abort_unless(
            $project->created_by === Auth::id(),
            403,
            'Kamu tidak memiliki akses ke project ini.'
        );

        $project->load(['skills', 'tags', 'participations']);

        $participationsMap = $project->participations->keyBy('user_id');

        $students = \App\Models\User::where('role', 'student')
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

        return view('lecturer.projects.talent_pool', compact('project', 'students'));
    }

    public function studentPortfolio(\App\Models\User $student): View
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
                return ($cert->course_offering_id && $cert->course_offering_id === $enrollment->course_offering_id)
                    || ($cert->course_id && $cert->course_id === $enrollment->course_id);
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

        return view('lecturer.projects.student_portfolio', compact('student', 'acquiredSkills', 'certificates'));
    }

    public function inviteTalent(Project $project, \App\Models\User $user): RedirectResponse
    {
        abort_unless(
            $project->created_by === Auth::id(),
            403,
            'Kamu tidak memiliki akses ke project ini.'
        );

        $participation = \App\Models\ProjectParticipation::updateOrCreate(
            [
                'project_id' => $project->id,
                'user_id' => $user->id,
            ],
            [
                'status' => 'invited',
                'progress_percent' => 0,
                'started_at' => now(),
            ]
        );

        return redirect()
            ->route('lecturer.projects.talent-pool', $project)
            ->with('success', "Undangan resmi telah dikirimkan kepada {$user->name}! Menunggu konfirmasi dari mahasiswa.");
    }

    public function approveCertificate(Project $project, ProjectParticipation $participation): RedirectResponse
    {
        abort_unless(
            $project->created_by === Auth::id() && $participation->project_id === $project->id,
            403,
            'Kamu tidak memiliki akses untuk menyetujui sertifikat project ini.'
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
            'user_id' => $participation->user_id,
            'project_id' => $project->id,
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
            'note' => 'Pengerjaan disetujui Pembimbing Dosen. Pengajuan sertifikat disalurkan ke Admin untuk verifikasi integritas & blockchain.',
        ]);

        // 4. Learning Activity Log
        LearningActivityLog::create([
            'user_id' => $participation->user_id,
            'project_id' => $project->id,
            'activity_type' => 'project_approved_by_mentor',
            'activity_value' => 100,
            'metadata' => [
                'participation_id' => $participation->id,
                'mentor_id' => Auth::id(),
                'certificate_id' => $certificate->id,
            ],
            'occurred_at' => now(),
        ]);

        $studentName = $participation->user->name ?? 'Mahasiswa';

        return redirect()
            ->route('lecturer.projects.show', $project)
            ->with('success', "Pengerjaan {$studentName} berhasil disetujui! Pengajuan penerbitan sertifikat telah disalurkan ke Admin untuk verifikasi integritas & blockchain.");
    }
}
