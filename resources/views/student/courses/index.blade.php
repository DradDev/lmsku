<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-wrap {
    min-height: 100vh;
    background: #f4f6fb;
    color: #1e2435;
    padding: 2.5rem 0 4rem;
    font-family: 'Inter', sans-serif;
}
.page-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
}
.page-top { margin-bottom: 1.8rem; }
.page-eyebrow {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #3b5bdb;
    margin-bottom: 5px;
}
.page-title {
    font-size: 26px;
    font-weight: 600;
    color: #1e2435;
}
.page-sub {
    font-size: 13px;
    color: #7b8399;
    margin-top: 3px;
}

.filter-tabs {
    display: flex;
    gap: 6px;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}
.filter-tab {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 16px;
    border-radius: 100px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    border: 1px solid #e8eaf2;
    background: #fff;
    color: #7b8399;
    font-family: 'Inter', sans-serif;
    transition: all 0.15s;
}
.filter-tab:hover {
    border-color: #c1cce8;
    color: #1e2435;
}
.filter-tab.active {
    background: #1e3a5f;
    border-color: #1e3a5f;
    color: #fff;
}
.filter-tab.active .tab-count {
    background: rgba(255,255,255,0.2);
    color: #fff;
}
.tab-count {
    background: #eef2ff;
    color: #2d5be3;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 100px;
}

.empty-state {
    background: #fff;
    border: 1.5px dashed #dde0ec;
    border-radius: 16px;
    padding: 4rem 2rem;
    text-align: center;
}
.empty-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: #eef2ff;
    border: 1px solid #c7d4f8;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}
