<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseOffering extends Model
{
    protected $fillable = [
        'master_course_id',
        'academic_term_id',
        'lecturer_id',
        'start_date',
        'end_date',
        'is_archived',
        'certificate_threshold',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_archived' => 'boolean',
        'certificate_threshold' => 'integer',
    ];

    public function masterCourse()
    {
        return $this->belongsTo(MasterCourse::class, 'master_course_id');
    }

    public function academicTerm()
    {
        return $this->belongsTo(AcademicTerm::class, 'academic_term_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'master_course_id', 'master_course_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'master_course_id', 'master_course_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'course_offering_id');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'course_offering_id');
    }

    // Accessors for 100% Backward Compatibility with Blade Views
    public function getNameAttribute(): string
    {
        return $this->masterCourse->name ?? 'Course';
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->masterCourse->description ?? null;
    }

    public function getLevelAttribute(): string
    {
        return $this->masterCourse->level ?? 'Beginner';
    }

    public function getCategoryIdAttribute(): ?int
    {
        return $this->masterCourse->category_id ?? null;
    }

    public function isExpired(): bool
    {
        return $this->end_date && $this->end_date->isPast();
    }

    public function isActive(): bool
    {
        return ! $this->is_archived && ! $this->isExpired();
    }
}
