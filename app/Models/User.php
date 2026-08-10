<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Course;
use App\Models\Project;
use App\Models\ProjectParticipation;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'peminatan',
        'registration_status',
        'registration_note',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function createdCourses()
    {
        return $this->hasMany(Course::class, 'user_id');
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isLecturer()
    {
        return $this->role === 'lecturer';
    }

    public function isVendor()
    {
        return $this->role === 'vendor';
    }

    public function isRegistrationPending()
    {
        return $this->registration_status === 'pending';
    }

    public function isRegistrationApproved()
    {
        return $this->registration_status === 'approved';
    }

    public function isRegistrationRejected()
    {
        return $this->registration_status === 'rejected';
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar) {
            return null;
        }

        if (\Illuminate\Support\Str::startsWith($this->avatar, ['http://', 'https://'])) {
            return $this->avatar;
        }

        return asset('storage/' . ltrim($this->avatar, '/'));
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function createdProjects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    public function projectParticipations()
    {
        return $this->hasMany(ProjectParticipation::class);
    }

    public function joinedProjects()
    {
        return $this->belongsToMany(Project::class, 'project_participations')
            ->withPivot([
                'status',
                'progress_percent',
                'started_at',
                'completed_at',
                'last_activity_at',
            ])
            ->withTimestamps();
    }

    public function learningActivityLogs()
    {
        return $this->hasMany(LearningActivityLog::class);
    }

    public function skillProfiles()
    {
        return $this->hasMany(UserSkillProfile::class);
    }

    public function interestProfiles()
    {
        return $this->hasMany(UserInterestProfile::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function materialProgresses()
    {
        return $this->hasMany(MaterialProgress::class);
    }

    public function projectComments()
    {
        return $this->hasMany(ProjectComment::class);
    }

    public function completedProjects()
    {
        return $this->belongsToMany(Project::class, 'project_participations')
            ->wherePivot('status', 'accepted')
            ->wherePivot('progress_percent', 100);
    }

    public function calculateTalentMatchScore(Project $project): int
    {
        // 1. Skill Match Score (50%)
        $projectSkillIds = $project->skills->pluck('id')->toArray();
        if (!empty($projectSkillIds)) {
            $userSkillScores = $this->skillProfiles()
                ->whereIn('skill_id', $projectSkillIds)
                ->pluck('avg_score');
            $skillMatch = $userSkillScores->count() > 0 ? $userSkillScores->avg() : 40;
        } else {
            $avgSkill = $this->skillProfiles()->avg('avg_score');
            $skillMatch = $avgSkill ?: 50;
        }

        // 2. Interest Match Score (30%)
        $projectTagIds = $project->tags->pluck('id')->toArray();
        if (!empty($projectTagIds)) {
            $userInterestScores = $this->interestProfiles()
                ->whereIn('tag_id', $projectTagIds)
                ->pluck('interest_score');
            $interestMatch = $userInterestScores->count() > 0 ? $userInterestScores->avg() : 50;
        } else {
            $interestMatch = 50;
        }

        // Bonus if student's peminatan matches project category
        if ($this->peminatan && $project->category && stripos($project->category->name, $this->peminatan) !== false) {
            $interestMatch = min(100, $interestMatch + 20);
        }

        // 3. Project Experience (20%)
        $completedCount = $this->completedProjects()->count();
        $expMatch = min(100, $completedCount * 25 + 30);

        $totalScore = (0.50 * $skillMatch) + (0.30 * $interestMatch) + (0.20 * $expMatch);

        return (int) round(min(100, max(20, $totalScore)));
    }
}
