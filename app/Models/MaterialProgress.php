<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialProgress extends Model
{
    protected $table = 'material_progresses';

    protected $fillable = [
        'user_id',
        'course_id',
        'material_id',
        'is_completed',
        'first_viewed_at',
        'last_viewed_at',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'first_viewed_at' => 'datetime',
        'last_viewed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
