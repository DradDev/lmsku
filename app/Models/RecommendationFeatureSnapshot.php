<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendationFeatureSnapshot extends Model
{
    protected $fillable = [
        'snapshot_date',
        'user_id',

        'item_type',
        'item_type_encoded',
        'item_id',

        'user_avg_skill_score',
        'user_lowest_skill_score',
        'user_completed_course_count',
        'user_completed_project_count',
        'user_recent_activity_score',
        'user_top_interest_tag_id',

        'item_difficulty_level',
        'item_main_skill_id',
        'item_popularity_score',
        'item_completion_rate',

        'interest_match_score',
        'weakness_match_score',
        'readiness_score',

        'label_clicked',
        'label_taken',
        'label_completed',
        'already_started_flag',

        'item_skill_count',
        'item_tag_count',
    ];

    protected $casts = [
        'snapshot_date' => 'date',

        'user_avg_skill_score' => 'decimal:2',
        'user_lowest_skill_score' => 'decimal:2',
        'user_recent_activity_score' => 'decimal:2',

        'item_popularity_score' => 'decimal:2',
        'item_completion_rate' => 'decimal:2',

        'interest_match_score' => 'decimal:2',
        'weakness_match_score' => 'decimal:2',
        'readiness_score' => 'decimal:2',

        'label_clicked' => 'boolean',
        'label_taken' => 'boolean',
        'label_completed' => 'boolean',
        'already_started_flag' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topInterestTag()
    {
        return $this->belongsTo(Tag::class, 'user_top_interest_tag_id');
    }

    public function mainSkill()
    {
        return $this->belongsTo(Skill::class, 'item_main_skill_id');
    }
}