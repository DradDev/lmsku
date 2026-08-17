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
    color: #6b21a8;
    background: #f3e8ff;
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
    color: #6b21a8;
    margin-bottom: 6px;
}

.page-title {
    font-size: 28px;
    font-weight: 800;
    color: #101828;
    letter-spacing: -0.5px;
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
    font-weight: 700;
    padding: 10px 18px;
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
    background: linear-gradient(135deg, #6b21a8 0%, #7e22ce 100%);
    color: #fff;
    box-shadow: 0 4px 14px rgba(107, 33, 168, 0.25);
}
.btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(107, 33, 168, 0.35); }

.stat-strip {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 2rem;
}

.stat-card {
    background: #fff;
    border: 1px solid #e4e7ec;
    border-radius: 16px;
    padding: 1.25rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}

.stat-label {
    font-size: 11px;
    font-weight: 700;
    color: #667085;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 24px;
    font-weight: 800;
    color: #101828;
    margin-top: 4px;
}

.courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.5rem;
}

.course-card {
    background: #fff;
    border: 1px solid #e4e7ec;
    border-radius: 20px;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    transition: all 0.2s;
}

.course-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
}

.course-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.course-tag {
    font-size: 11px;
    font-weight: 700;
    color: #6b21a8;
    background: #f3e8ff;
    padding: 3px 10px;
    border-radius: 100px;
}

.course-badge {
    font-size: 11px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 100px;
    border: 1px solid transparent;
}

.course-name {
    font-size: 17px;
    font-weight: 800;
    color: #101828;
    margin-bottom: 6px;
    line-height: 1.35;
}

.course-desc {
    font-size: 13px;
    color: #667085;
    line-height: 1.5;
    margin-bottom: 1.25rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.stats-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    padding: 10px;
    border-radius: 12px;
    margin-bottom: 1.25rem;
}

.stat-mini-label {
    font-size: 10px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
}

.stat-mini-value {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
}

.empty-state {
    grid-column: 1 / -1;
    background: rgba(255,255,255,0.94);
    border: 1.5px dashed #d0d5dd;
    border-radius: 22px;
    padding: 4rem 2rem;
    text-align: center;
}
</style>

