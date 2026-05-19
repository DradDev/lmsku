<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendationResult extends Model
{
    protected $fillable = [
        'snapshot_date',
        'user_id',
        'item_type',
        'item_id',
        'prediction_score',
        'rank',
        'model_name',
        'model_version',
    ];

    protected $casts = [
        'snapshot_date' => 'date',
        'prediction_score' => 'decimal:6',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'item_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'item_id');
    }
}
