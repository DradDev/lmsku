<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'course_offering_id',
        'project_id',
        'credential_code',
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

    public function courseOffering()
    {
        return $this->belongsTo(CourseOffering::class, 'course_offering_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
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

    /**
     * Formulasi Otomatis Credential ID Unik: CERT/[KODE_MASTER_COURSE]/[TAHUN_SEMESTER]/[USER_ID]
     */
    public function generateCredentialCode(): string
    {
        $userIdFormatted = sprintf('%04d', $this->user_id);

        if ($this->project_id) {
            $prjCode = sprintf('PRJ-%04d', $this->project_id);
            $year = $this->completed_at ? $this->completed_at->format('Y') : date('Y');
            return "CERT/{$prjCode}/{$year}/{$userIdFormatted}";
        }

        // Kode Master Course (Teknik Komputer / Academic)
        $courseCode = $this->courseOffering?->masterCourse?->code
            ?? $this->course?->masterCourse?->code
            ?? $this->course?->code
            ?? 'TK-SE-001';

        // Tahun & Semester (Ganjil = 1, Genap = 2)
        $termObj = $this->courseOffering?->academicTerm;
        $year = date('Y');
        $termSuffix = '1'; // Default Ganjil

        if ($termObj) {
            if ($termObj->start_date) {
                $year = $termObj->start_date->format('Y');
            } elseif ($termObj->academic_year) {
                $year = substr($termObj->academic_year, 0, 4);
            }
            if (strtolower($termObj->term_type) === 'genap' || strtolower($termObj->term_type) === 'even') {
                $termSuffix = '2';
            }
        }

        $termCode = "{$year}{$termSuffix}";

        return "CERT/{$courseCode}/{$termCode}/{$userIdFormatted}";
    }

    public function getCredentialCodeAttribute($value): string
    {
        if (!empty($value)) {
            return $value;
        }
        return $this->generateCredentialCode();
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
