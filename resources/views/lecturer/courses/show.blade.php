<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.lecturer-show-wrap {
    min-height: 100vh;
    background: linear-gradient(180deg, #f8faff 0%, #f1f5f9 100%);
    color: #0f172a;
    padding: 2rem 0 4.5rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.lecturer-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 1.75rem;
}

.top-bar-nav {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 1.5rem;
}

@media (min-width: 768px) {
    .top-bar-nav {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 700;
    color: #64748b;
    text-decoration: none;
    transition: all 0.2s;
}

.back-link:hover {
    color: #0f172a;
}

.header-actions-group {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-header {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 700;
    padding: 8px 14px;
    border-radius: 11px;
    text-decoration: none;
    transition: all 0.2s;
    cursor: pointer;
    border: none;
}

.btn-header-indigo {
    background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(79, 70, 229, 0.25);
}

.btn-header-indigo:hover {
    box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
    color: #ffffff;
}

.btn-header-secondary {
    background: #ffffff;
    color: #334155;
    border: 1px solid #cbd5e1;
}

.btn-header-secondary:hover {
    background: #f8fafc;
    border-color: #94a3b8;
}

.hero-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 24px;
    padding: 1.75rem 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    margin-bottom: 1.5rem;
}

.hero-eyebrow {
    font-size: 11px;
    font-weight: 800;
    color: #4f46e5;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 6px;
}

.hero-title {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.3;
    letter-spacing: -0.4px;
}

.hero-meta-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    margin-top: 10px;
    margin-bottom: 12px;
}

.hero-badge {
    font-size: 11.5px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 8px;
}

.hero-desc {
    font-size: 13px;
    color: #64748b;
    line-height: 1.6;
    max-width: 860px;
}

.hero-stats-strip {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    margin-top: 1.5rem;
    padding-top: 1.25rem;
    border-top: 1px solid #f1f5f9;
}

@media (min-width: 768px) {
    .hero-stats-strip {
        grid-template-columns: repeat(4, 1fr);
    }
}

.hero-stat-card {
    background: #f8fafc;
    border: 1px solid #edf2f7;
    border-radius: 14px;
    padding: 10px 14px;
}

.hero-stat-label {
    font-size: 10.5px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.hero-stat-val {
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
    margin-top: 2px;
}

/* SECTION MANAGEMENT CARD */
.section-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 22px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    margin-bottom: 1.5rem;
}

.section-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 1.25rem;
}

.section-title {
    font-size: 17px;
    font-weight: 800;
    color: #0f172a;
}

.section-sub {
    font-size: 12.5px;
    color: #64748b;
    margin-top: 2px;
}

.btn-action-indigo {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 700;
    padding: 8px 14px;
    border-radius: 10px;
    background: #4f46e5;
    color: #ffffff;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-action-indigo:hover {
    background: #4338ca;
    color: #ffffff;
}

.class-table-container {
    overflow-x: auto;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    background: #ffffff;
}

.class-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12.5px;
    text-align: left;
}

.class-table th {
    background: #f8fafc;
    color: #475569;
    font-weight: 800;
    text-transform: uppercase;
    font-size: 10.5px;
    letter-spacing: 0.5px;
    padding: 12px 16px;
    border-bottom: 1px solid #e2e8f0;
}

.class-table td {
    padding: 12px 16px;
    border-bottom: 1px solid #f1f5f9;
    color: #1e293b;
    vertical-align: middle;
}

.class-table tr:last-child td {
    border-bottom: none;
}

.class-table tr.active-row {
    background: #eef2ff;
}

.item-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 1rem 1.25rem;
    margin-bottom: 10px;
    transition: all 0.2s;
}

.item-card:hover {
    border-color: #cbd5e1;
    background: #ffffff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}

.content-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}

@media (min-width: 1024px) {
    .content-grid {
        grid-template-columns: 2fr 1fr;
    }
}
</style>

