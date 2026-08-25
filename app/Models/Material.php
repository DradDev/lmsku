<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Material extends Model
{
    protected $fillable = [
        'materialable_type',
        'materialable_id',
        'title',
        'file_path',
    ];

    /**
     * Polymorphic relation to parent (MasterCourse, CourseOffering, or Project)
     */
    public function materialable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Helper to check if material is global/master level
     */
    public function getIsGlobalAttribute(): bool
    {
        return $this->materialable_type === MasterCourse::class;
    }
}