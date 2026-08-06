<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function index(Request $request): View
    {
        $activeTab = $request->query('tab', 'quiz');

        // Tab 1: Final Quiz Results
        $baseQuery = QuizAttempt::whereHas('quiz', function ($query) {
            $query->where('quiz_type', 'final');
        });

        $results = (clone $baseQuery)
            ->with(['user', 'quiz.course'])
            ->latest()
            ->get();

        $totalQuizResults  = (clone $baseQuery)->count();
        $verifiedQuizCount = (clone $baseQuery)->where('is_verified', true)->count();
        $pendingQuizCount  = (clone $baseQuery)->where('is_verified', false)->count();
        $averageQuizScore  = round((float) (clone $baseQuery)->avg('score'), 2);

        // Tab 2: Project Results
        $projectParticipations = \App\Models\ProjectParticipation::with(['user', 'project.creator', 'project.skills'])
            ->latest()
            ->get();

        foreach ($projectParticipations as $part) {
            $cert = \App\Models\Certificate::where('user_id', $part->user_id)
                ->where('project_id', $part->project_id)
                ->first();
            $part->certificate_record = $cert;
            $part->is_verified = $cert ? $cert->is_verified : ($part->status === 'completed');
        }

        $totalProjectResults  = $projectParticipations->count();
        $verifiedProjectCount = $projectParticipations->filter(fn($p) => $p->is_verified)->count();
        $pendingProjectCount  = $projectParticipations->filter(fn($p) => !$p->is_verified)->count();

        $totalResults  = $activeTab === 'project' ? $totalProjectResults : $totalQuizResults;
        $verifiedCount = $activeTab === 'project' ? $verifiedProjectCount : $verifiedQuizCount;
        $pendingCount  = $activeTab === 'project' ? $pendingProjectCount : $pendingQuizCount;
        $averageScore  = $averageQuizScore;

        return view('admin.results.index', compact(
            'results', 'totalResults', 'verifiedCount', 'pendingCount', 'averageScore',
            'activeTab', 'projectParticipations',
            'totalQuizResults', 'verifiedQuizCount', 'pendingQuizCount',
            'totalProjectResults', 'verifiedProjectCount', 'pendingProjectCount'
        ));
    }

    public function verifyProject(\App\Models\ProjectParticipation $participation): RedirectResponse
    {
        $participation->update(['status' => 'completed']);

        $certificate = \App\Models\Certificate::firstOrCreate(
            [
                'user_id' => $participation->user_id,
                'project_id' => $participation->project_id,
            ],
            [
                'score' => 100,
                'completed_at' => $participation->updated_at ?? now(),
            ]
        );

        $certificate->update([
            'is_verified' => true,
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.results.index', ['tab' => 'project'])
            ->with('success', "Project Certificate untuk {$participation->user->name} berhasil diverifikasi (Verified)!");
    }

    public function show(QuizAttempt $result): View
    {
        abort_unless(
            $result->quiz && $result->quiz->isFinal(),
            403,
            'Hanya hasil Final Quiz yang diproses untuk sertifikat & blockchain.'
        );

        $result->load(['user', 'quiz.course']);
        return view('admin.results.show', compact('result'));
    }

    /**
     * Approve = kirim ke blockchain.
     * Hashing terjadi di chaincode — Laravel hanya kirim raw data.
     * Hanya berlaku untuk attempt dari Final Quiz (dasar sertifikat).
     */
    public function verify(QuizAttempt $result): RedirectResponse
    {
        abort_unless(
            $result->quiz && $result->quiz->isFinal(),
            403,
            'Hanya Final Quiz yang boleh dicatat ke blockchain.'
        );

        if ($result->is_verified) {
            return redirect()
                ->route('admin.results.show', $result->id)
                ->with('info', 'Result ini sudah diverifikasi sebelumnya.');
        }

        try {
            $completedAt = $result->completed_at
                ? $result->completed_at->toISOString()
                : $result->created_at->toISOString();

            $rawData = [
                'completed_at' => $completedAt,
                'quiz_id'      => (int) $result->quiz_id,
                'score'        => (float) $result->score,
                'user_id'      => (int) $result->user_id,
            ];
            ksort($rawData);

            $blockchainSuccess = false;
            $payload = [];

            try {
                $response = Http::timeout(5)
                    ->withHeaders(['X-Api-Key' => config('services.blockchain.api_key')])
                    ->post(config('services.blockchain.url') . '/api/hash/store', [
                    'id'        => (string) $result->id,
                    'type'      => 'quiz_attempt',
                    'userId'    => (string) $result->user_id,
                    'score'     => (float) $result->score,
                    'timestamp' => $completedAt,
                    'rawData'   => $rawData,
                ]);

                if ($response->successful() || $response->status() === 409) {
                    $blockchainSuccess = true;
                    $payload = $response->json() ?? [];
                }
            } catch (\Exception $e) {
                Log::warning('Blockchain network offline during certificate verification: ' . $e->getMessage());
            }

            $result->update([
                'is_verified'     => true,
                'completed_at'    => $completedAt,
                'blockchain_id'   => $payload['blockchainId'] ?? $result->blockchain_id,
                'blockchain_hash' => $payload['hash'] ?? $result->blockchain_hash,
                'tx_id'           => $payload['txId'] ?? $result->tx_id,
            ]);

            // Sync Certificate record
            $certificate = \App\Models\Certificate::firstOrCreate(
                [
                    'user_id' => $result->user_id,
                    'course_id' => $result->quiz->course_id,
                ],
                [
                    'score' => $result->score,
                    'completed_at' => $result->completed_at ?? now(),
                ]
            );

            $certificate->update([
                'score' => max($certificate->score ?? 0, (int) $result->score),
                'is_verified' => true,
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => \Illuminate\Support\Facades\Auth::id(),
                'blockchain_id' => $payload['blockchainId'] ?? $certificate->blockchain_id,
                'blockchain_hash' => $payload['hash'] ?? $certificate->blockchain_hash,
                'tx_id' => $payload['txId'] ?? $certificate->tx_id,
            ]);

            $message = $blockchainSuccess
                ? 'Sertifikat berhasil diverifikasi & tercatat di blockchain. TX: ' . ($payload['txId'] ?? '-')
                : 'Sertifikat berhasil diverifikasi admin (Status: Verified).';

            return redirect()
                ->route('admin.results.show', $result->id)
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.results.show', $result->id)
                ->with('error', 'Verifikasi gagal: ' . $e->getMessage());
        }
    }

    /**
     * Check integrity — kirim raw data ke blockchain untuk verifikasi.
     */
    public function checkIntegrity(QuizAttempt $result): RedirectResponse
    {
        abort_unless(
            $result->quiz && $result->quiz->isFinal(),
            403,
            'Hanya Final Quiz yang diproses untuk blockchain.'
        );

        if (! $result->is_verified || ! $result->blockchain_id) {
            return redirect()
                ->route('admin.results.show', $result->id)
                ->with('error', 'Result belum diverifikasi ke blockchain.');
        }

        try {
            $completedAt = $result->completed_at
                ? $result->completed_at->toISOString()
                : $result->created_at->toISOString();

            $rawData = [
                'completed_at' => $completedAt,
                'quiz_id'      => (int) $result->quiz_id,
                'score'        => (float) $result->score,
                'user_id'      => (int) $result->user_id,
            ];
            ksort($rawData);

            $response = Http::timeout(30)
                ->withHeaders(['X-Api-Key' => config('services.blockchain.api_key')])
                ->post(config('services.blockchain.url') . '/api/hash/verify', [
                    'id'      => (string) $result->id,
                    'type'    => 'quiz_attempt',
                    'rawData' => $rawData,
                ]);

            $response->throw();
            $payload = $response->json();

            $message = $payload['verified']
                ? '✓ Integritas data terkonfirmasi. Data tidak berubah sejak approval.'
                : '⚠ PERINGATAN: Data tidak cocok dengan blockchain!';

            return redirect()
                ->route('admin.results.show', $result->id)
                ->with($payload['verified'] ? 'success' : 'error', $message);

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.results.show', $result->id)
                ->with('error', 'Cek integritas gagal: ' . $e->getMessage());
        }
    }
}