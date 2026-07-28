<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class BlockchainVerificationController extends Controller
{
    public function index(): View
    {
        return view('public.blockchain-verify', $this->viewData());
    }

    public function verify(Request $request): View
    {
        $validated = $request->validate([
            'hash' => ['required', 'string', 'max:255'],
        ]);

        $hash = trim($validated['hash']);

        // Validasi tabel
        if (! Schema::hasTable('quiz_attempts')) {
            return $this->responseView(
                $hash,
                null,
                'error',
                'Tabel quiz_attempts belum tersedia.'
            );
        }

        if (! Schema::hasColumn('quiz_attempts', 'blockchain_hash')) {
            return $this->responseView(
                $hash,
                null,
                'error',
                'Kolom blockchain_hash belum tersedia di tabel quiz_attempts.'
            );
        }

        if (! Schema::hasTable('users') || ! Schema::hasTable('quizzes')) {
            return $this->responseView(
                $hash,
                null,
                'error',
                'Tabel users atau quizzes belum tersedia.'
            );
        }

        // Tentukan kolom title quiz
        $quizTitleColumn = DB::raw("'-' as quiz_title");

        if (Schema::hasColumn('quizzes', 'title')) {
            $quizTitleColumn = 'quizzes.title as quiz_title';
        } elseif (Schema::hasColumn('quizzes', 'name')) {
            $quizTitleColumn = 'quizzes.name as quiz_title';
        }

        // Query data
        $query = DB::table('quiz_attempts')
            ->leftJoin('users', 'quiz_attempts.user_id', '=', 'users.id')
            ->leftJoin('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
            ->select(
                'users.name as student_name',
                $quizTitleColumn,
                'quiz_attempts.blockchain_hash'
            )
            ->where('quiz_attempts.blockchain_hash', $hash);

        // Cek verifikasi jika kolom tersedia
        if (Schema::hasColumn('quiz_attempts', 'is_verified')) {
            $query->where('quiz_attempts.is_verified', true);
        }

        $result = $query->first();

        return $this->responseView(
            $hash,
            $result,
            $result ? 'valid' : 'invalid',
            $result
                ? 'Hash valid. Data hasil quiz ditemukan.'
                : 'Hash tidak ditemukan atau hasil quiz belum diverifikasi.'
        );
    }

    /**
     * Helper untuk return view response
     */
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

    /**
     * Data yang dikirim ke view
     */
    private function viewData(
        ?string $hash = null,
        mixed $result = null,
        ?string $status = null,
        ?string $message = null
    ): array {
        return [
            'hash' => $hash,
            'result' => $result,

            // Tetap dikirim supaya view lama tidak error
            'certificate' => null,

            'status' => $status,
            'message' => $message,
        ];
    }
}
