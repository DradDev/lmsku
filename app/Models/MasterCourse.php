<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterCourse extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'name',
        'description',
        'level',
        'certificate_threshold',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'master_course_id');
    }

    public function getMainSkillAttribute()
    {
        return $this->skills->firstWhere('pivot.is_main', true) ?? $this->skills->first();
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'master_course_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'master_course_id');
    }

    public function offerings()
    {
        return $this->hasMany(CourseOffering::class, 'master_course_id');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'master_course_skills', 'master_course_id', 'skill_id')
            ->withPivot('is_main')
            ->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'master_course_tags', 'master_course_id', 'tag_id')
            ->withTimestamps();
    }
}
