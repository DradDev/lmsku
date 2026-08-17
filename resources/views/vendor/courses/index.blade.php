<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.vendor-wrap {
    min-height: 100vh;
    background: linear-gradient(180deg, #f8faff 0%, #f1f5f9 100%);
    color: #0f172a;
    padding: 2.5rem 0 4.5rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.vendor-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 1.75rem;
}

.page-header {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    margin-bottom: 2rem;
}

@media (min-width: 768px) {
    .page-header {
        flex-direction: row;
        align-items: flex-end;
        justify-content: space-between;
    }
}

.page-eyebrow {
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #7c3aed;
    margin-bottom: 6px;
}

.page-title {
    font-size: 26px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.5px;
    line-height: 1.25;
}

.page-sub {
    font-size: 13.5px;
    color: #64748b;
    margin-top: 6px;
    max-width: 680px;
    line-height: 1.5;
}

.btn-primary-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 700;
    padding: 11px 20px;
    border-radius: 14px;
    text-decoration: none;
    background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: 0 4px 14px rgba(124, 58, 237, 0.25);
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    white-space: nowrap;
}

.btn-primary-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(124, 58, 237, 0.35);
    color: #ffffff;
}

.stat-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-bottom: 2.25rem;
}

@media (min-width: 1024px) {
    .stat-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
}

.stat-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 1.25rem 1.4rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.2s ease;
}

.stat-box:hover {
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
}

.stat-box-label {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-box-value {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    margin-top: 4px;
}

.stat-box-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.program-cards-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}

@media (min-width: 768px) {
    .program-cards-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1200px) {
    .program-cards-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.program-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 22px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    display: flex;
    flex-direction: column;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.program-card:hover {
    border-color: #cbd5e1;
    transform: translateY(-3px);
    box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.07);
}

.program-badge-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-bottom: 12px;
}

.badge-category {
    font-size: 11px;
    font-weight: 800;
    color: #7c3aed;
    background: #f5f3ff;
    border: 1px solid #ede9fe;
    padding: 3px 10px;
    border-radius: 8px;
}

.badge-batches-count {
    font-size: 11px;
    font-weight: 800;
    color: #0f172a;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    padding: 3px 10px;
    border-radius: 8px;
}

.program-name {
    font-size: 17px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.35;
    margin-bottom: 8px;
}

.program-meta-chips {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 6px;
    margin-bottom: 12px;
}

.meta-chip {
    font-size: 11px;
    font-weight: 700;
    padding: 2.5px 8px;
    border-radius: 6px;
}

.program-desc {
    font-size: 12.5px;
    color: #64748b;
    line-height: 1.55;
    margin-bottom: 14px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.batch-box-container {
    background: #f8fafc;
    border: 1px solid #edf2f7;
    border-radius: 14px;
    padding: 10px 12px;
    margin-bottom: 14px;
}

.batch-box-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}

.batch-box-title {
    font-size: 10.5px;
    font-weight: 800;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.batch-pills-list {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.batch-pill-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 11.5px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s;
}

.batch-pill-active {
    background: #ffffff;
    color: #6d28d9;
    border: 1px solid #ddd6fe;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
}

.batch-pill-active:hover {
    background: #f5f3ff;
    border-color: #c4b5fd;
}

.batch-pill-archived {
    background: #f1f5f9;
    color: #64748b;
    border: 1px solid #e2e8f0;
}

.batch-pill-count {
    font-size: 10px;
    font-weight: 800;
    background: rgba(124, 58, 237, 0.1);
    color: #7c3aed;
    padding: 1px 6px;
    border-radius: 6px;
}

.metrics-strip {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    padding: 8px 10px;
    margin-bottom: 16px;
    text-align: center;
}

.metric-item-label {
    font-size: 10px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
}

.metric-item-val {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    margin-top: 2px;
}

.btn-card-action {
    display: block;
    width: 100%;
    text-align: center;
    padding: 10px 16px;
    border-radius: 12px;
    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none;
    background: #f5f3ff;
    color: #7c3aed;
    border: 1px solid #ddd6fe;
    transition: all 0.2s ease;
    margin-top: auto;
}

.btn-card-action:hover {
    background: #7c3aed;
    color: #ffffff;
    border-color: #7c3aed;
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);
}

.empty-state-box {
    grid-column: 1 / -1;
    background: #ffffff;
    border: 1.5px dashed #cbd5e1;
    border-radius: 24px;
    padding: 4rem 2rem;
    text-align: center;
}
</style>

