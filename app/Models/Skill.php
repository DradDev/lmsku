<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function courses()
    {
        return $this->morphedByMany(MasterCourse::class, 'skillable')
            ->withPivot('weight', 'is_main')
            ->withTimestamps();
    }

    public function masterCourses()
    {
        return $this->morphedByMany(MasterCourse::class, 'skillable')
            ->withPivot('weight', 'is_main')
            ->withTimestamps();
    }

    public function projects()
    {
        return $this->morphedByMany(Project::class, 'skillable')
            ->withPivot('weight', 'is_main')
            ->withTimestamps();
    }

    public function questions()
    {
        return $this->morphedByMany(Question::class, 'skillable')
            ->withPivot('weight', 'is_main')
            ->withTimestamps();
    }

    public function userProfiles()
    {
        return $this->hasMany(UserSkillProfile::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
}
