<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemStatistic extends Model
{
    protected $fillable = [
        'item_type',
        'item_id',
        'viewed_count',
        'clicked_count',
        'taken_count',
        'completed_count',
        'popularity_score',
        'completion_rate',
        'last_activity_at',
        'last_calculated_at',
    ];

    protected $casts = [
        'popularity_score' => 'decimal:2',
        'completion_rate' => 'decimal:2',
        'last_activity_at' => 'datetime',
        'last_calculated_at' => 'datetime',
    ];
}