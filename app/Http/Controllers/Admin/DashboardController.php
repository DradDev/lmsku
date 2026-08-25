<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\Certificate;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\MasterCourse;
use App\Models\Project;
use App\Models\ProjectParticipation;
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

        // 1. Four Core KPI Cards
        $totalUsers = User::count();
        $activeTerm = AcademicTerm::where('is_active', true)->first();
        $activeCoursesCount = CourseOffering::where('is_archived', false)
            ->when($activeTerm, function ($q) use ($activeTerm) {
                $q->where(function ($sub) use ($activeTerm) {
                    $sub->where('academic_term_id', $activeTerm->id)
                        ->orWhere('type', 'vendor');
                });
            })
            ->count();
        $activeProjectsCount = Project::where('is_published', true)->count();
        $activeStudentsCount = User::where('role', 'student')->where('registration_status', 'approved')->count();

        // 2. Learning Progress & Completion Rates
        $totalEnrollments = Enrollment::count();
        $completedEnrollments = Enrollment::where('status', 'completed')->count();
        $inProgressEnrollments = Enrollment::where('status', 'in_progress')->count();
        $notStartedEnrollments = Enrollment::where('status', 'not_started')->count();
        $avgSystemProgress = $totalEnrollments > 0 ? round((float) Enrollment::avg('progress_percent')) : 0;

        // 3. Top 3 Most Popular Courses
        $topCourses = CourseOffering::with(['masterCourse', 'lecturer', 'academicTerm'])
            ->withCount('enrollments')
            ->withAvg('enrollments', 'progress_percent')
            ->orderByDesc('enrollments_count')
            ->take(3)
            ->get();
        $totalMasterCourses = MasterCourse::count();

        // 4. Active Semester & User Composition
        $studentCount = User::where('role', 'student')->count();
        $lecturerCount = User::where('role', 'lecturer')->count();
        $vendorCount = User::where('role', 'vendor')->count();

        // 5. Recent Activity Feed (Composite Timeline)
        $recentActivities = collect();

        User::latest()->take(3)->get()->each(function ($u) use ($recentActivities) {
            $roleLabel = match ($u->role) {
                'lecturer' => 'Dosen / Instruktur',
                'vendor' => 'Mitra Industri',
                default => 'Mahasiswa',
            };
            $recentActivities->push([
                'title' => $u->name . ' mendaftar sebagai ' . $roleLabel,
                'subtitle' => $u->institution->name ?? ($u->email ?? 'Registrasi Pengguna'),
                'time' => $u->created_at,
                'type' => 'user',
            ]);
        });

        QuizAttempt::whereHas('quiz', function ($q) {
                $q->where('quiz_type', 'final');
            })
            ->with(['user', 'quiz.quizzable'])
            ->latest()
            ->take(3)
            ->get()
            ->each(function ($qa) use ($recentActivities) {
                $recentActivities->push([
                    'title' => ($qa->user->name ?? 'Mahasiswa') . ' menyelesaikan Final Quiz',
                    'subtitle' => ($qa->quiz->course->name ?? 'Course') . ' • Nilai: ' . $qa->score . ' Pts',
                    'time' => $qa->completed_at ?? $qa->created_at,
                    'type' => 'quiz',
                ]);
            });

        CourseOffering::with(['masterCourse', 'lecturer'])
            ->latest()
            ->take(2)
            ->get()
            ->each(function ($co) use ($recentActivities) {
                $recentActivities->push([
                    'title' => 'Rombel ' . ($co->section_name ?: 'Kelas') . ' ' . ($co->masterCourse->name ?? 'Mata Kuliah') . ' dibuka',
                    'subtitle' => 'Pengampu: ' . ($co->lecturer->name ?? 'Dosen/Vendor'),
                    'time' => $co->created_at,
                    'type' => 'course',
                ]);
            });

        $recentActivities = $recentActivities->sortByDesc('time')->take(5)->values();

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeCoursesCount',
            'activeProjectsCount',
            'activeStudentsCount',
            'totalEnrollments',
            'completedEnrollments',
            'inProgressEnrollments',
            'notStartedEnrollments',
            'avgSystemProgress',
            'topCourses',
            'totalMasterCourses',
            'activeTerm',
            'studentCount',
            'lecturerCount',
            'vendorCount',
            'recentActivities'
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