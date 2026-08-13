<x-app-layout>
    @php
    $statusLabels = [
        'not_started' => 'Not Started',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
    ];

    $statusClasses = [
        'not_started' => 'bg-slate-100 text-slate-700 border-slate-200',
        'in_progress' => 'bg-purple-50 text-purple-700 border-purple-200',
        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    ];

    $students = $students ?? $course->students ?? collect();
    $materials = $materials ?? $course->materials ?? collect();
    $quizzes = $quizzes ?? $course->quizzes ?? collect();
    $completedStudentCount = $completedStudentCount ?? $students->filter(fn($s) => ($s->pivot->status ?? '') === 'completed')->count();
    @endphp

    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-6">

            @if (session('success'))
                <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-sm text-sm font-medium">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-emerald-900">Berhasil!</p>
                        <p class="mt-0.5 text-emerald-700 text-xs sm:text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl shadow-sm text-sm">
                    <div class="flex items-center gap-2 font-bold mb-1 text-rose-900">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        Mohon periksa kembali kesalahan berikut:
                    </div>
                    <ul class="list-disc pl-6 space-y-1 text-rose-700 text-xs">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($course->moderation_status === 'suspended')
                <div class="mb-6 p-5 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl shadow-sm space-y-1">
                    <div class="flex items-center gap-2 font-extrabold text-sm text-rose-900">
                        <span>Status Course: Dibekukan Sementara (Suspended) oleh Admin Kampus</span>
                    </div>
                    <p class="text-xs font-semibold text-rose-700 leading-relaxed">
                        Catatan Admin: "{{ $course->moderation_note ?? 'Course sedang ditangguhkan dari katalog publik untuk peninjauan lebih lanjut.' }}"
                    </p>
                </div>
            @endif

            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <a href="{{ route('vendor.courses.index') }}"
                        class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-700 transition">
                        ← Kembali ke Daftar Sertifikasi
                    </a>

                    <div class="flex items-center gap-2">
                        <!-- BUTTON LAUNCH BATCH BARU (3NF BATCH OFFERING) -->
                        <button type="button" 
                                onclick="document.getElementById('launch_batch_modal').classList.remove('hidden')"
                                class="px-4 py-2 bg-gradient-to-r from-purple-700 to-indigo-700 hover:from-purple-800 hover:to-indigo-800 text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center gap-1.5 cursor-pointer">
                            <span>Launch Batch Baru</span>
                        </button>

                        <a href="{{ route('vendor.courses.edit', $course) }}" 
                           class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                            Edit Course
                        </a>

                        <form action="{{ route('vendor.courses.toggle-archive', $course) }}" method="POST" onsubmit="return confirm('Ubah status publikasi/draft course ini?')">
                            @csrf
                            @if($course->is_archived)
                                <button type="submit" class="px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                    <span>Dipublikasikan (Aktif)</span>
                                </button>
                            @else
                                <button type="submit" class="px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-300 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                    <span>Simpan ke Draft Bank</span>
                                </button>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-6">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-purple-600 mb-2">
                            Portal Author Mitra Vendor &bull; Manajemen Sertifikasi Industri 3NF
                        </p>

                        <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                            {{ $course->name }}
                        </h1>

                        <p class="mt-3 max-w-3xl text-slate-500 leading-7">
                            {{ $course->description ?: 'Pengelolaan materi modul, kuis evaluasi, serta penentuan threshold sertifikat industri mahasiswa.' }}
                        </p>

                        <div class="mt-4 flex flex-wrap gap-2 text-sm">
                            <span class="rounded-full bg-indigo-100 text-indigo-900 border border-indigo-200 px-3.5 py-1 font-black">
                                {{ $course->batch_name ?? 'Batch 1 - 2026' }}
                            </span>

                            <span class="rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-700">
                                Level: {{ $course->level ?? 'Beginner' }}
                            </span>

                            <span class="rounded-full bg-purple-50 px-3 py-1 font-semibold text-purple-700 border border-purple-200">
                                Kategori: {{ optional($course->category)->name ?? 'General' }}
                            </span>

                            <span class="rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-700 border border-emerald-200">
                                Threshold Sertifikat: {{ $course->certificate_threshold ?? 75 }}%
                            </span>

                            <span class="rounded-full {{ $course->is_archived ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-emerald-100 text-emerald-900 border border-emerald-300' }} px-3.5 py-1 font-bold">
                                Status: {{ $course->is_archived ? 'Project Bank (Draft Internal)' : 'Active Course (Terbuka Dipublikasikan)' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BATCH SWITCHER CARD (DUPLIKASI/WARISAN ANGKATAN 3NF) -->
            @php $otherBatches = $otherBatches ?? collect(); @endphp
            <div class="rounded-3xl border border-indigo-200 bg-white p-5 shadow-sm mb-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-extrabold uppercase tracking-wider text-indigo-700 bg-indigo-50 px-2.5 py-0.5 rounded-full border border-indigo-100">
                                Angkatan Batch Rilis (Kurikulum Induk #MC-{{ $course->master_course_id ?? $course->id }})
                            </span>
                        </div>
                        <h3 class="text-sm font-extrabold text-slate-900">
                            Kelola Angkatan Batch & Warisan Modul/Kuis Pembelajaran
                        </h3>
                        <p class="text-xs text-slate-500">
                            Seluruh materi & kuis pada kurikulum ini diwariskan otomatis antar batch tanpa duplikasi file fisik server (Strict 3NF).
                        </p>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs font-bold text-slate-400">Batch Aktif Saat Ini:</span>
                        <span class="px-3.5 py-1.5 rounded-xl bg-purple-700 text-white font-extrabold text-xs shadow-sm ring-2 ring-purple-300">
                            ✓ {{ $course->batch_name ?? 'Batch 1 - 2026' }}
                        </span>

                        @foreach($otherBatches as $ob)
                            <a href="{{ route('vendor.courses.show', $ob) }}" 
                               class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-purple-100 hover:text-purple-900 text-slate-700 font-bold text-xs border border-slate-200 transition">
                                Switch ke {{ $ob->batch_name }}
                            </a>
                        @endforeach

                        <button type="button" 
                                onclick="document.getElementById('launch_batch_modal').classList.remove('hidden')"
                                class="px-3.5 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-extrabold text-xs border border-indigo-200 transition cursor-pointer">
                            + Launch Batch Baru
                        </button>
                    </div>
                </div>
            </div>

            <!-- MODAL LAUNCH BATCH BARU -->
            <div id="launch_batch_modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 md:p-8 space-y-5 animate-in fade-in zoom-in duration-200">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                            <span>Launch Angkatan Batch Baru</span>
                        </h3>
                        <button type="button" onclick="document.getElementById('launch_batch_modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold text-sm">
                            ✕
                        </button>
                    </div>

                    <form action="{{ route('vendor.courses.launch-batch', $course) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div class="p-4 bg-purple-50 rounded-2xl border border-purple-100 text-xs text-purple-900 leading-relaxed">
                            **Efisiensi Kurikulum 3NF**: Seluruh modul materi (PDF/Video) dan bank kuis dari **{{ $course->name }}** akan otomatis diwariskan ke batch baru ini tanpa perlu di-upload ulang.
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Nama Batch Angkatan Baru <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="batch_name" required placeholder="Contoh: Batch 2 - Intake Q3 2026"
                                   class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-3 font-extrabold text-purple-900">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Threshold Sertifikat (%) <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" name="certificate_threshold" value="{{ $course->certificate_threshold ?? 75 }}" min="0" max="100" required
                                       class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-3 font-bold text-slate-900">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Durasi (Minggu)
                                </label>
                                <input type="number" name="duration_weeks" value="{{ $course->duration_weeks ?? 4 }}" min="1" required
                                       class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-3 font-bold text-slate-900">
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

            <!-- CARD PENGATURAN THRESHOLD SANGAT PRAKTIS LANGSUNG DI VIEW -->
            <div class="rounded-3xl border border-purple-200 bg-gradient-to-br from-purple-50/50 via-white to-purple-50/30 p-6 shadow-sm mb-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                            <span>Threshold Kelulusan Sertifikat Industri</span>
                            <span class="text-xs font-bold text-purple-600 bg-purple-100 px-2.5 py-0.5 rounded-full">Otonomi Vendor</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed max-w-2xl">
                            Tentukan batas nilai minimal (%) pada Kuis Akhir yang harus dicapai mahasiswa agar Sertifikat Digital & Hash Blockchain terverifikasi industri otomatis diterbitkan.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <form action="{{ route('vendor.courses.update', $course) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="name" value="{{ $course->name }}">
                            <input type="hidden" name="batch_name" value="{{ $course->batch_name ?? 'Batch 1 - 2026' }}">
                            <input type="hidden" name="level" value="{{ $course->level }}">
                            <input type="hidden" name="category_id" value="{{ $course->category_id }}">
                            <input type="hidden" name="description" value="{{ $course->description }}">
                            @foreach($course->skills as $cSkill)
                                <input type="hidden" name="skill_ids[]" value="{{ $cSkill->id }}">
                            @endforeach

                            <div class="relative">
                                <input type="number" 
                                       name="certificate_threshold" 
                                       value="{{ old('certificate_threshold', $course->certificate_threshold ?? 75) }}" 
                                       min="0" 
                                       max="100" 
                                       class="w-24 rounded-xl border-slate-300 text-sm font-extrabold text-purple-900 text-center focus:border-purple-500 focus:ring-purple-500 py-2" 
                                       required>
                                <span class="absolute right-2 top-2.5 text-xs font-bold text-slate-400">%</span>
                            </div>
                            <button type="submit" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition whitespace-nowrap">
                                Simpan Threshold
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- STATISTIK KELAS (4 STAT CARDS STRIP) -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Student</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $students->count() }}</p>
                    <p class="mt-1 text-xs text-slate-500">Mahasiswa terdaftar</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Lulus / Completed</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-600">{{ $completedStudentCount }}</p>
                    <p class="mt-1 text-xs text-slate-500">Mahasiswa lulus sertifikasi</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Materials</p>
                    <p class="mt-2 text-3xl font-bold text-purple-600">{{ $materials->count() }}</p>
                    <p class="mt-1 text-xs text-slate-500">Modul / file pelatihan</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Quizzes</p>
                    <p class="mt-2 text-3xl font-bold text-indigo-600">{{ $quizzes->count() }}</p>
                    <p class="mt-1 text-xs text-slate-500">Kuis harian & kuis akhir</p>
                </div>
            </div>

            <!-- MAIN MANAGEMENT 2-COLUMN RESPONSIVE LAYOUT (3-COL GRID) -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                <!-- LEFT COLUMN: BANK KUIS & LEARNING MATERIALS (2 COLS) -->
                <div class="xl:col-span-2 space-y-6">

                    <!-- CARD 1: BANK KUIS VENDOR (KUIS HARI/AKHIR) -->
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h2 class="text-2xl font-semibold text-slate-900">
                                    Bank Kuis Pembelajaran
                                </h2>
                                <p class="mt-1 text-sm text-slate-500">
                                    Kelola kuis harian/mingguan dan Kuis Akhir (penentu sertifikat).
                                </p>
                            </div>

                            <button type="button" 
                                    onclick="document.getElementById('create-quiz-form-vendor').classList.toggle('hidden')" 
                                    class="inline-flex items-center justify-center rounded-xl bg-purple-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-purple-700 shadow-md transition whitespace-nowrap">
                                + Buat Kuis Baru
                            </button>
                        </div>

                        <!-- FORM INLINE BUAT KUIS BARU (SAMA PERSIS DENGAN LECTURER) -->
                        <div id="create-quiz-form-vendor" class="hidden mb-6 p-5 bg-purple-50/50 border border-purple-200 rounded-2xl transition">
                            <h3 class="text-sm font-extrabold text-purple-900 mb-3 flex items-center gap-2">
                                Form Buat Kuis Pembelajaran Baru
                            </h3>
                            <form method="POST" action="{{ route('vendor.quizzes.store', $course->id) }}" class="space-y-4 text-xs">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Judul Kuis</label>
                                        <input type="text" name="title" placeholder="Contoh: Kuis Akhir - Final Certification Exam" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold bg-white" required>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Tipe Kuis Pembelajaran</label>
                                        <select name="quiz_type" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-bold text-purple-900 bg-white" required>
                                            <option value="daily">Kuis Biasa / Harian (Section Quiz)</option>
                                            <option value="weekly">Kuis Mingguan / Evaluasi Bab</option>
                                            <option value="final">Kuis Akhir (Final Quiz / Penentu Sertifikat)</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Durasi Pengerjaan (Menit)</label>
                                        <input type="number" name="time_limit" min="1" placeholder="Contoh: 60 (kosongkan jika tanpa batas)" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold bg-white">
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Maksimal Percobaan (Attempts)</label>
                                        <div class="flex items-center gap-2">
                                            <input type="number" id="max_attempts_input_vendor" name="max_attempts" value="1" min="0" max="100" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold bg-white">
                                            <label class="inline-flex items-center gap-1.5 px-2.5 py-2.5 bg-slate-100 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-200 transition whitespace-nowrap">
                                                <input type="checkbox" name="is_unlimited" value="1" onchange="document.getElementById('max_attempts_input_vendor').disabled = this.checked; if(this.checked){ document.getElementById('max_attempts_input_vendor').value = 0; }" class="rounded text-purple-600 focus:ring-purple-500">
                                                <span class="text-[11px] font-bold text-slate-700">Unlimited</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Tanggal Rilis (Opsional)</label>
                                        <input type="datetime-local" name="start_date" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold bg-white">
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Deadline Selesai (Opsional)</label>
                                        <input type="datetime-local" name="end_date" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold bg-white">
                                    </div>

                                    <div class="md:col-span-2 mt-2 pt-2 border-t border-purple-100">
                                        <label class="block font-bold text-slate-700 mb-1.5">Target Scope Distribusi Kuis:</label>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                            <label class="flex items-center gap-2.5 p-2.5 border border-purple-200 rounded-xl bg-white cursor-pointer hover:border-purple-400 transition">
                                                <input type="radio" name="target_scope" value="all" checked class="text-purple-600 focus:ring-purple-500">
                                                <div>
                                                    <span class="block font-bold text-purple-950 text-xs">Semua Peserta Course Sertifikasi</span>
                                                    <span class="block text-[11px] text-slate-500">Kuis akan berlaku otomatis untuk seluruh mahasiswa terdaftar.</span>
                                                </div>
                                            </label>
                                            <label class="flex items-center gap-2.5 p-2.5 border border-slate-200 rounded-xl bg-white cursor-pointer hover:border-purple-400 transition">
                                                <input type="radio" name="target_scope" value="class" class="text-purple-600 focus:ring-purple-500">
                                                <div>
                                                    <span class="block font-bold text-slate-800 text-xs">Khusus Batch / Kelompok Ini</span>
                                                    <span class="block text-[11px] text-slate-500">Kuis khusus/remedial untuk batch peserta saat ini.</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end gap-2 pt-2 border-t border-purple-200">
                                    <button type="button" onclick="document.getElementById('create-quiz-form-vendor').classList.add('hidden')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                                    <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl text-xs shadow-md">Simpan Kuis & Lanjut Buat Soal</button>
                                </div>
                            </form>
                        </div>

                        <!-- DAFTAR KUIS (DENGAN TAMPILAN SAMA PERSIS LECTURER) -->
                        <div class="space-y-4">
                            @forelse ($quizzes as $quiz)
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                        <div>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <h3 class="text-lg font-semibold text-slate-900">
                                                    {{ $quiz->title }}
                                                </h3>

                                                <span class="inline-flex items-center rounded-full border border-purple-200 bg-purple-50 text-purple-800 px-3 py-1 text-xs font-semibold">
                                                    {{ $quiz->quiz_type === 'final' ? 'Final Quiz' : ($quiz->quiz_type === 'weekly' ? 'Weekly Quiz' : 'Daily Quiz') }}
                                                </span>
                                            </div>

                                            <p class="mt-2 text-sm text-slate-500">
                                                Time Limit:
                                                <span class="text-slate-700 font-semibold">
                                                    {{ $quiz->time_limit ? $quiz->time_limit . ' minutes' : 'No limit' }}
                                                </span>
                                            </p>

                                            <p class="mt-1 text-sm text-slate-500">
                                                Attempts Allowed:
                                                <span class="text-slate-700 font-semibold">
                                                    {{ $quiz->max_attempts === 0 ? 'Unlimited' : $quiz->max_attempts . 'x' }}
                                                </span>
                                            </p>

                                            @if (($quiz->quiz_type ?? '') === 'final')
                                                <p class="mt-2 text-xs font-extrabold text-emerald-700 flex items-center gap-1">
                                                    <span>Kuis Akhir Penentu Kelulusan Sertifikat Digital & Hash Blockchain.</span>
                                                </p>
                                            @endif
                                        </div>

                                        <div class="flex flex-wrap gap-2">
                                            <button type="button" onclick="document.getElementById('edit-quiz-form-vendor-{{ $quiz->id }}').classList.toggle('hidden')" class="rounded-xl bg-amber-50 border border-amber-200 text-amber-700 px-3.5 py-2 text-xs font-semibold hover:bg-amber-100 transition">
                                                Waktu & Durasi
                                            </button>

                                            <a href="{{ route('vendor.quizzes.show', $quiz) }}"
                                                class="rounded-xl bg-purple-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-purple-700 shadow-sm">
                                                + Kelola Soal ({{ $quiz->questions ? $quiz->questions->count() : 0 }})
                                            </a>

                                            <form method="POST"
                                                action="{{ route('vendor.quizzes.destroy', $quiz) }}"
                                                onsubmit="return confirm('Yakin ingin menghapus quiz ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700 shadow-sm">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- FORM INLINE EDIT WAKTU & DURASI KUIS -->
                                    <div id="edit-quiz-form-vendor-{{ $quiz->id }}" class="hidden mt-4 pt-4 border-t border-slate-200">
                                        <form method="POST" action="{{ route('vendor.courses.quizzes.update', [$course->id, $quiz->id]) }}" class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                            @csrf
                                            @method('PUT')

                                            <div>
                                                <label class="block font-bold text-slate-700 mb-1">Judul Kuis</label>
                                                <input type="text" name="title" value="{{ old('title', $quiz->title) }}" class="w-full rounded-xl border-slate-300 p-2 text-xs" required>
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 mb-1">Durasi (Menit)</label>
                                                <input type="number" name="time_limit" value="{{ old('time_limit', $quiz->time_limit) }}" min="1" placeholder="Kosongkan jika tidak ada batas" class="w-full rounded-xl border-slate-300 p-2 text-xs">
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 mb-1">Max Attempts</label>
                                                <input type="number" name="max_attempts" value="{{ old('max_attempts', $quiz->max_attempts) }}" min="1" max="100" class="w-full rounded-xl border-slate-300 p-2 text-xs" required>
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 mb-1">Start Date</label>
                                                <input type="datetime-local" name="start_date" value="{{ $quiz->start_date ? \Carbon\Carbon::parse($quiz->start_date)->format('Y-m-d\TH:i') : '' }}" class="w-full rounded-xl border-slate-300 p-2 text-xs">
                                            </div>

                                            <div class="md:col-span-2">
                                                <label class="block font-bold text-slate-700 mb-1">End Date / Deadline</label>
                                                <input type="datetime-local" name="end_date" value="{{ $quiz->end_date ? \Carbon\Carbon::parse($quiz->end_date)->format('Y-m-d\TH:i') : '' }}" class="w-full rounded-xl border-slate-300 p-2 text-xs">
                                            </div>

                                            <div class="md:col-span-2 flex justify-end gap-2 pt-2">
                                                <button type="button" onclick="document.getElementById('edit-quiz-form-vendor-{{ $quiz->id }}').classList.add('hidden')" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg">Batal</button>
                                                <button type="submit" class="px-4 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg shadow-sm">Simpan Waktu & Pengaturan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-8 text-center text-slate-500 text-sm">
                                    Belum ada kuis untuk course sertifikasi ini. Klik "+ Buat Kuis Baru" di atas untuk menambahkan.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- CARD 2: LEARNING MATERIALS VENDOR -->
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-6 flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-2xl font-semibold text-slate-900">
                                    Learning Materials
                                </h2>
                                <p class="mt-1 text-sm text-slate-500">
                                    Materi modul pelatihan yang tersedia di course sertifikasi ini.
                                </p>
                            </div>

                            <button type="button" 
                                    onclick="document.getElementById('upload-material-form-vendor').classList.toggle('hidden')"
                                    class="inline-flex items-center rounded-xl bg-purple-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-purple-700 shadow-md transition whitespace-nowrap">
                                + Add Material
                            </button>
                        </div>

                        <!-- FORM INLINE UPLOAD MATERI -->
                        <div id="upload-material-form-vendor" class="hidden mb-6 p-5 bg-purple-50/50 border border-purple-200 rounded-2xl transition">
                            <h3 class="text-sm font-extrabold text-purple-900 mb-3 flex items-center gap-2">
                                Form Upload Modul Pembelajaran Baru
                            </h3>
                            <form action="{{ route('vendor.materials.store', $course) }}" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Materi Pembelajaran</label>
                                    <input type="text" name="title" required placeholder="Contoh: Modul 1 - Setup Cloud Environment"
                                           class="w-full border-slate-300 rounded-xl text-xs p-2.5 bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Berkas File (PDF/DOCX/PPTX/MP4/ZIP)</label>
                                    <input type="file" name="file" required accept=".pdf,.docx,.pptx,.mp4,.zip"
                                           class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-100 file:text-purple-700">
                                </div>
                                <div class="flex justify-end gap-2 pt-2 border-t border-purple-200">
                                    <button type="button" onclick="document.getElementById('upload-material-form-vendor').classList.add('hidden')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                                    <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-sm transition">Upload Materi</button>
                                </div>
                            </form>
                        </div>

                        @if ($materials->count())
                            <div class="grid gap-4 md:grid-cols-2">
                                @foreach ($materials as $material)
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                        <h3 class="font-semibold text-slate-900">
                                            {{ $material->title }}
                                        </h3>

                                        <p class="mt-2 text-sm text-slate-500">
                                            Uploaded {{ optional($material->created_at)->format('d M Y') ?: '-' }}
                                        </p>

                                        <div class="mt-4 flex flex-wrap gap-2">
                                            <form action="{{ route('vendor.materials.destroy', $material) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin hapus materi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700 transition">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-8 text-center text-slate-500 text-sm">
                                Belum ada materi untuk course sertifikasi ini. Klik "+ Add Material" untuk mulai upload.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- RIGHT COLUMN: DAFTAR MAHASISWA TERDAFTAR (1 COL) -->
                <div class="space-y-6">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-slate-900 mb-5">
                            Mahasiswa Terdaftar
                        </h2>

                        @if ($students->count())
                            <div class="space-y-3">
                                @foreach ($students as $std)
                                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <p class="font-semibold text-slate-900">
                                                    {{ $std->name ?? 'Mahasiswa' }}
                                                </p>

                                                <p class="mt-1 text-sm text-slate-500">
                                                    {{ $std->email ?? 'No email' }}
                                                </p>
                                            </div>

                                            <span class="text-xs font-bold px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-800">
                                                Enrolled
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-500">
                                Belum ada mahasiswa yang terdaftar pada course sertifikasi ini.
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
