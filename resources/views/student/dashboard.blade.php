<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.dash-wrap {
    min-height: 100vh;
    background: #f4f6fb;
    color: #1e2435;
    padding: 2.5rem 0 4rem;
    font-family: 'Inter', sans-serif;
}

.dash-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
}

.dash-header { margin-bottom: 2rem; }

.dash-eyebrow {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #6366f1;
    margin-bottom: 6px;
}

.dash-title {
    font-size: 30px;
    font-weight: 700;
    color: #1e2435;
}

.alert {
    padding: 11px 16px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 1.5rem;
    border: 1px solid transparent;
}
.alert-success { background: #edfaf4; border-color: #a7e9c8; color: #1a7a4a; }
.alert-error { background: #fff0f0; border-color: #ffc2c2; color: #b91c1c; }

.stat-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 14px;
    margin-bottom: 2rem;
}

.stat-card {
    background: #fff;
    border: 1px solid #e8eaf2;
    border-radius: 16px;
    padding: 1.15rem 1.25rem;
    box-shadow: 0 6px 24px rgba(15, 23, 42, 0.04);
}

.stat-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: #98a2b3;
    margin-bottom: 8px;
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
}

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 1rem;
}

.section-title {
    font-size: 16px;
    font-weight: 700;
    color: #1e2435;
}

.section-meta {
    font-size: 12px;
    color: #6366f1;
    text-decoration: none;
    font-weight: 600;
}

.section-meta:hover { text-decoration: underline; }

.main-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 16px;
    margin-bottom: 2rem;
}

.card {
    background: #fff;
    border: 1px solid #e8eaf2;
    border-radius: 18px;
    padding: 1.25rem;
    box-shadow: 0 6px 24px rgba(15, 23, 42, 0.04);
}

.courses-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
}

.course-card {
    background: #f8faff;
    border: 1px solid #e5eaf7;
    border-radius: 16px;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.course-name {
    font-size: 15px;
    font-weight: 700;
    color: #1e2435;
}

.course-instructor {
    font-size: 12px;
    color: #9399b0;
}

.progress-wrap {
    margin-top: 3px;
}

.progress-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 6px;
}

.progress-label {
    font-size: 11px;
    color: #9399b0;
    font-weight: 600;
}

.progress-percent {
    font-size: 11px;
    color: #6366f1;
    font-weight: 700;
}