@php
    $courseEnrollments = $enrollments ?? collect();
    $completedStudentCount = $courseEnrollments->where('status', 'completed')->count();
    $averageProgress = $courseEnrollments->count() > 0 ? round($courseEnrollments->avg('progress_percent')) : 0;
    $allOfferings = $siblingOfferings ?? collect([$course]);
@endphp

<div class="lecturer-show-wrap">
    <div class="lecturer-container">

        {{-- NOTIFIKASI SUCCESS --}}
        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-xs text-xs font-bold">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-600 text-white text-xs">✓</span>
                <div>
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            </div>
        @endif

        {{-- NOTIFIKASI ERROR / VALIDASI --}}
        @if(isset($errors) && $errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl shadow-xs text-xs font-bold">
                <div class="flex items-center gap-2 text-rose-900 mb-1">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-rose-600 text-white text-xs">✕</span>
                    <strong>Mohon periksa kembali kesalahan berikut:</strong>
                </div>
                <ul class="list-disc pl-8 space-y-1 text-rose-700 font-semibold mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- NOTIFIKASI SEMESTER NON-AKTIF (READ ONLY) --}}
        @if(!($isTermActive ?? true))
            <div class="mb-6 p-4 bg-amber-50 border border-amber-200 text-amber-900 rounded-2xl flex items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold flex-shrink-0">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <div>
                        <p class="font-extrabold text-xs text-amber-950">Semester Non-Aktif (Mode Arsip & Read-Only)</p>
                        <p class="text-[11px] text-amber-800 mt-0.5">Periode <strong>{{ $course->academicTerm->name ?? 'Semester Ini' }}</strong> saat ini non-aktif. Seluruh modul materi, kuis, dan data nilai dikunci untuk arsip.</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 bg-amber-200/80 text-amber-900 rounded-lg text-[11px] font-extrabold whitespace-nowrap">
                    Terkunci
                </span>
            </div>
        @endif

        <!-- TOP BAR NAV -->
        <div class="top-bar-nav">
            <a href="{{ route('lecturer.courses.index') }}" class="back-link">
                ← Kembali ke Daftar Kelas
            </a>

            <div class="header-actions-group">
                @if(isset($retakeRequests) && $retakeRequests->count() > 0)
                    <a href="{{ route('lecturer.courses.retake-requests.index', $course->id) }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-3.5 py-2 text-xs font-bold text-white hover:bg-slate-800 transition shadow-sm">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        <span>Kelola Retake Kuis</span>
                        @php $pendingCount = $retakeRequests->where('status', 'pending')->count(); @endphp
                        @if($pendingCount > 0)
                            <span class="px-1.5 py-0.5 rounded-full bg-amber-500 text-white text-[10px] font-extrabold">
                                {{ $pendingCount }} Pending
                            </span>
                        @endif
                    </a>
                @endif

                @if($isTermActive ?? true)
                    <a href="{{ route('lecturer.materials.create', $course->id) }}" class="btn-header btn-header-secondary">
                        <span>+ Tambah Materi</span>
                    </a>

                    <button type="button" 
                            onclick="document.getElementById('create-quiz-form-container').classList.toggle('hidden'); window.scrollTo({top: document.getElementById('quiz-section').offsetTop - 80, behavior: 'smooth'})"
                            class="btn-header btn-header-indigo">
                        <span>+ Buat Kuis Baru</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- HERO HEADER CARD -->
        <div class="hero-card">
            <p class="hero-eyebrow">Portal Dosen &bull; {{ $course->academicTerm->name ?? 'Mata Kuliah Akademik' }}</p>
            <h1 class="hero-title">{{ $course->name }}</h1>

            <div class="hero-meta-row">
                <span class="hero-badge bg-indigo-50 text-indigo-800 border border-indigo-200">
                    {{ $course->main_skill->name ?? optional($course->category)->name ?? 'Mata Kuliah Utama' }}
                </span>
                <span class="hero-badge bg-slate-100 text-slate-700">
                    Level: <strong>{{ $course->level ?? 'Beginner' }}</strong>
                </span>
                @if($course->code || ($course->masterCourse && $course->masterCourse->code))
                    <span class="hero-badge bg-slate-50 text-slate-500 border border-slate-200">
                        Kode: {{ $course->code ?? $course->masterCourse->code }}
                    </span>
                @endif
                <span class="hero-badge bg-purple-50 text-purple-800 border border-purple-200">
                    Rombel Aktif: <strong>{{ $course->section_name ?: 'Kelas A' }}</strong>
                </span>
                <span class="hero-badge bg-emerald-50 text-emerald-800 border border-emerald-200">
                    Threshold: <strong>{{ $course->certificate_threshold ?? 75 }}%</strong>
                </span>
            </div>

            <p class="hero-desc">
                {{ $course->description ?: 'Pengelolaan modul materi pembelajaran, bank kuis evaluasi, penentuan threshold sertifikat, dan progres belajar mahasiswa.' }}
            </p>

            @if($course->masterCourse && $course->masterCourse->skills && $course->masterCourse->skills->isNotEmpty())
                <div class="flex flex-wrap items-center gap-1.5 mt-3">
                    <span class="text-[11px] font-bold text-slate-400">Target Kompetensi:</span>
                    @foreach($course->masterCourse->skills as $cSkill)
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100">
                            {{ $cSkill->name }}
                        </span>
                    @endforeach
                </div>
            @endif

            <!-- QUICK STATS STRIP -->
            <div class="hero-stats-strip">
                <div class="hero-stat-card">
                    <div class="hero-stat-label">Mahasiswa (Rombel Ini)</div>
                    <div class="hero-stat-val text-indigo-600">{{ $courseEnrollments->count() }}</div>
                </div>
                <div class="hero-stat-card">
                    <div class="hero-stat-label">Lulus / Selesai</div>
                    <div class="hero-stat-val text-emerald-600">{{ $completedStudentCount }}</div>
                </div>
                <div class="hero-stat-card">
                    <div class="hero-stat-label">Modul Pembelajaran</div>
                    <div class="hero-stat-val text-purple-700">{{ $materials->count() }}</div>
                </div>
                <div class="hero-stat-card">
                    <div class="hero-stat-label">Bank Kuis & Soal</div>
                    <div class="hero-stat-val text-amber-600">{{ $quizzes->count() }}</div>
                </div>
            </div>
        </div>

        <!-- SECTION: MANAJEMEN ROMBEL KELAS PARALEL -->
        @if($allOfferings->count() > 1)
            <div class="section-card">
                <div class="section-card-header">
                    <div>
                        <h2 class="section-title">Daftar & Kontrol Kelas Rombel Paralel</h2>
                        <p class="section-sub">Anda mengampu <strong>{{ $allOfferings->count() }} rombel kelas</strong> pada semester berjalan ini. Beralih untuk mengelola materi, kuis, dan mahasiswa per rombel.</p>
                    </div>
                </div>

                <div class="class-table-container">
                    <table class="class-table">
                        <thead>
                            <tr>
                                <th>Rombel Kelas</th>
                                <th>Semester Akademik</th>
                                <th>Kapasitas & Jadwal</th>
                                <th>Threshold Kelulusan</th>
                                <th>Mahasiswa Terdaftar</th>
                                <th class="text-right">Aksi Navigasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allOfferings as $offeringItem)
                                @php
                                    $isCurrent = $offeringItem->id === $course->id;
                                    $stdCount = $offeringItem->enrollments_count ?? ($offeringItem->enrollments ? $offeringItem->enrollments->count() : 0);
                                @endphp
                                <tr class="{{ $isCurrent ? 'active-row font-bold' : '' }}">
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full {{ $isCurrent ? 'bg-indigo-600' : 'bg-slate-300' }}"></span>
                                            <div>
                                                <span class="text-xs font-bold text-slate-900">{{ $offeringItem->section_name ?: 'Kelas ' . $loop->iteration }}</span>
                                                @if($isCurrent)
                                                    <span class="ml-1.5 px-2 py-0.5 bg-indigo-100 text-indigo-800 text-[10px] font-extrabold rounded-md">Sedang Dikelola</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-xs text-slate-600">{{ $offeringItem->academicTerm->name ?? 'Semester Berjalan' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-xs text-slate-600">{{ $offeringItem->capacity ? $offeringItem->capacity . ' Kursi' : 'Unlimited' }}</span>
                                    </td>
                                    <td>
                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-md text-xs font-bold">
                                            {{ $offeringItem->certificate_threshold ?? 75 }}%
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-xs font-bold text-slate-800">{{ $stdCount }} Mahasiswa</span>
                                    </td>
                                    <td class="text-right">
                                        @if(!$isCurrent)
                                            <a href="{{ route('lecturer.courses.show', $offeringItem->id) }}" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs rounded-xl border border-indigo-200 transition">
                                                Beralih ke Kelas Ini →
                                            </a>
                                        @else
                                            <span class="text-xs text-indigo-600 font-extrabold">Kelas Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- GRID KONTEN UTAMA: 2fr KIRI (KUIS & MATERI) vs 1fr KANAN (THRESHOLD & MAHASISWA) -->
        <div class="content-grid">
            
            <!-- KOLOM KIRI (2fr) -->
            <div class="space-y-6">

                <!-- 1. BANK KUIS PEMBELAJARAN -->
                <div id="quiz-section" class="section-card">
                    <div class="section-card-header">
                        <div>
                            <h2 class="section-title">Bank Kuis & Evaluasi Pembelajaran</h2>
                            <p class="section-sub">Kelola kuis harian, evaluasi bab, dan Kuis Akhir penentu penerbitan sertifikat digital.</p>
                        </div>

                        @if($isTermActive ?? true)
                            <button type="button" 
                                    onclick="document.getElementById('create-quiz-form-container').classList.toggle('hidden')" 
                                    class="btn-action-indigo">
                                + Buat Kuis Baru
                            </button>
                        @endif
                    </div>

                    <!-- FORM INLINE BUAT KUIS BARU -->
                    @if($isTermActive ?? true)
                        <div id="create-quiz-form-container" class="hidden mb-6 p-5 bg-indigo-50/60 border border-indigo-200 rounded-2xl transition">
                            <h3 class="text-xs font-extrabold text-indigo-950 mb-3 flex items-center gap-2">
                                Form Pembuatan Kuis Baru
                            </h3>
                            <form method="POST" action="{{ route('lecturer.courses.quizzes.store', $course->id) }}" class="space-y-4 text-xs">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Judul Kuis <span class="text-rose-500">*</span></label>
                                        <input type="text" name="title" placeholder="Contoh: Kuis Akhir - Final Certification Exam" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold focus:border-indigo-600 focus:ring-indigo-600" required>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Tipe Kuis Pembelajaran <span class="text-rose-500">*</span></label>
                                        <select name="quiz_type" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-bold text-indigo-900 bg-white focus:border-indigo-600 focus:ring-indigo-600" required>
                                            <option value="daily">Kuis Biasa / Harian (Section Quiz)</option>
                                            <option value="weekly">Kuis Mingguan / Evaluasi Bab</option>
                                            <option value="final">Kuis Akhir (Final Quiz / Penentu Sertifikat)</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Durasi Pengerjaan (Menit)</label>
                                        <input type="number" name="time_limit" min="1" placeholder="Contoh: 60 (kosongkan jika tanpa batas)" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold focus:border-indigo-600 focus:ring-indigo-600">
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Maksimal Percobaan (Attempts)</label>
                                        <div class="flex items-center gap-2">
                                            <input type="number" id="max_attempts_input" name="max_attempts" value="1" min="0" max="100" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold focus:border-indigo-600 focus:ring-indigo-600">
                                            <label class="inline-flex items-center gap-1.5 px-2.5 py-2.5 bg-white border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition whitespace-nowrap">
                                                <input type="checkbox" name="is_unlimited" value="1" onchange="document.getElementById('max_attempts_input').disabled = this.checked; if(this.checked){ document.getElementById('max_attempts_input').value = 0; }" class="rounded text-indigo-600 focus:ring-indigo-500">
                                                <span class="text-[11px] font-bold text-slate-700">Unlimited</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Tanggal & Waktu Mulai (Opsional)</label>
                                        <input type="datetime-local" name="start_date" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold focus:border-indigo-600 focus:ring-indigo-600">
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Batas Deadline Selesai (Opsional)</label>
                                        <input type="datetime-local" name="end_date" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold focus:border-indigo-600 focus:ring-indigo-600">
                                    </div>

                                    <div class="md:col-span-2 pt-2 border-t border-indigo-100">
                                        <label class="block font-bold text-slate-700 mb-1.5">Target Distribusi Kuis:</label>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                            <label class="flex items-center gap-2.5 p-2.5 border border-indigo-200 rounded-xl bg-white cursor-pointer hover:border-indigo-400 transition">
                                                <input type="radio" name="target_scope" value="all" checked class="text-indigo-600 focus:ring-indigo-500">
                                                <div>
                                                    <span class="block font-bold text-indigo-950 text-xs">Semua Kelas Paralel (Master)</span>
                                                    <span class="block text-[11px] text-slate-500">Kuis akan otomatis berlaku untuk seluruh rombel kelas.</span>
                                                </div>
                                            </label>
                                            <label class="flex items-center gap-2.5 p-2.5 border border-slate-200 rounded-xl bg-white cursor-pointer hover:border-indigo-400 transition">
                                                <input type="radio" name="target_scope" value="class" class="text-indigo-600 focus:ring-indigo-500">
                                                <div>
                                                    <span class="block font-bold text-slate-800 text-xs">Khusus {{ $course->section_name ?: 'Kelas Ini' }}</span>
                                                    <span class="block text-[11px] text-slate-500">Kuis khusus / remedial untuk rombel kelas ini saja.</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end gap-2 pt-2 border-t border-indigo-200">
                                    <button type="button" onclick="document.getElementById('create-quiz-form-container').classList.add('hidden')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-md">Simpan Kuis & Lanjut Buat Soal</button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <!-- DAFTAR KARTU KUIS -->
                    <div class="space-y-3">
                        @forelse ($quizzes as $quiz)
                            <div class="item-card">
                                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="font-bold text-sm text-slate-900">
                                                {{ $quiz->title }}
                                            </h3>

                                            <span class="inline-flex items-center rounded-full border {{ $quiz->quiz_type_badge_class }} px-2.5 py-0.5 text-[11px] font-bold">
                                                {{ $quiz->quiz_type_label }}
                                            </span>

                                            @if($quiz->start_date && now()->lt($quiz->start_date))
                                                <span class="inline-flex items-center rounded-md border border-amber-200 bg-amber-50 text-amber-800 px-2 py-0.5 text-[10.5px] font-bold">
                                                    Belum Dibuka
                                                </span>
                                            @elseif($quiz->end_date && now()->gt($quiz->end_date))
                                                <span class="inline-flex items-center rounded-md border border-slate-300 bg-slate-200 text-slate-700 px-2 py-0.5 text-[10.5px] font-bold">
                                                    Ditutup (Expired)
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-md border border-emerald-200 bg-emerald-50 text-emerald-800 px-2 py-0.5 text-[10.5px] font-bold">
                                                    Aktif & Terbuka
                                                </span>
                                            @endif
                                        </div>

                                        <div class="mt-1.5 flex flex-wrap items-center gap-y-1 gap-x-3 text-xs text-slate-500">
                                            <span>Durasi: <strong class="text-slate-700">{{ $quiz->time_limit ? $quiz->time_limit . ' Menit' : 'Tanpa Batas' }}</strong></span>
                                            <span>Percobaan: <strong class="text-slate-700">{{ $quiz->max_attempts === 0 ? 'Unlimited' : $quiz->max_attempts . 'x' }}</strong></span>
                                            <span>Dibuka: <strong class="text-slate-700">{{ $quiz->start_date ? $quiz->start_date->format('d M Y, H:i') : 'Langsung' }}</strong></span>
                                            @if($quiz->end_date)
                                                <span>Deadline: <strong class="{{ now()->gt($quiz->end_date) ? 'text-rose-600 font-bold' : 'text-slate-700' }}">{{ $quiz->end_date->format('d M Y, H:i') }}</strong></span>
                                            @endif
                                        </div>

                                        @if ($quiz->isFinal())
                                            <p class="mt-1 text-[11px] font-extrabold text-emerald-700">
                                                Kuis Akhir Penentu Kelulusan Sertifikat Digital & Hash Blockchain
                                            </p>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <a href="{{ route('lecturer.courses.quizzes.results.index', [$course->id, $quiz->id]) }}"
                                           class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                                            Lihat Hasil
                                        </a>

                                        @if($isTermActive ?? true)
                                            <button type="button" 
                                                    onclick="document.getElementById('edit-quiz-form-{{ $quiz->id }}').classList.toggle('hidden')" 
                                                    class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold text-xs rounded-xl border border-amber-200 transition">
                                                Waktu & Durasi
                                            </button>

                                            <a href="{{ route('lecturer.courses.quizzes.show', [$course->id, $quiz->id]) }}"
                                               class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                                                + Kelola Soal ({{ $quiz->questions_count ?? $quiz->questions()->count() }})
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('lecturer.courses.quizzes.destroy', [$course->id, $quiz->id]) }}"
                                                  onsubmit="return confirm('Yakin ingin menghapus quiz ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs rounded-xl border border-rose-200 transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                {{-- FORM INLINE EDIT WAKTU KUIS --}}
                                @if($isTermActive ?? true)
                                    <div id="edit-quiz-form-{{ $quiz->id }}" class="hidden mt-3 pt-3 border-t border-slate-200">
                                        <form method="POST" action="{{ route('lecturer.courses.quizzes.update', [$course->id, $quiz->id]) }}" class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs bg-amber-50/50 p-4 rounded-xl border border-amber-200">
                                            @csrf
                                            @method('PUT')

                                            <div>
                                                <label class="block font-bold text-slate-700 mb-1">Judul Kuis</label>
                                                <input type="text" name="title" value="{{ old('title', $quiz->title) }}" class="w-full rounded-xl border-slate-300 p-2 text-xs font-semibold focus:border-amber-600 focus:ring-amber-600" required>
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 mb-1">Durasi (Menit)</label>
                                                <input type="number" name="time_limit" value="{{ old('time_limit', $quiz->time_limit) }}" min="1" placeholder="Kosongkan jika tanpa batas" class="w-full rounded-xl border-slate-300 p-2 text-xs font-semibold focus:border-amber-600 focus:ring-amber-600">
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 mb-1">Max Attempts (Percobaan)</label>
                                                <div class="flex items-center gap-2">
                                                    <input type="number" id="edit_max_attempts_{{ $quiz->id }}" name="max_attempts" value="{{ old('max_attempts', $quiz->max_attempts) }}" min="0" max="100" class="w-full rounded-xl border-slate-300 p-2 text-xs font-semibold focus:border-amber-600 focus:ring-amber-600">
                                                    <label class="inline-flex items-center gap-1.5 px-2 py-2 bg-white border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition whitespace-nowrap">
                                                        <input type="checkbox" name="is_unlimited" value="1" @checked($quiz->max_attempts === 0) onchange="document.getElementById('edit_max_attempts_{{ $quiz->id }}').disabled = this.checked; if(this.checked){ document.getElementById('edit_max_attempts_{{ $quiz->id }}').value = 0; }" class="rounded text-amber-600 focus:ring-amber-500">
                                                        <span class="text-[11px] font-bold text-slate-700">Unlimited</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 mb-1">Tanggal Mulai (Start Date)</label>
                                                <input type="datetime-local" name="start_date" value="{{ $quiz->start_date ? \Carbon\Carbon::parse($quiz->start_date)->format('Y-m-d\TH:i') : '' }}" class="w-full rounded-xl border-slate-300 p-2 text-xs font-semibold focus:border-amber-600 focus:ring-amber-600">
                                            </div>

                                            <div class="md:col-span-2">
                                                <label class="block font-bold text-slate-700 mb-1">Batas Deadline Selesai (End Date)</label>
                                                <input type="datetime-local" name="end_date" value="{{ $quiz->end_date ? \Carbon\Carbon::parse($quiz->end_date)->format('Y-m-d\TH:i') : '' }}" class="w-full rounded-xl border-slate-300 p-2 text-xs font-semibold focus:border-amber-600 focus:ring-amber-600">
                                            </div>

                                            <div class="md:col-span-2 flex justify-end gap-2 pt-2 border-t border-amber-200">
                                                <button type="button" onclick="document.getElementById('edit-quiz-form-{{ $quiz->id }}').classList.add('hidden')" class="px-3 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                                                <button type="submit" class="px-4 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl text-xs shadow-xs">Simpan Pengaturan Kuis</button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="p-8 text-center bg-slate-50 border border-dashed border-slate-200 rounded-2xl text-xs text-slate-400">
                                Belum ada kuis untuk mata kuliah ini. Klik "+ Buat Kuis Baru" di atas untuk menambahkan.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- 2. MODUL MATERI PEMBELAJARAN -->
                <div class="section-card">
                    <div class="section-card-header">
                        <div>
                            <h2 class="section-title">Modul Materi Pembelajaran</h2>
                            <p class="section-sub">Koleksi berkas materi, slide PDF, video, dan panduan belajar untuk rombel kelas ini.</p>
                        </div>

                        @if($isTermActive ?? true)
                            <a href="{{ route('lecturer.materials.create', $course->id) }}" class="btn-action-indigo">
                                + Tambah Materi
                            </a>
                        @endif
                    </div>

                    <div class="space-y-3">
                        @forelse ($materials as $material)
                            <div class="item-card flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-200 text-indigo-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-xs text-slate-900">{{ $material->title }}</h3>
                                        <p class="text-[11px] text-slate-500 mt-0.5">
                                            Diunggah: {{ optional($material->created_at)->format('d M Y, H:i') ?: '-' }}
                                            @if($material->file_path)
                                                &bull; <span class="text-indigo-600 font-semibold">{{ strtoupper(pathinfo($material->file_path, PATHINFO_EXTENSION)) }} Document</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('lecturer.materials.show', $material->id) }}"
                                       class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs rounded-xl border border-indigo-200 transition">
                                        Buka File
                                    </a>

                                    @if($isTermActive ?? true)
                                        <a href="{{ route('lecturer.materials.edit', $material->id) }}"
                                           class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                                            Edit
                                        </a>

                                        <form action="{{ route('lecturer.materials.destroy', $material->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin hapus materi ini dari kelas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                                            <button type="submit"
                                                    class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs rounded-xl border border-rose-200 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center bg-slate-50 border border-dashed border-slate-200 rounded-2xl text-xs text-slate-400">
                                Belum ada modul materi pembelajaran diunggah untuk kelas ini.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN (1fr): THRESHOLD & DAFTAR MAHASISWA -->
            <div class="space-y-6">

                <!-- 1. OTONOMI THRESHOLD SERTIFIKAT -->
                <div class="section-card">
                    <h2 class="section-title text-base flex items-center justify-between">
                        <span>Threshold Kelulusan</span>
                        <span class="text-[10px] font-extrabold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-200">Otonomi Dosen</span>
                    </h2>
                    <p class="section-sub text-xs mt-1">
                        Batas nilai minimal (%) pada Kuis Akhir untuk kelulusan dan penerbitan Sertifikat Digital Blockchain.
                    </p>

                    <div class="mt-4 pt-3 border-t border-slate-100">
                        @if($isTermActive ?? true)
                            <form action="{{ route('lecturer.courses.update', $course->id) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <div class="relative flex-1">
                                    <input type="number" 
                                           name="certificate_threshold" 
                                           value="{{ old('certificate_threshold', $course->certificate_threshold ?? 75) }}" 
                                           min="0" 
                                           max="100" 
                                           class="w-full rounded-xl border-slate-300 text-sm font-extrabold text-indigo-900 text-center focus:border-indigo-600 focus:ring-indigo-600 py-2" 
                                           required>
                                    <span class="absolute right-3 top-2.5 text-xs font-bold text-slate-400">%</span>
                                </div>
                                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition whitespace-nowrap">
                                    Simpan
                                </button>
                            </form>
                        @else
                            <div class="flex items-center justify-between p-3 bg-slate-50 border border-slate-200 rounded-xl">
                                <span class="text-xs text-slate-500 font-bold">Threshold Terkunci:</span>
                                <span class="text-sm font-extrabold text-slate-900">{{ $course->certificate_threshold ?? 75 }}%</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 2. DAFTAR MAHASISWA TERDAFTAR -->
                <div class="section-card">
                    <div class="flex items-center justify-between gap-2 mb-4">
                        <div>
                            <h2 class="section-title text-base">Mahasiswa Terdaftar</h2>
                            <p class="section-sub text-xs">Total <strong>{{ $courseEnrollments->count() }}</strong> mahasiswa di rombel ini</p>
                        </div>
                        @if(!($isTermActive ?? true))
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-slate-100 text-slate-600 border border-slate-200">
                                Arsip
                            </span>
                        @endif
                    </div>

                    @if ($courseEnrollments->count())
                        <div class="space-y-3 max-h-[580px] overflow-y-auto pr-1">
                            @foreach ($courseEnrollments as $enrollment)
                                @php
                                    $studentUser = $enrollment->user;
                                    $progress = $enrollment->progress_percent ?? 0;
                                    $isPassed = $progress >= ($course->certificate_threshold ?? 75);
                                @endphp
                                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl hover:bg-white hover:border-indigo-200 transition">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-800 font-bold flex items-center justify-center text-xs flex-shrink-0">
                                                {{ strtoupper(substr($studentUser->name ?? 'M', 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-bold text-slate-900 text-xs truncate">
                                                    {{ $studentUser->name ?? 'Unknown Student' }}
                                                </p>
                                                <p class="text-[10.5px] text-slate-500 truncate">
                                                    {{ $studentUser->email ?? 'No email' }}
                                                </p>
                                            </div>
                                        </div>

                                        <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-md whitespace-nowrap {{ $enrollment->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : ($enrollment->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-slate-200 text-slate-700') }}">
                                            {{ $enrollment->status === 'completed' ? 'Completed' : ($enrollment->status === 'in_progress' ? 'In Progress' : 'Not Started') }}
                                        </span>
                                    </div>

                                    <div class="mt-2.5 pt-2 border-t border-slate-200/60">
                                        <div class="flex items-center justify-between text-[10.5px] font-semibold mb-1">
                                            <span class="text-slate-500">Progres Belajar</span>
                                            <span class="font-bold {{ $isPassed ? 'text-emerald-700' : 'text-slate-800' }}">{{ $progress }}%</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                            <div class="h-1.5 rounded-full {{ $isPassed ? 'bg-emerald-500' : 'bg-indigo-600' }}" style="width: {{ min(100, max(0, $progress)) }}%"></div>
                                        </div>

                                        <div class="flex items-center justify-between mt-2 pt-1 text-[10px] text-slate-400">
                                            <span>Terdaftar: {{ $enrollment->created_at ? $enrollment->created_at->format('d M Y') : '-' }}</span>
                                            @if($studentUser)
                                                <a href="{{ route('lecturer.students.portfolio', $studentUser->id) }}" class="text-indigo-600 font-bold hover:underline">
                                                    Portofolio →
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-6 text-center bg-slate-50 border border-dashed border-slate-200 rounded-xl text-xs text-slate-400">
                            Belum ada mahasiswa yang terdaftar di rombel kelas ini.
                        </div>
                    @endif
                </div>

            </div>

        </div>

    </div>
</div>
</x-app-layout>