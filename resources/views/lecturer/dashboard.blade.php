<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.lecturer-dash {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #0f172a;
    padding-bottom: 3rem;
}

.lecturer-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    transition: box-shadow 0.2s ease, border-color 0.2s ease;
}

.lecturer-card:hover {
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
</style>

<div class="lecturer-dash max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    {{-- ALERT NOTIFICATIONS --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 text-xs font-bold shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs space-y-1">
            <div class="font-bold flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                Mohon periksa kembali:
            </div>
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- TOP HEADER --}}
    <div class="lecturer-card p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="text-[11px] font-extrabold uppercase tracking-wider text-blue-600 mb-1">
                Portal Pengajaran & Bimbingan Dosen
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                Ringkasan Perkuliahan & Aktivitas Kelas
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Selamat datang kembali, <span class="font-bold text-slate-700">{{ Auth::user()->name }}</span>. Pantau rombel aktif, materi silabus, dan evaluasi kuis mahasiswa.
            </p>
        </div>

        <div class="flex items-center gap-3">
            @if($activeTerm)
                <div class="flex items-center gap-2 px-3.5 py-2 bg-blue-50/70 border border-blue-100 rounded-xl text-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-bold text-blue-950">{{ $activeTerm->name }}</span>
                    <span class="text-blue-600 font-semibold">• TA {{ $activeTerm->academic_year }}</span>
                </div>
            @endif

            <a href="{{ route('lecturer.courses.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
                Kelola Kelas Saya
            </a>
        </div>
    </div>

    {{-- ROW 1: 4 KPI CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- KPI 1: KELAS & ROMBEL --}}
        <div class="kpi-card flex items-center justify-between">
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Kelas / Rombel</div>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($offerings->count()) }}</div>
                <div class="text-[11px] text-slate-500 mt-0.5 font-medium">Rombel Diampu Semester Ini</div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </div>

        {{-- KPI 2: TOTAL MAHASISWA --}}
        <div class="kpi-card flex items-center justify-between">
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Mahasiswa</div>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($totalStudents) }}</div>
                <div class="text-[11px] text-slate-500 mt-0.5 font-medium">Mahasiswa Terdaftar</div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>

        {{-- KPI 3: MATERI & KUIS --}}
        <div class="kpi-card flex items-center justify-between">
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Modul & Bank Kuis</div>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ $materials->count() }} / {{ $quizzes->count() }}</div>
                <div class="text-[11px] text-slate-500 mt-0.5 font-medium">{{ $materials->count() }} Materi • {{ $quizzes->count() }} Kuis</div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>

        {{-- KPI 4: PENGAJUAN REMEDIAL --}}
        <div class="kpi-card flex items-center justify-between">
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Pengajuan Remedial</div>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($pendingRetakesCount) }}</div>
                <div class="text-[11px] text-slate-500 mt-0.5 font-medium">Permohonan Retake Quiz</div>
            </div>
            <div class="w-11 h-11 rounded-xl {{ $pendingRetakesCount > 0 ? 'bg-amber-50 text-amber-600' : 'bg-purple-50 text-purple-600' }} flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </div>
        </div>
    </div>

    {{-- MAIN 2-COLUMN WORKSPACE (60% KIRI : 40% KANAN) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- LEFT COLUMN (7 COLS / ~60%) --}}
        <div class="lg:col-span-7 space-y-6">

            {{-- 1. KELAS & ROMBEL YANG DIAMPU --}}
            <div class="lecturer-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Kelas & Rombel yang Diampu</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Daftar rombel aktif pada semester perkuliahan berjalan</p>
                    </div>
                    <a href="{{ route('lecturer.courses.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition">
                        Lihat Semua Kelas ({{ $offerings->count() }}) →
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($offerings->take(4) as $offering)
                        <div class="p-4 rounded-xl border border-slate-200/80 bg-slate-50/40 hover:bg-white hover:border-slate-300 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-sm font-bold text-slate-900">
                                        {{ $offering->masterCourse->name ?? ($offering->name ?? 'Course') }}
                                    </h3>
                                    @if($offering->section_name)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-blue-100 text-blue-800">
                                            {{ $offering->section_name }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                    <span>{{ $offering->academicTerm->name ?? 'Semester Aktif' }}</span>
                                    <span>•</span>
                                    <span class="font-semibold text-slate-700">{{ $offering->enrollments ? $offering->enrollments->count() : 0 }} Mahasiswa</span>
                                    <span>•</span>
                                    <span class="text-emerald-700 font-semibold">KKM: {{ $offering->certificate_threshold ?? 75 }}%</span>
                                </div>
                            </div>

                            <a href="{{ route('lecturer.courses.show', $offering->id) }}" 
                               class="px-3.5 py-2 bg-white hover:bg-blue-50 border border-slate-200 hover:border-blue-300 text-slate-700 hover:text-blue-700 text-xs font-bold rounded-xl shadow-2xs transition shrink-0 self-start sm:self-center">
                                Buka Kelas →
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-8 text-xs text-slate-400">
                            Belum ada kelas rombel yang ditugaskan kepada Anda pada semester ini.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- 2. MODUL SILABUS TERBARU --}}
            <div class="lecturer-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Silabus & Materi Pembelajaran</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Modul pembelajaran yang telah diunggah untuk mahasiswa</p>
                    </div>
                    <a href="{{ route('lecturer.materials.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition">
                        Kelola Silabus →
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($materials->take(4) as $material)
                        <div class="p-3.5 rounded-xl border border-slate-200/80 bg-slate-50/40 hover:bg-white hover:border-slate-300 transition flex items-center justify-between gap-3">
                            <div class="flex items-start gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-slate-900 truncate">{{ $material->title }}</h4>
                                    <div class="flex items-center gap-2 text-[11px] text-slate-500 mt-0.5">
                                        <span class="truncate">{{ $material->masterCourse->name ?? ($material->course->name ?? 'Course') }}</span>
                                        <span>•</span>
                                        @if($material->is_all_classes)
                                            <span class="text-blue-600 font-semibold">Semua Rombel</span>
                                        @else
                                            <span class="text-slate-600 font-medium">Rombel Khusus</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-1.5 shrink-0">
                                <a href="{{ route('lecturer.materials.show', $material->id) }}" 
                                   class="px-2.5 py-1 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg text-xs font-semibold transition">
                                    Lihat
                                </a>
                                <a href="{{ route('lecturer.materials.edit', $material->id) }}" 
                                   class="px-2.5 py-1 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg text-xs font-bold transition">
                                    Edit
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-xs text-slate-400">
                            Belum ada materi pembelajaran yang diunggah.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN (5 COLS / ~40%) --}}
        <div class="lg:col-span-5 space-y-6">

            {{-- 3. PENGAJUAN REMEDIAL / RETAKE QUIZ --}}
            <div class="lecturer-card p-6">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full {{ $pendingRetakesCount > 0 ? 'bg-amber-500 animate-pulse' : 'bg-emerald-500' }}"></div>
                        <h2 class="text-base font-extrabold text-slate-900">Pengajuan Remedial</h2>
                    </div>
                    @if($pendingRetakesCount > 0)
                        <span class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-extrabold">
                            {{ $pendingRetakesCount }} Antrean
                        </span>
                    @endif
                </div>

                @if($pendingRetakesCount > 0)
                    <div class="space-y-2.5 mb-3">
                        @foreach($pendingRetakes as $retake)
                            <div class="p-3 bg-amber-50/60 border border-amber-200 rounded-xl flex items-center justify-between gap-2">
                                <div class="min-w-0">
                                    <div class="text-xs font-bold text-amber-950 truncate">{{ $retake->user->name ?? 'Mahasiswa' }}</div>
                                    <div class="text-[11px] text-amber-800 truncate">
                                        {{ $retake->quiz->title ?? 'Kuis' }} • {{ $retake->courseOffering->section_name ?? 'Rombel' }}
                                    </div>
                                </div>
                                <a href="{{ route('lecturer.courses.retake-requests.index', $retake->course_offering_id) }}" 
                                   class="px-2.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-[11px] font-bold rounded-lg shadow-xs transition shrink-0">
                                    Tinjau
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 bg-emerald-50/60 border border-emerald-200/80 rounded-xl flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-emerald-900">Semua Remedial Tuntas</div>
                            <div class="text-[11px] text-emerald-700 mt-0.5">Tidak ada antrean permohonan retake kuis dari mahasiswa.</div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- 4. BIMBINGAN PROYEK INDUSTRI --}}
            <div class="lecturer-card p-6">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-base font-extrabold text-slate-900">Bimbingan Proyek</h2>
                    <a href="{{ route('lecturer.projects.index') }}" class="text-xs font-bold text-blue-600 hover:underline">
                        Lihat Proyek →
                    </a>
                </div>

                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="text-xs font-bold text-slate-900">Proyek Industri Mahasiswa</div>
                        <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 text-[10px] font-extrabold">
                            {{ $supervisedProjectsCount }} Proyek
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Supervisi kemajuan mahasiswa pada studi kasus nyata mitra industri dan berikan penilaian akhir.
                    </p>
                    <a href="{{ route('lecturer.projects.index') }}" 
                       class="block text-center py-2 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-lg border border-slate-300 transition">
                        Masuk Portal Bimbingan Proyek
                    </a>
                </div>
            </div>

            {{-- 5. PINTASAN PENGAJARAN CEPAT --}}
            <div class="lecturer-card p-6">
                <h2 class="text-base font-extrabold text-slate-900 mb-3">Pintasan Cepat</h2>
                <div class="space-y-1.5">
                    <a href="{{ route('lecturer.courses.index') }}" 
                       class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 text-slate-700 text-xs font-bold transition">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Silabus & Bank Kuis
                        </span>
                        <span class="text-slate-400">→</span>
                    </a>
                    <a href="{{ route('lecturer.projects.index') }}" 
                       class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 text-slate-700 text-xs font-bold transition">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Bimbingan Proyek Industri
                        </span>
                        <span class="text-slate-400">→</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" 
                       class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 text-slate-700 text-xs font-bold transition">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profil Pengajar
                        </span>
                        <span class="text-slate-400">→</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>
</x-app-layout>