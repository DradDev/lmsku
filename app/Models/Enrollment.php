<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $table = 'enrollments';

    protected $fillable = [
        'user_id',
        'course_id',
        'course_offering_id',
        'progress_percent',
        'completed_material_count',
        'completed_quiz_count',
        'total_material_count',
        'total_quiz_count',
        'status',
        'started_at',
        'completed_at',
        'last_activity_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_activity_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::saving(function ($enrollment) {
            if (empty($enrollment->course_offering_id) && !empty($enrollment->course_id)) {
                $offering = CourseOffering::find($enrollment->course_id);
                if ($offering) {
                    $enrollment->course_offering_id = $offering->id;
                } else {
                    $course = Course::find($enrollment->course_id);
                    if ($course && $course->master_course_id) {
                        $mapped = CourseOffering::where('master_course_id', $course->master_course_id)
                            ->where('section_name', $course->batch_name)
                            ->first();
                        if ($mapped) {
                            $enrollment->course_offering_id = $mapped->id;
                        }
                    }
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courseOffering()
    {
        return $this->belongsTo(CourseOffering::class, 'course_offering_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}

