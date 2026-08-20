<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizRetakeRequest extends Model
{
    protected $fillable = [
        'user_id',
        'quiz_id',
        'course_offering_id',
        'status',
        'reason',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function courseOffering()
    {
        return $this->belongsTo(CourseOffering::class, 'course_offering_id');
    }

    public function course()
    {
        return $this->belongsTo(CourseOffering::class, 'course_offering_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
