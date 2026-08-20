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
        return $this->belongsToMany(MasterCourse::class, 'master_course_tags')
            ->withTimestamps();
    }

    public function masterCourses()
    {
        return $this->belongsToMany(MasterCourse::class, 'master_course_tags')
            ->withTimestamps();
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_tags')
            ->withPivot('weight')
            ->withTimestamps();
    }

    public function userInterestProfiles()
    {
        return $this->hasMany(UserInterestProfile::class);
    }
}
