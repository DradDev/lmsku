<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.vendor-show-wrap {
    min-height: 100vh;
    background: linear-gradient(180deg, #f8faff 0%, #f1f5f9 100%);
    color: #0f172a;
    padding: 2rem 0 4.5rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.vendor-container {
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

.btn-header-purple {
    background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(124, 58, 237, 0.25);
}

.btn-header-purple:hover {
    box-shadow: 0 4px 14px rgba(124, 58, 237, 0.35);
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
    color: #7c3aed;
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

.btn-action-purple {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 700;
    padding: 8px 14px;
    border-radius: 10px;
    background: #7c3aed;
    color: #ffffff;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-action-purple:hover {
    background: #6d28d9;
    color: #ffffff;
}

.batch-table-container {
    overflow-x: auto;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    background: #ffffff;
}

.batch-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12.5px;
    text-align: left;
}

.batch-table th {
    background: #f8fafc;
    color: #475569;
    font-weight: 800;
    text-transform: uppercase;
    font-size: 10.5px;
    letter-spacing: 0.5px;
    padding: 12px 16px;
    border-bottom: 1px solid #e2e8f0;
}

.batch-table td {
    padding: 12px 16px;
    border-bottom: 1px solid #f1f5f9;
    color: #1e293b;
    vertical-align: middle;
}

.batch-table tr:last-child td {
    border-bottom: none;
}

.batch-table tr.active-row {
    background: #faf5ff;
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

.badge-pill {
    font-size: 10.5px;
    font-weight: 800;
    padding: 2.5px 8px;
    border-radius: 6px;
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

<div class="vendor-show-wrap">
    <div class="vendor-container">

        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-xs text-xs font-bold">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-600 text-white text-xs">✓</span>
                <div>
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 flex items-center gap-3 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl shadow-xs text-xs font-bold">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-rose-600 text-white text-xs">✕</span>
                <div>
                    <strong>Peringatan!</strong> {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- TOP BAR NAV -->
        <div class="top-bar-nav">
            <a href="{{ route('vendor.courses.index') }}" class="back-link">
                ← Kembali ke Daftar Program
            </a>

            <div class="header-actions-group">
                <button type="button" 
                        onclick="document.getElementById('launch_batch_modal').classList.remove('hidden')"
                        class="btn-header btn-header-purple">
                    <span>+ Launch Batch Baru</span>
                </button>

                <button type="button"
                        onclick="document.getElementById('edit_program_modal').classList.remove('hidden')"
                        class="btn-header btn-header-secondary">
                    ✏️ Edit Info Kurikulum
                </button>
            </div>
        </div>

        <!-- HERO HEADER CARD -->
        <div class="hero-card">
            <p class="hero-eyebrow">Program Sertifikasi Industri &bull; Kurikulum Induk Terpusat</p>
            <h1 class="hero-title">{{ $course->name }}</h1>

            <div class="hero-meta-row">
                <span class="hero-badge bg-purple-50 text-purple-800 border border-purple-200">
                    {{ optional($course->category)->name ?? 'Sertifikasi Industri' }}
                </span>
                <span class="hero-badge bg-slate-100 text-slate-700">
                    Level: <strong>{{ $course->level ?? 'Beginner' }}</strong>
                </span>
                @if($course->masterCourse && $course->masterCourse->code)
                    <span class="hero-badge bg-slate-50 text-slate-500 border border-slate-200">
                        Code: {{ $course->masterCourse->code }}
                    </span>
                @endif
                <span class="hero-badge bg-indigo-50 text-indigo-800 border border-indigo-200">
                    {{ $allBatches->count() }} Angkatan Batch Terdaftar
                </span>
            </div>

            <p class="hero-desc">
                {{ $course->description ?: 'Pengelolaan kurikulum terpusat, modul pembelajaran, bank kuis evaluasi, dan kelulusan sertifikat industri mahasiswa.' }}
            </p>

            @if($course->masterCourse && $course->masterCourse->skills && $course->masterCourse->skills->isNotEmpty())
                <div class="flex flex-wrap items-center gap-1.5 mt-3">
                    <span class="text-[11px] font-bold text-slate-400">Target Kompetensi:</span>
                    @foreach($course->masterCourse->skills as $cSkill)
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 border border-purple-100">
                            {{ $cSkill->name }}
                        </span>
                    @endforeach
                </div>
            @endif

            <!-- QUICK STATS -->
            <div class="hero-stats-strip">
                <div class="hero-stat-card">
                    <div class="hero-stat-label">Total Peserta (Semua Batch)</div>
                    <div class="hero-stat-val text-purple-700">{{ $allBatches->sum(fn($b) => $b->enrollments_count ?? $b->enrollments->count()) }}</div>
                </div>
                <div class="hero-stat-card">
                    <div class="hero-stat-label">Lulus / Completed (Batch Ini)</div>
                    <div class="hero-stat-val text-emerald-600">{{ $completedStudentCount }}</div>
                </div>
                <div class="hero-stat-card">
                    <div class="hero-stat-label">Modul Materi Bersama</div>
                    <div class="hero-stat-val text-indigo-600">{{ $materials->count() }}</div>
                </div>
                <div class="hero-stat-card">
                    <div class="hero-stat-label">Bank Kuis & Soal</div>
                    <div class="hero-stat-val text-amber-600">{{ $quizzes->count() }}</div>
                </div>
            </div>
        </div>

        <!-- SECTION: MANAJEMEN ANGKATAN BATCH (ALA ADMIN OFFERINGS) -->
        <div class="section-card">
            <div class="section-card-header">
                <div>
                    <h2 class="section-title">🏷️ Daftar & Kontrol Angkatan Batch</h2>
                    <p class="section-sub">Kelola jadwal rilis, status buka/tutup pendaftaran, threshold nilai, dan peserta per angkatan secara independen.</p>
                </div>

                <button type="button" 
                        onclick="document.getElementById('launch_batch_modal').classList.remove('hidden')" 
                        class="btn-action-purple">
                    + Launch Batch Baru
                </button>
            </div>

            <div class="batch-table-container">
                <table class="batch-table">
                    <thead>
                        <tr>
                            <th>Angkatan Batch</th>
                            <th>Status Pendaftaran</th>
                            <th>Durasi & Jadwal</th>
                            <th>Threshold Kelulusan</th>
                            <th>Mahasiswa</th>
                            <th class="text-right">Aksi Manajemen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allBatches as $batchItem)
                            @php
                                $isCurrent = $batchItem->id === $course->id;
                                $stdCount = $batchItem->enrollments_count ?? $batchItem->enrollments->count();
                            @endphp
                            <tr class="{{ $isCurrent ? 'active-row' : '' }}">
                                <td>
                                    <div class="flex items-center gap-2">
                                        <strong class="text-slate-900 font-bold text-xs">{{ $batchItem->batch_name ?: 'Batch ' . $loop->iteration }}</strong>
                                        @if($isCurrent)
                                            <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-purple-600 text-white shadow-xs">
                                                Aktif Ditampilkan
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if(!$batchItem->is_archived)
                                        <span class="badge-pill bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1">
                                            <span>✓</span> Terbuka (Pendaftaran Aktif)
                                        </span>
                                    @else
                                        <span class="badge-pill bg-slate-100 text-slate-600 border border-slate-200 inline-flex items-center gap-1">
                                            <span>📦</span> Draft / Diarsipkan
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="font-semibold text-slate-700 text-xs">{{ $batchItem->duration_weeks ?? 4 }} Minggu</span>
                                    @if($batchItem->start_date || $batchItem->end_date)
                                        <div class="text-[10.5px] text-slate-400 mt-0.5">
                                            {{ $batchItem->start_date ? \Carbon\Carbon::parse($batchItem->start_date)->format('d M Y') : '-' }} &bull; {{ $batchItem->end_date ? \Carbon\Carbon::parse($batchItem->end_date)->format('d M Y') : 'Selesai' }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="font-extrabold text-purple-900 text-xs">{{ $batchItem->certificate_threshold ?? 75 }}%</span>
                                </td>
                                <td>
                                    <span class="font-bold text-slate-800 text-xs">{{ $stdCount }} Mhs</span>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                        @if(!$isCurrent)
                                            <a href="{{ route('vendor.courses.show', $batchItem->id) }}" 
                                               class="px-2.5 py-1 bg-purple-50 hover:bg-purple-100 text-purple-700 font-bold text-xs rounded-lg border border-purple-200 transition">
                                                👥 Kelola Peserta
                                            </a>
                                        @endif

                                        <button type="button" 
                                                onclick="document.getElementById('edit_batch_modal_{{ $batchItem->id }}').classList.remove('hidden')"
                                                class="px-2.5 py-1 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs rounded-lg border border-slate-300 transition">
                                            ✏️ Edit Batch
                                        </button>

                                        <form action="{{ route('vendor.courses.toggle-archive', $batchItem->id) }}" method="POST" onsubmit="return confirm('Ubah status pendaftaran angkatan {{ $batchItem->batch_name }}?')">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 {{ $batchItem->is_archived ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border-slate-200 hover:bg-slate-200' }} font-bold text-xs rounded-lg border transition">
                                                {{ $batchItem->is_archived ? 'Buka Batch' : 'Tutup / Arsip' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-slate-400 text-xs">
                                    Belum ada angkatan batch terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MAIN 2-COLUMN LAYOUT -->
        <div class="content-grid">

            <!-- LEFT COLUMN: SILABUS KURIKULUM INDUK (MATERI & KUIS) -->
            <div class="space-y-6">

                <!-- SECTION 1: BANK KUIS & EVALUASI -->
                <div class="section-card">
                    <div class="section-card-header">
                        <div>
                            <h2 class="section-title">Bank Kuis & Evaluasi</h2>
                            <p class="section-sub">Kelola kuis harian/mingguan dan Kuis Akhir kelulusan sertifikasi.</p>
                        </div>

                        <button type="button" 
                                onclick="document.getElementById('create-quiz-form-vendor').classList.toggle('hidden')" 
                                class="btn-action-purple">
                            + Buat Kuis Baru
                        </button>
                    </div>

                    <!-- FORM BUAT KUIS BARU -->
                    <div id="create-quiz-form-vendor" class="hidden mb-6 p-5 bg-purple-50/50 border border-purple-200 rounded-2xl transition">
                        <h3 class="text-xs font-extrabold text-purple-900 mb-3 flex items-center gap-2">
                            Form Buat Kuis Evaluasi Baru
                        </h3>
                        <form method="POST" action="{{ route('vendor.quizzes.store', $course->id) }}" class="space-y-4 text-xs">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Judul Kuis</label>
                                    <input type="text" name="title" placeholder="Contoh: Final Certification Exam" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold bg-white" required>
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Tipe Kuis</label>
                                    <select name="quiz_type" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-bold text-purple-900 bg-white" required>
                                        <option value="daily">Kuis Biasa / Harian (Section Quiz)</option>
                                        <option value="weekly">Kuis Mingguan / Evaluasi Bab</option>
                                        <option value="final">Kuis Akhir (Penentu Sertifikat)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Durasi Pengerjaan (Menit)</label>
                                    <input type="number" name="time_limit" min="1" placeholder="Kosongkan jika tanpa batas" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold bg-white">
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Maksimal Percobaan</label>
                                    <div class="flex items-center gap-2">
                                        <input type="number" id="max_attempts_input_vendor" name="max_attempts" value="1" min="0" max="100" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold bg-white">
                                        <label class="inline-flex items-center gap-1.5 px-2.5 py-2.5 bg-slate-100 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-200 transition whitespace-nowrap">
                                            <input type="checkbox" name="is_unlimited" value="1" onchange="document.getElementById('max_attempts_input_vendor').disabled = this.checked; if(this.checked){ document.getElementById('max_attempts_input_vendor').value = 0; }" class="rounded text-purple-600 focus:ring-purple-500">
                                            <span class="text-[11px] font-bold text-slate-700">Unlimited</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Tanggal Mulai (Opsional)</label>
                                    <input type="datetime-local" name="start_date" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold bg-white">
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Deadline Selesai (Opsional)</label>
                                    <input type="datetime-local" name="end_date" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold bg-white">
                                </div>
                            </div>

                            <div class="flex justify-end gap-2 pt-2 border-t border-purple-200">
                                <button type="button" onclick="document.getElementById('create-quiz-form-vendor').classList.add('hidden')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                                <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl text-xs shadow-md">Simpan Kuis</button>
                            </div>
                        </form>
                    </div>

                    <!-- DAFTAR KUIS -->
                    <div class="space-y-3">
                        @forelse ($quizzes as $quiz)
                            <div class="item-card">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="font-bold text-slate-900 text-sm">
                                                {{ $quiz->title }}
                                            </h3>

                                            <span class="badge-pill {{ $quiz->quiz_type === 'final' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-purple-50 text-purple-800 border border-purple-200' }}">
                                                {{ $quiz->quiz_type === 'final' ? 'Final Quiz' : ($quiz->quiz_type === 'weekly' ? 'Weekly Quiz' : 'Daily Quiz') }}
                                            </span>

                                            @if($quiz->start_date && now()->lt($quiz->start_date))
                                                <span class="badge-pill bg-amber-50 text-amber-800 border border-amber-200">
                                                    Terjadwal
                                                </span>
                                            @elseif($quiz->end_date && now()->gt($quiz->end_date))
                                                <span class="badge-pill bg-slate-100 text-slate-600">
                                                    Ditutup
                                                </span>
                                            @else
                                                <span class="badge-pill bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    Aktif
                                                </span>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-3 text-xs text-slate-500 mt-1.5">
                                            <span>Durasi: <strong>{{ $quiz->time_limit ? $quiz->time_limit . ' Menit' : 'Tanpa Batas' }}</strong></span>
                                            <span>&bull;</span>
                                            <span>Percobaan: <strong>{{ $quiz->max_attempts === 0 ? 'Unlimited' : $quiz->max_attempts . 'x' }}</strong></span>
                                            <span>&bull;</span>
                                            <span>Soal: <strong>{{ $quiz->questions ? $quiz->questions->count() : 0 }} Butir</strong></span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 flex-wrap">
                                        <a href="{{ route('vendor.quizzes.show', $quiz) }}"
                                           class="px-3 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 font-bold text-xs rounded-lg border border-purple-200 transition">
                                            Kelola Soal ({{ $quiz->questions ? $quiz->questions->count() : 0 }})
                                        </a>

                                        <form method="POST"
                                              action="{{ route('vendor.quizzes.destroy', $quiz) }}"
                                              onsubmit="return confirm('Yakin ingin menghapus kuis ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-xs rounded-lg border border-rose-200 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 text-center text-xs text-slate-500">
                                Belum ada kuis untuk kurikulum ini. Klik "+ Buat Kuis Baru" di atas untuk menambahkan.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- SECTION 2: MODUL MATERI PEMBELAJARAN -->
                <div class="section-card">
                    <div class="section-card-header">
                        <div>
                            <h2 class="section-title">Modul Materi Kurikulum</h2>
                            <p class="section-sub">Materi modul pelatihan bersama yang diwariskan ke seluruh angkatan batch.</p>
                        </div>

                        <button type="button" 
                                onclick="document.getElementById('upload-material-form-vendor').classList.toggle('hidden')" 
                                class="btn-action-purple">
                            + Upload Modul
                        </button>
                    </div>

                    <!-- FORM UPLOAD MATERI -->
                    <div id="upload-material-form-vendor" class="hidden mb-6 p-5 bg-purple-50/50 border border-purple-200 rounded-2xl transition">
                        <h3 class="text-xs font-extrabold text-purple-900 mb-3 flex items-center gap-2">
                            Form Upload Modul Pembelajaran Baru
                        </h3>
                        <form action="{{ route('vendor.materials.store', $course) }}" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs">
                            @csrf
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Judul Modul Materi</label>
                                <input type="text" name="title" required placeholder="Contoh: Modul 1 - Setup Kubernetes Cluster"
                                       class="w-full border-slate-300 rounded-xl text-xs p-2.5 bg-white">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Berkas File (PDF/DOCX/PPTX/MP4/ZIP)</label>
                                <input type="file" name="file" required accept=".pdf,.docx,.pptx,.mp4,.zip"
                                       class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-100 file:text-purple-700">
                            </div>
                            <div class="flex justify-end gap-2 pt-2 border-t border-purple-200">
                                <button type="button" onclick="document.getElementById('upload-material-form-vendor').classList.add('hidden')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                                <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition">Upload Modul</button>
                            </div>
                        </form>
                    </div>

                    <!-- DAFTAR MATERI -->
                    @if ($materials->count())
                        <div class="grid gap-3 md:grid-cols-2">
                            @foreach ($materials as $material)
                                <div class="item-card">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-start gap-2.5">
                                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                                📄
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-slate-900 text-xs line-clamp-1">
                                                    {{ $material->title }}
                                                </h3>
                                                <p class="text-[11px] text-slate-400 mt-0.5">
                                                    {{ optional($material->created_at)->format('d M Y') ?: '-' }}
                                                </p>
                                            </div>
                                        </div>

                                        <form action="{{ route('vendor.materials.destroy', $material) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin hapus modul ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs font-bold p-1">
                                                ✕
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 text-center text-xs text-slate-500">
                            Belum ada modul materi untuk kurikulum ini. Klik "+ Upload Modul" di atas.
                        </div>
                    @endif
                </div>

            </div>

            <!-- RIGHT COLUMN: PESERTA ANGKATAN TERPILIH -->
            <div class="space-y-6">

                <div class="section-card">
                    <div class="flex items-center justify-between gap-2 mb-4">
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">
                                Peserta Angkatan
                            </h3>
                            <p class="text-xs text-slate-500">
                                {{ $course->batch_name }} &bull; <strong>{{ $course->enrollments->count() }}</strong> Mahasiswa
                            </p>
                        </div>

                        <span class="badge-pill bg-purple-50 text-purple-700 border border-purple-200">
                            {{ $course->batch_name }}
                        </span>
                    </div>

                    @if ($course->enrollments->count())
                        <div class="space-y-3">
                            @foreach ($course->enrollments as $enrollment)
                                @php
                                    $stdUser = $enrollment->user;
                                    $progress = $enrollment->progress_percent ?? 0;
                                    $isPassed = $progress >= ($course->certificate_threshold ?? 75);
                                @endphp
                                <div class="item-card">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-800 font-bold flex items-center justify-center text-xs flex-shrink-0">
                                                {{ strtoupper(substr($stdUser->name ?? 'M', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900 text-xs">
                                                    {{ $stdUser->name ?? 'Unknown Student' }}
                                                </p>
                                                <p class="text-[11px] text-slate-400">
                                                    {{ $stdUser->email ?? 'No email' }}
                                                </p>
                                            </div>
                                        </div>

                                        <span class="badge-pill {{ $enrollment->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : ($enrollment->status === 'in_progress' ? 'bg-purple-100 text-purple-800' : 'bg-slate-200 text-slate-700') }}">
                                            {{ $enrollment->status === 'completed' ? 'Completed' : ($enrollment->status === 'in_progress' ? 'In Progress' : 'Not Started') }}
                                        </span>
                                    </div>

                                    <div class="mt-2.5 pt-2.5 border-t border-slate-200/70">
                                        <div class="flex items-center justify-between text-[11px] font-semibold mb-1">
                                            <span class="text-slate-500">Progres Belajar</span>
                                            <span class="font-bold {{ $isPassed ? 'text-emerald-700' : 'text-slate-800' }}">{{ $progress }}%</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                            <div class="h-1.5 rounded-full {{ $isPassed ? 'bg-emerald-500' : 'bg-purple-600' }}" style="width: {{ min(100, max(0, $progress)) }}%"></div>
                                        </div>

                                        <div class="flex items-center justify-between mt-2 pt-1 text-[10px] text-slate-400">
                                            <span>{{ $enrollment->created_at ? $enrollment->created_at->format('d M Y') : '-' }}</span>
                                            @if($stdUser)
                                                <a href="{{ route('vendor.students.portfolio', $stdUser->id) }}" class="text-purple-600 font-bold hover:underline">
                                                    Portofolio →
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 text-center text-xs text-slate-500">
                            Belum ada mahasiswa terdaftar pada angkatan <strong>{{ $course->batch_name }}</strong> ini.
                        </div>
                    @endif
                </div>

            </div>

        </div>

        <!-- MODAL 1: EDIT PROGRAM KURIKULUM INDUK -->
        <div id="edit_program_modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-xl w-full p-6 md:p-8 space-y-4 animate-in fade-in zoom-in duration-200">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-lg font-extrabold text-slate-900">
                        Edit Informasi Kurikulum Sertifikasi
                    </h3>
                    <button type="button" onclick="document.getElementById('edit_program_modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold text-sm">
                        ✕
                    </button>
                </div>

                <form action="{{ route('vendor.courses.update', $course) }}" method="POST" class="space-y-3.5 text-xs">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Program Sertifikasi <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $course->name) }}" required class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold text-slate-900">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Kategori</label>
                            <select name="category_id" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold text-slate-800">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id', $course->category_id) == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Level Kesulitan <span class="text-rose-500">*</span></label>
                            <select name="level" required class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold text-slate-800">
                                <option value="Beginner" @selected(old('level', $course->level) === 'Beginner')>Beginner</option>
                                <option value="Intermediate" @selected(old('level', $course->level) === 'Intermediate')>Intermediate</option>
                                <option value="Advanced" @selected(old('level', $course->level) === 'Advanced')>Advanced</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Deskripsi Silabus Pelatihan <span class="text-rose-500">*</span></label>
                        <textarea name="description" rows="3" required class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-normal text-slate-800">{{ old('description', $course->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">Target Kompetensi (Skills) <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-36 overflow-y-auto p-2 bg-slate-50 rounded-xl border border-slate-200">
                            @php
                                $selectedSkillIds = $course->masterCourse && $course->masterCourse->skills ? $course->masterCourse->skills->pluck('id')->toArray() : $course->skills->pluck('id')->toArray();
                            @endphp
                            @foreach($skills as $skill)
                                <label class="flex items-center gap-1.5 cursor-pointer">
                                    <input type="checkbox" name="skill_ids[]" value="{{ $skill->id }}" @checked(in_array($skill->id, $selectedSkillIds)) class="rounded text-purple-600 focus:ring-purple-500">
                                    <span class="text-[11px] font-semibold text-slate-700">{{ $skill->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-3 flex items-center justify-end gap-2 border-t border-slate-100">
                        <button type="button" onclick="document.getElementById('edit_program_modal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-purple-700 hover:bg-purple-800 text-white font-extrabold text-xs rounded-xl shadow-md">
                            Simpan Perubahan Kurikulum
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL 2: LAUNCH BATCH BARU -->
        <div id="launch_batch_modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 md:p-8 space-y-4 animate-in fade-in zoom-in duration-200">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-lg font-extrabold text-slate-900">
                        Launch Angkatan Batch Baru
                    </h3>
                    <button type="button" onclick="document.getElementById('launch_batch_modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold text-sm">
                        ✕
                    </button>
                </div>

                <form action="{{ route('vendor.courses.launch-batch', $course) }}" method="POST" class="space-y-4 text-xs">
                    @csrf
                    
                    <div class="p-3.5 bg-purple-50 rounded-2xl border border-purple-100 text-xs text-purple-900 leading-relaxed">
                        <strong>Kurikulum 3NF:</strong> Modul materi dan bank kuis dari kurikulum <strong>{{ $course->name }}</strong> otomatis diwariskan ke batch baru ini tanpa duplikasi berkas fisik server.
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Nama Angkatan Batch Baru <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="batch_name" required placeholder="Contoh: Batch 2 - Q3 2026 Intake"
                               class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-2.5 font-extrabold text-purple-900 bg-white">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Threshold Sertifikat (%) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" name="certificate_threshold" value="{{ $course->certificate_threshold ?? 75 }}" min="0" max="100" required
                                   class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-2.5 font-bold text-slate-900 bg-white">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Durasi (Minggu)
                            </label>
                            <input type="number" name="duration_weeks" value="{{ $course->duration_weeks ?? 4 }}" min="1" required
                                   class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-2.5 font-bold text-slate-900 bg-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Tanggal Mulai (Opsional)
                            </label>
                            <input type="date" name="start_date" class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-2.5 text-slate-800 bg-white">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Tanggal Selesai (Opsional)
                            </label>
                            <input type="date" name="end_date" class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-2.5 text-slate-800 bg-white">
                        </div>
                    </div>

                    <div class="pt-3 flex items-center justify-end gap-2 border-t border-slate-100">
                        <button type="button" onclick="document.getElementById('launch_batch_modal').classList.add('hidden')"
                                class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2 bg-purple-700 hover:bg-purple-800 text-white font-extrabold text-xs rounded-xl shadow-md transition">
                            Rilis Batch Baru Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL 3: EDIT INDIVIDUAL BATCHES (LOOP PER BATCH) -->
        @foreach($allBatches as $b)
            <div id="edit_batch_modal_{{ $b->id }}" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 md:p-8 space-y-4 animate-in fade-in zoom-in duration-200 text-xs">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="text-lg font-extrabold text-slate-900">
                            Edit Pengaturan {{ $b->batch_name }}
                        </h3>
                        <button type="button" onclick="document.getElementById('edit_batch_modal_{{ $b->id }}').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold text-sm">
                            ✕
                        </button>
                    </div>

                    <form action="{{ route('vendor.courses.update-batch', $b->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Nama Angkatan Batch <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="batch_name" value="{{ old('batch_name', $b->batch_name) }}" required
                                   class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-2.5 font-bold text-slate-900 bg-white">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Threshold Sertifikat (%) <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" name="certificate_threshold" value="{{ old('certificate_threshold', $b->certificate_threshold ?? 75) }}" min="0" max="100" required
                                       class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-2.5 font-extrabold text-purple-900 bg-white">
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Durasi (Minggu)
                                </label>
                                <input type="number" name="duration_weeks" value="{{ old('duration_weeks', $b->duration_weeks ?? 4) }}" min="1" required
                                       class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-2.5 font-bold text-slate-900 bg-white">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Tanggal Mulai
                                </label>
                                <input type="date" name="start_date" value="{{ $b->start_date ? \Carbon\Carbon::parse($b->start_date)->format('Y-m-d') : '' }}"
                                       class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-2.5 text-slate-800 bg-white">
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Tanggal Selesai
                                </label>
                                <input type="date" name="end_date" value="{{ $b->end_date ? \Carbon\Carbon::parse($b->end_date)->format('Y-m-d') : '' }}"
                                       class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-2.5 text-slate-800 bg-white">
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Status Publikasi Angkatan Ini
                            </label>
                            <select name="is_archived" class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-2.5 font-bold text-purple-900 bg-white">
                                <option value="0" @selected(!$b->is_archived)>Terbuka (Pendaftaran Aktif Mahasiswa)</option>
                                <option value="1" @selected($b->is_archived)>Draft / Ditutup (Arsip Internal)</option>
                            </select>
                        </div>

                        <div class="pt-3 flex items-center justify-end gap-2 border-t border-slate-100">
                            <button type="button" onclick="document.getElementById('edit_batch_modal_{{ $b->id }}').classList.add('hidden')"
                                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl">
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-5 py-2 bg-purple-700 hover:bg-purple-800 text-white font-extrabold text-xs rounded-xl shadow-md">
                                Simpan Perubahan Batch
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

    </div>
</div>
</x-app-layout>
