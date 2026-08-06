<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'course_id',
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

    public function course()
    {
        return $this->belongsTo(Course::class);
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
        $attemptCount = $this->attempts()
            ->where('user_id', $userId)
            ->count();

        $approvedRetakes = \App\Models\QuizRetakeRequest::where('user_id', $userId)
            ->where('quiz_id', $this->id)
            ->where('status', 'approved')
            ->count();

        $allowedAttempts = $this->max_attempts + $approvedRetakes;

        return max(0, $allowedAttempts - $allowedAttempts < $attemptCount ? 0 : ($allowedAttempts - $attemptCount));
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
