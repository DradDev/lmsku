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
        return view('public.blockchain-verify', [
            'hash' => null,
            'certificate' => null,
            'status' => null,
            'message' => null,
        ]);
    }

    public function verify(Request $request): View
    {
        $validated = $request->validate([
            'hash' => ['required', 'string', 'max:255'],
        ]);

        $hash = trim($validated['hash']);

        if (! Schema::hasTable('certificates')) {
            return view('public.blockchain-verify', [
                'hash' => $hash,
                'certificate' => null,
                'status' => 'error',
                'message' => 'Tabel certificates belum tersedia.',
            ]);
        }

        $hasBlockchainHash = Schema::hasColumn('certificates', 'blockchain_hash');
        $hasCertificateHash = Schema::hasColumn('certificates', 'certificate_hash');
        $hasHash = Schema::hasColumn('certificates', 'hash');

        if (! $hasBlockchainHash && ! $hasCertificateHash && ! $hasHash) {
            return view('public.blockchain-verify', [
                'hash' => $hash,
                'certificate' => null,
                'status' => 'error',
                'message' => 'Kolom hash certificate belum tersedia di tabel certificates.',
            ]);
        }

        $query = DB::table('certificates')
            ->leftJoin('users', 'certificates.user_id', '=', 'users.id')
            ->leftJoin('courses', 'certificates.course_id', '=', 'courses.id')
            ->select(
                'certificates.*',
                'users.name as student_name',
                'users.email as student_email',
                'courses.name as course_name'
            );

        $query->where(function ($q) use ($hash, $hasBlockchainHash, $hasCertificateHash, $hasHash) {
            if ($hasBlockchainHash) {
                $q->orWhere('certificates.blockchain_hash', $hash);
            }

            if ($hasCertificateHash) {
                $q->orWhere('certificates.certificate_hash', $hash);
            }

            if ($hasHash) {
                $q->orWhere('certificates.hash', $hash);
            }
        });

        $certificate = $query->first();

        return view('public.blockchain-verify', [
            'hash' => $hash,
            'certificate' => $certificate,
            'status' => $certificate ? 'valid' : 'invalid',
            'message' => $certificate
                ? 'Hash valid. Certificate ditemukan dan cocok dengan data sistem.'
                : 'Hash tidak ditemukan. Certificate tidak valid atau belum tercatat di sistem.',
        ]);
    }
}