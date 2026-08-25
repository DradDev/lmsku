<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

    protected static function booted()
    {
        static::deleting(function ($model) {
            $model->skills()->detach();
            $model->tags()->detach();
            // Optional: delete related polymorphic children if needed
            $model->materials()->delete();
            $model->quizzes()->delete();
            $model->certificates()->delete();
        });
    }

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

    /**
     * Polymorphic materials relation (Induk Kurikulum)
     */
    public function materials(): MorphMany
    {
        return $this->morphMany(Material::class, 'materialable');
    }

    /**
     * Polymorphic quizzes relation (Induk Kurikulum)
     */
        /**
     * Polymorphic certificates relation
     */
    public function certificates(): MorphMany
    {
        return $this->morphMany(Certificate::class, 'certifiable');
    }

    public function quizzes(): MorphMany
    {
        return $this->morphMany(Quiz::class, 'quizzable');
    }

    public function offerings()
    {
        return $this->hasMany(CourseOffering::class, 'master_course_id');
    }

    public function skills()
    {
        return $this->morphToMany(Skill::class, 'skillable')
            ->withPivot('weight', 'is_main')
            ->withTimestamps();
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')
            ->withPivot('weight')
            ->withTimestamps();
    }
}