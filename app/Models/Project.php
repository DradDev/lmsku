<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Skill;
use App\Models\Tag;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'difficulty_level',
        'duration_days',
        'max_students',
        'created_by',
        'provider_type',
        'brief_file',
        'benefits',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    protected static function booted()
    {
        static::deleting(function ($model) {
            $model->skills()->detach();
            $model->tags()->detach();
            $model->materials()->delete();
            $model->certificates()->delete();
        });
    }

    public function getBriefFileUrlAttribute()
    {
        if (empty($this->brief_file)) {
            return null;
        }

        return asset('storage/' . $this->brief_file);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participations()
    {
        return $this->hasMany(ProjectParticipation::class);
    }

        /**
     * Polymorphic certificates relation
     */
    public function certificates(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Certificate::class, 'certifiable');
    }

    public function certificate(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Certificate::class, 'certifiable');
    }

    public function materials(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Material::class, 'materialable');
    }


    public function students()
    {
        return $this->belongsToMany(User::class, 'project_participations')
            ->withPivot([
                'status',
                'progress_percent',
                'started_at',
                'completed_at',
                'last_activity_at',
            ])
            ->withTimestamps();
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

    public function statusHistories()
    {
        return $this->hasMany(ProjectStatusHistory::class);
    }

    public function getJoinedStudentsCountAttribute()
    {
        return $this->participations()->count();
    }

    public function getRemainingSlotsAttribute()
    {
        return max(0, $this->max_students - $this->joined_students_count);
    }

    public function getIsFullAttribute()
    {
        return $this->joined_students_count >= $this->max_students;
    }

    public function comments()
    {
        return $this->hasMany(ProjectComment::class);
    }
    
    public function getMainSkillAttribute()
    {
        return $this->skills->firstWhere('pivot.is_main', true) ?? $this->skills->first();
    }

    public function getCategoryAttribute()
    {
        $mainSkillName = $this->main_skill?->name;
        return $mainSkillName ? (object)['name' => $mainSkillName] : null;
    }
}
