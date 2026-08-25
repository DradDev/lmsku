<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.lecturer-show-wrap {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #0f172a;
    padding-bottom: 3.5rem;
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

.selector-banner {
    background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 50%, #f8fafc 100%);
    border: 2px solid #bfdbfe;
    border-radius: 18px;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.06);
}

.custom-select-control {
    background-color: #ffffff;
    border: 2px solid #93c5fd;
    border-radius: 14px;
    padding: 0.65rem 1rem;
    font-size: 0.875rem;
    font-weight: 800;
    color: #0f172a;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.08);
}

.custom-select-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
    outline: none;
}

.quick-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 10px;
    font-size: 11.5px;
    font-weight: 700;
    cursor: pointer;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #475569;
    transition: all 0.15s ease;
}

.quick-pill:hover {
    background: #eff6ff;
    border-color: #93c5fd;
    color: #1d4ed8;
}

.quick-pill.active {
    background: #2563eb;
    border-color: #2563eb;
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
}

.item-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 1rem 1.25rem;
    transition: all 0.2s;
}

.item-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
}
</style>

@php
    $allOfferings = $siblingOfferings ?? collect([$course]);
    $masterCourse = $course->masterCourse;
    $initialClassId = request()->query('class_id') ? "'" . request()->query('class_id') . "'" : "'null'";
@endphp

