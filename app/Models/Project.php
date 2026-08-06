<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Skill;
use App\Models\Tag;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'difficulty_level',
        'duration_days',
        'max_students',
        'created_by',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participations()
    {
        return $this->hasMany(ProjectParticipation::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'project_participations')
            ->withPivot([
                'status',
                'progress_percent',
                'started_at',
                'completed_at',
                'last_activity_at',
            ])
            ->withTimestamps();
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'project_skills')
            ->withPivot('weight', 'is_main')
            ->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'project_tags')
            ->withPivot('weight')
            ->withTimestamps();
    }

    public function statusHistories()
    {
        return $this->hasMany(ProjectStatusHistory::class);
    }

    public function getJoinedStudentsCountAttribute()
    {
        return $this->participations()->count();
    }

    public function getRemainingSlotsAttribute()
    {
        return max(0, $this->max_students - $this->joined_students_count);
    }

    public function getIsFullAttribute()
    {
        return $this->joined_students_count >= $this->max_students;
    }

    public function comments()
    {
        return $this->hasMany(ProjectComment::class);
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
