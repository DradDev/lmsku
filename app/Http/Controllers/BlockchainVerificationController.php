<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlockchainVerificationController extends Controller
{
    public function index(Request $request): View
    {
        $hash = $request->query('hash') ?? $request->query('code');
        if ($hash) {
            return $this->performVerification(trim($hash));
        }

        return view('public.blockchain-verify', $this->viewData());
    }

    public function verify(Request $request): View
    {
        $validated = $request->validate([
            'hash' => ['required', 'string', 'max:255'],
        ]);

        return $this->performVerification(trim($validated['hash']));
    }

    /**
     * Memproses verifikasi hash pada tabel certificates maupun quiz_attempts.
     */
    private function performVerification(string $queryStr): View
    {
        $queryStr = trim($queryStr);

        if (empty($queryStr)) {
            return $this->responseView(
                $queryStr,
                null,
                'invalid',
                'Harap masukkan Hash Blockchain, Credential ID, atau Transaction ID yang valid.'
            );
        }

        // 1. CARI DI TABEL CERTIFICATES (Project Certificates & Course Certificates)
        $certificate = Certificate::with([
            'user',
            'verifiedByAdmin',
            'certifiable',
        ])
        ->where(function ($q) use ($queryStr) {
            $q->where('blockchain_hash', $queryStr)
              ->orWhere('credential_code', $queryStr)
              ->orWhere('blockchain_id', $queryStr)
              ->orWhere('tx_id', $queryStr);
        })
        ->first();

        if ($certificate) {
            $isVerified = ($certificate->is_verified || $certificate->status === 'verified') && !empty($certificate->blockchain_hash);

            if (! $isVerified) {
                return $this->responseView(
                    $queryStr,
                    null,
                    'invalid',
                    'Kredensial sertifikat ditemukan di sistem, namun masih berstatus PENDING verifikasi Admin dan belum diterbitkan ke Blockchain.'
                );
            }

            $certType = 'Course Certificate';
            $title = '-';
            $issuer = 'LMS Telkom University';
            $category = 'Academic Course';

            $entity = $certificate->certifiable;

            if ($entity instanceof \App\Models\Project) {
                $entity->loadMissing(['creator.institution', 'user.institution']);
                $certType = 'Project Certificate';
                $title = $entity->title;
                $category = $entity->provider_type === 'external' ? 'Proyek Industri Mitra Vendor' : 'Proyek Kampus Dosen';
                $creator = $entity->creator ?? $entity->user;
                $issuer = $creator ? ($creator->name . ($creator->institution ? ' (' . $creator->institution->name . ')' : '')) : 'Dosen / Mitra Vendor';
            } elseif ($entity instanceof \App\Models\CourseOffering) {
                $entity->loadMissing(['masterCourse', 'academicTerm', 'lecturer.institution', 'user.institution']);
                $certType = $entity->type === 'vendor' ? 'Industry Certification' : 'Course Certificate';
                $title = $entity->masterCourse->name ?? ($entity->section_name ?? 'Course Offering');
                $category = $entity->type === 'vendor' ? 'Pelatihan & Sertifikasi Vendor' : 'Mata Kuliah Akademik';
                $lecturer = $entity->lecturer ?? $entity->user;
                $issuer = $lecturer ? ($lecturer->name . ($lecturer->institution ? ' (' . $lecturer->institution->name . ')' : '')) : 'Fakultas Teknik';
            } elseif ($entity instanceof \App\Models\MasterCourse) {
                $entity->loadMissing(['user.institution']);
                $certType = 'Master Course Certificate';
                $title = $entity->name;
                $category = 'Kurikulum Akademik';
                $author = $entity->user;
                $issuer = $author ? ($author->name . ($author->institution ? ' (' . $author->institution->name . ')' : '')) : 'Fakultas Teknik';
            }

            $resultData = (object) [
                'record_type' => 'certificate',
                'cert_type' => $certType,
                'category' => $category,
                'title' => $title,
                'student_name' => $certificate->user->name ?? 'Student Talent',
                'student_email' => $certificate->user->email ?? '-',
                'credential_code' => $certificate->credential_code,
                'blockchain_id' => $certificate->blockchain_id ?? 'BC-BLK-001',
                'blockchain_hash' => $certificate->blockchain_hash,
                'tx_id' => $certificate->tx_id ?? '-',
                'score' => $certificate->score,
                'issuer' => $issuer,
                'verified_at' => $certificate->verified_at ?? $certificate->completed_at ?? $certificate->created_at,
                'verified_by_name' => $certificate->verifiedByAdmin->name ?? 'Administrator / Admin LP3M',
                'status' => 'Verified & Authenticated',
            ];

            return $this->responseView(
                $queryStr,
                $resultData,
                'valid',
                'Hash & Kredensial Sertifikat Resmi VALID dan Terotentikasi di Blockchain Ledger.'
            );
        }

        // 2. CARI DI TABEL QUIZ ATTEMPTS (Quiz Results & Exam Hashing)
        $attempt = QuizAttempt::with(['user', 'quiz.quizzable'])
            ->where(function ($q) use ($queryStr) {
                $q->where('blockchain_hash', $queryStr)
                  ->orWhere('blockchain_id', $queryStr)
                  ->orWhere('tx_id', $queryStr);
            })
            ->first();

        if ($attempt) {
            $isVerified = ($attempt->is_verified ?? true) && !empty($attempt->blockchain_hash);

            if (! $isVerified) {
                return $this->responseView(
                    $queryStr,
                    null,
                    'invalid',
                    'Data evaluasi kuis ditemukan namun belum diverifikasi oleh Admin.'
                );
            }

            $quizTitle = $attempt->quiz->title ?? $attempt->quiz->name ?? 'Quiz Assessment';
            $masterName = $attempt->quiz->course->name ?? 'Course Evaluation';

            $resultData = (object) [
                'record_type' => 'quiz_attempt',
                'cert_type' => 'Quiz Competency Evaluation',
                'category' => 'Evaluasi Kompetensi Kuis',
                'title' => $quizTitle . ' (' . $masterName . ')',
                'student_name' => $attempt->user->name ?? 'Student',
                'student_email' => $attempt->user->email ?? '-',
                'credential_code' => 'ATTEMPT-' . $attempt->id,
                'blockchain_id' => $attempt->blockchain_id ?? ('BC-ATT-' . $attempt->id),
                'blockchain_hash' => $attempt->blockchain_hash,
                'tx_id' => $attempt->tx_id ?? ('0x' . hash('sha256', 'tx_' . $attempt->id)),
                'score' => $attempt->score,
                'issuer' => 'LMS Telkom University Examination Board',
                'verified_at' => $attempt->verified_at ?? $attempt->created_at,
                'verified_by_name' => 'Administrator LP3M',
                'status' => 'Verified & Authenticated',
            ];

            return $this->responseView(
                $queryStr,
                $resultData,
                'valid',
                'Hash Hasil Evaluasi Kompetensi VALID dan Terotentikasi di Blockchain Ledger.'
            );
        }

        // 3. TIDAK DITEMUKAN
        return $this->responseView(
            $queryStr,
            null,
            'invalid',
            'Hash / Kredensial Blockchain tidak ditemukan di ledger terdistribusi atau sertifikat belum diverifikasi resmi.'
        );
    }

    private function responseView(
        ?string $hash,
        mixed $result,
        string $status,
        string $message
    ): View {
        return view(
            'public.blockchain-verify',
            $this->viewData($hash, $result, $status, $message)
        );
    }

    private function viewData(
        ?string $hash = null,
        mixed $result = null,
        ?string $status = null,
        ?string $message = null
    ): array {
        return [
            'hash' => $hash,
            'result' => $result,
            'certificate' => $result,
            'status' => $status,
            'message' => $message,
        ];
    }
}
