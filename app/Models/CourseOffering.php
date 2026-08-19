<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseOffering extends Model
{
    protected $fillable = [
        'master_course_id',
        'type',
        'academic_term_id',
        'lecturer_id',
        'section_name',
        'capacity',
        'start_date',
        'end_date',
        'is_archived',
        'certificate_threshold',
        'status',
        'user_id',
        'batch_name',
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

    public function category()
    {
        return $this->hasOneThrough(
            Category::class,
            MasterCourse::class,
            'id',
            'id',
            'master_course_id',
            'category_id'
        );
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'master_course_id', 'master_course_id');
    }

    public function getMaterialsAttribute()
    {
        return Material::where('master_course_id', $this->master_course_id)
            ->latest()
            ->get();
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

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_offering_id', 'user_id')
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

    // Accessors for 100% Backward Compatibility with Blade Views
    public function getUserIdAttribute(): ?int
    {
        return $this->lecturer_id;
    }

    public function setUserIdAttribute($value): void
    {
        $this->attributes['lecturer_id'] = $value;
    }

    public function getNameAttribute(): string
    {
        return $this->masterCourse->name ?? 'Course';
    }

    public function getBatchNameAttribute(): string
    {
        return $this->section_name ?? 'Batch 1';
    }

    public function setBatchNameAttribute($value): void
    {
        $this->attributes['section_name'] = $value;
    }

    public function getDurationWeeksAttribute(): int
    {
        if ($this->start_date && $this->end_date) {
            return max(1, (int) round($this->start_date->diffInWeeks($this->end_date)));
        }
        return 4;
    }

    public function getCodeAttribute(): ?string
    {
        return $this->masterCourse->code ?? null;
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

    public function getCategoryAttribute()
    {
        return $this->masterCourse->category ?? null;
    }

    public function getSkillsAttribute()
    {
        return $this->masterCourse ? $this->masterCourse->skills : collect();
    }

    public function getTagsAttribute()
    {
        return $this->masterCourse ? $this->masterCourse->tags : collect();
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
        if ($this->capacity <= 0) {
            return false; // Kuota 0 atau negatif = Penuh / Ditutup
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
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeAcademic($query)
    {
        return $query->where('type', 'academic');
    }

    public function scopeVendor($query)
    {
        return $query->where('type', 'vendor');
    }

    public function scopeActive($query)
    {
        return $query->where('is_archived', false)
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now()->startOfDay());
            });
    }
}

