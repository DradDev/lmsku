<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'master_course_id',
        'course_offering_id',
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
     * Relasi ke penawaran kelas spesifik (opsional)
     */
    public function courseOffering()
    {
        return $this->belongsTo(CourseOffering::class, 'course_offering_id');
    }

    /**
     * Backward compatibility course relationship
     */
    public function course()
    {
        return $this->belongsTo(MasterCourse::class, 'master_course_id');
    }
}

