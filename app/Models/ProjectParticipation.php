<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectParticipation extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'status',
        'progress_percent',
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

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(ProjectStatusHistory::class);
    }

    public function comments()
    {
        return $this->hasMany(ProjectComment::class);
    }   
}
