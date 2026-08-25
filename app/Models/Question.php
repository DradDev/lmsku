<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'quiz_id',
        'user_id',
        'question_type',
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'status',
        'difficulty',
    ];

    protected static function booted()
    {
        static::deleting(function ($model) {
            $model->skills()->detach();
        });
    }

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
        return $this->hasMany(QuizAnswer::class);
    }

    public function isEssay()
    {
        return $this->question_type === 'essay';
    }

    public function isMultipleChoice()
    {
        return $this->question_type === 'multiple_choice';
    }

    public function skills()
    {
        return $this->morphToMany(Skill::class, 'skillable')
            ->withPivot('weight', 'is_main')
            ->withTimestamps();
    }
}
