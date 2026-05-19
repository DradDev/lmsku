<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

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

        .page-top {
            margin-bottom: 1.8rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }

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

        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            font-size: 13px;
        }

        .alert-success {
            background: #edfaf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
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
            background: rgba(255, 255, 255, 0.2);
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

        .empty-sub {
            font-size: 13px;
            color: #b0b4c9;
        }

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
            box-shadow: 0 6px 24px rgba(45, 91, 227, 0.09);
            transform: translateY(-2px);
        }

        .badge-row {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 12px;
        }

        .course-badge {
            display: inline-flex;
            align-items: center;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 11px;
            border-radius: 100px;
            width: fit-content;
            letter-spacing: 0.3px;
        }

        .badge-enrolled {
            background: #edfaf4;
            color: #1a7a4a;
            border: 1px solid #a7e9c8;
        }

        .badge-available {
            background: #eef2ff;
            color: #2d5be3;
            border: 1px solid #c7d4f8;
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

        .course-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e2435;
            line-height: 1.4;
            margin-bottom: 8px;
        }

        .course-description {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        .course-meta {
            font-size: 12px;
            color: #5f667c;
            display: grid;
            gap: 6px;
            margin-bottom: 1.1rem;
        }

        .course-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .progress-section {
            margin-bottom: 1.1rem;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .progress-text {
            font-size: 11px;
            color: #9399b0;
            font-weight: 500;
        }

        .progress-pct {
            font-size: 11px;
            font-weight: 600;
            color: #2d5be3;
        }

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
            transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
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

        .btn-primary {
            background: #1e3a5f;
            color: #fff;
        }

        .btn-primary:hover {
            background: #162d4a;
        }

        .btn-success {
            background: #16a34a;
            color: #fff;
        }

        .btn-success:hover {
            background: #15803d;
        }

        .btn-muted {
            background: #f3f4f6;
            color: #4b5563;
        }

        .btn-cert {
            background: #edfaf4;
            color: #1a7a4a;
            border: 1px solid #a7e9c8;
        }

        .btn-cert:hover {
            background: #d4f5e5;
        }

        @media (max-width: 1024px) {
            .courses-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .page-container {
                padding: 0 1rem;
            }

            .page-top {
                flex-direction: column;
            }

            .courses-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="page-wrap">
        <div class="page-container">

            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
            @endif

            <div class="page-top">
                <div>
                    <p class="page-eyebrow">Student Portal</p>
                    <h1 class="page-title">Daftar Course</h1>
                    <p class="page-sub">
                        Lihat semua course yang tersedia, ambil course baru, atau lanjutkan course yang sudah kamu ikuti.
                    </p>
                </div>
            </div>

            <div class="filter-tabs">
                <button class="filter-tab active" onclick="filterCourses('all', this)">
                    All Courses
                    <span class="tab-count" id="count-all">0</span>
                </button>

                <button class="filter-tab" onclick="filterCourses('available', this)">
                    Available
                    <span class="tab-count" id="count-available">0</span>
                </button>

                <button class="filter-tab" onclick="filterCourses('enrolled', this)">
                    Enrolled
                    <span class="tab-count" id="count-enrolled">0</span>
                </button>

                <button class="filter-tab" onclick="filterCourses('progress', this)">
                    In Progress
                    <span class="tab-count" id="count-progress">0</span>
                </button>

                <button class="filter-tab" onclick="filterCourses('completed', this)">
                    Completed
                    <span class="tab-count" id="count-completed">0</span>
                </button>
            </div>

            @if($courses->count() > 0)
            <div class="courses-grid" id="courses-grid">
                @foreach($courses as $course)
                @php
                $alreadyEnrolled = in_array($course->id, $enrolledCourseIds ?? []);

                $progress = $alreadyEnrolled ? ($course->progress ?? 0) : 0;
                $isCompleted = $alreadyEnrolled && ($course->is_completed ?? false);

                if (! $alreadyEnrolled) {
                $status = 'available';
                } elseif ($isCompleted) {
                $status = 'completed';
                } elseif ($progress > 0) {
                $status = 'progress';
                } else {
                $status = 'enrolled';
                }
                @endphp

                <div class="course-card" data-status="{{ $status }}">
                    <div class="badge-row">
                        @if($alreadyEnrolled)
                        <span class="course-badge badge-enrolled">
                            Sudah Diambil
                        </span>
                        @else
                        <span class="course-badge badge-available">
                            Available
                        </span>
                        @endif

                        @if($isCompleted)
                        <span class="course-badge badge-done">
                            Completed
                        </span>
                        @elseif($alreadyEnrolled && $progress > 0)
                        <span class="course-badge badge-progress">
                            In Progress
                        </span>
                        @endif
                    </div>

                    <div class="course-title">
                        {{ $course->name }}
                    </div>

                    <p class="course-description">
                        {{ \Illuminate\Support\Str::limit($course->description, 120) }}
                    </p>

                    <div class="course-meta">
                        <div class="course-meta-item">
                            <strong>Lecturer:</strong>
                            <span>{{ $course->user->name ?? 'Unknown' }}</span>
                        </div>

                        <div class="course-meta-item">
                            <strong>Level:</strong>
                            <span>{{ $course->level ?? '-' }}</span>
                        </div>

                        <div class="course-meta-item">
                            <strong>Durasi:</strong>
                            <span>{{ $course->duration_weeks ?? '-' }} minggu</span>
                        </div>

                        <div class="course-meta-item">
                            <strong>Student:</strong>
                            <span>{{ $course->students_count ?? 0 }} terdaftar</span>
                        </div>
                    </div>

                    @if($alreadyEnrolled)
                    <div class="progress-section">
                        <div class="progress-header">
                            <span class="progress-text">Progress</span>
                            <span class="progress-pct">{{ $progress }}%</span>
                        </div>

                        <div class="progress-track">
                            <div class="progress-fill {{ $isCompleted ? 'done' : '' }}"
                                style="width: {{ $progress }}%">
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="progress-section">
                        <div class="progress-header">
                            <span class="progress-text">Status</span>
                            <span class="progress-pct">Belum diambil</span>
                        </div>

                        <div class="progress-track">
                            <div class="progress-fill" style="width: 0%"></div>
                        </div>
                    </div>
                    @endif

                    <div class="course-actions">
                        @if($alreadyEnrolled)
                        <a href="{{ route('student.courses.show', $course) }}"
                            class="btn btn-primary">
                            {{ $isCompleted ? 'Review Course' : 'Lanjut Belajar' }}
                        </a>

                        @if($course->can_get_certificate ?? false)
                        <a href="{{ route('student.certificate.show', $course->id) }}"
                            class="btn btn-cert">
                            Certificate
                        </a>
                        @endif
                        @else
                        <form action="{{ route('student.courses.enroll', $course) }}" method="POST">
                            @csrf

                            <button type="submit" class="btn btn-success">
                                Ambil Course
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2d5be3" stroke-width="1.5">
                        <rect x="2" y="3" width="20" height="14" rx="2" />
                        <path d="M8 21h8M12 17v4" />
                    </svg>
                </div>

                <div class="empty-title">
                    Belum ada course yang tersedia.
                </div>

                <div class="empty-sub">
                    Course akan muncul setelah lecturer membuat course.
                </div>
            </div>
            @endif

        </div>
    </div>

    <script>
        function filterCourses(status, btn) {
            document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
            btn.classList.add('active');

            document.querySelectorAll('.course-card').forEach(card => {
                if (status === 'all') {
                    card.style.display = 'flex';
                    return;
                }

                if (status === 'enrolled') {
                    card.style.display = ['enrolled', 'progress', 'completed'].includes(card.dataset.status) ?
                        'flex' :
                        'none';
                    return;
                }

                card.style.display = card.dataset.status === status ? 'flex' : 'none';
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.course-card');

            const counts = {
                all: cards.length,
                available: 0,
                enrolled: 0,
                progress: 0,
                completed: 0
            };

            cards.forEach(card => {
                const status = card.dataset.status;

                if (status === 'available') {
                    counts.available++;
                }

                if (['enrolled', 'progress', 'completed'].includes(status)) {
                    counts.enrolled++;
                }

                if (status === 'progress') {
                    counts.progress++;
                }

                if (status === 'completed') {
                    counts.completed++;
                }
            });

            document.getElementById('count-all').textContent = counts.all;
            document.getElementById('count-available').textContent = counts.available;
            document.getElementById('count-enrolled').textContent = counts.enrolled;
            document.getElementById('count-progress').textContent = counts.progress;
            document.getElementById('count-completed').textContent = counts.completed;
        });
    </script>
</x-app-layout>