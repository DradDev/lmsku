<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.vendor-dash {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #0f172a;
    padding-bottom: 3rem;
}

.compro-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    transition: box-shadow 0.2s ease, border-color 0.2s ease;
}

.compro-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
}

.row-item {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 12px 16px;
    transition: all 0.15s ease;
}

.row-item:hover {
    background: #ffffff;
    border-color: #cbd5e1;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
}
</style>

<div class="vendor-dash max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5">

    {{-- ALERT NOTIFICATIONS --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 text-xs font-bold shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl flex items-center gap-3 text-xs font-bold shadow-xs">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- 1. TOP HEADER (MINIMALIS & ELEGAN) --}}
    <div class="compro-card p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="text-[11px] font-extrabold uppercase tracking-wider text-purple-700 mb-1">
                Portal Mitra Vendor • COMPRO LMS
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                Dashboard Mitra Industri
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Selamat datang, <span class="font-bold text-slate-700">{{ Auth::user()->name }}</span>. Pantau kursus sertifikasi aktif dan seleksi talenta mahasiswa.
            </p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('vendor.courses.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold rounded-xl shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                + Kursus Baru
            </a>
            <a href="{{ route('vendor.projects.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
                <svg class="w-4 h-4 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                + Publikasikan Proyek
            </a>
        </div>
    </div>

    {{-- 2. COMPACT KPI STRIP (SATU BARIS BERSIH & RAMPING) --}}
    <div class="compro-card p-5 grid grid-cols-2 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-slate-100 gap-4 md:gap-0">
        {{-- KPI 1: KURSUS SERTIFIKASI --}}
        <div class="px-3 md:px-5 py-1">
            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Kursus Sertifikasi</div>
            <div class="text-2xl font-extrabold text-slate-900 mt-0.5 font-mono">{{ number_format($totalCourses) }}</div>
            <div class="text-[11px] text-purple-700 font-semibold mt-0.5">Program Aktif</div>
        </div>

        {{-- KPI 2: PROYEK INDUSTRI --}}
        <div class="px-3 md:px-5 py-1 pt-3 md:pt-1">
            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Proyek Industri</div>
            <div class="text-2xl font-extrabold text-slate-900 mt-0.5 font-mono">{{ number_format($totalProjects) }}</div>
            <div class="text-[11px] text-blue-600 font-semibold mt-0.5">Studi Kasus Klien</div>
        </div>

        {{-- KPI 3: PESERTA TERDAFTAR --}}
        <div class="px-3 md:px-5 py-1 pt-3 md:pt-1">
            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Peserta Terdaftar</div>
            <div class="text-2xl font-extrabold text-slate-900 mt-0.5 font-mono">{{ number_format($totalEnrolledStudents) }}</div>
            <div class="text-[11px] text-emerald-600 font-semibold mt-0.5">Mahasiswa Aktif</div>
        </div>

        {{-- KPI 4: TALENT POOL --}}
        <div class="px-3 md:px-5 py-1 pt-3 md:pt-1">
            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Talent Pool</div>
            <div class="text-2xl font-extrabold text-slate-900 mt-0.5 font-mono">{{ number_format($totalProjectStudents) }}</div>
            <div class="text-[11px] text-amber-600 font-semibold mt-0.5">Pelamar Proyek</div>
        </div>
    </div>

    {{-- 3. MAIN WORKSPACE 2 KOLOM (50% : 50% - SEIMBANG, LAPANG & PRAKTIS) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

        {{-- KOLOM KIRI: KURSUS & BATCH SERTIFIKASI --}}
        <div class="compro-card p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Kursus Sertifikasi Industri</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Program pelatihan dan sertifikasi kompetensi aktif</p>
                </div>
                <a href="{{ route('vendor.courses.index') }}" class="text-xs font-bold text-purple-700 hover:text-purple-900 transition">
                    Lihat Semua ({{ $totalCourses }}) →
                </a>
            </div>

            <div class="space-y-3">
                @forelse($courses->take(4) as $course)
                    <div class="row-item flex items-center justify-between gap-3">
                        <div class="min-w-0 space-y-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-xs font-bold text-slate-900 truncate">
                                    {{ $course->name }}
                                </h3>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-purple-50 text-purple-700 border border-purple-100">
                                    {{ $course->level ?? 'Beginner' }}
                                </span>
                            </div>
                            <div class="text-[11px] text-slate-500 flex items-center gap-2">
                                <span class="font-bold text-slate-700 font-mono">{{ $course->students_count ?? 0 }} Peserta</span>
                                <span>•</span>
                                <span>{{ $course->materials_count ?? 0 }} Modul</span>
                                <span>•</span>
                                <span>KKM: <strong class="text-emerald-700 font-mono">{{ $course->certificate_threshold ?? 75 }}%</strong></span>
                            </div>
                        </div>

                        <a href="{{ route('vendor.courses.show', $course->id) }}" 
                           class="px-3.5 py-2 bg-white hover:bg-purple-700 text-slate-700 hover:text-white border border-slate-200 hover:border-purple-700 text-xs font-bold rounded-xl shadow-2xs transition shrink-0">
                            Kelola Batch →
                        </a>
                    </div>
                @empty
                    <div class="p-8 bg-slate-50 rounded-xl text-center space-y-2">
                        <p class="text-xs text-slate-500 font-medium">Belum ada program kursus sertifikasi aktif.</p>
                        <a href="{{ route('vendor.courses.create') }}" class="inline-block px-3 py-1.5 bg-purple-700 text-white font-bold text-xs rounded-lg">
                            + Buat Kursus Pertama
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- KOLOM KANAN: PROYEK INDUSTRI & SELEKSI TALENTA --}}
        <div class="compro-card p-6 space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Proyek & Seleksi Talenta</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Studi kasus industri dan seleksi pelamar mahasiswa</p>
                </div>
                <a href="{{ route('vendor.projects.index') }}" class="text-xs font-bold text-purple-700 hover:text-purple-900 transition">
                    Kelola Proyek →
                </a>
            </div>

            {{-- BAGIAN 1: PROYEK INDUSTRI AKTIF --}}
            <div class="space-y-3">
                <div class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Proyek Aktif Terbuka:</div>
                @forelse($projects->take(2) as $proj)
                    <div class="row-item flex items-center justify-between gap-3">
                        <div class="min-w-0 space-y-1">
                            <h3 class="text-xs font-bold text-slate-900 truncate">
                                {{ $proj->title }}
                            </h3>
                            <div class="text-[11px] text-slate-500 flex items-center gap-2">
                                <span class="font-bold text-purple-700 font-mono">{{ $proj->participations_count }} Pelamar</span>
                                <span>•</span>
                                <span>Durasi: {{ $proj->duration_days ? $proj->duration_days . ' Hari' : 'Fleksibel' }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-1.5 shrink-0">
                            <a href="{{ route('vendor.projects.talent-pool', $proj->id) }}" 
                               class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 text-xs font-bold rounded-xl transition">
                                Talent Pool
                            </a>
                            <a href="{{ route('vendor.projects.show', $proj->id) }}" 
                               class="px-3 py-1.5 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                                Detail →
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-4 bg-slate-50 rounded-xl text-center text-xs text-slate-400">
                        Belum ada proyek industri aktif dipublikasikan.
                    </div>
                @endforelse
            </div>

            {{-- BAGIAN 2: PELAMAR TERBARU YANG PERLU DIREVIEW --}}
            <div class="pt-2 border-t border-slate-100 space-y-3">
                <div class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Pelamar Mahasiswa Terbaru:</div>
                @if($participations->isNotEmpty())
                    <div class="space-y-2">
                        @foreach($participations->take(3) as $part)
                            <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-xl flex items-center justify-between gap-2">
                                <div class="min-w-0">
                                    <div class="text-xs font-bold text-slate-900 truncate">{{ $part->user->name ?? 'Mahasiswa' }}</div>
                                    <div class="text-[11px] text-slate-500 truncate">
                                        {{ $part->project->title ?? 'Proyek' }}
                                    </div>
                                </div>
                                @if($part->user)
                                    <a href="{{ route('vendor.students.portfolio', $part->user->id) }}" 
                                       class="px-2.5 py-1 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg text-xs font-bold transition shrink-0">
                                        Portofolio →
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-3 bg-slate-50 rounded-xl text-center text-xs text-slate-400">
                        Belum ada pelamar baru pada proyek Anda.
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
</x-app-layout>