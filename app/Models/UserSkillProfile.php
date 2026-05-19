<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSkillProfile extends Model
{
    protected $fillable = [
        'user_id',
        'skill_id',
        'avg_score',
        'lowest_score',
        'highest_score',
        'attempt_count',
        'correct_count',
        'wrong_count',
        'last_activity_at',
        'last_calculated_at',
    ];

    protected $casts = [
        'avg_score' => 'decimal:2',
        'lowest_score' => 'decimal:2',
        'highest_score' => 'decimal:2',
        'last_activity_at' => 'datetime',
        'last_calculated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
}