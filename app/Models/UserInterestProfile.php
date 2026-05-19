<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInterestProfile extends Model
{
    protected $fillable = [
        'user_id',
        'tag_id',
        'interest_score',
        'interaction_count',
        'last_activity_at',
        'last_calculated_at',
    ];

    protected $casts = [
        'interest_score' => 'decimal:2',
        'last_activity_at' => 'datetime',
        'last_calculated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
