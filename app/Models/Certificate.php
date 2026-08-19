<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'user_id',
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
        return $this->belongsTo(CourseOffering::class, 'course_offering_id');
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
     * Helper untuk memetakan nama skill menjadi inisial 2-3 huruf baku.
     */
    public static function getSkillCodeByName(string $name): string
    {
        $n = strtolower(trim($name));
        if (str_contains($n, 'embedded') || str_contains($n, 'robotic')) {
            return 'EMB';
        }
        if (str_contains($n, 'network') || str_contains($n, 'security') || str_contains($n, 'cyber')) {
            return 'NET';
        }
        if (str_contains($n, 'software') || str_contains($n, 'web') || str_contains($n, 'mobile') || str_contains($n, 'app')) {
            return 'SE';
        }
        if (str_contains($n, 'machine learning') || str_contains($n, 'artificial') || str_contains($n, 'ai') || str_contains($n, 'data')) {
            return 'AI';
        }
        if (str_contains($n, 'multimedia') || str_contains($n, 'game') || str_contains($n, 'design') || str_contains($n, 'ui/ux')) {
            return 'MM';
        }

        $words = array_values(array_filter(explode(' ', preg_replace('/[^a-zA-Z0-9\s]/', '', $name))));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        } elseif (count($words) === 1) {
            return strtoupper(substr($words[0], 0, 3));
        }

        return 'GEN';
    }

    /**
     * Helper untuk mengekstrak gabungan inisial skill (Multi-Skill Tag: e.g. SE-AI, EMB-NET).
     */
    public static function extractSkillsTag($skills): string
    {
        if (empty($skills) || (is_countable($skills) && count($skills) === 0)) {
            return 'GEN';
        }

        $codes = [];
        foreach ($skills as $skill) {
            $name = is_object($skill) ? ($skill->name ?? '') : (string) $skill;
            if (!empty($name)) {
                $c = static::getSkillCodeByName($name);
                if (!in_array($c, $codes, true)) {
                    $codes[] = $c;
                }
            }
            if (count($codes) >= 3) {
                break; // Maksimal 3 singkatan skill
            }
        }

        return !empty($codes) ? implode('-', $codes) : 'GEN';
    }

    /**
     * Helper untuk mengambil kode institusi vendor (PT/CV atau inisial perorangan).
     */
    public static function getVendorCode(?User $creator): string
    {
        if (!$creator) {
            return 'IND01';
        }

        // Jika berafiliasi ke Institusi resmi (PT / CV)
        if ($creator->institution && !empty($creator->institution->code)) {
            return strtoupper($creator->institution->code);
        }

        // Jika Perorangan / Praktisi Independen: Inisial nama + User ID
        $cleanName = preg_replace('/[^a-zA-Z\s]/', '', $creator->name);
        $words = array_values(array_filter(explode(' ', trim($cleanName))));
        $initials = '';
        foreach ($words as $w) {
            if (!empty($w)) {
                $initials .= strtoupper(substr($w, 0, 1));
            }
            if (strlen($initials) >= 2) {
                break;
            }
        }
        if (strlen($initials) < 2) {
            $initials = str_pad($initials, 2, 'X');
        }

        return sprintf('%s%03d', $initials, $creator->id);
    }

    /**
     * Formulasi Otomatis Credential ID Unik untuk 4 Kategori Sertifikat:
     * 1. Academic Course  : CERT/{KODE_MASTER_COURSE}/{TAHUN_SEMESTER}/{USER_ID}
     * 2. Lecturer Project : CERT/TK-PRJ-{KODE_SKILL}-{PROJECT_ID}/{TAHUN_SEMESTER}/{USER_ID}
     * 3. Vendor Project   : CERT/IND-PRJ-{KODE_VENDOR}-{KODE_SKILL}-{PROJECT_ID}/{TAHUN_BULAN}/{USER_ID}
     * 4. Vendor Course    : CERT/IND-CRS-{KODE_VENDOR}-{KODE_SKILL}-{COURSE_ID}/{TAHUN_BULAN}/{USER_ID}
     */
    public function generateCredentialCode(): string
    {
        $userIdFormatted = sprintf('%04d', $this->user_id);
        $completedDate = $this->completed_at ?? now();

        // ==========================================
        // DOMAIN A: PROJECT CERTIFICATE
        // ==========================================
        if ($this->project_id || $this->project) {
            $project = $this->project;
            if (!$project && $this->project_id) {
                $project = Project::with(['creator.institution', 'skills'])->find($this->project_id);
            }

            if ($project) {
                $project->loadMissing(['creator.institution', 'skills']);
                $creator = $project->creator;
                $isInternal = ($project->provider_type === 'internal') || ($creator?->role === 'lecturer') || ($creator?->role === 'admin');
                $skillTag = static::extractSkillsTag($project->skills);
                $prjIdFormatted = sprintf('%04d', $project->id);

                if ($isInternal) {
                    // KATEGORI 2: LECTURER PROJECT (Internal Teknik Komputer)
                    // Semester: Ganjil = 1 (Jul-Des), Genap = 2 (Jan-Jun)
                    $month = (int) $completedDate->format('n');
                    $termSuffix = ($month >= 1 && $month <= 6) ? '2' : '1';
                    $termCode = $completedDate->format('Y') . $termSuffix;

                    return "CERT/TK-PRJ-{$skillTag}-{$prjIdFormatted}/{$termCode}/{$userIdFormatted}";
                } else {
                    // KATEGORI 3: VENDOR PROJECT (Industry Partner External)
                    $vendorCode = static::getVendorCode($creator);
                    $periodMonth = $completedDate->format('Ym');

                    return "CERT/IND-PRJ-{$vendorCode}-{$skillTag}-{$prjIdFormatted}/{$periodMonth}/{$userIdFormatted}";
                }
            }

            // Fallback project
            $prjCode = sprintf('PRJ-%04d', $this->project_id ?? 1);
            $year = $completedDate->format('Y');
            return "CERT/{$prjCode}/{$year}/{$userIdFormatted}";
        }

        // ==========================================
        // DOMAIN B: COURSE CERTIFICATE
        // ==========================================
        $offering = $this->courseOffering;
        if (!$offering && $this->course_offering_id) {
            $offering = CourseOffering::with(['masterCourse.skills', 'masterCourse.user.institution', 'lecturer.institution', 'academicTerm'])->find($this->course_offering_id);
        }

        $courseCreator = $offering?->lecturer ?? $offering?->masterCourse?->user ?? $offering?->user;
        $isVendorCourse = ($offering?->type === 'vendor' || $courseCreator?->role === 'vendor');

        if ($isVendorCourse) {
            // KATEGORI 4: VENDOR COURSE (Industry Course)
            $vendorCode = static::getVendorCode($courseCreator);
            $skills = $offering?->skills ?? $offering?->masterCourse?->skills ?? collect();
            $skillTag = static::extractSkillsTag($skills);
            $courseIdFormatted = sprintf('%04d', $offering?->id ?? 1);
            $periodMonth = $completedDate->format('Ym');

            return "CERT/IND-CRS-{$vendorCode}-{$skillTag}-{$courseIdFormatted}/{$periodMonth}/{$userIdFormatted}";
        }

        // KATEGORI 1: ACADEMIC COURSE (Internal Teknik Komputer / Master Course)
        $courseCode = $offering?->masterCourse?->code ?? 'TK-SE-001';

        // Tahun & Semester (Ganjil = 1, Genap = 2)
        $termObj = $offering?->academicTerm;
        $year = $completedDate->format('Y');
        $termSuffix = '1';

        if ($termObj) {
            if ($termObj->start_date) {
                $year = $termObj->start_date->format('Y');
            } elseif ($termObj->academic_year) {
                $year = substr($termObj->academic_year, 0, 4);
            }
            if (strtolower((string) $termObj->term_type) === 'genap' || strtolower((string) $termObj->term_type) === 'even') {
                $termSuffix = '2';
            }
        } else {
            $month = (int) $completedDate->format('n');
            $termSuffix = ($month >= 1 && $month <= 6) ? '2' : '1';
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
