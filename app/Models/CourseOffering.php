<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseOffering extends Model
{
    protected $fillable = [
        'master_course_id',
        'academic_term_id',
        'lecturer_id',
        'section_name',
        'capacity',
        'start_date',
        'end_date',
        'is_archived',
        'certificate_threshold',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_archived' => 'boolean',
        'certificate_threshold' => 'integer',
        'capacity' => 'integer',
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

    public function lecturer()
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

    /**
     * Nama tampilan lengkap: "Pemrograman Web - Kelas A"
     */
    public function getFullNameAttribute(): string
    {
        $name = $this->masterCourse->name ?? 'Course';
        return $this->section_name ? "{$name} - {$this->section_name}" : $name;
    }

    /**
     * Hitung jumlah mahasiswa yang terdaftar
     */
    public function getEnrolledCountAttribute(): int
    {
        return $this->enrollments()->count();
    }

    /**
     * Cek apakah kuota masih tersedia
     */
    public function hasAvailableCapacity(): bool
    {
        if (is_null($this->capacity)) {
            return true; // Unlimited
        }
        return $this->enrolled_count < $this->capacity;
    }

    public function isExpired(): bool
    {
        return $this->end_date && $this->end_date->isPast();
    }

    public function isActive(): bool
    {
        return ! $this->is_archived && ! $this->isExpired();
    }

    /**
     * Scope: hanya kelas yang published
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}