.empty-title {
    font-size: 14px;
    font-weight: 600;
    color: #5a607a;
    margin-bottom: 5px;
}
.empty-sub { font-size: 13px; color: #b0b4c9; }

.courses-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

.course-card {
    background: #fff;
    border: 1px solid #e8eaf2;
    border-radius: 16px;
    padding: 1.4rem;
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.2s, transform 0.2s;
}
.course-card:hover {
    box-shadow: 0 6px 24px rgba(45,91,227,0.09);
    transform: translateY(-2px);
}

.course-badge {
    display: inline-flex;
    align-items: center;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 11px;
    border-radius: 100px;
    width: fit-content;
    margin-bottom: 12px;
    letter-spacing: 0.3px;
}
.badge-progress {
    background: #fffbeb;
    color: #92400e;
    border: 1px solid #fcd34d;
}
.badge-done {
    background: #edfaf4;
    color: #1a7a4a;
    border: 1px solid #a7e9c8;
}
.badge-new {
    background: #eef2ff;
    color: #2d5be3;
    border: 1px solid #c7d4f8;
}

.course-title {
    font-size: 15px;
    font-weight: 600;
    color: #1e2435;
    line-height: 1.4;
    margin-bottom: 6px;
}
.course-instructor {
    font-size: 12px;
    color: #9399b0;
    margin-bottom: 1.1rem;
    display: flex;
    align-items: center;
    gap: 5px;
}
.course-instructor svg { flex-shrink: 0; }

.progress-section { margin-bottom: 1.1rem; }
.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
}
.progress-text { font-size: 11px; color: #9399b0; font-weight: 500; }
.progress-pct  { font-size: 11px; font-weight: 600; color: #2d5be3; }
.progress-track {
    height: 5px;
    background: #eef0f8;
    border-radius: 100px;
    overflow: hidden;
}
.progress-fill {
    height: 100%;
    border-radius: 100px;
    background: linear-gradient(90deg, #2d5be3, #6366f1);
    transition: width 0.8s cubic-bezier(0.4,0,0.2,1);
}
.progress-fill.done {
    background: linear-gradient(90deg, #10b981, #059669);
}

.course-actions {
    margin-top: auto;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    padding-top: 1rem;
    border-top: 1px solid #f0f2f9;
}
.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    padding: 8px 14px;
    border-radius: 9px;
    text-decoration: none;
    transition: all 0.15s;
    cursor: pointer;
    border: none;
    font-family: 'Inter', sans-serif;
}
.btn-primary { background: #1e3a5f; color: #fff; }
.btn-primary:hover { background: #162d4a; }
.btn-cert {
    background: #edfaf4;
    color: #1a7a4a;
    border: 1px solid #a7e9c8;
}
.btn-cert:hover { background: #d4f5e5; }

@media (max-width: 1024px) { .courses-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 640px) {
    .page-container { padding: 0 1rem; }
    .courses-grid   { grid-template-columns: 1fr; }
}
</style>

<div class="page-wrap">
    <div class="page-container">

        <div class="page-top">
            <p class="page-eyebrow">Student Portal</p>
            <h1 class="page-title">My Courses</h1>
            <p class="page-sub">Browse and continue your enrolled learning journey.</p>
        </div>

        <div class="filter-tabs">
            <button class="filter-tab active" onclick="filterCourses('all', this)">
                All Courses
                <span class="tab-count" id="count-all">0</span>
            </button>

            <button class="filter-tab" onclick="filterCourses('progress', this)">
                In Progress
                <span class="tab-count" id="count-progress">0</span>
            </button>

            <button class="filter-tab" onclick="filterCourses('completed', this)">
                Completed
                <span class="tab-count" id="count-completed">0</span>
            </button>

            <button class="filter-tab" onclick="filterCourses('not-started', this)">
                Not Started
                <span class="tab-count" id="count-not-started">0</span>
            </button>
        </div>

        @if($courses->count() > 0)
            <div class="courses-grid" id="courses-grid">
                @foreach($courses as $course)
                    @php
                        $progress = $course->progress ?? 0;
                        $isCompleted = $course->is_completed ?? false;
                        $status = $isCompleted ? 'completed' : ($progress > 0 ? 'progress' : 'not-started');
                    @endphp

                    <div class="course-card" data-status="{{ $status }}">
                        @if($isCompleted)
                            <span class="course-badge badge-done">Completed</span>
                        @elseif($progress > 0)
                            <span class="course-badge badge-progress">In Progress</span>
                        @else
                            <span class="course-badge badge-new">Not Started</span>
                        @endif

                        <div class="course-title">{{ $course->name }}</div>

                        <div class="course-instructor">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            {{ $course->user->name ?? 'Unknown' }}
                        </div>

                        <div class="progress-section">
                            <div class="progress-header">
                                <span class="progress-text">Progress</span>
                                <span class="progress-pct">{{ $progress }}%</span>
                            </div>

                            <div class="progress-track">
                                <div class="progress-fill {{ $isCompleted ? 'done' : '' }}" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>

                        <div class="course-actions">
                            <a href="{{ route('student.courses.show', $course->id) }}" class="btn btn-primary">
                                {{ $isCompleted ? 'Review' : 'Continue' }}
                            </a>

                            @if($course->can_get_certificate ?? false)
                                <a href="{{ route('student.certificate.show', $course->id) }}" class="btn btn-cert">
                                    Certificate
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2d5be3" stroke-width="1.5">
                        <rect x="2" y="3" width="20" height="14" rx="2"/>
                        <path d="M8 21h8M12 17v4"/>
                    </svg>
                </div>

                <div class="empty-title">Kamu belum terdaftar di course apa pun.</div>
                <div class="empty-sub">Hubungi lecturer atau admin untuk mendaftar ke course.</div>
            </div>
        @endif

    </div>
</div>

<script>
function filterCourses(status, btn) {
    document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
    btn.classList.add('active');

    document.querySelectorAll('.course-card').forEach(card => {
        card.style.display = (status === 'all' || card.dataset.status === status) ? 'flex' : 'none';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.course-card');

    const counts = {
        all: cards.length,
        progress: 0,
        completed: 0,
        'not-started': 0
    };

    cards.forEach(card => {
        if (counts[card.dataset.status] !== undefined) {
            counts[card.dataset.status]++;
        }
    });

    document.getElementById('count-all').textContent = counts.all;
    document.getElementById('count-progress').textContent = counts.progress;
    document.getElementById('count-completed').textContent = counts.completed;
    document.getElementById('count-not-started').textContent = counts['not-started'];
});
</script>
</x-app-layout>
