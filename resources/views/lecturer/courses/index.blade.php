<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-wrap {
    min-height: 100vh;
    background: linear-gradient(180deg, #f8faff 0%, #f3f6fc 100%);
    color: #1e2435;
    padding: 2.5rem 0 4rem;
    font-family: 'Inter', sans-serif;
}

.tabs-nav {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    border-bottom: 1px solid #e4e7ec;
    padding-bottom: 1rem;
}

.tab-btn {
    background: none;
    border: none;
    font-size: 15px;
    font-weight: 600;
    color: #667085;
    cursor: pointer;
    padding: 8px 16px;
    border-radius: 8px;
    transition: all 0.2s;
}

.tab-btn:hover {
    color: #101828;
    background: #f8fafc;
}

.tab-btn.active {
    color: #4338ca;
    background: #eef2ff;
}

.page-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
}

.page-header {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
}

@media (min-width: 768px) {
    .page-header {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }
}

.page-eyebrow {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.3px;
    text-transform: uppercase;
    color: #4f46e5;
    margin-bottom: 6px;
}

.page-title {
    font-size: 30px;
    font-weight: 700;
    color: #101828;
}

.page-sub {
    font-size: 14px;
    color: #667085;
    margin-top: 6px;
    max-width: 720px;
}

.alert-success {
    padding: 12px 16px;
    border-radius: 14px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 1.5rem;
    background: #ecfdf3;
    border: 1px solid #abefc6;
    color: #067647;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    padding: 9px 16px;
    border-radius: 12px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    font-family: 'Inter', sans-serif;
    white-space: nowrap;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
}

.btn-primary {
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
    color: #fff;
    box-shadow: 0 4px 14px rgba(79, 70, 229, 0.25);
}
.btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(79, 70, 229, 0.35); }

.btn-solid {
    background: #1e293b;
    color: #fff;
    box-shadow: 0 4px 12px rgba(30, 41, 59, 0.15);
}
.btn-solid:hover { background: #0f172a; transform: translateY(-1px); }

.btn-outline {
    background: #fff;
    color: #475569;
    border: 1.5px solid #e2e8f0;
}
.btn-outline:hover { background: #f8fafc; border-color: #cbd5e1; transform: translateY(-1px); }

.btn-danger {
    background: #fef2f2;
    color: #dc2626;
    border: 1.5px solid #fecaca;
}
.btn-danger:hover { background: #fee2e2; border-color: #fca5a5; transform: translateY(-1px); }

.stat-strip {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 2rem;
}

@media (max-width: 1024px) {
    .stat-strip { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 640px) {
    .page-container { padding: 0 1rem; }
    .stat-strip { grid-template-columns: 1fr; }
}

.stat-card {
    background: rgba(255,255,255,0.9);
    border: 1px solid #e4e7ec;
    border-radius: 18px;
    padding: 1.1rem 1.2rem;
    box-shadow: 0 10px 30px rgba(16, 24, 40, 0.04);
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
    font-size: 30px;
    font-weight: 700;
    color: #101828;
}

.courses-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
}

@media (max-width: 1100px) {
    .courses-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 640px) {
    .courses-grid { grid-template-columns: 1fr; }
}

.course-card {
    background: rgba(255,255,255,0.94);
    backdrop-filter: blur(10px);
    border: 1px solid #e4e7ec;
    border-radius: 22px;
    padding: 1.4rem;
    display: flex;
    flex-direction: column;
    min-height: 320px;
    box-shadow: 0 14px 36px rgba(17, 24, 39, 0.05);
    transition: transform .18s ease, box-shadow .18s ease;
}

.course-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 18px 42px rgba(17, 24, 39, 0.08);
}

.course-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 14px;
}

.course-tag {
    display: inline-flex;
    align-items: center;
    font-size: 11px;
    font-weight: 700;
    padding: 5px 11px;
    border-radius: 999px;
    background: #eef2ff;
    color: #4338ca;
    border: 1px solid #c7d2fe;
}

.course-badge {
    display: inline-flex;
    align-items: center;
    font-size: 11px;
    font-weight: 700;
    padding: 5px 11px;
    border-radius: 999px;
    background: #f8fafc;
    color: #475467;
    border: 1px solid #e4e7ec;
}

.course-name {
    font-size: 20px;
    font-weight: 700;
    color: #101828;
    line-height: 1.35;
    margin-bottom: 10px;
}

.course-desc {
    font-size: 14px;
    color: #667085;
    line-height: 1.7;
    margin-bottom: 1.2rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}

.stats-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-bottom: 1.2rem;
}

