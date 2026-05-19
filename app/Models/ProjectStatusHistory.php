<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectStatusHistory extends Model
{
    protected $fillable = [
        'project_id',
        'project_participation_id',
        'user_id',
        'old_status',
        'new_status',
        'old_progress_percent',
        'new_progress_percent',
        'note',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function participation()
    {
        return $this->belongsTo(ProjectParticipation::class, 'project_participation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
