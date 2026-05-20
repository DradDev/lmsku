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
}
