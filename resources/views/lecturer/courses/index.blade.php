<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.courses-wrap {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #0f172a;
    padding-bottom: 3rem;
}

.compro-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    transition: box-shadow 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
}

.compro-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
    transform: translateY(-2px);
}
</style>

<div class="courses-wrap max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    {{-- ALERT NOTIFICATION --}}
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
                Portal Dosen • COMPRO LMS
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                Mata Kuliah & Kurikulum Dosen
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Daftar mata kuliah yang Anda ampu pada semester aktif. Buka mata kuliah untuk memilih dan mengelola rombel kelas.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('lecturer.dashboard') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard Dosen
            </a>
        </div>
    </div>

    {{-- CARD-GRID MATA KULIAH (2 KOLOM MODERN, BUKAN TABEL PANJANG) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @if(isset($groupedOfferings) && $groupedOfferings->isNotEmpty())
            @foreach($groupedOfferings as $masterCourseId => $offeringsGroup)
                @php
                    $firstOffering = $offeringsGroup->first();
                    $totalGroupStudents = $offeringsGroup->sum(fn($o) => $o->enrollments ? $o->enrollments->count() : 0);
                    $masterCourse = $firstOffering->masterCourse;
                @endphp
                <div class="compro-card p-6 flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-100 text-[11px] font-extrabold">
                                {{ $firstOffering->academicTerm->name ?? 'Semester Aktif' }}
                            </span>
                            @if($masterCourse && $masterCourse->code)
                                <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-bold font-mono">
                                    {{ $masterCourse->code }}
                                </span>
                            @endif
                        </div>

                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900 leading-snug">
                                {{ $masterCourse->name ?? ($firstOffering->name ?? 'Mata Kuliah') }}
                            </h2>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                {{ $firstOffering->description ?: 'Pengelolaan silabus materi, bank kuis, dan rombel perkuliahan mahasiswa.' }}
                            </p>
                        </div>

                        {{-- RINGKASAN JUMLAH KELAS & MAHASISWA --}}
                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-xl">
                                <div class="text-[10.5px] font-bold uppercase text-slate-400">Kelas Diampu</div>
                                <div class="text-base font-extrabold text-slate-900 mt-0.5 font-mono">
                                    {{ $offeringsGroup->count() }} Rombel
                                </div>
                            </div>
                            <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-xl">
                                <div class="text-[10.5px] font-bold uppercase text-slate-400">Total Mahasiswa</div>
                                <div class="text-base font-extrabold text-blue-600 mt-0.5 font-mono">
                                    {{ $totalGroupStudents }} Mahasiswa
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TOMBOL UTAMA MASUK MASTER COURSE --}}
                    <div class="pt-3 border-t border-slate-100">
                        <a href="{{ route('lecturer.courses.show', $firstOffering->id) }}" 
                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                            <span>Kelola Mata Kuliah & Kelas</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        @elseif(isset($activeCourses) && $activeCourses->isNotEmpty())
            @foreach($activeCourses as $course)
                <div class="compro-card p-6 flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-100 text-[11px] font-extrabold">
                                {{ $course->academicTerm->name ?? 'Semester Aktif' }}
                            </span>
                            @if($course->code)
                                <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-bold font-mono">
                                    {{ $course->code }}
                                </span>
                            @endif
                        </div>

                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">
                                {{ $course->name }}
                            </h2>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ $course->description ?: 'Pengelolaan silabus materi, bank kuis, dan mahasiswa terdaftar.' }}
                            </p>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100">
                        <a href="{{ route('lecturer.courses.show', $course->id) }}" 
                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                            <span>Kelola Mata Kuliah & Kelas</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-span-2 compro-card p-12 text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-800">Belum Ada Kelas Aktif</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto">
                    Mata kuliah dan penawaran kelas semester aktif akan disiapkan dan ditugaskan oleh Admin Akademik.
                </p>
            </div>
        @endif
    </div>

</div>
</x-app-layout>