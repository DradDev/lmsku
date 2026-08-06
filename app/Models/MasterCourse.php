<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterCourse extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'level',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
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
}
