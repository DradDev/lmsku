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

        .filter-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 2rem;
            overflow-x: auto;
            padding-bottom: 4px;
        }

        .filter-tab {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 100px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .filter-tab:hover {
            border-color: #cbd5e1;
            color: #334155;
        }

        .filter-tab.active {
            background: #3b5bdb;
            color: #ffffff;
            border-color: #3b5bdb;
            box-shadow: 0 4px 12px rgba(59, 91, 219, 0.25);
        }

        .tab-count {
            font-size: 11px;
            padding: 2px 7px;
            border-radius: 100px;
            background: rgba(0, 0, 0, 0.06);

        }

        .filter-tab.active .tab-count {
            background: rgba(255, 255, 255, 0.2);

        }

        .courses-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        .course-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            transition: all 0.25s ease;
        }

        .course-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
            border-color: #cbd5e1;
        }

        .badge-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            flex-wrap: wrap;
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
            font-size: 17px;
            font-weight: 700;
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
            justify-content: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            padding: 9px 18px;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: none;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: #fff;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.25);
            width: 100%;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%);
            box-shadow: 0 6px 18px rgba(79, 70, 229, 0.35);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #fff;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.25);
            width: 100%;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            box-shadow: 0 6px 18px rgba(16, 185, 129, 0.35);
        }

        .btn-cert {
            background: #ecfdf5;
            color: #047857;
            border: 1.5px solid #a7f3d0;
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.1);
            width: 100%;
        }

        .btn-cert:hover {
            background: #d1fae5;
            border-color: #6ee7b7;
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
            <div class="alert alert-success" style="padding: 12px 16px; background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; border-radius: 12px; margin-bottom: 1rem; font-size: 14px; font-weight: 600;">
                {{ session('success') }}
            </div>
            @endif

            @if (session('info'))
            <div class="alert alert-info" style="padding: 12px 16px; background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; border-radius: 12px; margin-bottom: 1rem; font-size: 14px; font-weight: 600;">
                {{ session('info') }}
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-error" style="padding: 12px 16px; background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; border-radius: 12px; margin-bottom: 1rem; font-size: 14px; font-weight: 600;">
                {{ session('error') }}
            </div>
            @endif

            <div class="page-top">
                <div>
                    <p class="page-eyebrow">COMPRO TEKKOM · Student Course</p>
                    <h1 class="page-title">Katalog Mata Kuliah Kompetensi</h1>
                    <p class="page-sub">
                        Pilih mata kuliah dan tentukan rombel kelas pararel (Kelas A, B, C, D) yang ingin Anda ikuti.
                    </p>
                </div>
            </div>

            <!-- Author Filter Dropdown Card -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 700; color: #1e2435; margin-bottom: 8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #2d5be3;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span>Daftar Dosen Pengampu / Author</span>
                </label>

                <select id="author-filter-select" onchange="applyFilters()" style="width: 100%; max-width: 480px; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 12px; font-size: 14px; font-weight: 600; color: #1e2435; background-color: #ffffff; cursor: pointer; outline: none;">
                    <option value="all">-- Semua Dosen Pengampu --</option>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}">{{ $author->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-tabs">
                <button class="filter-tab active" onclick="filterCourses('all', this)">
                    Semua Mata Kuliah
                    <span class="tab-count" id="count-all">0</span>
                </button>

                <button class="filter-tab" onclick="filterCourses('available', this)">
                    Tersedia
                    <span class="tab-count" id="count-available">0</span>
                </button>

                <button class="filter-tab" onclick="filterCourses('enrolled', this)">
                    Terdaftar
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

            @if($groupedCourses->count() > 0)
            <div class="courses-grid" id="courses-grid">
                @foreach($groupedCourses as $item)
                @php
                    $master = $item->master_course;
                    $offerings = $item->offerings;
                    $isEnrolled = $item->is_enrolled;
                    $enrolledOffering = $item->enrolled_offering;
                    $activeOffering = $item->active_offering;
                    $progress = $item->progress;
                    $isCompleted = $item->is_completed;

                    if (! $isEnrolled) {
                        $status = 'available';
                    } elseif ($isCompleted) {
                        $status = 'completed';
                    } elseif ($progress > 0) {
                        $status = 'progress';
                    } else {
                        $status = 'enrolled';
                    }

                    $lecturerIdsStr = $offerings->pluck('lecturer_id')->join(',');
                @endphp

                <div class="course-card" data-status="{{ $status }}" data-author-id="{{ $lecturerIdsStr }}">
                    <div class="badge-row">
                        @if($isEnrolled)
                            <span class="course-badge badge-enrolled">
                                ✓ Terdaftar di {{ $enrolledOffering->section_name }}
                            </span>
                        @else
                            <span class="course-badge badge-available">
                                Available
                            </span>
                        @endif

                        @if($isCompleted)
                            <span class="course-badge badge-completed">
                                Completed
                            </span>
                        @elseif($isEnrolled && $progress > 0)
                            <span class="course-badge badge-progress">
                                In Progress
                            </span>
                        @endif
                    </div>

                    <div class="course-title">
                        {{ $master->name }}
                    </div>

                    <p class="course-description">
                        {{ \Illuminate\Support\Str::limit($master->description, 110) }}
                    </p>

                    <!-- SECTION PILLS: PEMILIHAN KELAS PARAREL -->
                    <div style="margin-top: 4px; margin-bottom: 12px; padding: 10px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                        <div style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: flex; align-items: center; justify-content: space-between;">
                            <span>🎓 Rombel Kelas ({{ $offerings->count() }} Kelas):</span>
                            @if($isEnrolled && $enrolledOffering)
                                <span style="color: #059669; font-weight: 800; font-size: 10px;">{{ $enrolledOffering->section_name }}</span>
                            @endif
                        </div>

                        <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                            @foreach($offerings as $off)
                                @php
                                    $isThisEnrolled = $enrolledOffering && $enrolledOffering->id === $off->id;
                                    $isFull = !$off->hasAvailableCapacity();
                                    $capText = is_null($off->capacity) 
                                        ? '♾️ Kuota Unlimited' 
                                        : ($isFull ? '🔒 KUOTA PENUH' : 'Sisa: ' . max(0, $off->capacity - $off->enrollments_count));
                                @endphp
                                @if($isThisEnrolled)
                                    <div style="font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 8px; background: #d1fae5; color: #065f46; border: 1.5px solid #34d399;">
                                        📌 {{ $off->section_name }} <span style="font-weight: 400; opacity: 0.85;">({{ $off->lecturer->name ?? 'Dosen' }} | {{ $capText }})</span>
                                    </div>
                                @elseif($isFull)
                                    <div style="font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 8px; background: #f1f5f9; color: #94a3b8; border: 1px solid #cbd5e1;">
                                        🔒 {{ $off->section_name }} <span style="font-weight: 400;">({{ $off->lecturer->name ?? 'Dosen' }} | {{ $capText }})</span>
                                    </div>
                                @else
                                    <div style="font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 8px; background: #ffffff; color: #334155; border: 1px solid #cbd5e1;">
                                        📌 {{ $off->section_name }} <span style="font-weight: 400; opacity: 0.85;">({{ $off->lecturer->name ?? 'Dosen' }} | {{ $capText }})</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="course-meta">
                        <div class="course-meta-item">
                            <strong>Level:</strong>
                            <span>{{ $master->level ?? 'Beginner' }}</span>
                        </div>

                        <div class="course-meta-item">
                            <strong>Kategori:</strong>
                            <span>{{ $master->category->name ?? 'General' }}</span>
                        </div>
                    </div>

                    @if($isEnrolled)
                    <div class="progress-section">
                        <div class="progress-header">
                            <span class="progress-text">Progress Pembelajaran</span>
                            <span class="progress-pct">{{ $progress }}%</span>
                        </div>

                        <div class="progress-track">
                            <div class="progress-fill {{ $isCompleted ? 'done' : '' }}"
                                style="width: {{ $progress }}%">
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="course-actions">
                        @if($isEnrolled)
                            <a href="{{ route('student.courses.show', $enrolledOffering->id) }}"
                                class="btn btn-primary">
                                {{ $isCompleted ? 'Review Course' : 'Lanjut Belajar ('.$enrolledOffering->section_name.')' }}
                            </a>

                            @if($item->can_get_certificate)
                            <a href="{{ route('student.certificate.show', $enrolledOffering->id) }}"
                                class="btn btn-cert" style="margin-top: 6px;">
                                📜 Klaim Sertifikat
                            </a>
                            @endif
                        @else
                            @php
                                $availableOfferings = $offerings->filter(fn($o) => $o->hasAvailableCapacity());
                                $firstAvailable = $availableOfferings->first() ?? $activeOffering;
                            @endphp
                            @if($availableOfferings->count() > 0)
                                <form action="{{ route('student.courses.enroll', $firstAvailable->id) }}" method="POST" id="enroll-form-{{ $master->id }}" style="width: 100%;">
                                    @csrf
                                    <div style="margin-bottom: 8px;">
                                        <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Pilih Rombel Kelas:</label>
                                        <select onchange="document.getElementById('enroll-form-{{ $master->id }}').action = '/student/courses/' + this.value + '/enroll'" style="width: 100%; padding: 7px 10px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 12px; font-weight: 600; color: #1e293b; background: #ffffff; cursor: pointer; outline: none;">
                                            @foreach($offerings as $off)
                                                @php
                                                    $isFull = !$off->hasAvailableCapacity();
                                                    $optCapText = is_null($off->capacity) 
                                                        ? '♾️ Unlimited' 
                                                        : ($isFull ? '🔒 KELAS PENUH' : 'Sisa Kuota: ' . max(0, $off->capacity - $off->enrollments_count));
                                                @endphp
                                                <option value="{{ $off->id }}" {{ $isFull ? 'disabled style=color:#94a3b8;background:#f8fafc;' : '' }}>
                                                    {{ $isFull ? '🔒' : '📌' }} {{ $off->section_name }} — {{ $off->lecturer->name ?? 'Dosen' }} ({{ $optCapText }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-success">
                                        🎓 Ambil Kelas Ini
                                    </button>
                                </form>
                            @else
                                <div style="width: 100%;">
                                    <div style="font-size: 11px; font-weight: 700; color: #94a3b8; text-align: center; margin-bottom: 6px;">
                                        🔒 Seluruh Rombel Kelas Penuh
                                    </div>
                                    <button type="button" class="btn" disabled style="width: 100%; background: #e2e8f0; color: #64748b; cursor: not-allowed; opacity: 0.8;">
                                        🔒 Pendaftaran Ditutup
                                    </button>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 3rem; text-align: center;">
                <div class="empty-title" style="font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 8px;">
                    Belum ada mata kuliah yang tersedia.
                </div>

                <div class="empty-sub" style="font-size: 13px; color: #64748b;">
                    Mata kuliah akan muncul setelah Admin membuka rombel kelas pada semester aktif.
                </div>
            </div>
            @endif

        </div>
    </div>

    <script>
        let currentStatusFilter = 'all';

        function filterCourses(status, btn) {
            document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
            btn.classList.add('active');
            currentStatusFilter = status;
            applyFilters();
        }

        function applyFilters() {
            const selectedAuthorId = document.getElementById('author-filter-select').value;
            const cards = document.querySelectorAll('.course-card');

            cards.forEach(card => {
                const cardStatus = card.dataset.status;
                const cardAuthorIdStr = card.dataset.authorId || '';
                const authorIds = cardAuthorIdStr.split(',');

                let matchesStatus = false;
                if (currentStatusFilter === 'all') {
                    matchesStatus = true;
                } else if (currentStatusFilter === 'enrolled') {
                    matchesStatus = ['enrolled', 'progress', 'completed'].includes(cardStatus);
                } else {
                    matchesStatus = (cardStatus === currentStatusFilter);
                }

                let matchesAuthor = (selectedAuthorId === 'all') || authorIds.includes(selectedAuthorId);

                if (matchesStatus && matchesAuthor) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
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