<div class="vendor-wrap">
    <div class="vendor-container">

        @php
            $totalPrograms = $masterCourses->count();
            $totalMaterials = $masterCourses->sum(fn($mc) => $mc->materials ? $mc->materials->count() : 0);
            $totalQuizzes = $masterCourses->sum(fn($mc) => $mc->quizzes ? $mc->quizzes->count() : 0);
            $totalStudents = $masterCourses->sum(function($mc) {
                return $mc->courses->sum(fn($c) => $c->enrollments ? $c->enrollments->count() : 0);
            });
        @endphp

        <!-- HEADER -->
        <div class="page-header">
            <div>
                <p class="page-eyebrow">Portal Author Mitra Vendor &bull; Manajemen Sertifikasi Industri 3NF</p>
                <h1 class="page-title">Daftar Program Sertifikasi Industri</h1>
                <p class="page-sub">Kelola kurikulum induk terpusat, modul pembelajaran, bank kuis evaluasi, dan pelaksanaan angkatan batch secara mandiri.</p>
            </div>

            <a href="{{ route('vendor.courses.create') }}" class="btn-primary-action">
                <span>+ Buat Program Sertifikasi</span>
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-xs text-xs font-bold">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-600 text-white text-xs">✓</span>
                <div>
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- STATS STRIP -->
        <div class="stat-grid">
            <div class="stat-box">
                <div>
                    <div class="stat-box-label">Program Sertifikasi</div>
                    <div class="stat-box-value">{{ $totalPrograms }}</div>
                </div>
                <div class="stat-box-icon bg-purple-50 text-purple-600 border border-purple-100">
                    📜
                </div>
            </div>

            <div class="stat-box">
                <div>
                    <div class="stat-box-label">Modul Pembelajaran</div>
                    <div class="stat-box-value">{{ $totalMaterials }}</div>
                </div>
                <div class="stat-box-icon bg-indigo-50 text-indigo-600 border border-indigo-100">
                    📚
                </div>
            </div>

            <div class="stat-box">
                <div>
                    <div class="stat-box-label">Bank Kuis & Soal</div>
                    <div class="stat-box-value">{{ $totalQuizzes }}</div>
                </div>
                <div class="stat-box-icon bg-amber-50 text-amber-600 border border-amber-100">
                    📝
                </div>
            </div>

            <div class="stat-box">
                <div>
                    <div class="stat-box-label">Total Mahasiswa</div>
                    <div class="stat-box-value">{{ $totalStudents }}</div>
                </div>
                <div class="stat-box-icon bg-emerald-50 text-emerald-600 border border-emerald-100">
                    🎓
                </div>
            </div>
        </div>

        <!-- PROGRAM CARDS GRID (DIRECT CLEAN REPOSITORY VIEW) -->
        <div class="program-cards-grid">
            @forelse($masterCourses as $mc)
                @php
                    $latestBatch = $mc->courses->where('is_archived', false)->first() ?? $mc->courses->first();
                    $mcStudentCount = $mc->courses->sum(fn($c) => $c->enrollments ? $c->enrollments->count() : 0);
                @endphp
                <div class="program-card">
                    <div class="program-badge-row">
                        <span class="badge-category">{{ $mc->category->name ?? 'Sertifikasi Industri' }}</span>
                        <span class="badge-batches-count">
                            {{ $mc->courses->count() }} Angkatan Batch
                        </span>
                    </div>

                    <h3 class="program-name">{{ $mc->name }}</h3>

                    <div class="program-meta-chips">
                        <span class="meta-chip bg-slate-100 text-slate-700">
                            Level: <strong>{{ $mc->level ?? 'Beginner' }}</strong>
                        </span>
                        @if($mc->code)
                            <span class="meta-chip bg-slate-50 text-slate-500 border border-slate-200">
                                {{ $mc->code }}
                            </span>
                        @endif
                    </div>

                    <p class="program-desc">{{ $mc->description ?: 'Kurikulum sertifikasi terpusat dengan modul materi dan kuis terintegrasi blockchain.' }}</p>

                    <!-- BATCHES CONTAINER -->
                    <div class="batch-box-container">
                        <div class="batch-box-header">
                            <span class="batch-box-title">Angkatan Terdaftar:</span>
                            <span class="text-[11px] font-bold text-purple-700">{{ $mc->courses->count() }} Batch</span>
                        </div>
                        <div class="batch-pills-list">
                            @forelse($mc->courses as $batchItem)
                                <a href="{{ route('vendor.courses.show', $batchItem->id) }}" 
                                   class="batch-pill-item {{ $batchItem->is_archived ? 'batch-pill-archived' : 'batch-pill-active' }}"
                                   title="Buka {{ $batchItem->batch_name }}">
                                    <span>{{ $batchItem->batch_name ?: 'Batch ' . $loop->iteration }}</span>
                                    <span class="batch-pill-count">
                                        {{ $batchItem->enrollments ? $batchItem->enrollments->count() : 0 }} Mhs
                                    </span>
                                </a>
                            @empty
                                <span class="text-xs text-slate-400">Belum ada angkatan batch dibuka</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- METRICS -->
                    <div class="metrics-strip">
                        <div>
                            <div class="metric-item-label">Materi</div>
                            <div class="metric-item-val">{{ $mc->materials ? $mc->materials->count() : 0 }}</div>
                        </div>
                        <div>
                            <div class="metric-item-label">Kuis</div>
                            <div class="metric-item-val">{{ $mc->quizzes ? $mc->quizzes->count() : 0 }}</div>
                        </div>
                        <div>
                            <div class="metric-item-label">Total Peserta</div>
                            <div class="metric-item-val text-purple-700">{{ $mcStudentCount }}</div>
                        </div>
                    </div>

                    <div style="margin-top: auto;">
                        @if($latestBatch)
                            <a href="{{ route('vendor.courses.show', $latestBatch->id) }}" class="btn-card-action">
                                Kelola Program & Angkatan →
                            </a>
                        @else
                            <a href="{{ route('vendor.courses.create') }}" class="btn-card-action">
                                + Buka Batch Perdana
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state-box">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mx-auto mb-3 text-2xl">📜</div>
                    <h3 class="text-base font-extrabold text-slate-900">Belum Ada Program Sertifikasi</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Klik tombol "+ Buat Program Sertifikasi" di atas untuk menerbitkan kurikulum mandiri pertama Anda.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
</x-app-layout>