.progress-track {
    height: 6px;
    background: #e9edf8;
    border-radius: 100px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    border-radius: 100px;
    background: linear-gradient(90deg, #6366f1, #818cf8);
}

.status-badge {
    display: inline-flex;
    width: fit-content;
    align-items: center;
    font-size: 11px;
    font-weight: 700;
    padding: 5px 10px;
    border-radius: 999px;
}

.status-ready {
    background: #edfaf4;
    color: #1a7a4a;
    border: 1px solid #a7e9c8;
}

.status-progress {
    background: #fffbeb;
    color: #92400e;
    border: 1px solid #fcd34d;
}

.actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: auto;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 700;
    padding: 8px 13px;
    border-radius: 10px;
    text-decoration: none;
    transition: all .15s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: #6366f1;
    color: #fff;
}
.btn-primary:hover { background: #4f46e5; }

.btn-secondary {
    background: #eef2ff;
    color: #4f46e5;
    border: 1px solid #c7d2fe;
}
.btn-secondary:hover { background: #e5e7ff; }

.side-stack {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.info-kpi {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.mini-box {
    background: #f8faff;
    border: 1px solid #e5eaf7;
    border-radius: 14px;
    padding: 14px;
}

.mini-label {
    font-size: 11px;
    color: #98a2b3;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    margin-bottom: 6px;
}

.mini-value {
    font-size: 22px;
    font-weight: 700;
    color: #111827;
}

.muted-box {
    margin-top: 10px;
    border-radius: 12px;
    background: #f7f8fc;
    border: 1px solid #eef0f8;
    padding: 12px 14px;
    font-size: 13px;
    color: #5a607a;
    line-height: 1.6;
}

.muted-label {
    display: block;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .7px;
    text-transform: uppercase;
    color: #6366f1;
    margin-bottom: 5px;
}

.activity-list {
    list-style: none;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;
    padding: 10px 0;
    border-bottom: 1px solid #f0f2f9;
}

.activity-item:last-child { border-bottom: none; }

.activity-left {
    display: flex;
    gap: 10px;
    align-items: flex-start;
}

.activity-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-top: 6px;
    flex-shrink: 0;
}

.activity-text {
    font-size: 13px;
    color: #475467;
    line-height: 1.5;
}

.activity-meta {
    font-size: 11px;
    color: #98a2b3;
    white-space: nowrap;
}

.list-card-item {
    padding: 0 0 14px;
    margin: 0 0 14px;
    border-bottom: 1px solid #f0f2f9;
}

.list-card-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.item-tag {
    font-size: 11px;
    font-weight: 700;
    color: #6366f1;
    margin-bottom: 4px;
}

.item-title {
    font-size: 14px;
    font-weight: 700;
    color: #1e2435;
    margin-bottom: 4px;
}

.item-sub {
    font-size: 12px;
    color: #9399b0;
    margin-bottom: 10px;
}

.result-score {
    font-size: 34px;
    font-weight: 700;
    color: #6366f1;
    margin-bottom: 6px;
}

.empty-text {
    font-size: 13px;
    color: #b0b4c9;
    font-style: italic;
}

@media (max-width: 1100px) {
    .stat-grid { grid-template-columns: repeat(3, 1fr); }
    .main-grid { grid-template-columns: 1fr; }
}

@media (max-width: 760px) {
    .dash-container { padding: 0 1rem; }
    .stat-grid,
    .courses-grid,
    .dashboard-two-column,
    .info-kpi { grid-template-columns: 1fr; }
}

.dashboard-two-column {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 2rem;
}

.dashboard-two-column .card {
    height: 100%;
}
</style>

<div class="dash-wrap">
    <div class="dash-container">

        @php
            $certificateReadyCount = $courses->where('can_get_certificate', true)->count();
        @endphp

        <div class="dash-header">
            <p class="dash-eyebrow">Student Portal</p>
            <h1 class="dash-title">Student Dashboard</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <!-- TOP STATS -->
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-label">Total Courses</div>
                <div class="stat-value">{{ $totalCourses }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">In Progress</div>
                <div class="stat-value">{{ $inProgress }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Completed</div>
                <div class="stat-value">{{ $completed }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Active Projects</div>
                <div class="stat-value">{{ $totalJoinedProjectsCount ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Latest Quiz Score</div>
                <div class="stat-value">
                    @if($latestQuiz)
                        {{ $latestQuiz->score }}
                    @elseif(!empty($pendingQuiz))
                        Pending
                    @else
                        N/A
                    @endif
                </div>
            </div>
        </div>

        <!-- MAIN GRID: COURSES (LEFT) & SIDE STACK (RIGHT) -->
        <div class="main-grid">
            <!-- LEFT: CONTINUE LEARNING COURSES -->
            <div class="card">
                <div class="section-header">
                    <h2 class="section-title">Continue Learning</h2>
                    <a href="{{ route('student.courses.index') }}" class="section-meta">View all →</a>
                </div>

                @if($courses->count() > 0)
                    <div class="courses-grid">
                        @foreach($courses->take(4) as $course)
                            @php 
                                $progress = $course->progress ?? 0;
                                $instructorName = $course->user->name 
                                    ?? ($course->lecturer->name ?? 'Dosen Pengampu');
                                $courseId = $course->id;
                            @endphp

                            <div class="course-card">
                                <div class="course-name">{{ $course->name ?? ($course->masterCourse->name ?? 'Course') }}</div>
                                <div class="course-instructor">{{ $instructorName }}</div>

                                <div class="progress-wrap">
                                    <div class="progress-top">
                                        <span class="progress-label">Progress</span>
                                        <span class="progress-percent">{{ $progress }}%</span>
                                    </div>

                                    <div class="progress-track">
                                        <div class="progress-fill" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>

                                @if($course->can_get_certificate ?? false)
                                    <span class="status-badge status-ready">Certificate Ready</span>
                                @else
                                    <span class="status-badge status-progress">In Progress</span>
                                @endif

                                <div class="actions">
                                    <a href="{{ route('student.courses.show', $courseId) }}" class="btn btn-primary">
                                        Continue
                                    </a>

                                    @if($course->can_get_certificate ?? false)
                                        <a href="{{ route('student.certificate.show', $courseId) }}" class="btn btn-secondary">
                                            Certificate
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-text">You haven't enrolled in any courses yet.</div>
                @endif
            </div>

            <!-- RIGHT STACK: CERTIFICATES, PROYEK AKTIF, & RECENT ACTIVITY -->
            <div class="side-stack">
                <!-- 1. PROYEK & PORTOFOLIO AKTIF (PENGGANTI GRAFIK DUMMY) -->
                <div class="card">
                    <div class="section-header">
                        <div class="flex items-center gap-2">
                            <h2 class="section-title">Proyek Aktif</h2>
                            @if(($pendingInvitationsCount ?? 0) > 0)
                                <a href="{{ route('student.projects.invitations') }}" class="px-2 py-0.5 bg-rose-100 text-rose-700 text-[10px] font-bold rounded-full hover:bg-rose-200 transition-all">
                                    {{ $pendingInvitationsCount }} Undangan
                                </a>
                            @endif
                        </div>
                        <a href="{{ route('student.projects.my') }}" class="section-meta">Lihat Semua →</a>
                    </div>

                    @if(($activeParticipations ?? collect())->count() > 0)
                        <div class="space-y-3">
                            @foreach($activeParticipations as $part)
                                @php
                                    $prj = $part->project;
                                    if (!$prj) continue;
                                    $statusColor = match($part->status) {
                                        'completed' => ['bg' => '#edfaf4', 'text' => '#1a7a4a', 'border' => '#a7e9c8', 'label' => 'Selesai'],
                                        'review' => ['bg' => '#f5f3ff', 'text' => '#7c3aed', 'border' => '#ddd6fe', 'label' => 'Review'],
                                        'development' => ['bg' => '#eff6ff', 'text' => '#2563eb', 'border' => '#bfdbfe', 'label' => 'Development'],
                                        default => ['bg' => '#fffbeb', 'text' => '#b45309', 'border' => '#fde68a', 'label' => 'In Progress'],
                                    };
                                @endphp
                                <div class="p-3 bg-[#f8faff] border border-[#e5eaf7] rounded-xl flex flex-col gap-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex-1 min-w-0">
                                            <div class="font-bold text-xs text-[#1e2435] truncate">{{ $prj->title }}</div>
                                            <div class="text-[11px] text-[#9399b0] truncate mt-0.5">
                                                {{ $prj->creator->name ?? 'Pembimbing' }}
                                                @if($prj->creator?->institution)
                                                    • {{ $prj->creator->institution->name }}
                                                @endif
                                            </div>
                                        </div>
                                        <span style="background: {{ $statusColor['bg'] }}; color: {{ $statusColor['text'] }}; border: 1px solid {{ $statusColor['border'] }};" class="px-2 py-0.5 text-[10px] font-bold rounded-full shrink-0">
                                            {{ $statusColor['label'] }}
                                        </span>
                                    </div>

                                    <!-- Progress bar -->
                                    <div>
                                        <div class="flex items-center justify-between text-[10px] font-semibold mb-1">
                                            <span class="text-[#9399b0]">Progres</span>
                                            <span class="text-[#6366f1] font-bold">{{ $part->progress_percent ?? 0 }}%</span>
                                        </div>
                                        <div class="h-1.5 w-full bg-[#e9edf8] rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-full" style="width: {{ $part->progress_percent ?? 0 }}%"></div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pt-1">
                                        <span class="text-[10px] font-medium text-slate-500">
                                            {{ $prj->provider_type === 'internal' ? 'Kampus (Dosen)' : 'Mitra Industri' }}
                                        </span>
                                        <a href="{{ route('student.projects.show', $prj->id) }}" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800">
                                            Detail →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl text-center">
                            <p class="text-xs text-slate-500 mb-2">Belum ada proyek yang diambil.</p>
                            <a href="{{ route('student.projects.index') }}" class="btn btn-secondary text-xs py-1 px-3">
                                Jelajahi Proyek →
                            </a>
                        </div>
                    @endif
                </div>

                <!-- 2. CERTIFICATES OVERVIEW -->
                <div class="card">
                    <div class="section-header">
                        <h2 class="section-title">Certificates</h2>
                        <a href="{{ route('student.certificate.index') }}" class="section-meta">Open →</a>
                    </div>

                    <div class="info-kpi">
                        <div class="mini-box">
                            <div class="mini-label">Ready</div>
                            <div class="mini-value">{{ $certificateReadyCount }}</div>
                        </div>
                        <div class="mini-box">
                            <div class="mini-label">Locked</div>
                            <div class="mini-value">{{ max($totalCourses - $certificateReadyCount, 0) }}</div>
                        </div>
                    </div>

                    <div class="muted-box">
                        <span class="muted-label">Status</span>
                        @if($certificateReadyCount > 0)
                            You have certificates ready to be viewed or downloaded.
                        @else
                            No certificates ready yet. Complete final quizzes or projects to unlock certificates.
                        @endif
                    </div>
                </div>

                <!-- 3. RECENT ACTIVITY -->
                <div class="card">
                    <div class="section-header">
                        <h2 class="section-title">Recent Activity</h2>
                    </div>

                    <ul class="activity-list">
                        @if($latestQuiz)
                            <li class="activity-item">
                                <div class="activity-left">
                                    <span class="activity-dot" style="background:#10b981;"></span>
                                    <div class="activity-text">Quiz verified by admin</div>
                                </div>
                                <div class="activity-meta">Score: {{ $latestQuiz->score }}</div>
                            </li>
                        @elseif(!empty($pendingQuiz))
                            <li class="activity-item">
                                <div class="activity-left">
                                    <span class="activity-dot" style="background:#f59e0b;"></span>
                                    <div class="activity-text">Submitted quiz</div>
                                </div>
                                <div class="activity-meta">Pending</div>
                            </li>
                        @endif

                        <li class="activity-item">
                            <div class="activity-left">
                                <span class="activity-dot" style="background:#3b82f6;"></span>
                                <div class="activity-text">Active on LMS Platform</div>
                            </div>
                            <div class="activity-meta">Today</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- BOTTOM TWO COLUMN: AVAILABLE QUIZZES & LATEST QUIZ RESULT -->
        <div class="dashboard-two-column">
            <div class="card">
                <div class="section-header">
                    <h2 class="section-title">Available Quizzes</h2>
                </div>

                @if(($availableQuizzes ?? collect())->count() > 0)
                    @foreach(($availableQuizzes ?? collect())->take(3) as $quiz)
                        <div class="list-card-item">
                            <div class="item-tag">{{ $quiz->course->name ?? 'Course' }}</div>
                            <div class="item-title">
                                {{ $quiz->title }}
                                @if($quiz->quiz_type === 'final')
                                    <span style="margin-left:8px; font-size:11px; color:#10b981; font-weight:700;">
                                        Final Quiz
                                    </span>
                                @endif
                            </div>
                            <div class="item-sub">{{ $quiz->approved_questions_count ?? 0 }} approved question(s)</div>
                            <a href="{{ route('student.quiz.show', $quiz->id) }}" class="btn btn-primary">
                                Take Quiz
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="empty-text">No quizzes available yet.</div>
                @endif
            </div>

            <div class="card">
                <div class="section-header">
                    <h2 class="section-title">Latest Quiz Result</h2>
                    <a href="{{ route('student.results.index') }}" class="section-meta">View all →</a>
                </div>

                @if(($latestQuizResults ?? collect())->count() > 0)
                    @foreach($latestQuizResults as $quiz)
                        <div class="result-score">
                            {{ $quiz->score }}
                        </div>

                        <div class="muted-box">
                            <span class="muted-label">Status</span>
                            {{ $quiz->quiz->title ?? 'Quiz' }} — Verified by Admin.
                        </div>

                        @if(!$loop->last)
                            <hr style="margin:15px 0; border: none; border-top: 1px solid #f0f2f9;">
                        @endif
                    @endforeach
                @elseif(!empty($pendingQuiz))
                    <div class="result-score" style="font-size:24px; color:#f59e0b;">Pending</div>
                    <div class="muted-box">
                        <span class="muted-label">Status</span>
                        Quiz submitted, waiting for admin verification.
                    </div>
                @else
                    <div class="empty-text">No quizzes taken yet.</div>
                @endif
            </div>
        </div>

    </div>
</div>
</x-app-layout>
