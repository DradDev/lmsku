<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'course_id',
        'master_course_id',
        'title',
        'file_path',
    ];

    /**
     * Materi dimiliki oleh MasterCourse (Pustaka Induk)
     */
    public function masterCourse()
    {
        return $this->belongsTo(MasterCourse::class, 'master_course_id');
    }

    /**
     * Legacy course relationship
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}

