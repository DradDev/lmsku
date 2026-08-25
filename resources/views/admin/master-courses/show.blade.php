<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.admin-mc-wrap {
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

<div class="admin-mc-wrap max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6" 
     x-data="{ selectedOfferingId: 'null' }">

    {{-- ALERTS --}}
    @if (session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 text-xs font-bold shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl flex items-center gap-3 text-xs font-bold shadow-xs">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- TOP NAVIGATION / BACK BUTTON ROW --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <a href="{{ route('admin.master-courses.index') }}" 
           class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Katalog Master Course</span>
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.course-offerings.create') }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                + Buka Rombel Baru
            </a>
            <a href="{{ route('admin.master-courses.edit', $masterCourse) }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
                Edit Matkul
            </a>
        </div>
    </div>

    {{-- 1. MASTER COURSE HERO CARD --}}
    <div class="compro-card p-6 space-y-3">
        <div class="flex items-center gap-2 flex-wrap">
            <span class="px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-100 text-[11px] font-extrabold">
                Master Course Induk
            </span>
            <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-bold font-mono">
                {{ $masterCourse->code }}
            </span>
            <span class="px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-100 text-[11px] font-extrabold">
                Level: {{ $masterCourse->level }}
            </span>
            <span class="text-xs font-bold text-slate-400">
                • Total {{ $courseOfferings->count() }} Rombel Kelas Terbuka
            </span>
        </div>

        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                {{ $masterCourse->name }}
            </h1>
            <p class="text-xs text-slate-500 mt-1 max-w-3xl leading-relaxed">
                {{ $masterCourse->description ?: 'Silabus kurikulum induk. Kelola penawaran rombel kelas paralel, penugasan dosen pengampu, dan target kompetensi skill.' }}
            </p>
        </div>

        @if($masterCourse->skills && $masterCourse->skills->isNotEmpty())
            <div class="flex flex-wrap items-center gap-1.5 pt-2 border-t border-slate-100">
                <span class="text-[11px] font-bold text-slate-400">Target Skill:</span>
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
                        <span class="text-xs font-extrabold text-blue-600 uppercase tracking-wider">Pemantauan Rombel</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                        <span class="text-xs font-bold text-slate-500">{{ $courseOfferings->count() }} Rombel Terbuka</span>
                    </div>
                    <h2 class="text-lg font-extrabold text-slate-900 tracking-tight mt-0.5">
                        Pilih Rombel Kelas untuk Dipantau/Dikelola
                    </h2>
                    <p class="text-xs text-slate-600 mt-0.5">
                        Pilih kelas rombel aktif untuk melihat penugasan dosen, modul silabus, kuis, dan data mahasiswa.
                    </p>
                </div>
            </div>

            {{-- DROPDOWN UTAMA --}}
            <div class="flex items-center gap-2.5 w-full lg:w-auto shrink-0">
                <div class="relative flex-1 lg:w-80">
                    <select x-model="selectedOfferingId" 
                            class="custom-select-control w-full pr-10 cursor-pointer">
                        <option value="null">-- Silakan Pilih Rombel Kelas --</option>
                        @foreach($courseOfferings as $offeringItem)
                            <option value="{{ $offeringItem->id }}">
                                {{ $offeringItem->section_name ?: 'Rombel ' . $loop->iteration }} • Dosen: {{ $offeringItem->lecturer->name ?? 'Belum Ditugaskan' }} ({{ $offeringItem->academicTerm->name ?? 'Semester Aktif' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="button" 
                        x-show="selectedOfferingId !== 'null' && selectedOfferingId !== null" 
                        @click="selectedOfferingId = 'null'"
                        style="display: none;"
                        class="px-3.5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition whitespace-nowrap shadow-2xs">
                    ✕ Reset
                </button>
            </div>

        </div>

        {{-- QUICK PILL BUTTONS (PILIHAN CEPAT 1-KLIK) --}}
        @if($courseOfferings->isNotEmpty())
            <div class="pt-3 border-t border-blue-200/70 flex flex-wrap items-center gap-2">
                <span class="text-[11px] font-extrabold uppercase text-slate-400 mr-1">Pintasan Rombel:</span>
                @foreach($courseOfferings as $offeringItem)
                    <button type="button" 
                            @click="selectedOfferingId = {{ $offeringItem->id }}"
                            :class="selectedOfferingId == {{ $offeringItem->id }} ? 'active' : ''"
                            class="quick-pill">
                        <span class="w-2 h-2 rounded-full" 
                              :class="selectedOfferingId == {{ $offeringItem->id }} ? 'bg-white' : 'bg-blue-600'"></span>
                        <span>{{ $offeringItem->section_name ?: 'Kelas ' . $loop->iteration }}</span>
                        <span class="text-[10.5px] px-1.5 py-0.2 rounded-md font-mono"
                              :class="selectedOfferingId == {{ $offeringItem->id }} ? 'bg-blue-800 text-white' : 'bg-slate-100 text-slate-700'">
                            {{ $offeringItem->lecturer->name ?? 'Dosen' }}
                        </span>
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    {{-- 3. STATE AWAL: BELUM MEMILIH KELAS --}}
    <div x-show="selectedOfferingId === 'null' || selectedOfferingId === null" class="compro-card p-12 text-center space-y-3">
        <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto shadow-xs">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        <h3 class="text-base font-extrabold text-slate-800">Silakan Pilih Rombel Kelas</h3>
        <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
            Gunakan menu dropdown atau tombol pintasan kelas di atas untuk melihat detail penugasan dosen, silabus materi, kuis, dan data mahasiswa.
        </p>
    </div>

    {{-- 4. STATE TERPILIH: WORKSPACE DETAIL ROMBEL TERPILIH --}}
    <div x-show="selectedOfferingId !== 'null' && selectedOfferingId !== null" style="display: none;" class="space-y-6">
        @foreach($courseOfferings as $offeringItem)
            @php
                $offeringEnrollments = $offeringItem->enrollments ?? collect();
            @endphp
            <div x-show="selectedOfferingId == {{ $offeringItem->id }}" class="space-y-6">

                {{-- SUB-BAR KELAS AKTIF DENGAN STATS MINI --}}
                <div class="p-4 bg-blue-50/80 border border-blue-200 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-extrabold text-base shadow-xs">
                            ✓
                        </div>
                        <div>
                            <div class="text-xs font-extrabold text-blue-950 flex items-center gap-2">
                                <span>Sedang Memantau: {{ $offeringItem->section_name ?: 'Kelas ' . $loop->iteration }}</span>
                                <span class="px-2 py-0.5 rounded-md bg-blue-600 text-white text-[10px] font-extrabold uppercase">Aktif</span>
                            </div>
                            <div class="text-[11px] text-blue-700 mt-0.5 flex items-center gap-2">
                                <span>Dosen Pengampu: <strong>{{ $offeringItem->lecturer->name ?? 'Belum Ditugaskan' }}</strong></span>
                                <span>•</span>
                                <span>{{ $offeringItem->academicTerm->name ?? 'Semester Aktif' }}</span>
                                <span>•</span>
                                <span>KKM: <strong>{{ $offeringItem->certificate_threshold ?? 75 }}%</strong></span>
                            </div>
                        </div>
                    </div>

                    <button type="button" 
                            @click="selectedOfferingId = 'null'"
                            class="px-3.5 py-1.5 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 rounded-xl text-xs font-bold transition self-start sm:self-auto shadow-2xs">
                        ✕ Tutup Rombel Ini
                    </button>
                </div>

                {{-- WORKSPACE 2 KOLOM (MATERI & KUIS vs DETAIL PENUGASAN & MAHASISWA) --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                    {{-- LEFT COLUMN: SILABUS & KUIS --}}
                    <div class="lg:col-span-8 space-y-6">

                        {{-- SILABUS MATERI --}}
                        <div class="compro-card p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-base font-extrabold text-slate-900">Modul Materi Pembelajaran</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Slide materi dan panduan kurikulum untuk rombel ini</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                @forelse ($materials as $material)
                                    <div class="item-card flex items-center justify-between gap-3">
                                        <div class="flex items-start gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
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
                                    </div>
                                @empty
                                    <div class="text-center py-6 text-xs text-slate-400">
                                        Belum ada modul materi pembelajaran diunggah.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- BANK KUIS --}}
                        <div class="compro-card p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-base font-extrabold text-slate-900">Bank Kuis & Evaluasi</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Kuis kurikulum akademik dan kelulusan sertifikasi</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                @forelse ($quizzes as $quiz)
                                    <div class="item-card flex items-center justify-between gap-3">
                                        <div>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <h4 class="font-bold text-xs text-slate-900">{{ $quiz->title }}</h4>
                                                <span class="px-2 py-0.5 rounded-md text-[10.5px] font-extrabold bg-slate-100 text-slate-700">
                                                    {{ $quiz->quiz_type_label ?? 'Kuis' }}
                                                </span>
                                            </div>
                                            <div class="text-[11px] text-slate-500 mt-1">
                                                Durasi: <strong>{{ $quiz->time_limit ? $quiz->time_limit . ' Menit' : 'Tanpa Batas' }}</strong> • Percobaan: <strong>{{ $quiz->max_attempts === 0 ? 'Unlimited' : $quiz->max_attempts . 'x' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6 text-xs text-slate-400">
                                        Belum ada kuis pada mata kuliah ini.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    </div>

                    {{-- RIGHT COLUMN: INFO PENUGASAN DOSEN & MAHASISWA --}}
                    <div class="lg:col-span-4 space-y-6">

                        {{-- INFO PENUGASAN DOSEN --}}
                        <div class="compro-card p-5 space-y-3">
                            <h4 class="text-sm font-extrabold text-slate-900">Penugasan Dosen & Kelas</h4>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between py-1.5 border-b border-slate-100">
                                    <span class="text-slate-500">Dosen Pengampu:</span>
                                    <strong class="text-slate-900">{{ $offeringItem->lecturer->name ?? 'Belum Ditugaskan' }}</strong>
                                </div>
                                <div class="flex justify-between py-1.5 border-b border-slate-100">
                                    <span class="text-slate-500">Semester:</span>
                                    <strong class="text-slate-900">{{ $offeringItem->academicTerm->name ?? '-' }}</strong>
                                </div>
                                <div class="flex justify-between py-1.5 border-b border-slate-100">
                                    <span class="text-slate-500">Kapasitas Kursi:</span>
                                    <strong class="text-slate-900">{{ $offeringItem->capacity ? $offeringItem->capacity . ' Kursi' : 'Unlimited' }}</strong>
                                </div>
                                <div class="flex justify-between py-1.5">
                                    <span class="text-slate-500">KKM Kelulusan:</span>
                                    <strong class="text-emerald-700 font-extrabold">{{ $offeringItem->certificate_threshold ?? 75 }}%</strong>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        @endforeach
    </div>

    {{-- 5. BOTTOM SECTION: TARGET SKILL & TAG KOMPETENSI KURIKULUM --}}
    <div class="compro-card p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
            <div>
                <h2 class="text-base font-extrabold text-slate-900">Target Skill & Tag Kompetensi Matkul</h2>
                <p class="text-xs text-slate-500 mt-0.5">Tentukan skill utama dan sub-topik tag yang harus dicapai mahasiswa pada kurikulum ini.</p>
            </div>
        </div>

        <form action="{{ route('admin.master-courses.competencies.sync', $masterCourse) }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">1. Pilih Skill Utama:</label>
                    <div class="max-h-60 overflow-y-auto border border-slate-200 rounded-xl p-3 bg-slate-50/50 space-y-1.5">
                        @foreach($allSkills as $sk)
                            @php
                                $isChecked = $masterCourse->skills->contains($sk->id);
                            @endphp
                            <label class="flex items-center gap-2.5 p-2 bg-white rounded-lg border border-slate-200 text-xs cursor-pointer hover:bg-blue-50/50 transition">
                                <input type="checkbox" name="skill_ids[]" value="{{ $sk->id }}" {{ $isChecked ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                                <span class="font-bold text-slate-900">{{ $sk->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">2. Pilih Tag Sub-Topik:</label>
                    <div class="max-h-60 overflow-y-auto border border-slate-200 rounded-xl p-3 bg-slate-50/50 space-y-1.5">
                        @foreach($allTags as $tg)
                            @php
                                $isTagChecked = $masterCourse->tags->contains($tg->id);
                            @endphp
                            <label class="flex items-center gap-2.5 p-2 bg-white rounded-lg border border-slate-200 text-xs cursor-pointer hover:bg-blue-50/50 transition">
                                <input type="checkbox" name="tag_ids[]" value="{{ $tg->id }}" {{ $isTagChecked ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                                <span class="font-semibold text-slate-700">#{{ $tg->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-2 border-t border-slate-100">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                    Simpan Skill & Tag Kompetensi
                </button>
            </div>
        </form>
    </div>

</div>
</x-app-layout>