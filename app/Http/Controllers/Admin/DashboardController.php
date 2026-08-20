<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\MasterCourse;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $resultStatus = $request->input('result_status', 'all');
        $search = trim($request->input('search', ''));

        // 1. Executive System Metrics
        $totalUsers = User::count();
        $pendingUsersCount = User::where('registration_status', 'pending')->count();
        $studentCount = User::where('role', 'student')->count();
        $lecturerCount = User::where('role', 'lecturer')->count();
        $vendorCount = User::where('role', 'vendor')->count();

        $totalMasterCourses = MasterCourse::count();
        $academicCoursesCount = MasterCourse::whereNull('user_id')->count();
        $vendorCoursesCount = MasterCourse::whereNotNull('user_id')->count();

        $activeTerm = AcademicTerm::where('is_active', true)->first();
        $totalActiveOfferings = CourseOffering::where('is_archived', false)
            ->when($activeTerm, function ($q) use ($activeTerm) {
                $q->where(function ($sub) use ($activeTerm) {
                    $sub->where('academic_term_id', $activeTerm->id)
                        ->orWhere('type', 'vendor');
                });
            })
            ->count();

        $totalEnrollments = Enrollment::count();

        // 2. Pending User Approvals (Top 5 latest)
        $pendingUsers = User::where('registration_status', 'pending')
            ->with('institution')
            ->latest()
            ->take(5)
            ->get();

        // 3. Final Quiz Results & Blockchain Integrity
        $resultsQuery = QuizAttempt::whereHas('quiz', function ($query) {
                $query->where('quiz_type', 'final');
            })
            ->with(['user', 'quiz.course', 'quiz.masterCourse'])
            ->latest();

        if ($resultStatus === 'verified') {
            $resultsQuery->where('is_verified', true);
        } elseif ($resultStatus === 'unverified') {
            $resultsQuery->where('is_verified', false);
        }

        if ($search !== '') {
            $resultsQuery->where(function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('quiz.course', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('quiz.masterCourse', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        $results = $resultsQuery->take(15)->get();

        $finalQuizAttempts = QuizAttempt::whereHas('quiz', function ($query) {
            $query->where('quiz_type', 'final');
        });

        $resultStats = [
            'total' => (clone $finalQuizAttempts)->count(),
            'verified' => (clone $finalQuizAttempts)->where('is_verified', true)->count(),
            'unverified' => (clone $finalQuizAttempts)->where('is_verified', false)->count(),
            'average_score' => round((float) (clone $finalQuizAttempts)->avg('score')),
        ];

        return view('admin.dashboard', compact(
            'search',
            'resultStatus',
            'results',
            'resultStats',
            'totalUsers',
            'pendingUsersCount',
            'studentCount',
            'lecturerCount',
            'vendorCount',
            'totalMasterCourses',
            'academicCoursesCount',
            'vendorCoursesCount',
            'activeTerm',
            'totalActiveOfferings',
            'totalEnrollments',
            'pendingUsers'
        ));
    }

    public function verifyResult($id): RedirectResponse
    {
        $attempt = QuizAttempt::findOrFail($id);

        if (!$attempt->is_verified) {
            $attempt->update([
                'is_verified' => true,
                'blockchain_hash' => '0x' . Str::upper(
                    substr(
                        hash('sha256', $attempt->id . '|' . $attempt->user_id . '|' . now()),
                        0,
                        16
                    )
                ),
            ]);
        }

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Hasil kelulusan mahasiswa berhasil diverifikasi ke Blockchain.');
    }
}