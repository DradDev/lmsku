<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectCommentController;
use App\Http\Controllers\BlockchainVerificationController;

// Student Controllers
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\MaterialController as StudentMaterialController;
use App\Http\Controllers\Student\QuizController as StudentQuizController;
use App\Http\Controllers\Student\ResultController as StudentResultController;
use App\Http\Controllers\Student\CertificateController as StudentCertificateController;
use App\Http\Controllers\Student\ProjectController as StudentProjectController;
use App\Http\Controllers\Student\RecommendationController as StudentRecommendationController;

// Lecturer Controllers
use App\Http\Controllers\Lecturer\DashboardController as LecturerDashboardController;
use App\Http\Controllers\Lecturer\CourseController as LecturerCourseController;
use App\Http\Controllers\Lecturer\MaterialController as LecturerMaterialController;
use App\Http\Controllers\Lecturer\QuizController as LecturerQuizController;
use App\Http\Controllers\Lecturer\QuestionController as LecturerQuestionController;
use App\Http\Controllers\Lecturer\QuizAnswerController as LecturerQuizAnswerController;
use App\Http\Controllers\Lecturer\ResultController as LecturerResultController;
use App\Http\Controllers\Lecturer\ProjectController as LecturerProjectController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ResultController as AdminResultController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SkillController as AdminSkillController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Admin\MasterCourseController as AdminMasterCourseController;
use App\Http\Controllers\Admin\AcademicTermController as AdminAcademicTermController;
use App\Http\Controllers\Admin\CourseOfferingController as AdminCourseOfferingController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;

// Vendor Controllers
use App\Http\Controllers\Vendor\DashboardController as VendorDashboardController;
use App\Http\Controllers\Vendor\CourseController as VendorCourseController;
use App\Http\Controllers\Vendor\ProjectController as VendorProjectController;
use App\Http\Controllers\Vendor\MaterialController as VendorMaterialController;
use App\Http\Controllers\Vendor\QuizController as VendorQuizController;
use App\Http\Controllers\Vendor\QuestionController as VendorQuestionController;

Route::get('/', function () {
    if (! Auth::check()) {
        return view('welcome');
    }

    return match (Auth::user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'lecturer' => redirect()->route('lecturer.dashboard'),
        'vendor' => redirect()->route('vendor.dashboard'),
        'student' => redirect()->route('student.dashboard'),
        default => redirect()->route('student.dashboard'),
    };
});

