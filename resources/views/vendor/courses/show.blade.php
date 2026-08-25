<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.vendor-show-wrap {
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
    background: linear-gradient(135deg, #faf5ff 0%, #ffffff 50%, #f8fafc 100%);
    border: 2px solid #e9d5ff;
    border-radius: 18px;
    box-shadow: 0 4px 14px rgba(124, 58, 237, 0.06);
}

.custom-select-control {
    background-color: #ffffff;
    border: 2px solid #c084fc;
    border-radius: 14px;
    padding: 0.65rem 1rem;
    font-size: 0.875rem;
    font-weight: 800;
    color: #0f172a;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(124, 58, 237, 0.08);
}

.custom-select-control:focus {
    border-color: #7c3aed;
    box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.15);
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
    background: #faf5ff;
    border-color: #c084fc;
    color: #6b21a8;
}

.quick-pill.active {
    background: #7c3aed;
    border-color: #7c3aed;
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(124, 58, 237, 0.25);
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
    $allBatches = $allBatches ?? collect([$course]);
    $initialBatchId = request()->query('batch_id') ? "'" . request()->query('batch_id') . "'" : "'null'";
@endphp

<div class="vendor-show-wrap max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6" 
     x-data="{ selectedBatchId: {{ $initialBatchId }} }">

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

    {{-- TOP NAVIGATION & ACTIONS BAR --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <a href="{{ route('vendor.courses.index') }}" 
           class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Daftar Kursus Industri</span>
        </a>

        <div class="flex flex-wrap items-center gap-2">
            @if(isset($retakeRequests) && $retakeRequests->count() > 0)
                <a href="{{ route('vendor.courses.retake-requests.index', $course->id) }}"
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

            <a href="{{ route('vendor.courses.edit', $course->id) }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
                Edit Kursus
            </a>
        </div>
    </div>

    {{-- 1. MASTER COURSE HERO CARD --}}
    <div class="compro-card p-6 space-y-3">
        <div class="flex items-center gap-2 flex-wrap">
            <span class="px-2.5 py-0.5 rounded-md bg-purple-50 text-purple-700 border border-purple-100 text-[11px] font-extrabold">
                Sertifikasi Industri Mitra
            </span>
            <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-bold font-mono">
                {{ $masterCourse->code ?? ('VMC-' . $course->id) }}
            </span>
            <span class="px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-100 text-[11px] font-extrabold">
                Level: {{ $course->level ?? 'Beginner' }}
            </span>
            <span class="text-xs font-bold text-slate-400">
                • Total {{ $allBatches->count() }} Batch Pelatihan
            </span>
        </div>

        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                {{ $masterCourse->name ?? $course->name }}
            </h1>
            @if(!empty($course->description))
                <p class="text-xs text-slate-500 mt-1 max-w-3xl leading-relaxed">
                    {{ $course->description }}
                </p>
            @endif
        </div>

        @if($course->skills && $course->skills->isNotEmpty())
            <div class="flex flex-wrap items-center gap-1.5 pt-2 border-t border-slate-100">
                <span class="text-[11px] font-bold text-slate-400">Kompetensi Target:</span>
                @foreach($course->skills as $cSkill)
                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 border border-purple-100">
                        {{ $cSkill->name }}
                    </span>
                @endforeach
            </div>
        @endif
    </div>

    {{-- 2. CONTROL CENTER: PEMILIHAN BATCH PELATIHAN (PROMINENT BANNER) --}}
    <div class="selector-banner p-5 sm:p-6 space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            {{-- LABEL & IKON --}}
            <div class="flex items-start sm:items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-purple-700 text-white flex items-center justify-center font-extrabold shadow-md shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-extrabold text-purple-700 uppercase tracking-wider">Kontrol Batch</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-600"></span>
                        <span class="text-xs font-bold text-slate-500">{{ $allBatches->count() }} Batch Pelatihan</span>
                    </div>
                    <h2 class="text-lg font-extrabold text-slate-900 tracking-tight mt-0.5">
                        Pilih Batch Pelatihan untuk Dikelola
                    </h2>
                    <p class="text-xs text-slate-600 mt-0.5">
                        Pilih batch pelatihan aktif untuk mengelola modul industri, ujian sertifikasi, dan data peserta.
                    </p>
                </div>
            </div>

            {{-- DROPDOWN UTAMA --}}
            <div class="flex items-center gap-2.5 w-full lg:w-auto shrink-0">
                <div class="relative flex-1 lg:w-80">
                    <select x-model="selectedBatchId" 
                            class="custom-select-control w-full pr-10 cursor-pointer">
                        <option value="null">-- Silakan Pilih Batch Pelatihan --</option>
                        @foreach($allBatches as $batchItem)
                            @php
                                $bCount = $batchItem->enrollments ? $batchItem->enrollments->count() : 0;
                            @endphp
                            <option value="{{ $batchItem->id }}">
                                {{ $batchItem->batch_name ?: ('Batch ' . $loop->iteration) }} • {{ $bCount }} Peserta (KKM {{ $batchItem->certificate_threshold ?? 75 }}%)
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="button" 
                        x-show="selectedBatchId !== 'null' && selectedBatchId !== null" 
                        @click="selectedBatchId = 'null'"
                        style="display: none;"
                        class="px-3.5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition whitespace-nowrap shadow-2xs">
                    ✕ Reset
                </button>
            </div>

        </div>


    {{-- 3. STATE AWAL: BELUM MEMILIH BATCH --}}
    <div x-show="selectedBatchId === 'null' || selectedBatchId === null" class="compro-card p-12 text-center space-y-3">
        <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center mx-auto shadow-xs">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        <h3 class="text-base font-extrabold text-slate-800">Silakan Pilih Batch Pelatihan</h3>
        <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
            Gunakan menu dropdown atau pintasan batch di atas untuk melihat modul materi industri, ujian sertifikasi, setting KKM kelulusan, dan data peserta terdaftar.
        </p>
    </div>

    {{-- 4. STATE TERPILIH: WORKSPACE DETAIL BATCH TERPILIH --}}
    <div x-show="selectedBatchId !== 'null' && selectedBatchId !== null" style="display: none;" class="space-y-6">
        @foreach($allBatches as $batchItem)
            @php
                $batchEnrollments = $batchItem->enrollments ?? collect();
            @endphp
            <div x-show="selectedBatchId == {{ $batchItem->id }}" class="space-y-6">

                {{-- SUB-BAR BATCH AKTIF --}}
                <div class="p-4 bg-purple-50/80 border border-purple-200 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-700 text-white flex items-center justify-center font-extrabold text-base shadow-xs">
                            ✓
                        </div>
                        <div>
                            <div class="text-xs font-extrabold text-purple-950 flex items-center gap-2">
                                <span>Sedang Mengelola: {{ $batchItem->batch_name ?: ('Batch ' . $loop->iteration) }}</span>
                                <span class="px-2 py-0.5 rounded-md bg-purple-700 text-white text-[10px] font-extrabold uppercase">Aktif</span>
                            </div>
                            <div class="text-[11px] text-purple-700 mt-0.5 flex items-center gap-2">
                                <span><strong>{{ $batchEnrollments->count() }}</strong> Peserta Terdaftar</span>
                                <span>•</span>
                                <span>KKM Sertifikasi: <strong>{{ $batchItem->certificate_threshold ?? 75 }}%</strong></span>
                            </div>
                        </div>
                    </div>

                    <button type="button" 
                            @click="selectedBatchId = 'null'"
                            class="px-3.5 py-1.5 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 rounded-xl text-xs font-bold transition self-start sm:self-auto shadow-2xs">
                        ✕ Tutup Batch Ini
                    </button>
                </div>

                {{-- WORKSPACE 2 KOLOM (KUIS & MATERI vs THRESHOLD & PESERTA) --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                    {{-- LEFT COLUMN: KUIS & MATERI --}}
                    <div class="lg:col-span-8 space-y-6">

                        {{-- BANK KUIS & UJIAN SERTIFIKASI --}}
                        <div class="compro-card p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-base font-extrabold text-slate-900">Ujian & Kuis Sertifikasi</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Ujian sertifikasi digital dan kuis evaluasi peserta</p>
                                </div>
                                <button type="button" 
                                        onclick="document.getElementById('vendor-create-quiz-form-{{ $batchItem->id }}').classList.toggle('hidden')"
                                        class="px-3 py-1.5 bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                    + Buat Kuis
                                </button>
                            </div>

                            {{-- INLINE CREATE QUIZ FORM --}}
                            <div id="vendor-create-quiz-form-{{ $batchItem->id }}" class="hidden mb-6 p-5 bg-purple-50/60 border border-purple-200 rounded-2xl">
                                <h4 class="text-xs font-extrabold text-purple-950 mb-3">Form Pembuatan Kuis Baru</h4>
                                <form method="POST" action="{{ route('vendor.quizzes.store', $batchItem->id) }}" class="space-y-3 text-xs">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1">Judul Kuis <span class="text-rose-500">*</span></label>
                                            <input type="text" name="title" placeholder="Contoh: Ujian Sertifikasi Cloud Architect" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold focus:border-purple-600 focus:ring-purple-600" required>
                                        </div>

                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1">Tipe Kuis <span class="text-rose-500">*</span></label>
                                            <select name="quiz_type" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-bold text-purple-900 bg-white focus:border-purple-600 focus:ring-purple-600" required>
                                                <option value="daily">Kuis Praktik / Harian</option>
                                                <option value="weekly">Kuis Evaluasi Modul</option>
                                                <option value="final">Ujian Akhir Sertifikasi (Final)</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1">Durasi Pengerjaan (Menit)</label>
                                            <input type="number" name="time_limit" min="1" placeholder="Contoh: 60 (kosongkan jika tanpa batas)" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold focus:border-purple-600 focus:ring-purple-600">
                                        </div>

                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1">Maksimal Percobaan</label>
                                            <input type="number" name="max_attempts" value="1" min="0" max="100" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold focus:border-purple-600 focus:ring-purple-600">
                                        </div>

                                        <div class="md:col-span-2">
                                            <label class="block font-bold text-slate-700 mb-1">Target Batch / Cakupan Kuis</label>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                <label class="flex items-center gap-2 p-2.5 bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-purple-300 transition">
                                                    <input type="radio" name="target_scope" value="class" checked class="text-purple-600 focus:ring-purple-500">
                                                    <div>
                                                        <div class="text-[11.5px] font-bold text-slate-800">Khusus Batch Ini ({{ $batchItem->section_name ?? $batchItem->batch_name ?? 'Batch' }})</div>
                                                        <div class="text-[10px] text-slate-500">Hanya peserta di batch ini yang dapat mengakses</div>
                                                    </div>
                                                </label>
                                                <label class="flex items-center gap-2 p-2.5 bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-purple-300 transition">
                                                    <input type="radio" name="target_scope" value="all" class="text-purple-600 focus:ring-purple-500">
                                                    <div>
                                                        <div class="text-[11.5px] font-bold text-slate-800">Semua Batch (Kurikulum Induk)</div>
                                                        <div class="text-[10px] text-slate-500">Berlaku untuk seluruh batch kursus sertifikasi ini</div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-2 pt-2 border-t border-purple-200">
                                        <button type="button" onclick="document.getElementById('vendor-create-quiz-form-{{ $batchItem->id }}').classList.add('hidden')" class="px-3.5 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                                        <button type="submit" class="px-4 py-1.5 bg-purple-700 hover:bg-purple-800 text-white font-bold rounded-xl text-xs shadow-xs">Simpan Kuis</button>
                                    </div>
                                </form>
                            </div>

                            {{-- DAFTAR KUIS --}}
                            <div class="space-y-3">
                                @forelse ($quizzes as $quiz)
                                    <div class="item-card flex items-center justify-between gap-3">
                                        <div>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <h4 class="font-bold text-xs text-slate-900">{{ $quiz->title }}</h4>
                                                <span class="px-2 py-0.5 rounded-md text-[10.5px] font-extrabold bg-purple-100 text-purple-800">
                                                    {{ $quiz->quiz_type_label ?? 'Kuis' }}
                                                </span>
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold {{ $quiz->is_global ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">
                                                    {{ $quiz->is_global ? '🌐 Kurikulum Global' : '🏭 Khusus Batch' }}
                                                </span>
                                            </div>
                                            <div class="text-[11px] text-slate-500 mt-1">
                                                Durasi: <strong>{{ $quiz->time_limit ? $quiz->time_limit . ' Menit' : 'Tanpa Batas' }}</strong> • Percobaan: <strong>{{ $quiz->max_attempts === 0 ? 'Unlimited' : $quiz->max_attempts . 'x' }}</strong>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <a href="{{ route('vendor.courses.quizzes.show', [$batchItem->id, $quiz->id]) }}" 
                                               class="px-3 py-1 bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold rounded-lg shadow-xs transition">
                                                Kelola Soal ({{ $quiz->questions ? $quiz->questions->count() : 0 }})
                                            </a>
                                            <form action="{{ route('vendor.quizzes.destroy', $quiz->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kuis ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1 text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg text-xs font-bold transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6 text-xs text-slate-400">
                                        Belum ada kuis sertifikasi untuk kursus industri ini.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- MODUL MATERI INDUSTRI --}}
                        <div class="compro-card p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-base font-extrabold text-slate-900">Modul Materi Industri</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Slide materi dan panduan praktikum industri</p>
                                </div>
                                <button type="button" 
                                        onclick="document.getElementById('vendor-upload-material-form-{{ $batchItem->id }}').classList.toggle('hidden')"
                                        class="px-3 py-1.5 bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                    + Tambah Materi
                                </button>
                            </div>

                            {{-- INLINE UPLOAD MATERIAL FORM --}}
                            <div id="vendor-upload-material-form-{{ $batchItem->id }}" class="hidden mb-6 p-5 bg-purple-50/60 border border-purple-200 rounded-2xl">
                                <h4 class="text-xs font-extrabold text-purple-950 mb-3">Upload Modul Materi Baru</h4>
                                <form method="POST" action="{{ route('vendor.materials.store', $batchItem->id) }}" enctype="multipart/form-data" class="space-y-3 text-xs">
                                    @csrf
                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Judul Materi <span class="text-rose-500">*</span></label>
                                        <input type="text" name="title" placeholder="Contoh: Modul 1 - Pengenalan Arsitektur Cloud" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold focus:border-purple-600 focus:ring-purple-600" required>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Berkas Dokumen / Slide (PDF, PPT, DOC)</label>
                                        <input type="file" name="file" class="w-full rounded-xl border border-slate-300 p-2 text-xs bg-white focus:border-purple-600 focus:ring-purple-600">
                                    </div>

                                    <div class="flex justify-end gap-2 pt-2 border-t border-purple-200">
                                        <button type="button" onclick="document.getElementById('vendor-upload-material-form-{{ $batchItem->id }}').classList.add('hidden')" class="px-3.5 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                                        <button type="submit" class="px-4 py-1.5 bg-purple-700 hover:bg-purple-800 text-white font-bold rounded-xl text-xs shadow-xs">Unggah Materi</button>
                                    </div>
                                </form>
                            </div>

                            <div class="space-y-3">
                                @forelse ($materials as $material)
                                    <div class="item-card flex items-center justify-between gap-3">
                                        <div class="flex items-start gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center shrink-0 mt-0.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <h4 class="font-bold text-xs text-slate-900 truncate">{{ $material->title }}</h4>
                                                <div class="text-[11px] text-slate-500 mt-0.5">
                                                    Diunggah: {{ optional($material->created_at)->format('d M Y') ?: '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <form action="{{ route('vendor.materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus materi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1 text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg text-xs font-bold transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6 text-xs text-slate-400">
                                        Belum ada modul materi industri diunggah.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    </div>

                    {{-- RIGHT COLUMN: THRESHOLD & PESERTA --}}
                    <div class="lg:col-span-4 space-y-6">

                        {{-- SETTING THRESHOLD --}}
                        <div class="compro-card p-5 space-y-3">
                            <h4 class="text-sm font-extrabold text-slate-900">KKM Sertifikasi Industri</h4>
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                Standar batas nilai minimal kelulusan sertifikat industri & verifikasi hash blockchain.
                            </p>
                            <div class="p-3 bg-purple-50/70 border border-purple-200 rounded-xl flex items-center justify-between text-xs font-bold">
                                <span class="text-purple-900">Threshold Kelulusan:</span>
                                <span class="text-base text-purple-700 font-mono">{{ $batchItem->certificate_threshold ?? 75 }}%</span>
                            </div>
                        </div>

                        {{-- PESERTA TERDAFTAR --}}
                        <div class="compro-card p-5 space-y-3">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-extrabold text-slate-900">Peserta Batch</h4>
                                <span class="text-xs font-bold text-slate-400">{{ $batchEnrollments->count() }} Terdaftar</span>
                            </div>

                            @if($batchEnrollments->count())
                                <div class="space-y-2.5 max-h-[480px] overflow-y-auto pr-1">
                                    @foreach ($batchEnrollments as $enrollment)
                                        @php
                                            $studentUser = $enrollment->user;
                                            $progress = $enrollment->progress_percent ?? 0;
                                        @endphp
                                        <div class="p-3 bg-slate-50/70 border border-slate-200/80 rounded-xl text-xs space-y-1.5">
                                            <div class="flex items-center justify-between gap-2">
                                                <div class="font-bold text-slate-900 truncate">{{ $studentUser->name ?? 'Peserta' }}</div>
                                                <span class="text-[10px] font-bold text-slate-500 font-mono">{{ $progress }}%</span>
                                            </div>
                                            <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                                <div class="h-1.5 rounded-full {{ $progress >= 80 ? 'bg-emerald-500' : 'bg-purple-600' }}" style="width: {{ min(100, max(0, $progress)) }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-6 text-xs text-slate-400">
                                    Belum ada peserta terdaftar di batch ini.
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