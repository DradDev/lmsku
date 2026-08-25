<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.compro-dash {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #0f172a;
    padding-bottom: 3rem;
}

.compro-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    transition: box-shadow 0.2s ease, border-color 0.2s ease;
}

.compro-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
}

.kpi-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}

.progress-track {
    width: 100%;
    height: 8px;
    background: #f1f5f9;
    border-radius: 999px;
    overflow: hidden;
}

.progress-bar-segment {
    height: 100%;
    transition: width 0.3s ease;
}
</style>

<div class="compro-dash max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    {{-- ALERT MESSAGES --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 text-xs font-bold shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- TOP HEADER --}}
    <div class="compro-card p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="text-[11px] font-extrabold uppercase tracking-wider text-blue-600 mb-1">
                Administrator Portal • COMPRO LMS
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                Ringkasan Sistem Akademik
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Selamat datang kembali, <span class="font-bold text-slate-700">{{ Auth::user()->name }}</span>. Pantau kondisi operasional dan aktivitas pembelajaran secara menyeluruh.
            </p>
        </div>

        <div class="flex items-center gap-3">
            @if($activeTerm)
                <div class="flex items-center gap-2 px-3.5 py-2 bg-blue-50/70 border border-blue-100 rounded-xl text-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-bold text-blue-950">{{ $activeTerm->name }}</span>
                    <span class="text-blue-600 font-semibold">• TA {{ $activeTerm->academic_year }}</span>
                </div>
            @else
                <div class="px-3.5 py-2 bg-amber-50 border border-amber-200 rounded-xl text-xs font-bold text-amber-800">
                    Belum Ada Semester Aktif
                </div>
            @endif
        </div>
    </div>

    {{-- ROW 1: 4 KPI CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- KPI 1: TOTAL PENGGUNA --}}
        <div class="kpi-card flex items-center justify-between">
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pengguna</div>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($totalUsers) }}</div>
                <div class="text-[11px] text-slate-500 mt-0.5 font-medium">Semua Akun Terdaftar</div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>

        {{-- KPI 2: COURSE AKTIF --}}
        <div class="kpi-card flex items-center justify-between">
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Course Aktif</div>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($activeCoursesCount) }}</div>
                <div class="text-[11px] text-slate-500 mt-0.5 font-medium">Rombel & Pelatihan Berjalan</div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
        </div>

        {{-- KPI 3: PROJECT AKTIF --}}
        <div class="kpi-card flex items-center justify-between">
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Project Aktif</div>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($activeProjectsCount) }}</div>
                <div class="text-[11px] text-slate-500 mt-0.5 font-medium">Studi Kasus Industri Tayang</div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
        </div>

        {{-- KPI 4: MAHASISWA AKTIF --}}
        <div class="kpi-card flex items-center justify-between">
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Mahasiswa Aktif</div>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($activeStudentsCount) }}</div>
                <div class="text-[11px] text-slate-500 mt-0.5 font-medium">Mahasiswa Terverifikasi</div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- MAIN 2-COLUMN WORKSPACE (60% KIRI : 40% KANAN) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- LEFT COLUMN (7 COLS / ~60%) --}}
        <div class="lg:col-span-7 space-y-6">

            {{-- 1. GRAFIK RINGKAS PROGRESS PENYELESAIAN BELAJAR --}}
            <div class="compro-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Progress Penyelesaian Belajar</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Tingkat kelulusan dan aktivitas belajar mahasiswa di seluruh kelas</p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-extrabold text-blue-600 font-mono">{{ $avgSystemProgress }}%</span>
                        <div class="text-[10px] text-slate-400 font-bold uppercase">Rata-rata Kemajuan</div>
                    </div>
                </div>

                {{-- SEGMENTED PROGRESS BAR --}}
                @php
                    $pctCompleted = $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100) : 0;
                    $pctInProgress = $totalEnrollments > 0 ? round(($inProgressEnrollments / $totalEnrollments) * 100) : 0;
                    $pctNotStarted = $totalEnrollments > 0 ? round(($notStartedEnrollments / $totalEnrollments) * 100) : 0;
                @endphp
                <div class="progress-track flex mb-4">
                    <div class="progress-bar-segment bg-emerald-500" style="width: {{ $pctCompleted }}%;" title="Selesai: {{ $pctCompleted }}%"></div>
                    <div class="progress-bar-segment bg-blue-500" style="width: {{ $pctInProgress }}%;" title="Sedang Belajar: {{ $pctInProgress }}%"></div>
                    <div class="progress-bar-segment bg-slate-200" style="width: {{ $pctNotStarted }}%;" title="Belum Mulai: {{ $pctNotStarted }}%"></div>
                </div>

                {{-- 3 STATUS BOXES --}}
                <div class="grid grid-cols-3 gap-3">
                    <div class="p-3 bg-emerald-50/60 rounded-xl border border-emerald-100 text-center">
                        <div class="text-sm font-extrabold text-emerald-800">{{ number_format($completedEnrollments) }}</div>
                        <div class="text-[11px] font-semibold text-emerald-600 mt-0.5">Selesai ({{ $pctCompleted }}%)</div>
                    </div>
                    <div class="p-3 bg-blue-50/60 rounded-xl border border-blue-100 text-center">
                        <div class="text-sm font-extrabold text-blue-800">{{ number_format($inProgressEnrollments) }}</div>
                        <div class="text-[11px] font-semibold text-blue-600 mt-0.5">Sedang Belajar ({{ $pctInProgress }}%)</div>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-center">
                        <div class="text-sm font-extrabold text-slate-800">{{ number_format($notStartedEnrollments) }}</div>
                        <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Belum Mulai ({{ $pctNotStarted }}%)</div>
                    </div>
                </div>
            </div>

            {{-- 2. TOP 3 COURSE TERPOPULER (CARD-BASED, BUKAN TABEL PANJANG) --}}
            <div class="compro-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Course Terpopuler</h2>
                        <p class="text-xs text-slate-500 mt-0.5">3 Mata kuliah dan pelatihan dengan pendaftar terbanyak</p>
                    </div>
                    <a href="{{ route('admin.master-courses.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition">
                        Semua Master Courses ({{ $totalMasterCourses }}) →
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($topCourses as $idx => $tc)
                        <div class="p-4 rounded-xl border border-slate-200/80 bg-slate-50/40 hover:bg-white hover:border-slate-300 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center text-xs font-extrabold shrink-0">
                                    #{{ $idx + 1 }}
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900">{{ $tc->masterCourse->name ?? 'Mata Kuliah' }}</h3>
                                    <div class="text-xs text-slate-500 mt-0.5 flex flex-wrap items-center gap-2">
                                        <span>{{ $tc->section_name ?: 'Kelas Reguler' }}</span>
                                        <span>•</span>
                                        <span class="text-slate-600 font-semibold">{{ $tc->lecturer->name ?? 'Dosen/Vendor' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="shrink-0">
                                <span class="px-3 py-1.5 bg-blue-50 text-blue-700 font-mono font-bold text-xs rounded-xl border border-blue-100/80">
                                    {{ $tc->enrollments_count }} Mahasiswa
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-xs text-slate-400">
                            Belum ada data pendaftaran course aktif.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN (5 COLS / ~40%) --}}
        <div class="lg:col-span-5 space-y-6">

            {{-- 3. CARD SEMESTER AKTIF & KOMPOSISI USER --}}
            <div class="compro-card p-6">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-base font-extrabold text-slate-900">Semester & Pengguna</h2>
                    <a href="{{ route('admin.academic-terms.index') }}" class="text-xs font-bold text-blue-600 hover:underline">
                        Kelola Semester →
                    </a>
                </div>

                @if($activeTerm)
                    <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl mb-4">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-slate-900 text-xs">{{ $activeTerm->name }}</span>
                            <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-extrabold">🟢 Aktif</span>
                        </div>
                        <div class="text-[11px] text-slate-500 mt-1">
                            Tipe: <span class="font-bold uppercase text-slate-700">{{ $activeTerm->term_type }}</span> • Tahun Ajaran {{ $activeTerm->academic_year }}
                        </div>
                    </div>
                @else
                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-xs font-semibold mb-4">
                        Belum ada semester akademik yang diaktifkan.
                    </div>
                @endif

                {{-- 3 METRIC BOXES --}}
                <div class="grid grid-cols-3 gap-2.5">
                    <div class="p-3 bg-blue-50/60 rounded-xl border border-blue-100 text-center">
                        <div class="text-base font-extrabold text-blue-900 font-mono">{{ number_format($studentCount) }}</div>
                        <div class="text-[11px] font-semibold text-blue-600 mt-0.5">Mahasiswa</div>
                    </div>
                    <div class="p-3 bg-indigo-50/60 rounded-xl border border-indigo-100 text-center">
                        <div class="text-base font-extrabold text-indigo-900 font-mono">{{ number_format($lecturerCount) }}</div>
                        <div class="text-[11px] font-semibold text-indigo-600 mt-0.5">Dosen</div>
                    </div>
                    <div class="p-3 bg-purple-50/60 rounded-xl border border-purple-100 text-center">
                        <div class="text-base font-extrabold text-purple-900 font-mono">{{ number_format($vendorCount) }}</div>
                        <div class="text-[11px] font-semibold text-purple-600 mt-0.5">Mitra</div>
                    </div>
                </div>
            </div>

            {{-- 4. AKTIVITAS TERBARU (RECENT ACTIVITY FEED) --}}
            <div class="compro-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-extrabold text-slate-900">Aktivitas Terbaru</h2>
                    <span class="text-[11px] font-bold text-slate-400">Real-time</span>
                </div>

                <div class="space-y-4">
                    @forelse($recentActivities as $activity)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 shrink-0 mt-0.5">
                                @if($activity['type'] === 'user')
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                @elseif($activity['type'] === 'quiz')
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                @else
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-bold text-slate-900 truncate">{{ $activity['title'] }}</div>
                                <div class="text-[11px] text-slate-500 truncate mt-0.5">{{ $activity['subtitle'] }}</div>
                            </div>
                            <span class="text-[10px] text-slate-400 font-semibold shrink-0">
                                {{ $activity['time']->diffForHumans() }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-6 text-xs text-slate-400">
                            Belum ada aktivitas terbaru yang tercatat.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>
</x-app-layout>