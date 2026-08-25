<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Tag extends Model
{
    protected $fillable = [
        'name',
        'skill_id',
    ];

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function courses()
    {
        return $this->morphedByMany(MasterCourse::class, 'taggable')
            ->withPivot('weight')
            ->withTimestamps();
    }

    public function masterCourses()
    {
        return $this->morphedByMany(MasterCourse::class, 'taggable')
            ->withPivot('weight')
            ->withTimestamps();
    }

    public function projects()
    {
        return $this->morphedByMany(Project::class, 'taggable')
            ->withPivot('weight')
            ->withTimestamps();
    }

    public function userInterestProfiles()
    {
        return $this->hasMany(UserInterestProfile::class);
    }
}
