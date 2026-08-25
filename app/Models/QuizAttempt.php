<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = [
        'quiz_id',
        'user_id',
        'score',
        'is_verified',
        'blockchain_hash',
        'blockchain_id',
        'tx_id',
        'completed_at',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'score'       => 'float',
        'completed_at'=> 'datetime',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'quiz_attempt_id');
    }

    /**
     * Scope a query to only include the best final attempts per user and quiz.
     * Extracts complex query logic from Admin/ResultController.
     */
    public function scopeBestFinalAttempts($query)
    {
        $bestAttemptIds = self::whereHas('quiz', function ($q) {
                $q->where('quiz_type', 'final');
            })
            ->where(function ($q) {
                $q->where('score', '>=', 75)->orWhere('is_verified', true);
            })
            ->select(\Illuminate\Support\Facades\DB::raw('MAX(id) as id'))
            ->groupBy('user_id', 'quiz_id')
            ->pluck('id');

        return $query->whereIn('id', $bestAttemptIds);
    }
}
