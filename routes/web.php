<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;

// Student Controllers
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\MaterialController as StudentMaterialController;
use App\Http\Controllers\Student\QuizController as StudentQuizController;
use App\Http\Controllers\Student\SubmissionController as StudentSubmissionController;
use App\Http\Controllers\Student\ResultController as StudentResultController;
use App\Http\Controllers\Student\CertificateController as StudentCertificateController;

// Lecturer Controllers
use App\Http\Controllers\Lecturer\DashboardController as LecturerDashboardController;
use App\Http\Controllers\Lecturer\CourseController as LecturerCourseController;
use App\Http\Controllers\Lecturer\MaterialController as LecturerMaterialController;
use App\Http\Controllers\Lecturer\QuizController as LecturerQuizController;
use App\Http\Controllers\Lecturer\QuestionController as LecturerQuestionController;
use App\Http\Controllers\Lecturer\AssignmentController as LecturerAssignmentController;
use App\Http\Controllers\Lecturer\QuizAnswerController as LecturerQuizAnswerController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\Admin\ResultController as AdminResultController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'lecturer' => redirect()->route('lecturer.dashboard'),
            default => redirect()->route('student.dashboard'),
        };
    }

    return view('welcome');
});

Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

        Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('courses.show');

        Route::get('/certificates', [StudentCertificateController::class, 'index'])->name('certificate.index');
        Route::get('/certificate/{course}', [StudentCertificateController::class, 'show'])->name('certificate.show');
        Route::get('/certificate/{course}/download', [StudentCertificateController::class, 'download'])->name('certificate.download');

        Route::get('/materials', [StudentMaterialController::class, 'index'])->name('materials.index');
        Route::get('/materials/{material}', [StudentMaterialController::class, 'show'])->name('materials.show');

        Route::get('/quiz/{quiz}', [StudentQuizController::class, 'show'])->name('quiz.show');
        Route::post('/quiz/{quiz}/submit', [StudentQuizController::class, 'submit'])->name('quiz.submit');

        Route::get('/submissions', [StudentSubmissionController::class, 'index'])->name('submissions.index');
        Route::get('/assignments/{assignment}/submit', [StudentSubmissionController::class, 'create'])->name('submissions.create');
        Route::post('/submissions', [StudentSubmissionController::class, 'store'])->name('submissions.store');
        Route::get('/submissions/{submission}', [StudentSubmissionController::class, 'show'])->name('submissions.show');

        Route::get('/results', [StudentResultController::class, 'index'])->name('results.index');
        Route::get('/results/{result}', [StudentResultController::class, 'show'])->name('results.show');
    });

Route::middleware(['auth', 'role:lecturer'])
    ->prefix('lecturer')
    ->name('lecturer.')
    ->group(function () {
        Route::get('/dashboard', [LecturerDashboardController::class, 'index'])->name('dashboard');

        Route::get('/courses', [LecturerCourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [LecturerCourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [LecturerCourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course}', [LecturerCourseController::class, 'show'])->name('courses.show');
        Route::get('/courses/{course}/edit', [LecturerCourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [LecturerCourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [LecturerCourseController::class, 'destroy'])->name('courses.destroy');

        Route::resource('materials', LecturerMaterialController::class);

        Route::resource('assignments', LecturerAssignmentController::class);

        Route::patch('/assignments/{assignment}/submissions/{submission}/grade', [LecturerAssignmentController::class, 'gradeSubmission'])
            ->name('assignments.submissions.grade');

        Route::post('/courses/{course}/quizzes', [LecturerQuizController::class, 'store'])->name('courses.quizzes.store');
        Route::patch('/courses/{course}/quizzes/{quiz}/make-final', [LecturerQuizController::class, 'makeFinal'])
            ->name('courses.quizzes.make-final');
        Route::delete('/courses/{course}/quizzes/{quiz}', [LecturerQuizController::class, 'destroy'])->name('courses.quizzes.destroy');

        Route::get('/courses/{course}/quizzes/{quiz}/essay-answers', [LecturerQuizAnswerController::class, 'index'])
            ->name('courses.quizzes.answers.index');
        Route::patch('/courses/{course}/quizzes/{quiz}/essay-answers/{quizAnswer}/grade', [LecturerQuizAnswerController::class, 'grade'])
            ->name('courses.quizzes.answers.grade');

        Route::get('/questions', [LecturerQuestionController::class, 'index'])->name('questions.index');
        Route::get('/questions/create', [LecturerQuestionController::class, 'create'])->name('questions.create');
        Route::post('/questions', [LecturerQuestionController::class, 'store'])->name('questions.store');
        Route::get('/questions/{question}', [LecturerQuestionController::class, 'show'])->name('questions.show');
        Route::get('/questions/{question}/edit', [LecturerQuestionController::class, 'edit'])->name('questions.edit');
        Route::put('/questions/{question}', [LecturerQuestionController::class, 'update'])->name('questions.update');
        Route::delete('/questions/{question}', [LecturerQuestionController::class, 'destroy'])->name('questions.destroy');
        Route::post('/questions/{question}/submit', [LecturerQuestionController::class, 'submit'])->name('questions.submit');
    });

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/results', [AdminResultController::class, 'index'])->name('results.index');
        Route::get('/results/{result}', [AdminResultController::class, 'show'])->name('results.show');
        Route::post('/results/{result}/verify', [AdminResultController::class, 'verify'])->name('results.verify');

        Route::get('/questions', [AdminQuestionController::class, 'index'])->name('questions.index');
        Route::get('/questions/{question}', [AdminQuestionController::class, 'show'])->name('questions.show');
        Route::get('/questions/{question}/edit', [AdminQuestionController::class, 'edit'])->name('questions.edit');
        Route::put('/questions/{question}', [AdminQuestionController::class, 'update'])->name('questions.update');
        Route::post('/questions/{question}/approve', [AdminQuestionController::class, 'approve'])->name('questions.approve');
        Route::post('/questions/{question}/reject', [AdminQuestionController::class, 'reject'])->name('questions.reject');
        Route::delete('/questions/{question}', [AdminQuestionController::class, 'destroy'])->name('questions.destroy');

        Route::resource('users', AdminUserController::class);
    });

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__ . '/auth.php';