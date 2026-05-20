<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ResultController extends Controller
{
    public function index(): View
    {
        $results = QuizAttempt::query()
            ->with(['user', 'quiz.course'])
            ->latest()
            ->get();

        $totalResults  = QuizAttempt::count();
        $verifiedCount = QuizAttempt::where('is_verified', true)->count();
        $pendingCount  = QuizAttempt::where('is_verified', false)->count();
        $averageScore  = round((float) QuizAttempt::avg('score'), 2);

        return view('admin.results.index', compact(
            'results', 'totalResults', 'verifiedCount', 'pendingCount', 'averageScore'
        ));
    }

    public function show(QuizAttempt $result): View
    {
        $result->load(['user', 'quiz.course', 'answers.question']);
        return view('admin.results.show', compact('result'));
    }

    /**
     * Approve = kirim ke blockchain.
     * Hashing terjadi di chaincode — Laravel hanya kirim raw data.
     */
    public function verify(QuizAttempt $result): RedirectResponse
    {
        if ($result->is_verified) {
            return redirect()
                ->route('admin.results.show', $result->id)
                ->with('info', 'Result ini sudah diverifikasi sebelumnya.');
        }

        try {
            // Gunakan completed_at yang sudah ada, atau created_at sebagai fallback
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
             ->post(config('services.blockchain.url') . '/api/hash/store', [
                'id'        => (string) $result->id,
                'type'      => 'quiz_attempt',
                'userId'    => (string) $result->user_id,
                'score'     => (float) $result->score,
                'timestamp' => $completedAt,
                'rawData'   => $rawData,
            ]);

            if ($response->status() === 409) {
                $result->update(['is_verified' => true]);
                return redirect()
                    ->route('admin.results.show', $result->id)
                    ->with('info', 'Data sudah tercatat di blockchain sebelumnya.');
            }

            if (! $response->successful()) {
                throw new \Exception('Node API error: ' . $response->body());
            }

            $payload = $response->json();

            // Simpan completed_at yang dipakai — ini yang akan dipakai saat verify
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
        if (! $result->is_verified || ! $result->blockchain_id) {
            return redirect()
                ->route('admin.results.show', $result->id)
                ->with('error', 'Result belum diverifikasi ke blockchain.');
        }

        try {
            // Pakai completed_at yang disimpan saat approve — TIDAK pakai updated_at
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
