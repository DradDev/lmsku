<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferingQuiz extends Model
{
    protected $fillable = [
        'course_offering_id',
        'quiz_id',
        'start_date',
        'end_date',
        'time_limit',
        'max_attempts',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'time_limit' => 'integer',
        'max_attempts' => 'integer',
    ];

    public function courseOffering()
    {
        return $this->belongsTo(CourseOffering::class, 'course_offering_id');
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class, 'offering_quiz_id');
    }
}