.stat-mini {
    background: linear-gradient(180deg, #f8fafc 0%, #f2f4f7 100%);
    border: 1px solid #e4e7ec;
    border-radius: 14px;
    padding: 12px 10px;
    text-align: center;
}

.stat-mini-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: #98a2b3;
    margin-bottom: 5px;
}

.stat-mini-value {
    font-size: 22px;
    font-weight: 700;
    color: #111827;
}

.card-actions {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-top: auto;
}

.empty-state {
    grid-column: 1 / -1;
    background: rgba(255,255,255,0.94);
    border: 1.5px dashed #d0d5dd;
    border-radius: 22px;
    padding: 4rem 2rem;
    text-align: center;
}

.empty-state h3 {
    font-size: 22px;
    font-weight: 700;
    color: #101828;
    margin-bottom: 8px;
}

.empty-state p {
    font-size: 14px;
    color: #98a2b3;
    margin-bottom: 1.2rem;
}
</style>

<div class="page-wrap" x-data="{ tab: 'active' }">
    <div class="page-container">

        @php
            $allCourses = $activeCourses->concat($bankCourses);
            $totalCourses = $allCourses->count();
            $totalMaterials = $allCourses->sum(fn($course) => $course->materials->count());
            $totalQuizzes = $allCourses->sum(fn($course) => $course->quizzes->count());
            $totalStudents = $allCourses->sum(fn($course) => $course->students->count());
        @endphp

        <div class="page-header">
            <div>
                <p class="page-eyebrow">Author / Instructor Portal</p>
                <h1 class="page-title">Competency Courses Management</h1>
                <p class="page-sub">Manage competency courses, learning modules, evaluation quizzes, and enrolled students.</p>
            </div>

            <a href="{{ route('lecturer.courses.create') }}" class="btn btn-primary">
                + Add New Course
            </a>
        </div>

        @if(session('success'))
            <div class="alert-success flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-600 text-white font-bold text-xs">✓</span>
                <div>
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="stat-strip">
            <div class="stat-card">
                <div class="stat-label">Total Courses</div>
                <div class="stat-value">{{ $totalCourses }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Materials</div>
                <div class="stat-value">{{ $totalMaterials }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Quizzes</div>
                <div class="stat-value">{{ $totalQuizzes }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Students</div>
                <div class="stat-value">{{ $totalStudents }}</div>
            </div>
        </div>

        <div class="tabs-nav">
            <button class="tab-btn" :class="{ 'active': tab === 'active' }" @click="tab = 'active'">
                🎓 Kelas Saya ({{ isset($assignedOfferings) && $assignedOfferings->count() > 0 ? $assignedOfferings->count() : $activeCourses->count() }})
            </button>
            @if(isset($masterCourses) && $masterCourses->isNotEmpty())
            <button class="tab-btn" :class="{ 'active': tab === 'master' }" @click="tab = 'master'">
                📚 Master Kurikulum ({{ $masterCourses->count() }})
            </button>
            @endif
            <button class="tab-btn" :class="{ 'active': tab === 'bank' }" @click="tab = 'bank'">
                Course Bank ({{ $bankCourses->count() }})
            </button>
        </div>

        <div x-show="tab === 'active'">
            <div class="courses-grid">
                @if(isset($assignedOfferings) && $assignedOfferings->isNotEmpty())
                    @foreach($assignedOfferings as $offering)
                        <div class="course-card">
                            <div class="course-top">
                                <span class="course-tag">Kelas Pararel</span>
                                <span class="course-badge text-indigo-700 bg-indigo-50 border-indigo-200">
                                    {{ $offering->academicTerm->name ?? '2025/2026 Ganjil' }}
                                </span>
                            </div>

                            <div class="course-name">{{ $offering->name }}</div>

                            <div class="text-xs text-slate-500 mb-2 font-medium">
                                Threshold Sertifikat: {{ $offering->certificate_threshold ?? 60 }}%
                            </div>

                            <p class="course-desc">{{ $offering->description ?: 'Pengelolaan kegiatan belajar mengajar untuk kelas ini.' }}</p>

                            <div class="stats-row">
                                <div class="stat-mini">
                                    <div class="stat-mini-label">Modul</div>
                                    <div class="stat-mini-value">{{ $offering->materials->count() }}</div>
                                </div>
                                <div class="stat-mini">
                                    <div class="stat-mini-label">Quiz</div>
                                    <div class="stat-mini-value">{{ $offering->quizzes->count() }}</div>
                                </div>
                                <div class="stat-mini">
                                    <div class="stat-mini-label">Siswa</div>
                                    <div class="stat-mini-value">{{ $offering->enrollments->count() }}</div>
                                </div>
                            </div>

                            <div class="card-actions mt-auto" style="display: flex; flex-direction: column; gap: 8px;">
                                <a href="{{ route('lecturer.courses.show', $offering->master_course_id ?? 1) }}" class="btn btn-primary text-center w-full">
                                    🎓 Buka Kelas Saya
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                @forelse($activeCourses as $course)
                    <div class="course-card">
                        <div class="course-top">
                            <span class="course-tag">Course</span>
                            <span class="course-badge text-green-700 bg-green-50 border-green-200">Active</span>
                        </div>

                        <div class="course-name">{{ $course->name }}</div>

                        @if($course->start_date || $course->end_date)
                            <div class="text-xs text-slate-500 mb-2 font-medium">
                                {{ $course->start_date ? \Carbon\Carbon::parse($course->start_date)->format('d M Y') : '-' }} s/d {{ $course->end_date ? \Carbon\Carbon::parse($course->end_date)->format('d M Y') : '-' }}
                            </div>
                        @endif

                        <div class="text-xs text-indigo-600 mb-3 font-semibold">
                            Certificate Threshold: {{ $course->certificate_threshold ?? 60 }}
                        </div>

                        <p class="course-desc">{{ $course->description ?: 'Belum ada deskripsi untuk mata kuliah ini.' }}</p>

                        <div class="stats-row">
                            <div class="stat-mini">
                                <div class="stat-mini-label">Materials</div>
                                <div class="stat-mini-value">{{ $course->materials->count() }}</div>
                            </div>
                            <div class="stat-mini">
                                <div class="stat-mini-label">Quizzes</div>
                                <div class="stat-mini-value">{{ $course->quizzes->count() }}</div>
                            </div>
                            <div class="stat-mini">
                                <div class="stat-mini-label">Students</div>
                                <div class="stat-mini-value">{{ $course->students->count() }}</div>
                            </div>
                        </div>

                        <div class="card-actions mt-auto" style="display: flex; flex-direction: column; gap: 8px;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                <a href="{{ route('lecturer.courses.show', $course->id) }}" class="btn btn-solid text-center w-full">View</a>
                                <a href="{{ route('lecturer.courses.edit', $course->id) }}" class="btn btn-outline text-center w-full">Edit</a>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                <form action="{{ route('lecturer.courses.archive', $course->id) }}" method="POST" onsubmit="return confirm('Archive course ini?')" class="w-full">
                                    @csrf
                                    <button type="submit" class="btn btn-outline w-full text-amber-600 border-amber-200 hover:bg-amber-50">Archive</button>
                                </form>
                                <form action="{{ route('lecturer.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus course ini?')" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-full">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <h3>Belum ada mata kuliah aktif</h3>
                        <p>Mulai buat course pertama Anda untuk mengelola materi, quiz, dan aktivitas belajar.</p>
                        <a href="{{ route('lecturer.courses.create') }}" class="btn btn-primary">
                            Tambah mata kuliah pertama
                        </a>
                    </div>
                @endforelse
                @endif
            </div>
        </div>

        @if(isset($masterCourses) && $masterCourses->isNotEmpty())
        <div x-show="tab === 'master'" style="display: none;">
            <div class="courses-grid">
                @foreach($masterCourses as $master)
                    <div class="course-card border-indigo-100 bg-indigo-50/20">
                        <div class="course-top">
                            <span class="course-tag">Master Kurikulum</span>
                            <span class="course-badge text-indigo-700 bg-indigo-100 border-indigo-200">
                                {{ $master->category->name ?? 'Umum' }}
                            </span>
                        </div>

                        <div class="course-name">{{ $master->name }}</div>
                        <div class="text-xs text-slate-500 mb-2 font-mono">Kode: {{ $master->code ?? '-' }}</div>
                        <p class="course-desc">{{ $master->description ?: 'Kurikulum master untuk seluruh kelas.' }}</p>

                        <div class="stats-row">
                            <div class="stat-mini">
                                <div class="stat-mini-label">Modul</div>
                                <div class="stat-mini-value">{{ $master->materials->count() }}</div>
                            </div>
                            <div class="stat-mini">
                                <div class="stat-mini-label">Quiz</div>
                                <div class="stat-mini-value">{{ $master->quizzes->count() }}</div>
                            </div>
                            <div class="stat-mini">
                                <div class="stat-mini-label">Level</div>
                                <div class="stat-mini-value text-xs font-bold">{{ $master->level ?? 'Beginner' }}</div>
                            </div>
                        </div>

                        <div class="card-actions mt-auto">
                            <a href="{{ route('lecturer.courses.show', $master->id) }}" class="btn btn-outline text-center w-full">
                                📝 Kelola Modul & Bank Quiz
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div x-show="tab === 'bank'" style="display: none;">
            <div class="courses-grid">
                @forelse($bankCourses as $course)
                    <div class="course-card opacity-90">
                        <div class="course-top">
                            <span class="course-tag">Bank</span>
                            @php
                                $badgeClass = $course->status === 'expired' ? 'text-red-700 bg-red-50 border-red-200' : 'text-slate-700 bg-slate-100 border-slate-300';
                                $badgeText = ucfirst($course->status ?? 'archived');
                            @endphp
                            <span class="course-badge {{ $badgeClass }}">{{ $badgeText }}</span>
                        </div>

                        <div class="course-name">{{ $course->name }}</div>

                        @if($course->start_date || $course->end_date)
                            <div class="text-xs text-slate-500 mb-2 font-medium">
                                {{ $course->start_date ? \Carbon\Carbon::parse($course->start_date)->format('d M Y') : '-' }} s/d {{ $course->end_date ? \Carbon\Carbon::parse($course->end_date)->format('d M Y') : '-' }}
                            </div>
                        @endif

                        <div class="text-xs text-indigo-600 mb-3 font-semibold">
                            Certificate Threshold: {{ $course->certificate_threshold ?? 60 }}
                        </div>

                        <p class="course-desc">{{ $course->description ?: 'Belum ada deskripsi untuk mata kuliah ini.' }}</p>

                        <div class="stats-row">
                            <div class="stat-mini">
                                <div class="stat-mini-label">Materials</div>
                                <div class="stat-mini-value">{{ $course->materials->count() }}</div>
                            </div>
                            <div class="stat-mini">
                                <div class="stat-mini-label">Quizzes</div>
                                <div class="stat-mini-value">{{ $course->quizzes->count() }}</div>
                            </div>
                            <div class="stat-mini">
                                <div class="stat-mini-label">Students</div>
                                <div class="stat-mini-value">{{ $course->students->count() }}</div>
                            </div>
                        </div>

                        <div class="card-actions mt-auto" style="display: flex; flex-direction: column; gap: 8px;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                <a href="{{ route('lecturer.courses.show', $course->id) }}" class="btn btn-solid text-center w-full">View</a>
                                <form action="{{ route('lecturer.courses.archive', $course->id) }}" method="POST" onsubmit="return confirm('Aktifkan kembali course ini dengan start date hari ini? Seluruh riwayat mahasiswa, materi, dan soal akan tetap utuh.')" class="w-full">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-full">Activate</button>
                                </form>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr; gap: 8px;">
                                <form action="{{ route('lecturer.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus permanent course ini?')" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline text-rose-600 border-rose-200 hover:bg-rose-50 w-full text-center">Delete Course</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <h3>Bank Course Kosong</h3>
                        <p>Belum ada course yang diarsipkan atau expired.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
</x-app-layout>
