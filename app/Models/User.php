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

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : null;
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
                'profile_photo_path',
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
}
