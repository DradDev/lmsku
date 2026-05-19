<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $table = 'enrollments';

    protected $fillable = [
        'user_id',
        'course_id',
        'progress_percent',
        'completed_material_count',
        'completed_quiz_count',
        'total_material_count',
        'total_quiz_count',
        'status',
        'started_at',
        'completed_at',
        'last_activity_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_activity_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
