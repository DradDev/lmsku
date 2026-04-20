<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name',
        'description',
        'user_id',
    ];

    /**
     * =========================================================
     * Course dimiliki oleh dosen (user)
     * =========================================================
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * =========================================================
     * Course memiliki banyak materi
     * =========================================================
     */
    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    /**
     * =========================================================
     * Course memiliki banyak assignment
     * =========================================================
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * =========================================================
     * Course memiliki quiz
     * =========================================================
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * =========================================================
     * RELASI ENROLLMENT (Mahasiswa join course)
     * =========================================================
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments');
    }
}
