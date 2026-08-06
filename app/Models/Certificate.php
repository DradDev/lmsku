<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'project_id',
        'score',
        'blockchain_hash',
        'blockchain_id',
        'tx_id',
        'completed_at',
        'is_verified',
        'status',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'score' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function verifiedByAdmin()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'verified' => 'Verified',
            'rejected' => 'Rejected',
            default => 'Pending Review',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'verified' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            'rejected' => 'bg-rose-100 text-rose-700 border-rose-200',
            default => 'bg-amber-100 text-amber-700 border-amber-200',
        };
    }
}
