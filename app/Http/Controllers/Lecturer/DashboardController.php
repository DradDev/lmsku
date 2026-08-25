<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\CourseOffering;
use App\Models\MasterCourse;
use App\Models\Material;
use App\Models\Project;
use App\Models\Quiz;
use App\Models\QuizRetakeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $lecturerId = Auth::id();

        // 1. Ambil rombel & mata kuliah milik Dosen
        $offerings = CourseOffering::where('lecturer_id', $lecturerId)
            ->with(['masterCourse', 'academicTerm', 'enrollments'])
            ->latest()
            ->get();

        $masterCourseIds = $offerings->pluck('master_course_id')->filter()->unique()->toArray();
        $offeringIds = $offerings->pluck('id')->toArray();

        // 2. Modul Materi Pembelajaran
        $materials = Material::query()
            ->where(function ($query) use ($masterCourseIds, $offeringIds) {
                $query->where(function ($sub) use ($masterCourseIds) {
                    $sub->where('materialable_type', MasterCourse::class)
                        ->whereIn('materialable_id', $masterCourseIds);
                })->orWhere(function ($sub) use ($offeringIds) {
                    $sub->where('materialable_type', CourseOffering::class)
                        ->whereIn('materialable_id', $offeringIds);
                });
            })
            ->with(['materialable'])
            ->latest()
            ->get();

        // 3. Bank Kuis Kurikulum & Kelas
        $quizzes = Quiz::query()
            ->where(function ($query) use ($masterCourseIds, $offeringIds) {
                $query->where(function ($sub) use ($masterCourseIds) {
                    $sub->where('quizzable_type', \App\Models\MasterCourse::class)
                        ->whereIn('quizzable_id', $masterCourseIds);
                })->orWhere(function ($sub) use ($offeringIds) {
                    $sub->where('quizzable_type', CourseOffering::class)
                        ->whereIn('quizzable_id', $offeringIds);
                });
            })
            ->with(['quizzable'])
            ->withCount('questions')
            ->latest()
            ->get();

        // 4. Metrik Pengajaran
        $totalStudents = $offerings->flatMap->enrollments->unique('user_id')->count();
        $activeTerm = AcademicTerm::where('is_active', true)->first();

        // 5. Pengajuan Remedial / Retake Quiz Mahasiswa
        $pendingRetakesCount = QuizRetakeRequest::whereIn('course_offering_id', $offeringIds)
            ->where('status', 'pending')
            ->count();

        $pendingRetakes = QuizRetakeRequest::whereIn('course_offering_id', $offeringIds)
            ->where('status', 'pending')
            ->with(['user', 'quiz', 'courseOffering.masterCourse'])
            ->latest()
            ->take(3)
            ->get();

        // 6. Proyek Industri yang Dibimbing / Dikelola
        $supervisedProjects = Project::where('created_by', $lecturerId)
            ->withCount('participations')
            ->latest()
            ->take(3)
            ->get();
        $supervisedProjectsCount = Project::where('created_by', $lecturerId)->count();

        return view('lecturer.dashboard', compact(
            'offerings',
            'materials',
            'quizzes',
            'totalStudents',
            'activeTerm',
            'pendingRetakes',
            'pendingRetakesCount',
            'supervisedProjects',
            'supervisedProjectsCount'
        ));
    }
}
