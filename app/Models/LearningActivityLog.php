<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'project_id',
        'material_id',
        'quiz_id',
        'activity_type',
        'activity_value',
        'duration_seconds',
        'metadata',
        'occurred_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'occurred_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}