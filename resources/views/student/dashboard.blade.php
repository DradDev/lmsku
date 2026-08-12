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

.dash-sub {
    font-size: 14px;
    color: #475569;
    margin-top: 5px;
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
    color: #475569;
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
    color: #475569;
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
    color: #475569;
    font-weight: 600;
}

.progress-percent {
    font-size: 11px;
    color: #4f46e5;
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
    background: linear-gradient(90deg, #4f46e5, #6366f1);
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
    color: #166534;
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
    background: #4f46e5;
    color: #fff;
}
.btn-primary:hover { background: #4338ca; }

.btn-secondary {
    background: #eef2ff;
    color: #3730a3;
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
    color: #475569;
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

.mini-sub {
    font-size: 12px;
    color: #334155;
    line-height: 1.6;
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
    color: #334155;
    line-height: 1.5;
}

.activity-meta {
    font-size: 11px;
    color: #475569;
    white-space: nowrap;
}

.two-col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 2rem;
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
    color: #4f46e5;
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
    color: #475569;
    margin-bottom: 10px;
}

.result-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 2rem;
}

.result-score {
    font-size: 34px;
    font-weight: 700;
    color: #4f46e5;
    margin-bottom: 6px;
}

.muted-box {
    margin-top: 10px;
    border-radius: 12px;
    background: #f7f8fc;
    border: 1px solid #eef0f8;
    padding: 12px 14px;
    font-size: 13px;
    color: #334155;
    line-height: 1.6;
}

.muted-label {
    display: block;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .7px;
    text-transform: uppercase;
    color: #4f46e5;
    margin-bottom: 5px;
}

.chart-card canvas {
    margin-top: 4px;
}

.empty-text {
    font-size: 13px;
    color: #475569;
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
    .two-col,
    .result-grid,
    .info-kpi { grid-template-columns: 1fr; }
}

.dashboard-two-column{
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 2rem;
}

.dashboard-two-column .card{
    height: 100%;
}

@media (max-width:768px){
    .dashboard-two-column{
        grid-template-columns:1fr;
    }
}

</style>

<main class="dash-wrap" role="main" aria-label="Student Dashboard Utama">
    <div class="dash-container">

        @php
            $certificateReadyCount = $courses->where('can_get_certificate', true)->count();
        @endphp

        <div class="dash-header flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <p class="dash-eyebrow">Student Portal</p>
                <h1 class="dash-title">Student Dashboard</h1>
            </div>
            @if(auth()->user()->peminatan)
            <div class="px-4 py-2 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-900 shadow-xs max-w-md">
                <span class="font-bold text-amber-950 block">Initial Registered Interest:</span>
                <span class="font-medium text-amber-800">{{ auth()->user()->peminatan }}</span>
                <span class="ml-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-200 text-amber-900 inline-block">Competency Pending</span>
            </div>
            @endif
        </div>

        @if(session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error" role="alert">{{ session('error') }}</div>
        @endif

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
                <div class="stat-label">Certificates</div>
                <div class="stat-value">{{ $certificateReadyCount }}</div>
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

        <div class="main-grid">
            <div class="card">
                <div class="section-header">
                    <h2 class="section-title">Continue Learning</h2>
                    <a href="{{ route('student.courses.index') }}" class="section-meta" aria-label="Lihat semua course Anda">View all →</a>
                </div>

                @if($courses->count() > 0)
                    <div class="courses-grid">
                        @foreach($courses->take(4) as $course)
                            @php $progress = $course->progress ?? 0; @endphp

                            <div class="course-card">
                                <div class="course-name">{{ $course->name }}</div>
                                <div class="course-instructor">{{ $course->user->name ?? 'Unknown Lecturer' }}</div>

                                <div class="progress-wrap">
                                    <div class="progress-top">
                                        <span class="progress-label">Progress</span>
                                        <span class="progress-percent">{{ $progress }}%</span>
                                    </div>

                                    <div class="progress-track" role="progressbar" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100" aria-label="Progres course {{ $course->name }}">
                                        <div class="progress-fill" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>

                                @if($course->can_get_certificate ?? false)
                                    <span class="status-badge status-ready">Certificate Ready</span>
                                @else
                                    <span class="status-badge status-progress">In Progress</span>
                                @endif

                                <div class="actions">
                                    <a href="{{ route('student.courses.show', $course->id) }}" class="btn btn-primary" aria-label="Lanjutkan course {{ $course->name }}">
                                        Continue
                                    </a>

                                    @if($course->can_get_certificate ?? false)
                                        <a href="{{ route('student.certificate.show', $course->id) }}" class="btn btn-secondary" aria-label="Lihat sertifikat course {{ $course->name }}">
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

            <div class="side-stack">
                <div class="card">
                    <div class="section-header">
                        <h2 class="section-title">Certificates</h2>
                        <a href="{{ route('student.certificate.index') }}" class="section-meta" aria-label="Buka halaman sertifikat">Open →</a>
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
                            No certificates ready yet. Complete the final quiz and wait for admin verification.
                        @endif
                    </div>
                </div>

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

                        @if(!empty($latestEssayAnswer))
                            <li class="activity-item">
                                <div class="activity-left">
                                    <span class="activity-dot" style="background:#8b5cf6;"></span>
                                    <div class="activity-text">Essay graded by lecturer</div>
                                </div>
                                <div class="activity-meta">Score: {{ $latestEssayAnswer->score }}</div>
                            </li>
                        @endif

                        <li class="activity-item">
                            <div class="activity-left">
                                <span class="activity-dot" style="background:#3b82f6;"></span>
                                <div class="activity-text">Logged into LMS</div>
                            </div>
                            <div class="activity-meta">Today</div>
                        </li>
                    </ul>
                </div>

                <div class="card chart-card">
                    <div class="section-header">
                        <h2 class="section-title">Learning Progress</h2>
                    </div>
                    <canvas id="progressChart" height="120" role="img" aria-label="Grafik Progres Pembelajaran Mingguan"></canvas>
                </div>
            </div>
        </div>

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
                                    <span style="margin-left:8px; font-size:11px; color:#166534; font-weight:700;">
                                        Final Quiz
                                    </span>
                                @endif
                            </div>
                            <div class="item-sub">{{ $quiz->approved_questions_count ?? 0 }} approved question(s)</div>
                            <a href="{{ route('student.quiz.show', $quiz->id) }}" class="btn btn-primary" aria-label="Kerjakan kuis {{ $quiz->title }}">
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
                    <a href="{{ route('student.results.index') }}" class="section-meta" aria-label="Lihat semua hasil kuis Anda">View all →</a>
                </div>

                @if(($latestQuizResults ?? collect())->count() > 0)

                    @foreach($latestQuizResults as $quiz)

                        <div class="result-score">
                            {{ $quiz->score }}
                        </div>

                        <div class="muted-box">
                            <span class="muted-label">
                                Status
                            </span>

                            Quiz result verified by admin.
                        </div>

                        @if(!$loop->last)
                            <hr style="margin:15px 0;">
                        @endif

                    @endforeach
                @elseif(!empty($pendingQuiz))
                    <div class="result-score" style="font-size:24px; color:#b45309;">Pending</div>
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
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('progressChart');
if (ctx) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'],
            datasets: [{
                label: 'Learning Progress',
                data: [60, 65, 70, 80, 85],
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79,70,229,0.07)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#4f46e5',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#475569',
                        font: { family: 'Inter', size: 12 }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { color: '#475569', font: { family: 'Inter', size: 11 } }
                },
                y: {
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { color: '#475569', font: { family: 'Inter', size: 11 } }
                }
            }
        }
    });
}
</script>
</x-app-layout>
