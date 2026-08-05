<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class ResultController extends Controller
{
    public function index(): View
    {
        $baseQuery = QuizAttempt::whereHas('quiz', function ($query) {
            $query->where('quiz_type', 'final');
        });

        $results = (clone $baseQuery)
            ->with(['user', 'quiz.course'])
            ->latest()
            ->get();

        $totalResults  = (clone $baseQuery)->count();
        $verifiedCount = (clone $baseQuery)->whereNotNull('blockchain_hash')->count();
        $pendingCount  = (clone $baseQuery)->whereNull('blockchain_hash')->count();
        $averageScore  = round((float) (clone $baseQuery)->avg('score'), 2);

        return view('admin.results.index', compact(
            'results', 'totalResults', 'verifiedCount', 'pendingCount', 'averageScore'
        ));
    }

    public function show(QuizAttempt $result): View
    {
        abort_unless(
            $result->quiz && $result->quiz->quiz_type === 'final',
            403,
            'Hanya hasil Final Quiz yang diproses untuk sertifikat & blockchain.'
        );

        $result->load(['user', 'quiz.course']);
        return view('admin.results.show', compact('result'));
    }

    /**
     * Approve = kirim ke blockchain.
     * Status "sudah dicatat ke blockchain" ditentukan dari ada/tidaknya blockchain_hash,
     * BUKAN dari is_verified — karena is_verified sudah otomatis true begitu student
     * submit quiz MC-only (final quiz wajib MC-only), jauh sebelum admin approve.
     */
    public function verify(QuizAttempt $result): RedirectResponse
    {
        abort_unless(
            $result->quiz && $result->quiz->quiz_type === 'final',
            403,
            'Hanya Final Quiz yang boleh dicatat ke blockchain.'
        );

        if (!empty($result->blockchain_hash)) {
            return redirect()
                ->route('admin.results.show', $result->id)
                ->with('info', 'Result ini sudah tercatat di blockchain sebelumnya.');
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
                ->post(config('services.blockchain.url') . '/api/hash/store', [
                'id'        => (string) $result->id,
                'type'      => 'quiz_attempt',
                'userId'    => (string) $result->user_id,
                'score'     => (float) $result->score,
                'timestamp' => $completedAt,
                'rawData'   => $rawData,
            ]);

            if ($response->status() === 409) {
                return redirect()
                    ->route('admin.results.show', $result->id)
                    ->with('info', 'Data sudah tercatat di blockchain sebelumnya, tapi hash belum tersinkron. Hubungi developer.');
            }

            if (! $response->successful()) {
                throw new \Exception('Node API error: ' . $response->body());
            }

            $payload = $response->json();

            $result->update([
                'is_verified'     => true,
                'completed_at'    => $completedAt,
                'blockchain_id'   => $payload['blockchainId'],
                'blockchain_hash' => $payload['hash'],
                'tx_id'           => $payload['txId'],
            ]);

            return redirect()
                ->route('admin.results.show', $result->id)
                ->with('success', 'Berhasil tercatat di blockchain. TX: ' . $payload['txId']);

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
            $result->quiz && $result->quiz->quiz_type === 'final',
            403,
            'Hanya Final Quiz yang diproses untuk blockchain.'
        );

        if (empty($result->blockchain_hash) || empty($result->blockchain_id)) {
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