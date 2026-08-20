<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedMaterial extends Model
{
    protected $table = 'saved_materials';

    protected $fillable = [
        'user_id',
        'material_id',
        'course_offering_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function courseOffering()
    {
        return $this->belongsTo(CourseOffering::class, 'course_offering_id');
    }

    public function course()
    {
        return $this->belongsTo(CourseOffering::class, 'course_offering_id');
    }
}
