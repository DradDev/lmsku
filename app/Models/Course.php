<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name',
        'description',
        'user_id',
        'master_course_id',
        'level',
        'progress',
        'duration_weeks',
        'start_date',
        'end_date',
        'is_archived',
        'certificate_threshold',
        'category_id',
        'batch_name',
        'moderation_status',
        'moderation_note',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_archived' => 'boolean',
        'certificate_threshold' => 'integer',
    ];

    public function isExpired(): bool
    {
        return $this->end_date !== null && $this->end_date->isPast() && !$this->end_date->isToday();
    }

    public function isActive(): bool
    {
        return !$this->is_archived && !$this->isExpired();
    }

    public function getStatusAttribute(): string
    {
        if ($this->is_archived) {
            return 'archived';
        }
        if ($this->isExpired()) {
            return 'expired';
        }
        return 'active';
    }

    public function scopeActive($query)
    {
        return $query->where('is_archived', false)
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now()->startOfDay());
            });
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true)
            ->orWhere(function ($q) {
                $q->whereNotNull('end_date')
                  ->where('end_date', '<', now()->startOfDay());
            });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withTimestamps();
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'master_course_skills', 'master_course_id', 'skill_id', 'master_course_id', 'id')
            ->withPivot('is_main')
            ->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'master_course_tags', 'master_course_id', 'tag_id', 'master_course_id', 'id')
            ->withTimestamps();
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function materialProgresses()
    {
        return $this->hasMany(MaterialProgress::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function masterCourse()
    {
        return $this->belongsTo(MasterCourse::class, 'master_course_id');
    }
}
