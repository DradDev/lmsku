<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name',
        'description',
        'user_id',
        'level',
        'progress',
        'duration_weeks',
        'start_date',
        'end_date',
        'is_archived',
        'certificate_threshold',
        'category_id',
        'batch_name',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_archived' => 'boolean',
        'certificate_threshold' => 'integer',
    ];

    public function isExpired(): bool
    {
        if (!$this->end_date) {
            return false;
        }
        $endDate = $this->end_date instanceof \Carbon\Carbon ? $this->end_date : \Carbon\Carbon::parse($this->end_date);
        return $endDate->isPast() && !$endDate->isToday();
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
        return $this->belongsToMany(Skill::class, 'course_skills')
            ->withPivot('weight', 'is_main')
            ->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'course_tags')
            ->withPivot('weight')
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

    public function offerings()
    {
        return $this->hasMany(CourseOffering::class, 'master_course_id');
    }

    public function latestOffering()
    {
        return $this->hasOne(CourseOffering::class, 'master_course_id')->latestOfMany();
    }

    public function getStartDateAttribute($value)
    {
        $val = $value ?? $this->latestOffering?->start_date;
        return $val ? \Carbon\Carbon::parse($val) : null;
    }

    public function getEndDateAttribute($value)
    {
        $val = $value ?? $this->latestOffering?->end_date;
        return $val ? \Carbon\Carbon::parse($val) : null;
    }
}