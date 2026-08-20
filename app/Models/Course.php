<?php

namespace App\Models;

/**
 * Class Course
 * 
 * Alias model for CourseOffering to provide 100% backward compatibility
 * with legacy routes and controllers under Strict 3NF architecture.
 */
class Course extends CourseOffering
{
    protected $table = 'course_offerings';

    protected $fillable = [
        'master_course_id',
        'type',
        'academic_term_id',
        'lecturer_id',
        'section_name',
        'capacity',
        'start_date',
        'end_date',
        'is_archived',
        'certificate_threshold',
        'status',
    ];
}