/*
|--------------------------------------------------------------------------
| Shared Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::post('/projects/{project}/comments', [ProjectCommentController::class, 'store'])
        ->name('projects.comments.store');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
});

Route::get('/validasi-blockchain', [BlockchainVerificationController::class, 'index'])
    ->name('blockchain.verify.index');

Route::post('/validasi-blockchain', [BlockchainVerificationController::class, 'verify'])
    ->name('blockchain.verify.check');

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])
            ->name('dashboard');

        // Courses
        Route::get('/courses', [StudentCourseController::class, 'index'])
            ->name('courses.index');

        Route::post('/courses/{course}/enroll', [StudentCourseController::class, 'enroll'])
            ->name('courses.enroll');

        Route::get('/courses/{course}', [StudentCourseController::class, 'show'])
            ->name('courses.show');

        // Materials
        Route::get('/materials', [StudentMaterialController::class, 'index'])
            ->name('materials.index');

        Route::get('/materials/{material}', [StudentMaterialController::class, 'show'])
            ->name('materials.show');

        // Quizzes
        Route::get('/quiz/{quiz}', [StudentQuizController::class, 'show'])
            ->name('quiz.show');

        Route::post('/quiz/{quiz}/submit', [StudentQuizController::class, 'submit'])
            ->name('quiz.submit');

        Route::post('/quiz/{quiz}/request-retake', [StudentQuizController::class, 'requestRetake'])
            ->name('quiz.request-retake');

        // Results
        Route::get('/results', [StudentResultController::class, 'index'])
            ->name('results.index');

        Route::get('/results/{result}', [StudentResultController::class, 'show'])
            ->name('results.show');

        // Certificates
        Route::get('/certificates', [StudentCertificateController::class, 'index'])
            ->name('certificate.index');

        Route::get('/certificate/{course}', [StudentCertificateController::class, 'show'])
            ->name('certificate.show');

        Route::get('/certificate/{course}/download', [StudentCertificateController::class, 'download'])
            ->name('certificate.download');

        Route::get('/certificate/project/{project}', [StudentCertificateController::class, 'showProject'])
            ->name('certificate.project.show');

        Route::get('/certificate/project/{project}/download', [StudentCertificateController::class, 'downloadProject'])
            ->name('certificate.project.download');

        // Projects & Digital Portfolio
        Route::get('/portfolio', [StudentProjectController::class, 'portfolio'])
            ->name('portfolio');

        Route::get('/projects', [StudentProjectController::class, 'index'])
            ->name('projects.index');

        Route::get('/my-projects', [StudentProjectController::class, 'myProjects'])
            ->name('projects.my');

        Route::get('/project-invitations', [StudentProjectController::class, 'invitations'])
            ->name('projects.invitations');

        Route::post('/projects/{project}/join', [StudentProjectController::class, 'join'])
            ->name('projects.join');

        Route::post('/projects/{project}/accept-invite', [StudentProjectController::class, 'acceptInvite'])
            ->name('projects.accept-invite');

        Route::post('/projects/{project}/decline-invite', [StudentProjectController::class, 'declineInvite'])
            ->name('projects.decline-invite');

        Route::patch('/projects/{project}/progress', [StudentProjectController::class, 'updateProgress'])
            ->name('projects.update-progress');

        Route::patch('/projects/{project}/complete', [StudentProjectController::class, 'complete'])
            ->name('projects.complete');

        Route::get('/projects/{project}', [StudentProjectController::class, 'show'])
            ->name('projects.show');

        // Recommendations
        Route::get('/recommendations', [StudentRecommendationController::class, 'index'])
            ->name('recommendations.index');
    });

/*
|--------------------------------------------------------------------------
| Lecturer Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:lecturer'])
    ->prefix('lecturer')
    ->name('lecturer.')
    ->group(function () {
        Route::get('/dashboard', [LecturerDashboardController::class, 'index'])
            ->name('dashboard');

        // Courses
        Route::get('/courses', [LecturerCourseController::class, 'index'])
            ->name('courses.index');

        Route::get('/courses/{course}', [LecturerCourseController::class, 'show'])
            ->name('courses.show');

        Route::get('/courses/{course}/edit', [LecturerCourseController::class, 'edit'])
            ->name('courses.edit');

        Route::put('/courses/{course}', [LecturerCourseController::class, 'update'])
            ->name('courses.update');

        Route::delete('/courses/{course}', [LecturerCourseController::class, 'destroy'])
            ->name('courses.destroy');

        Route::post('/courses/{course}/archive', [LecturerCourseController::class, 'archive'])->name('courses.archive');
        Route::post('/courses/{course}/duplicate', [LecturerCourseController::class, 'duplicate'])->name('courses.duplicate');

        // Course Quiz Management
        Route::post('/courses/{course}/quizzes', [LecturerQuizController::class, 'store'])
            ->name('courses.quizzes.store');

        Route::put('/courses/{course}/quizzes/{quiz}', [LecturerQuizController::class, 'update'])
            ->name('courses.quizzes.update');

        Route::delete('/courses/{course}/quizzes/{quiz}', [LecturerQuizController::class, 'destroy'])
            ->name('courses.quizzes.destroy');

        Route::get('/courses/{course}/quizzes/{quiz}/essay-answers', [LecturerQuizAnswerController::class, 'index'])
            ->name('courses.quizzes.answers.index');

        Route::patch('/courses/{course}/quizzes/{quiz}/essay-answers/{quizAnswer}/grade', [LecturerQuizAnswerController::class, 'grade'])
            ->name('courses.quizzes.answers.grade');

        Route::get('/courses/{course}/quizzes/{quiz}/results', [LecturerResultController::class, 'index'])
            ->name('courses.quizzes.results.index');

        Route::get('/courses/{course}/quizzes/{quiz}/results/{result}', [LecturerResultController::class, 'show'])
            ->name('courses.quizzes.results.show');

        // Retake Requests Approval
        Route::post('/retake-requests/{retakeRequest}/approve', [LecturerQuizController::class, 'approveRetake'])
            ->name('quizzes.retake.approve');

        Route::post('/retake-requests/{retakeRequest}/reject', [LecturerQuizController::class, 'rejectRetake'])
            ->name('quizzes.retake.reject');

        // Materials — hanya bisa dikelola dari dalam Course (nested)
        Route::get('/courses/{course}/materials/create', [LecturerMaterialController::class, 'create'])
            ->name('materials.create');

        Route::post('/courses/{course}/materials', [LecturerMaterialController::class, 'store'])
            ->name('materials.store');

        Route::get('/materials/{material}', [LecturerMaterialController::class, 'show'])
            ->name('materials.show');

        Route::get('/materials/{material}/edit', [LecturerMaterialController::class, 'edit'])
            ->name('materials.edit');

        Route::put('/materials/{material}', [LecturerMaterialController::class, 'update'])
            ->name('materials.update');

        Route::delete('/materials/{material}', [LecturerMaterialController::class, 'destroy'])
            ->name('materials.destroy');

        // Questions
        Route::get('/questions', [LecturerQuestionController::class, 'index'])
            ->name('questions.index');

        Route::get('/questions/create', [LecturerQuestionController::class, 'create'])
            ->name('questions.create');

        Route::post('/questions', [LecturerQuestionController::class, 'store'])
            ->name('questions.store');

        Route::get('/questions/{question}', [LecturerQuestionController::class, 'show'])
            ->name('questions.show');

        Route::get('/questions/{question}/edit', [LecturerQuestionController::class, 'edit'])
            ->name('questions.edit');

        Route::put('/questions/{question}', [LecturerQuestionController::class, 'update'])
            ->name('questions.update');

         Route::delete('/questions/{question}', [LecturerQuestionController::class, 'destroy'])
            ->name('questions.destroy');

        // Projects
        Route::get('/projects/{project}/talent-pool', [LecturerProjectController::class, 'talentPool'])
            ->name('projects.talent-pool');

        Route::get('/students/{student}/portfolio', [LecturerProjectController::class, 'studentPortfolio'])
            ->name('students.portfolio');

        Route::match(['get', 'post'], '/projects/{project}/invite/{user}', [LecturerProjectController::class, 'inviteTalent'])
            ->name('projects.invite');

        Route::post('/projects/{project}/toggle-publish', [LecturerProjectController::class, 'togglePublish'])
            ->name('projects.toggle-publish');

        Route::post('/projects/{project}/participations/{participation}/approve-certificate', [LecturerProjectController::class, 'approveCertificate'])
            ->name('projects.approve-certificate');

        Route::resource('projects', LecturerProjectController::class);
    });

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Results
        Route::get('/results', [AdminResultController::class, 'index'])
            ->name('results.index');

        Route::get('/results/{result}', [AdminResultController::class, 'show'])
            ->name('results.show');

        Route::get('/results/project/{participation}', [AdminResultController::class, 'showProject'])
            ->name('results.project.show');

        Route::post('/results/{result}/verify', [AdminResultController::class, 'verify'])
            ->name('results.verify');

        Route::post('/results/{result}/integrity', [AdminResultController::class, 'checkIntegrity'])
            ->name('results.integrity');

        Route::post('/results/project/{participation}/verify', [AdminResultController::class, 'verifyProject'])
            ->name('results.project.verify');

        Route::post('/results/project/{participation}/integrity', [AdminResultController::class, 'checkProjectIntegrity'])
            ->name('results.project.integrity');

        // Users, Skills, Tags
        Route::post('/results/{result}/integrity', [AdminResultController::class, 'checkIntegrity'])->name('results.integrity');

        Route::resource('users', AdminUserController::class)->only(['index', 'show', 'destroy']);

        Route::post('/users/{user}/approve', [AdminUserController::class, 'approve'])
            ->name('users.approve');

        Route::post('/users/{user}/reject', [AdminUserController::class, 'reject'])
            ->name('users.reject');
        Route::resource('skills', AdminSkillController::class);
        Route::resource('tags', AdminTagController::class)->except(['index', 'show', 'create', 'edit']);

        // Master Courses
        Route::post('/master-courses/{masterCourse}/competencies', [AdminMasterCourseController::class, 'syncCompetencies'])
            ->name('master-courses.competencies.sync');
        Route::resource('master-courses', AdminMasterCourseController::class);

        // Academic Terms
        Route::post('/academic-terms/offerings', [AdminAcademicTermController::class, 'storeOffering'])
            ->name('academic-terms.offerings.store');
        Route::put('/academic-terms/offerings/{offering}', [AdminAcademicTermController::class, 'updateOffering'])
            ->name('academic-terms.offerings.update');
        Route::delete('/academic-terms/offerings/{offering}', [AdminAcademicTermController::class, 'destroyOffering'])
            ->name('academic-terms.offerings.destroy');

        Route::resource('academic-terms', AdminAcademicTermController::class);
        Route::post('/academic-terms/{academicTerm}/toggle-active', [AdminAcademicTermController::class, 'toggleActive'])
            ->name('academic-terms.toggle-active');

        // Course Offerings
        Route::resource('course-offerings', AdminCourseOfferingController::class);

        // Projects Audit & Emergency Moderation
        Route::post('/projects/{project}/toggle-publish', [AdminProjectController::class, 'togglePublish'])
            ->name('projects.toggle-publish');
        Route::resource('projects', AdminProjectController::class)->only(['index', 'show', 'destroy']);

        // Courses Audit & Moderation (Vendor & Lecturer Courses)
        Route::post('/courses/{course}/suspend', [AdminCourseController::class, 'suspend'])
            ->name('courses.suspend');
        Route::post('/courses/{course}/approve', [AdminCourseController::class, 'approve'])
            ->name('courses.approve');
        Route::post('/courses/{course}/toggle-archive', [AdminCourseController::class, 'toggleArchive'])
            ->name('courses.toggle-archive');
        Route::resource('courses', AdminCourseController::class)->only(['index', 'show', 'destroy']);
    });

/*
|--------------------------------------------------------------------------
| Vendor / External Industry Partner Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:vendor'])
    ->prefix('vendor')
    ->name('vendor.')
    ->group(function () {
        Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');

        // Industry Certified Courses
        Route::post('/courses/{course}/toggle-archive', [VendorCourseController::class, 'toggleArchive'])->name('courses.toggle-archive');
        Route::post('/courses/{course}/launch-batch', [VendorCourseController::class, 'launchBatch'])->name('courses.launch-batch');
        Route::put('/courses/{course}/batch', [VendorCourseController::class, 'updateBatch'])->name('courses.update-batch');
        Route::resource('courses', VendorCourseController::class);

        // Course Materials & Quizzes
        Route::post('/courses/{course}/materials', [VendorMaterialController::class, 'store'])->name('materials.store');
        Route::delete('/materials/{material}', [VendorMaterialController::class, 'destroy'])->name('materials.destroy');

        Route::post('/courses/{course}/quizzes', [VendorQuizController::class, 'store'])->name('quizzes.store');
        Route::put('/courses/{course}/quizzes/{quiz}', [VendorQuizController::class, 'update'])->name('courses.quizzes.update');
        Route::get('/quizzes/{quiz}', [VendorQuizController::class, 'show'])->name('quizzes.show');
        Route::delete('/quizzes/{quiz}', [VendorQuizController::class, 'destroy'])->name('quizzes.destroy');
        Route::post('/quizzes/{quiz}/questions', [VendorQuizController::class, 'storeQuestion'])->name('quizzes.questions.store');
        Route::delete('/questions/{question}', [VendorQuizController::class, 'destroyQuestion'])->name('questions.destroy');

        // Quiz Retake Requests Approval
        Route::post('/retake-requests/{retakeRequest}/approve', [VendorQuizController::class, 'approveRetake'])->name('quizzes.retake.approve');
        Route::post('/retake-requests/{retakeRequest}/reject', [VendorQuizController::class, 'rejectRetake'])->name('quizzes.retake.reject');

        // Question Builder & Batch Routes
        Route::post('/questions', [VendorQuestionController::class, 'store'])->name('questions.store');
        Route::get('/questions/{question}/edit', [VendorQuestionController::class, 'edit'])->name('questions.edit');
        Route::put('/questions/{question}', [VendorQuestionController::class, 'update'])->name('questions.update');

        // Industry Projects
        Route::get('/students/{student}/portfolio', [VendorProjectController::class, 'studentPortfolio'])->name('students.portfolio');
        Route::post('/projects/{project}/toggle-publish', [VendorProjectController::class, 'togglePublish'])->name('projects.toggle-publish');
        Route::post('/projects/{project}/participations/{participation}/approve-certificate', [VendorProjectController::class, 'approveCertificate'])
            ->name('projects.approve-certificate');
        Route::get('/projects/{project}/talent-pool', [VendorProjectController::class, 'talentPool'])->name('projects.talent-pool');
        Route::post('/projects/{project}/invite/{user}', [VendorProjectController::class, 'inviteTalent'])->name('projects.invite');
        Route::resource('projects', VendorProjectController::class);
    });

require __DIR__ . '/auth.php';
