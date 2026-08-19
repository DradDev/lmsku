<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = [
        'name',
        'description',
        'parent_id',
    ];


    public function parent()
    {
        return $this->belongsTo(Skill::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Skill::class, 'parent_id');
    }

    public function courses()
    {
        return $this->belongsToMany(MasterCourse::class, 'master_course_skills')
            ->withPivot('is_main')
            ->withTimestamps();
    }

    public function masterCourses()
    {
        return $this->belongsToMany(MasterCourse::class, 'master_course_skills')
            ->withPivot('is_main')
            ->withTimestamps();
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_skills')
            ->withPivot('weight', 'is_main')
            ->withTimestamps();
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'question_skills')
            ->withPivot('weight')
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