<div class="page-wrap" x-data="{ tab: 'active' }">
    <div class="page-container">

        @php
            $totalPrograms = $masterCourses->count();
            $totalMaterials = $masterCourses->sum(fn($mc) => $mc->materials ? $mc->materials->count() : 0);
            $totalQuizzes = $masterCourses->sum(fn($mc) => $mc->quizzes ? $mc->quizzes->count() : 0);
            $totalStudents = $masterCourses->sum(function($mc) {
                return $mc->courses->sum(fn($c) => $c->enrollments ? $c->enrollments->count() : 0);
            });
        @endphp

        <div class="page-header">
            <div>
                <p class="page-eyebrow">Author Mitra Vendor Portal &bull; Manajemen Sertifikasi Industri 3NF</p>
                <h1 class="page-title">Daftar Program Sertifikasi Mitra Vendor</h1>
                <p class="page-sub">Kelola kurikulum induk, modul materi terpusat, bank kuis evaluasi, dan angkatan batch mahasiswa secara mandiri.</p>
            </div>

            <a href="{{ route('vendor.courses.create') }}" class="btn btn-primary">
                + Buat Program Sertifikasi Baru
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
                <div class="stat-label">Program Sertifikasi</div>
                <div class="stat-value">{{ $totalPrograms }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Materi Kurikulum</div>
                <div class="stat-value">{{ $totalMaterials }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Bank Kuis & Soal</div>
                <div class="stat-value">{{ $totalQuizzes }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Mahasiswa Terdaftar</div>
                <div class="stat-value">{{ $totalStudents }}</div>
            </div>
        </div>

        <div class="tabs-nav">
            <button class="tab-btn" :class="{ 'active': tab === 'active' }" @click="tab = 'active'">
                Program Aktif ({{ $activeMasterCourses->count() }})
            </button>
            <button class="tab-btn" :class="{ 'active': tab === 'bank' }" @click="tab = 'bank'">
                Draft / Arsip ({{ $archivedMasterCourses->count() }})
            </button>
        </div>

        <!-- TAB 1: ACTIVE PROGRAMS -->
        <div x-show="tab === 'active'">
            <div class="courses-grid">
                @forelse($activeMasterCourses as $mc)
                    @php
                        $latestBatch = $mc->courses->where('is_archived', false)->first() ?? $mc->courses->first();
                        $mcStudentCount = $mc->courses->sum(fn($c) => $c->enrollments ? $c->enrollments->count() : 0);
                    @endphp
                    <div class="course-card">
                        <div class="course-top">
                            <span class="course-tag">{{ $mc->category->name ?? 'Vendor Certified' }}</span>
                            <span class="course-badge text-purple-700 bg-purple-50 border-purple-200 font-bold">
                                {{ $mc->courses->count() }} Angkatan Batch
                            </span>
                        </div>

                        <div class="course-name">{{ $mc->name }}</div>

                        <div class="text-xs text-purple-700 mb-2 font-bold flex items-center gap-1.5">
                            <span>Certificate Threshold:</span>
                            <span class="bg-purple-100 text-purple-900 px-2 py-0.5 rounded-md font-extrabold">{{ $mc->certificate_threshold ?? 75 }}%</span>
                        </div>

                        <!-- Pill Daftar Angkatan Batch yang Terdaftar -->
                        <div class="my-2 flex flex-wrap gap-1.5" style="display: flex; flex-wrap: wrap; gap: 6px; margin: 8px 0;">
                            @foreach($mc->courses as $batchItem)
                                <a href="{{ route('vendor.courses.show', $batchItem->id) }}" 
                                   class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold {{ $batchItem->is_archived ? 'bg-slate-100 text-slate-600 border-slate-200' : 'bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100' }} transition"
                                   style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; text-decoration: none;">
                                    <span>{{ $batchItem->batch_name ?: 'Batch ' . $loop->iteration }}</span>
                                    <span style="font-size: 10px; background: rgba(107, 33, 168, 0.12); color: #6b21a8; padding: 1px 6px; border-radius: 999px; font-weight: 800;">
                                        {{ $batchItem->enrollments ? $batchItem->enrollments->count() : 0 }} Mhs
                                    </span>
                                </a>
                            @endforeach
                        </div>

                        <p class="course-desc">{{ $mc->description ?: 'Pengelolaan kurikulum materi modul, bank kuis evaluasi, dan kelulusan sertifikat industri.' }}</p>

                        <div class="stats-row">
                            <div class="stat-mini">
                                <div class="stat-mini-label">Materials</div>
                                <div class="stat-mini-value">{{ $mc->materials ? $mc->materials->count() : 0 }}</div>
                            </div>
                            <div class="stat-mini">
                                <div class="stat-mini-label">Quizzes</div>
                                <div class="stat-mini-value">{{ $mc->quizzes ? $mc->quizzes->count() : 0 }}</div>
                            </div>
                            <div class="stat-mini">
                                <div class="stat-mini-label">Total Mhs</div>
                                <div class="stat-mini-value">{{ $mcStudentCount }}</div>
                            </div>
                        </div>

                        <div style="margin-top: auto;">
                            @if($latestBatch)
                                <a href="{{ route('vendor.courses.show', $latestBatch->id) }}" class="btn btn-primary w-full text-center">
                                    Kelola Program & Angkatan
                                </a>
                            @else
                                <a href="{{ route('vendor.courses.create') }}" class="btn btn-primary w-full text-center">
                                    Buka Batch Perdana
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <h3 style="font-size: 18px; font-weight: 800; color: #1e293b;">Belum Ada Program Sertifikasi Aktif</h3>
                        <p style="font-size: 13px; color: #64748b; margin-top: 4px;">Klik tombol "+ Buat Program Sertifikasi Baru" di atas untuk menerbitkan silabus mandiri pertama Anda.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- TAB 2: BANK / ARCHIVED PROGRAMS -->
        <div x-show="tab === 'bank'" style="display: none;">
            <div class="courses-grid">
                @forelse($archivedMasterCourses as $mc)
                    @php
                        $latestBatch = $mc->courses->first();
                        $mcStudentCount = $mc->courses->sum(fn($c) => $c->enrollments ? $c->enrollments->count() : 0);
                    @endphp
                    <div class="course-card">
                        <div class="course-top">
                            <span class="course-tag">{{ $mc->category->name ?? 'Draft Vendor' }}</span>
                            <span class="course-badge text-slate-700 bg-slate-100 border-slate-200">Arsip / Draft</span>
                        </div>

                        <div class="course-name">{{ $mc->name }}</div>

                        <div class="text-xs text-slate-500 mb-3 font-semibold">
                            Threshold: {{ $mc->certificate_threshold ?? 75 }}%
                        </div>

                        <p class="course-desc">{{ $mc->description ?: 'Pengelolaan materi pembelajaran dan bank kuis.' }}</p>

                        <div class="stats-row">
                            <div class="stat-mini">
                                <div class="stat-mini-label">Materials</div>
                                <div class="stat-mini-value">{{ $mc->materials ? $mc->materials->count() : 0 }}</div>
                            </div>
                            <div class="stat-mini">
                                <div class="stat-mini-label">Quizzes</div>
                                <div class="stat-mini-value">{{ $mc->quizzes ? $mc->quizzes->count() : 0 }}</div>
                            </div>
                            <div class="stat-mini">
                                <div class="stat-mini-label">Total Mhs</div>
                                <div class="stat-mini-value">{{ $mcStudentCount }}</div>
                            </div>
                        </div>

                        <div style="margin-top: auto;">
                            @if($latestBatch)
                                <a href="{{ route('vendor.courses.show', $latestBatch->id) }}" class="btn btn-primary w-full text-center">
                                    Pratinjau & Buka Batch
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <h3 style="font-size: 18px; font-weight: 800; color: #1e293b;">Belum Ada Draft Program di Bank</h3>
                        <p style="font-size: 13px; color: #64748b; margin-top: 4px;">Seluruh program yang diarsipkan atau belum dipublikasikan akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
</x-app-layout>
