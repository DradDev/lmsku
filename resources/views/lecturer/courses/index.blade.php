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
    font-weight: 700;
    color: #344054;
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
    color: #312e81;
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
    color: #4338ca;
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
    color: #344054;
    font-weight: 500;
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
    background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%);
    color: #fff;
    box-shadow: 0 4px 14px rgba(67, 56, 202, 0.25);
}
.btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(67, 56, 202, 0.35); }

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
    color: #344054;
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
    color: #312e81;
    background: #eef2ff;
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
    color: #344054;
    font-weight: 500;
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
    color: #344054;
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

<main class="page-wrap" role="main" aria-label="Manajemen Kelas Pembelajaran Dosen" x-data="{ tab: 'active' }">
    <div class="page-container">

        @php
            $allCourses = $activeCourses->concat($bankCourses);
            $totalCourses = $allCourses->count();
            $totalMaterials = $allCourses->sum(fn($course) => $course->materials ? $course->materials->count() : 0);
            $totalQuizzes = $allCourses->sum(fn($course) => $course->quizzes ? $course->quizzes->count() : 0);
            $totalStudents = $allCourses->sum(function($course) {
                if (isset($course->students) && $course->students) return $course->students->count();
                if (isset($course->enrollments) && $course->enrollments) return $course->enrollments->count();
                return 0;
            });
        @endphp

        <div class="page-header">
            <div>
                <p class="page-eyebrow">Dosen Pengampu Portal &bull; Manajemen Kelas Pembelajaran</p>
                <h1 class="page-title">Daftar Kelas Pembelajaran Dosen</h1>
                <p class="page-sub">Kelola materi modul, bank kuis (harian/akhir), dan kelulusan sertifikat mahasiswa di kelas Anda.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success flex items-center gap-3" role="alert">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-700 text-white font-bold text-xs" aria-hidden="true">✓</span>
                <div>
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="stat-strip">
            <div class="stat-card">
                <div class="stat-label">Total Kelas</div>
                <div class="stat-value">{{ $totalCourses }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Materi Pembelajaran</div>
                <div class="stat-value">{{ $totalMaterials }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Bank Kuis</div>
                <div class="stat-value">{{ $totalQuizzes }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Mahasiswa Terdaftar</div>
                <div class="stat-value">{{ $totalStudents }}</div>
            </div>
        </div>

        <div class="tabs-nav" role="tablist" aria-label="Tab Pilihan Kelas Dosen">
            <button class="tab-btn" role="tab" :aria-selected="tab === 'active'" aria-controls="tab-active-panel" :class="{ 'active': tab === 'active' }" @click="tab = 'active'">
                Kelas Aktif ({{ $activeCourses->count() }})
            </button>
            <button class="tab-btn" role="tab" :aria-selected="tab === 'bank'" aria-controls="tab-bank-panel" :class="{ 'active': tab === 'bank' }" @click="tab = 'bank'">
                Arsip / Bank Kelas ({{ $bankCourses->count() }})
            </button>
        </div>

        <div id="tab-active-panel" role="tabpanel" aria-label="Daftar Kelas Aktif" x-show="tab === 'active'">
            <div class="courses-grid">
                @if(isset($groupedOfferings) && $groupedOfferings->isNotEmpty())
                    @foreach($groupedOfferings as $masterCourseId => $offeringsGroup)
                        @php
                            $firstOffering = $offeringsGroup->first();
                            $totalGroupStudents = $offeringsGroup->sum(fn($o) => $o->enrollments ? $o->enrollments->count() : 0);
                        @endphp
                        <div class="course-card">
                            <div class="course-top">
                                <span class="course-tag">{{ $firstOffering->academicTerm->name ?? 'Semester Aktif' }}</span>
                                <span class="course-badge text-indigo-900 bg-indigo-100 border-indigo-300 font-extrabold">
                                    {{ $offeringsGroup->count() }} Rombel Kelas
                                </span>
                            </div>

                            <h2 class="course-name">{{ $firstOffering->name }}</h2>

                            <!-- List Pill Kelas Pararel (Kelas A, B, C) yang Diampu Dosen -->
                            <div class="my-2 flex flex-wrap gap-1.5" style="display: flex; flex-wrap: wrap; gap: 6px; margin: 8px 0;">
                                @foreach($offeringsGroup as $offeringItem)
                                    <a href="{{ route('lecturer.courses.show', $offeringItem->id) }}" 
                                       aria-label="Buka rombel {{ $offeringItem->section_name ?: 'Kelas ' . $loop->iteration }} untuk {{ $firstOffering->name }}"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-900 border border-indigo-200 hover:bg-indigo-100 transition"
                                       style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; background: #eef2ff; color: #312e81; border: 1px solid #c7d2fe; text-decoration: none;">
                                        <span>📌 {{ $offeringItem->section_name ?: 'Kelas ' . $loop->iteration }}</span>
                                        <span style="font-size: 10px; background: #c7d2fe; color: #1e1b4b; padding: 1px 6px; border-radius: 999px; font-weight: 800;">
                                            {{ $offeringItem->enrollments ? $offeringItem->enrollments->count() : 0 }} Mhs
                                        </span>
                                    </a>
                                @endforeach
                            </div>

                            <p class="course-desc">{{ $firstOffering->description ?: 'Pengelolaan materi pembelajaran, bank kuis, dan kelulusan sertifikat.' }}</p>

                            <div class="stats-row">
                                <div class="stat-mini">
                                    <div class="stat-mini-label">Materials</div>
                                    <div class="stat-mini-value">{{ $firstOffering->materials ? $firstOffering->materials->count() : 0 }}</div>
                                </div>
                                <div class="stat-mini">
                                    <div class="stat-mini-label">Quizzes</div>
                                    <div class="stat-mini-value">{{ $firstOffering->quizzes ? $firstOffering->quizzes->count() : 0 }}</div>
                                </div>
                                <div class="stat-mini">
                                    <div class="stat-mini-label">Total Mhs</div>
                                    <div class="stat-mini-value">{{ $totalGroupStudents }}</div>
                                </div>
                            </div>

                            <!-- TOMBOL TUNGGAL GERBANG KELAS DOSEN -->
                            <div style="margin-top: auto;">
                                <a href="{{ route('lecturer.courses.show', $firstOffering->id) }}" 
                                   aria-label="Buka gerbang kelas {{ $firstOffering->name }}"
                                   class="btn btn-primary w-full text-center">
                                    🚀 Buka Gerbang Kelas
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                @forelse($activeCourses as $course)
                    <div class="course-card">
                        <div class="course-top">
                            <span class="course-tag">{{ $course->academicTerm->name ?? 'Semester Aktif' }}</span>
                            <span class="course-badge text-emerald-900 bg-emerald-100 border-emerald-300 font-bold">Aktif</span>
                        </div>

                        <h2 class="course-name">{{ $course->name }}</h2>

                        <div class="text-xs text-indigo-900 mb-3 font-extrabold flex items-center gap-1.5">
                            <span>⚡ Certificate Threshold:</span>
                            <span class="bg-indigo-100 text-indigo-900 px-2 py-0.5 rounded-md font-extrabold border border-indigo-200">{{ $course->certificate_threshold ?? 75 }}%</span>
                        </div>

                        <p class="course-desc">{{ $course->description ?: 'Pengelolaan materi pembelajaran, bank kuis, dan kelulusan sertifikat.' }}</p>

                        <div class="stats-row">
                            <div class="stat-mini">
                                <div class="stat-mini-label">Materials</div>
                                <div class="stat-mini-value">{{ $course->materials ? $course->materials->count() : 0 }}</div>
                            </div>
                            <div class="stat-mini">
                                <div class="stat-mini-label">Quizzes</div>
                                <div class="stat-mini-value">{{ $course->quizzes ? $course->quizzes->count() : 0 }}</div>
                            </div>
                            <div class="stat-mini">
                                <div class="stat-mini-label">Students</div>
                                <div class="stat-mini-value">{{ isset($course->students) && $course->students ? $course->students->count() : ($course->enrollments ? $course->enrollments->count() : 0) }}</div>
                            </div>
                        </div>

                        <!-- TOMBOL TUNGGAL GERBANG KELAS DOSEN SANGAT RAPI -->
                        <div style="margin-top: auto;">
                            <a href="{{ route('lecturer.courses.show', $course->id) }}" 
                               aria-label="Buka gerbang kelas {{ $course->name }}"
                               class="btn btn-primary w-full text-center">
                                🚀 Buka Gerbang Kelas
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <h3 style="font-size: 18px; font-weight: 800; color: #101828;">Belum Ada Kelas Aktif</h3>
                        <p style="font-size: 13px; color: #344054; font-weight: 500;">Mata kuliah dan penawaran kelas akan disiapkan dan ditugaskan oleh Admin.</p>
                    </div>
                @endforelse
                @endif
            </div>
        </div>

        <div id="tab-bank-panel" role="tabpanel" aria-label="Daftar Arsip Kelas" x-show="tab === 'bank'" style="display: none;">
            <div class="courses-grid">
                @forelse($bankCourses as $course)
                    <div class="course-card">
                        <div class="course-top">
                            <span class="course-tag">{{ $course->academicTerm->name ?? 'Semester Lalu' }}</span>
                            <span class="course-badge text-slate-900 bg-slate-100 border-slate-300 font-bold">Arsip</span>
                        </div>

                        <h2 class="course-name">{{ $course->name }}</h2>

                        <div class="text-xs text-slate-700 mb-3 font-bold">
                            Threshold: {{ $course->certificate_threshold ?? 75 }}%
                        </div>

                        <p class="course-desc">{{ $course->description ?: 'Pengelolaan materi pembelajaran dan bank kuis.' }}</p>

                        <div class="stats-row">
                            <div class="stat-mini">
                                <div class="stat-mini-label">Materials</div>
                                <div class="stat-mini-value">{{ $course->materials ? $course->materials->count() : 0 }}</div>
                            </div>
                            <div class="stat-mini">
                                <div class="stat-mini-label">Quizzes</div>
                                <div class="stat-mini-value">{{ $course->quizzes ? $course->quizzes->count() : 0 }}</div>
                            </div>
                            <div class="stat-mini">
                                <div class="stat-mini-label">Students</div>
                                <div class="stat-mini-value">{{ isset($course->students) && $course->students ? $course->students->count() : ($course->enrollments ? $course->enrollments->count() : 0) }}</div>
                            </div>
                        </div>

                        <div style="margin-top: auto;">
                            <a href="{{ route('lecturer.courses.show', $course->id) }}" 
                               aria-label="Buka gerbang kelas terarsip {{ $course->name }}"
                               class="btn btn-primary w-full text-center">
                                🚀 Buka Gerbang Kelas
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <h3 style="font-size: 18px; font-weight: 800; color: #101828;">Belum Ada Kelas Terarsip</h3>
                        <p style="font-size: 13px; color: #344054; font-weight: 500;">Seluruh kelas aktif yang telah selesai akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</main>
</x-app-layout>
