<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicTerm extends Model
{
    protected $fillable = [
        'name',
        'academic_year',
        'term_type',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function offerings()
    {
        return $this->hasMany(CourseOffering::class, 'academic_term_id');
    }

    /**
     * Scope: hanya semester aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

