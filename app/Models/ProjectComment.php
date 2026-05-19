<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectComment extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'project_participation_id',
        'comment',
        'comment_type',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function participation()
    {
        return $this->belongsTo(ProjectParticipation::class, 'project_participation_id');
    }
}
