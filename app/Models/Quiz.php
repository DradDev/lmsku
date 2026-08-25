<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'quizzable_type',
        'quizzable_id',
        'title',
        'time_limit',
        'quiz_type',
        'max_attempts',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'max_attempts' => 'integer',
    ];

    /**
     * Polymorphic relation to MasterCourse or CourseOffering
     */
    public function quizzable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Dynamic accessors for seamless backward compatibility
     */
    public function getIsGlobalAttribute(): bool
    {
        return $this->quizzable_type === MasterCourse::class;
    }

    public function isGlobal(): bool
    {
        return $this->quizzable_type === MasterCourse::class;
    }

    public function isClassSpecific(): bool
    {
        return $this->quizzable_type === CourseOffering::class;
    }

    public function getCourseAttribute()
    {
        if ($this->quizzable_type === MasterCourse::class) {
            return $this->quizzable;
        }

        if ($this->quizzable_type === CourseOffering::class) {
            return $this->quizzable?->masterCourse ?? $this->quizzable;
        }

        return null;
    }

    public function getMasterCourseAttribute()
    {
        return $this->getCourseAttribute();
    }

    public function getMasterCourseIdAttribute()
    {
        if ($this->quizzable_type === MasterCourse::class) {
            return $this->quizzable_id;
        }

        if ($this->quizzable_type === CourseOffering::class) {
            return $this->quizzable?->master_course_id;
        }

        return null;
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function isFinal(): bool
    {
        return $this->quiz_type === 'final';
    }

    public function isDaily(): bool
    {
        return $this->quiz_type === 'daily';
    }

    public function isWeekly(): bool
    {
        return $this->quiz_type === 'weekly';
    }

    public function isAvailable(): bool
    {
        $now = now();

        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        return true;
    }

    public function canAttempt(int $userId): bool
    {
        if (! $this->isAvailable()) {
            return false;
        }

        if ($this->max_attempts === null || $this->max_attempts === 0) {
            return true;
        }

        $attemptCount = $this->attempts()
            ->where('user_id', $userId)
            ->count();

        $approvedRetakes = \App\Models\QuizRetakeRequest::where('user_id', $userId)
            ->where('quiz_id', $this->id)
            ->where('status', 'approved')
            ->count();

        $allowedAttempts = $this->max_attempts + $approvedRetakes;

        return $attemptCount < $allowedAttempts;
    }

    public function remainingAttempts(int $userId): int
    {
        if ($this->max_attempts === null || $this->max_attempts === 0) {
            return 999; // Unlimited Attempts
        }

        $attemptCount = $this->attempts()
            ->where('user_id', $userId)
            ->count();

        $approvedRetakes = \App\Models\QuizRetakeRequest::where('user_id', $userId)
            ->where('quiz_id', $this->id)
            ->where('status', 'approved')
            ->count();

        $allowedAttempts = $this->max_attempts + $approvedRetakes;

        return max(0, $allowedAttempts - $attemptCount);
    }

    public function getQuizTypeLabelAttribute(): string
    {
        return match ($this->quiz_type) {
            'final' => 'Final Quiz',
            'weekly' => 'Weekly Quiz',
            default => 'Daily Quiz',
        };
    }

    public function getQuizTypeBadgeClassAttribute(): string
    {
        return match ($this->quiz_type) {
            'final' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            'weekly' => 'bg-blue-100 text-blue-700 border-blue-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }
}