<div class="lecturer-show-wrap max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6" 
     x-data="{ selectedClassId: {{ $initialClassId }} }">

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

    {{-- NOTIFIKASI SEMESTER NON-AKTIF --}}
    @if(!($isTermActive ?? true))
        <div class="p-4 bg-amber-50 border border-amber-200 text-amber-900 rounded-xl flex items-center justify-between gap-3 text-xs">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <div>
                    <strong>Semester Non-Aktif (Mode Read-Only):</strong> Seluruh modul materi, kuis, dan nilai dikunci untuk arsip akademik.
                </div>
            </div>
            <span class="px-2.5 py-1 bg-amber-200/80 text-amber-900 rounded-lg font-bold text-[11px] shrink-0">
                Terkunci
            </span>
        </div>
    @endif

    {{-- TOP NAVIGATION & ACTIONS BAR --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <a href="{{ route('lecturer.courses.index') }}" 
           class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Daftar Mata Kuliah</span>
        </a>

        <div class="flex flex-wrap items-center gap-2">
            @if(isset($retakeRequests) && $retakeRequests->count() > 0)
                <a href="{{ route('lecturer.courses.retake-requests.index', $course->id) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition shadow-xs">
                    <span>Kelola Retake Kuis</span>
                    @php $pendingCount = $retakeRequests->where('status', 'pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="px-1.5 py-0.2 rounded-full bg-amber-500 text-white text-[10px] font-extrabold">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
            @endif

            @if($isTermActive ?? true)
                <a href="{{ route('lecturer.materials.create', $course->id) }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
                    + Tambah Materi
                </a>

                <a href="{{ route('lecturer.courses.quizzes.create', $course->id) }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                    + Buat Kuis Baru
                </a>
            @endif
        </div>
    </div>

    {{-- 1. MASTER COURSE HERO CARD --}}
    <div class="compro-card p-6 space-y-3">
        <div class="flex items-center gap-2 flex-wrap">
            <span class="px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-100 text-[11px] font-extrabold">
                {{ $course->academicTerm->name ?? 'Semester Aktif' }}
            </span>
            @if($masterCourse && $masterCourse->code)
                <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-bold font-mono">
                    {{ $masterCourse->code }}
                </span>
            @endif
            <span class="text-xs font-bold text-slate-400">
                • Total {{ $allOfferings->count() }} Rombel Kelas Diampu
            </span>
        </div>

        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                {{ $masterCourse->name ?? ($course->name ?? 'Mata Kuliah') }}
            </h1>
            @if(!empty($course->description))
                <p class="text-xs text-slate-500 mt-1 max-w-3xl leading-relaxed">
                    {{ $course->description }}
                </p>
            @endif
        </div>

        @if($masterCourse && $masterCourse->skills && $masterCourse->skills->isNotEmpty())
            <div class="flex flex-wrap items-center gap-1.5 pt-2 border-t border-slate-100">
                <span class="text-[11px] font-bold text-slate-400">Kompetensi Target:</span>
                @foreach($masterCourse->skills as $cSkill)
                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-100">
                        {{ $cSkill->name }}
                    </span>
                @endforeach
            </div>
        @endif
    </div>

    {{-- 2. CONTROL CENTER: PEMILIHAN KELAS / ROMBEL (PROMINENT & SANGAT KELIHATAN) --}}
    <div class="selector-banner p-5 sm:p-6 space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            {{-- LABEL & IKON --}}
            <div class="flex items-start sm:items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-extrabold shadow-md shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-extrabold text-blue-600 uppercase tracking-wider">Langkah Utama</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                        <span class="text-xs font-bold text-slate-500">{{ $allOfferings->count() }} Rombel Tersedia</span>
                    </div>
                    <h2 class="text-lg font-extrabold text-slate-900 tracking-tight mt-0.5">
                        Pilih Rombel Kelas Perkuliahan
                    </h2>
                    <p class="text-xs text-slate-600 mt-0.5">
                        Pilih kelas rombel aktif untuk membuka modul silabus, bank kuis, dan data mahasiswa.
                    </p>
                </div>
            </div>

            {{-- DROPDOWN UTAMA --}}
            <div class="flex items-center gap-2.5 w-full lg:w-auto shrink-0">
                <div class="relative flex-1 lg:w-80">
                    <select x-model="selectedClassId" 
                            class="custom-select-control w-full pr-10 cursor-pointer">
                        <option value="null">-- Silakan Pilih Rombel Kelas --</option>
                        @foreach($allOfferings as $offeringItem)
                            @php
                                $stdCount = $offeringItem->enrollments ? $offeringItem->enrollments->count() : 0;
                            @endphp
                            <option value="{{ $offeringItem->id }}">
                                {{ $offeringItem->section_name ?: 'Rombel ' . $loop->iteration }} • {{ $stdCount }} Mhs (KKM {{ $offeringItem->certificate_threshold ?? 75 }}%)
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="button" 
                        x-show="selectedClassId !== 'null' && selectedClassId !== null" 
                        @click="selectedClassId = 'null'"
                        style="display: none;"
                        class="px-3.5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition whitespace-nowrap shadow-2xs">
                    ✕ Reset
                </button>
            </div>

        </div>


    {{-- 2. STATE AWAL: BELUM MEMILIH KELAS --}}
    <div x-show="selectedClassId === 'null' || selectedClassId === null" class="compro-card p-12 text-center space-y-3">
        <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto shadow-xs">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        <h3 class="text-base font-extrabold text-slate-800">Silakan Pilih Rombel Kelas</h3>
        <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
            Gunakan menu dropdown atau pintasan kelas di atas untuk mulai mengelola silabus materi, bank kuis, setting KKM kelulusan, dan data mahasiswa.
        </p>
    </div>

    {{-- 3. STATE TERPILIH: WORKSPACE DETAIL KELAS YANG DIPILIH --}}
    <div x-show="selectedClassId !== 'null' && selectedClassId !== null" style="display: none;" class="space-y-6">

        @foreach($allOfferings as $offeringItem)
            @php
                $offeringEnrollments = $offeringItem->enrollments ?? collect();
            @endphp
            <div x-show="selectedClassId == {{ $offeringItem->id }}" class="space-y-6">

                {{-- SUB-BAR KELAS AKTIF DENGAN STATS MINI --}}
                <div class="p-4 bg-blue-50/80 border border-blue-200 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-extrabold text-base shadow-xs">
                            ✓
                        </div>
                        <div>
                            <div class="text-xs font-extrabold text-blue-950 flex items-center gap-2">
                                <span>Sedang Mengelola: {{ $offeringItem->section_name ?: 'Kelas ' . $loop->iteration }}</span>
                                <span class="px-2 py-0.5 rounded-md bg-blue-600 text-white text-[10px] font-extrabold uppercase">Aktif</span>
                            </div>
                            <div class="text-[11px] text-blue-700 mt-0.5 flex items-center gap-2">
                                <span><strong>{{ $offeringEnrollments->count() }}</strong> Mahasiswa Terdaftar</span>
                                <span>•</span>
                                <span>KKM Kelulusan: <strong>{{ $offeringItem->certificate_threshold ?? 75 }}%</strong></span>
                            </div>
                        </div>
                    </div>

                    <button type="button" 
                            @click="selectedClassId = 'null'"
                            class="px-3.5 py-1.5 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 rounded-xl text-xs font-bold transition self-start sm:self-auto shadow-2xs">
                        ✕ Tutup Kelas Ini
                    </button>
                </div>

                {{-- WORKSPACE 2 KOLOM (KUIS & MATERI vs THRESHOLD & MAHASISWA) --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                    {{-- LEFT COLUMN: BANK KUIS & MATERI (8 COLS) --}}
                    <div class="lg:col-span-8 space-y-6">

                        {{-- BANK KUIS --}}
                        <div id="quiz-section" class="compro-card p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-base font-extrabold text-slate-900">Bank Kuis & Evaluasi</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Kuis harian, evaluasi bab, dan Final Quiz sertifikasi</p>
                                </div>
                                @if($isTermActive ?? true)
                                    <a href="{{ route('lecturer.courses.quizzes.create', $offeringItem->id) }}?scope=class" 
                                       class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                        + Buat Kuis Khusus {{ $offeringItem->section_name ?: 'Kelas Ini' }}
                                    </a>
                                @endif
                            </div>

                            {{-- DAFTAR KUIS (TERFILTER PRESISI SEPERTI MATERI) --}}
                            @php
                                $masterQuizzes = $quizzes->filter(fn($q) => $q->quizzable_type === \App\Models\MasterCourse::class);
                                $classQuizzes = $quizzes->filter(fn($q) => $q->quizzable_type === \App\Models\CourseOffering::class && (int)$q->quizzable_id === (int)$offeringItem->id);
                            @endphp

                            {{-- 1. SUBSECTION: KUIS KHUSUS ROMBEL INI --}}
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                        <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Kuis Khusus {{ $offeringItem->section_name ?: 'Kelas Ini' }}</h4>
                                        <span class="px-2 py-0.2 rounded-md bg-blue-50 text-blue-700 text-[10px] font-extrabold">Hanya {{ $offeringItem->section_name ?: 'Kelas Ini' }}</span>
                                    </div>
                                </div>

                                <div class="space-y-2.5">
                                    @forelse ($classQuizzes as $quiz)
                                        <div class="item-card flex flex-col sm:flex-row sm:items-center justify-between gap-3 border border-blue-100 shadow-sm">
                                            <div>
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <h4 class="font-bold text-xs text-slate-900">{{ $quiz->title }}</h4>
                                                    <span class="px-2 py-0.5 rounded-md text-[10.5px] font-extrabold {{ $quiz->isFinal() ? 'bg-purple-100 text-purple-800' : 'bg-slate-100 text-slate-700' }}">
                                                        {{ $quiz->quiz_type_label }}
                                                    </span>
                                                </div>
                                                <div class="text-[11px] text-slate-500 mt-1 flex flex-wrap items-center gap-2">
                                                    <span>Durasi: <strong class="text-slate-700">{{ $quiz->time_limit ? $quiz->time_limit . ' Menit' : 'Tanpa Batas' }}</strong></span>
                                                    <span>•</span>
                                                    <span>Percobaan: <strong class="text-slate-700">{{ $quiz->max_attempts === 0 ? 'Unlimited' : $quiz->max_attempts . 'x' }}</strong></span>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-1.5 shrink-0 flex-wrap">
                                                <a href="{{ route('lecturer.courses.quizzes.results.index', [$offeringItem->id, $quiz->id]) }}" 
                                                   class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition">
                                                    Hasil
                                                </a>
                                                @if($isTermActive ?? true)
                                                    <a href="{{ route('lecturer.courses.quizzes.show', [$offeringItem->id, $quiz->id]) }}" 
                                                       class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-xs transition">
                                                        + Soal ({{ $quiz->questions_count ?? $quiz->questions()->count() }})
                                                    </a>
                                                    <form method="POST" action="{{ route('lecturer.courses.quizzes.destroy', [$offeringItem->id, $quiz->id]) }}" onsubmit="return confirm('Hapus kuis khusus kelas ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold rounded-lg transition">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 px-4 rounded-xl border border-dashed border-slate-200 text-xs text-slate-400">
                                            Belum ada kuis tambahan khusus untuk rombel {{ $offeringItem->section_name ?: 'ini' }}.
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- 2. SUBSECTION: KUIS INDUK KURIKULUM --}}
                            <div class="space-y-3 pt-3 border-t border-slate-100 mt-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                        <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Kuis Induk Kurikulum</h4>
                                        <span class="px-2 py-0.2 rounded-md bg-slate-100 text-slate-600 text-[10px] font-extrabold">Semua Rombel</span>
                                    </div>
                                    @if($isTermActive ?? true)
                                        <a href="{{ route('lecturer.courses.quizzes.create', $offeringItem->id) }}?scope=all" 
                                           class="text-[11px] font-bold text-blue-600 hover:text-blue-800 transition">
                                            + Tambah Kuis Induk
                                        </a>
                                    @endif
                                </div>

                                <div class="space-y-2.5">
                                    @forelse ($masterQuizzes as $quiz)
                                        <div class="item-card flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                                            <div>
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <h4 class="font-bold text-xs text-slate-900">{{ $quiz->title }}</h4>
                                                    <span class="px-2 py-0.5 rounded-md text-[10.5px] font-extrabold {{ $quiz->isFinal() ? 'bg-purple-100 text-purple-800' : 'bg-slate-200 text-slate-800' }}">
                                                        {{ $quiz->quiz_type_label }}
                                                    </span>
                                                </div>
                                                <div class="text-[11px] text-slate-500 mt-1 flex flex-wrap items-center gap-2">
                                                    <span>Durasi: <strong class="text-slate-700">{{ $quiz->time_limit ? $quiz->time_limit . ' Menit' : 'Tanpa Batas' }}</strong></span>
                                                    <span>•</span>
                                                    <span>Percobaan: <strong class="text-slate-700">{{ $quiz->max_attempts === 0 ? 'Unlimited' : $quiz->max_attempts . 'x' }}</strong></span>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-1.5 shrink-0 flex-wrap">
                                                <a href="{{ route('lecturer.courses.quizzes.results.index', [$offeringItem->id, $quiz->id]) }}" 
                                                   class="px-2.5 py-1 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold rounded-lg transition">
                                                    Hasil
                                                </a>
                                                @if($isTermActive ?? true)
                                                    <a href="{{ route('lecturer.courses.quizzes.show', [$offeringItem->id, $quiz->id]) }}" 
                                                       class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-xs transition">
                                                        Kelola Soal ({{ $quiz->questions_count ?? $quiz->questions()->count() }})
                                                    </a>
                                                    <form method="POST" action="{{ route('lecturer.courses.quizzes.destroy', [$offeringItem->id, $quiz->id]) }}" onsubmit="return confirm('PERINGATAN: Kuis ini adalah Kuis Induk Kurikulum untuk SEMUA rombel. Menghapus kuis ini akan menghapusnya dari seluruh kelas. Lanjutkan?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="px-2.5 py-1 text-slate-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg text-xs font-bold transition">
                                                            Hapus Global
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 px-4 rounded-xl border border-dashed border-slate-200 text-xs text-slate-400">
                                            Belum ada kuis induk kurikulum.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- MODUL MATERI PEMBELAJARAN (TERFILTER PRESISI) --}}
                        @php
                            $masterMaterials = $materials->filter(fn($m) => $m->materialable_type === \App\Models\MasterCourse::class);
                            $classMaterials = $materials->filter(fn($m) => $m->materialable_type === \App\Models\CourseOffering::class && (int)$m->materialable_id === (int)$offeringItem->id);
                        @endphp

                        <div class="compro-card p-6 space-y-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                                <div>
                                    <h3 class="text-base font-extrabold text-slate-900">Modul Materi Pembelajaran</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Silabus induk kurikulum dan materi pengayaan khusus {{ $offeringItem->section_name ?: 'kelas ini' }}</p>
                                </div>
                                @if($isTermActive ?? true)
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('lecturer.materials.create', $offeringItem->id) }}?scope=class" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                            + Materi Khusus {{ $offeringItem->section_name ?: 'Kelas Ini' }}
                                        </a>
                                    </div>
                                @endif
                            </div>

                            {{-- 1. SUBSECTION: MATERI KHUSUS ROMBEL INI --}}
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                        <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Materi Khusus {{ $offeringItem->section_name ?: 'Kelas Ini' }}</h4>
                                        <span class="px-2 py-0.2 rounded-md bg-blue-50 text-blue-700 text-[10px] font-extrabold">Hanya {{ $offeringItem->section_name ?: 'Kelas Ini' }}</span>
                                    </div>
                                </div>

                                <div class="space-y-2.5">
                                    @forelse ($classMaterials as $cMat)
                                        <div class="item-card flex items-center justify-between gap-3">
                                            <div class="flex items-start gap-3 min-w-0">
                                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <h4 class="font-bold text-xs text-slate-900 truncate">{{ $cMat->title }}</h4>
                                                    <div class="text-[11px] text-slate-500 mt-0.5">
                                                        Diunggah: {{ optional($cMat->created_at)->format('d M Y') ?: '-' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-1.5 shrink-0">
                                                <a href="{{ route('lecturer.materials.show', $cMat->id) }}" 
                                                   class="px-2.5 py-1 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg text-xs font-semibold transition">
                                                    Lihat
                                                </a>
                                                @if($isTermActive ?? true)
                                                    <a href="{{ route('lecturer.materials.edit', $cMat->id) }}" 
                                                       class="px-2.5 py-1 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg text-xs font-bold transition">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('lecturer.materials.destroy', $cMat->id) }}" method="POST" onsubmit="return confirm('Hapus materi tambahan khusus {{ $offeringItem->section_name ?: 'kelas ini' }}?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="course_id" value="{{ $offeringItem->id }}">
                                                        <button type="submit" class="px-2.5 py-1 text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg text-xs font-bold transition">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 px-4 rounded-xl border border-dashed border-slate-200 text-xs text-slate-400">
                                            Belum ada materi tambahan khusus untuk rombel {{ $offeringItem->section_name ?: 'ini' }}.
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- 2. SUBSECTION: MATERI INDUK KURIKULUM --}}
                            <div class="space-y-3 pt-3 border-t border-slate-100">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                        <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Materi Induk Kurikulum</h4>
                                        <span class="px-2 py-0.2 rounded-md bg-slate-100 text-slate-600 text-[10px] font-extrabold">Semua Rombel</span>
                                    </div>
                                    @if($isTermActive ?? true)
                                        <a href="{{ route('lecturer.materials.create', $offeringItem->id) }}?scope=all" 
                                           class="text-[11px] font-bold text-blue-600 hover:text-blue-800 transition">
                                            + Tambah Materi Induk
                                        </a>
                                    @endif
                                </div>

                                <div class="space-y-2.5">
                                    @forelse ($masterMaterials as $mMat)
                                        <div class="item-card flex items-center justify-between gap-3 bg-slate-50/50">
                                            <div class="flex items-start gap-3 min-w-0">
                                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 mt-0.5">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                    </svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <h4 class="font-bold text-xs text-slate-900 truncate">{{ $mMat->title }}</h4>
                                                    <div class="text-[11px] text-slate-500 mt-0.5">
                                                        Diunggah: {{ optional($mMat->created_at)->format('d M Y') ?: '-' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-1.5 shrink-0">
                                                <a href="{{ route('lecturer.materials.show', $mMat->id) }}" 
                                                   class="px-2.5 py-1 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg text-xs font-semibold transition">
                                                    Lihat
                                                </a>
                                                @if($isTermActive ?? true)
                                                    <a href="{{ route('lecturer.materials.edit', $mMat->id) }}" 
                                                       class="px-2.5 py-1 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg text-xs font-bold transition">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('lecturer.materials.destroy', $mMat->id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Materi ini adalah Materi Induk Kurikulum untuk SEMUA rombel. Menghapus materi ini akan menghapusnya dari seluruh kelas. Lanjutkan?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="course_id" value="{{ $offeringItem->id }}">
                                                        <button type="submit" class="px-2.5 py-1 text-slate-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg text-xs font-bold transition">
                                                            Hapus Global
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 px-4 rounded-xl border border-dashed border-slate-200 text-xs text-slate-400">
                                            Belum ada materi induk kurikulum.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- RIGHT COLUMN: THRESHOLD & MAHASISWA (4 COLS) --}}
                    <div class="lg:col-span-4 space-y-6">

                        {{-- SETTING THRESHOLD KELULUSAN --}}
                        <div class="compro-card p-5 space-y-3">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-extrabold text-slate-900">KKM Kelulusan</h4>
                                <span class="text-[10px] font-extrabold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100">Otonomi Dosen</span>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                Batas nilai minimal (%) pada Kuis Akhir untuk kelulusan dan sertifikat blockchain.
                            </p>

                            @if($isTermActive ?? true)
                                <form action="{{ route('lecturer.courses.update', $offeringItem->id) }}" method="POST" class="flex items-center gap-2 pt-2 border-t border-slate-100">
                                    @csrf
                                    @method('PUT')
                                    <div class="relative flex-1">
                                        <input type="number" name="certificate_threshold" value="{{ old('certificate_threshold', $offeringItem->certificate_threshold ?? 75) }}" min="0" max="100" class="w-full rounded-xl border-slate-300 text-xs font-extrabold text-center py-1.5 focus:border-blue-600 focus:ring-blue-600" required>
                                    </div>
                                    <button type="submit" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                                        Simpan
                                    </button>
                                </form>
                            @else
                                <div class="p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 flex justify-between">
                                    <span>Threshold:</span>
                                    <span>{{ $offeringItem->certificate_threshold ?? 75 }}%</span>
                                </div>
                            @endif
                        </div>

                        {{-- MAHASISWA TERDAFTAR --}}
                        <div class="compro-card p-5 space-y-3">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-extrabold text-slate-900">Mahasiswa Rombel</h4>
                                <span class="text-xs font-bold text-slate-400">{{ $offeringEnrollments->count() }} Terdaftar</span>
                            </div>

                            @if($offeringEnrollments->count())
                                <div class="space-y-2.5 max-h-[480px] overflow-y-auto pr-1">
                                    @foreach ($offeringEnrollments as $enrollment)
                                        @php
                                            $studentUser = $enrollment->user;
                                            $progress = $enrollment->progress_percent ?? 0;
                                        @endphp
                                        <div class="p-3 bg-slate-50/70 border border-slate-200/80 rounded-xl text-xs space-y-1.5">
                                            <div class="flex items-center justify-between gap-2">
                                                <div class="font-bold text-slate-900 truncate">{{ $studentUser->name ?? 'Mahasiswa' }}</div>
                                                <span class="text-[10px] font-bold text-slate-500 font-mono">{{ $progress }}%</span>
                                            </div>
                                            <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                                <div class="h-1.5 rounded-full {{ $progress >= 80 ? 'bg-emerald-500' : 'bg-blue-600' }}" style="width: {{ min(100, max(0, $progress)) }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-6 text-xs text-slate-400">
                                    Belum ada mahasiswa di rombel ini.
                                </div>
                            @endif
                        </div>

                    </div>

                </div>

            </div>
        @endforeach

    </div>

</div>
</x-app-layout>