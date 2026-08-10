<x-app-layout>
    @php
    $courseEnrollments = $enrollments ?? collect();

    $statusLabels = [
    'not_started' => 'Not Started',
    'in_progress' => 'In Progress',
    'completed' => 'Completed',
    ];

    $statusClasses = [
    'not_started' => 'bg-slate-100 text-slate-700 border-slate-200',
    'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200',
    'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    ];

    $averageProgress = $courseEnrollments->count() > 0
    ? round($courseEnrollments->avg('progress_percent'))
    : 0;

    $completedStudentCount = $courseEnrollments
    ->where('status', 'completed')
    ->count();
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

            <div class="mb-8">
                @if(isset($siblingOfferings) && $siblingOfferings->count() > 1)
                    <div class="mb-6 p-4 bg-slate-900 border border-indigo-500/30 rounded-2xl shadow-md text-white flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-500/20 border border-indigo-400/30 flex items-center justify-center font-bold text-indigo-300 text-lg">
                                🎓
                            </div>
                            <div>
                                <h3 class="font-bold text-sm text-slate-100">Beralih Kelola Kelas Pararel</h3>
                                <p class="text-xs text-slate-400">Anda mengampu {{ $siblingOfferings->count() }} kelas untuk mata kuliah ini. Pilih kelas untuk melihat data & nilainya:</p>
                            </div>
                        </div>

                        <div class="flex items-center flex-wrap gap-2">
                            @foreach($siblingOfferings as $sOffering)
                                <a href="{{ route('lecturer.courses.show', $sOffering->id) }}"
                                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-2 border text-decoration-none {{ $sOffering->id == $course->id ? 'bg-indigo-600 text-white border-indigo-500 shadow-md' : 'bg-white/10 text-slate-300 border-white/10 hover:bg-white/20' }}">
                                    <span>📌 {{ $sOffering->section_name ?: 'Kelas ' . $loop->iteration }}</span>
                                    <span class="px-1.5 py-0.5 rounded-md text-[10px] font-extrabold {{ $sOffering->id == $course->id ? 'bg-white/20 text-white' : 'bg-black/20 text-slate-300' }}">
                                        {{ $sOffering->enrollments ? $sOffering->enrollments->count() : 0 }} Mhs
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex items-center justify-between mb-4">
                    <a href="{{ route('lecturer.courses.index') }}"
                        class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-700">
                        ← Kembali ke Daftar Kelas
                    </a>

                    <div class="flex items-center gap-2">
                        <form action="{{ route('lecturer.courses.archive', $course->id) }}" method="POST" onsubmit="return confirm('Ubah status aktif kelas ini?')">
                            @csrf
                            <button type="submit" class="rounded-xl border px-4 py-2 text-sm font-semibold transition {{ ($course->status ?? 'active') === 'active' ? 'border-amber-200 text-amber-700 bg-amber-50 hover:bg-amber-100' : 'border-emerald-200 text-emerald-700 bg-emerald-50 hover:bg-emerald-100' }}">
                                {{ ($course->status ?? 'active') === 'active' ? 'Arsipkan Kelas' : 'Aktifkan Kelas' }}
                            </button>
                        </form>
                    </div>
                </div>

                <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-6">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                            Portal Dosen &bull; Manajemen Kelas Pembelajaran
                        </p>

                        <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                            {{ $course->name }}
                        </h1>

                        <p class="mt-3 max-w-3xl text-slate-500 leading-7">
                            {{ $course->description ?: 'Pengelolaan materi pembelajaran, bank kuis, serta penentuan threshold sertifikasi mahasiswa.' }}
                        </p>

                        <div class="mt-4 flex flex-wrap gap-2 text-sm">
                            <span class="rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-700">
                                Level: {{ $course->level ?? 'Beginner' }}
                            </span>

                            <span class="rounded-full bg-indigo-50 px-3 py-1 font-semibold text-indigo-700">
                                Avg Progress: {{ $averageProgress }}%
                            </span>

                            <span class="rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-700">
                                Threshold Sertifikat: {{ $course->certificate_threshold ?? 75 }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD PENGATURAN THRESHOLD SANGAT PRAKTIS LANGSUNG DI VIEW -->
            <div class="rounded-3xl border border-indigo-200 bg-gradient-to-br from-indigo-50/50 via-white to-indigo-50/30 p-6 shadow-sm mb-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                            <span>⚡ Threshold Kelulusan Sertifikat Kelas</span>
                            <span class="text-xs font-bold text-indigo-600 bg-indigo-100 px-2.5 py-0.5 rounded-full">Otonomi Dosen</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed max-w-2xl">
                            Tentukan batas nilai minimal (%) pada Kuis Akhir yang harus dicapai mahasiswa agar Sertifikat Digital & Hash Blockchain otomatis diterbitkan.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <form action="{{ route('lecturer.courses.update', $course->id) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PUT')
                            <div class="relative">
                                <input type="number" 
                                       name="certificate_threshold" 
                                       value="{{ old('certificate_threshold', $course->certificate_threshold ?? 75) }}" 
                                       min="0" 
                                       max="100" 
                                       class="w-24 rounded-xl border-slate-300 text-sm font-extrabold text-indigo-900 text-center focus:border-indigo-500 focus:ring-indigo-500 py-2" 
                                       required>
                                <span class="absolute right-2 top-2.5 text-xs font-bold text-slate-400">%</span>
                            </div>
                            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md transition whitespace-nowrap">
                                Simpan Threshold
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- STATISTIK KELAS -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Student</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $courseEnrollments->count() }}</p>
                    <p class="mt-1 text-xs text-slate-500">Mahasiswa terdaftar</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Lulus / Completed</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-600">{{ $completedStudentCount }}</p>
                    <p class="mt-1 text-xs text-slate-500">Mahasiswa telah selesai</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Materials</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $materials->count() }}</p>
                    <p class="mt-1 text-xs text-slate-500">Modul / file pembelajaran</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Quizzes</p>
                    <p class="mt-2 text-3xl font-bold text-indigo-600">{{ $quizzes->count() }}</p>
                    <p class="mt-1 text-xs text-slate-500">Kuis harian & kuis akhir</p>
                </div>
            </div>

            <!-- PERINTAH RETAKE KUIS (JIKA ADA) -->
            @if(isset($retakeRequests) && $retakeRequests->count() > 0)
                <div class="mb-8 rounded-3xl border border-amber-200 bg-amber-50/60 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-amber-900 mb-3 flex items-center gap-2">
                        <span>⚠️ Permintaan Retake Kuis Mahasiswa ({{ $retakeRequests->where('status', 'pending')->count() }} Pending)</span>
                    </h2>
                    <div class="space-y-3">
                        @foreach($retakeRequests as $req)
                            <div class="flex items-center justify-between p-3.5 bg-white border border-amber-200 rounded-2xl">
                                <div>
                                    <span class="font-bold text-slate-900 text-sm">{{ $req->user->name ?? 'Student' }}</span>
                                    <span class="text-xs text-slate-500 font-medium"> meminta retake kuis </span>
                                    <span class="font-bold text-indigo-700 text-xs">'{{ $req->quiz->title ?? 'Quiz' }}'</span>
                                    <p class="text-xs text-slate-400 mt-0.5">Alasan: {{ $req->reason ?: 'Tidak ada alasan' }}</p>
                                </div>
                                @if($req->status === 'pending')
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('lecturer.quizzes.retake.approve', $req->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm">Setujui</button>
                                        </form>
                                        <form action="{{ route('lecturer.quizzes.retake.reject', $req->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-sm">Tolak</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs font-bold px-3 py-1 rounded-full {{ $req->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ ucfirst($req->status) }}
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2 space-y-6">

                    <!-- BANK KUIS DOSEN (KUIS BIASA VS KUIS AKHIR) -->
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
                                    onclick="document.getElementById('create-quiz-form-container').classList.toggle('hidden')" 
                                    class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-indigo-700 shadow-md transition whitespace-nowrap">
                                + Buat Kuis Baru
                            </button>
                        </div>

                        <!-- FORM INLINE BUAT KUIS BARU (DENGAN PILIHAN KUIS BIASA VS KUIS AKHIR) -->
                        <div id="create-quiz-form-container" class="hidden mb-6 p-5 bg-indigo-50/50 border border-indigo-200 rounded-2xl transition">
                            <h3 class="text-sm font-extrabold text-indigo-900 mb-3 flex items-center gap-2">
                                📝 Form Buat Kuis Pembelajaran Baru
                            </h3>
                            <form method="POST" action="{{ route('lecturer.courses.quizzes.store', $course->id) }}" class="space-y-4 text-xs">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Judul Kuis</label>
                                        <input type="text" name="title" placeholder="Contoh: Kuis Akhir - Final Certification Exam" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold" required>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Tipe Kuis Pembelajaran</label>
                                        <select name="quiz_type" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-bold text-indigo-900 bg-white" required>
                                            <option value="daily">📝 Kuis Biasa / Harian (Section Quiz)</option>
                                            <option value="weekly">📅 Kuis Mingguan / Evaluasi Bab</option>
                                            <option value="final">🏆 Kuis Akhir (Final Quiz / Penentu Sertifikat)</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Durasi Pengerjaan (Menit)</label>
                                        <input type="number" name="time_limit" min="1" placeholder="Contoh: 60 (kosongkan jika tanpa batas)" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold">
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Maksimal Percobaan (Attempts)</label>
                                        <div class="flex items-center gap-2">
                                            <input type="number" id="max_attempts_input" name="max_attempts" value="1" min="0" max="100" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold">
                                            <label class="inline-flex items-center gap-1.5 px-2.5 py-2.5 bg-slate-100 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-200 transition whitespace-nowrap">
                                                <input type="checkbox" name="is_unlimited" value="1" onchange="document.getElementById('max_attempts_input').disabled = this.checked; if(this.checked){ document.getElementById('max_attempts_input').value = 0; }" class="rounded text-indigo-600 focus:ring-indigo-500">
                                                <span class="text-[11px] font-bold text-slate-700">♾️ Unlimited</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Tanggal Rilis (Opsional)</label>
                                        <input type="datetime-local" name="start_date" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold">
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Deadline Selesai (Opsional)</label>
                                        <input type="datetime-local" name="end_date" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold">
                                    </div>

                                    <div class="md:col-span-2 mt-2 pt-2 border-t border-indigo-100">
                                        <label class="block font-bold text-slate-700 mb-1.5">🎯 Target Scope Distribusi Kuis:</label>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                            <label class="flex items-center gap-2.5 p-2.5 border border-indigo-200 rounded-xl bg-white cursor-pointer hover:border-indigo-400 transition">
                                                <input type="radio" name="target_scope" value="all" checked class="text-indigo-600 focus:ring-indigo-500">
                                                <div>
                                                    <span class="block font-bold text-indigo-950 text-xs">🌐 Semua Kelas Pararel (Master)</span>
                                                    <span class="block text-[11px] text-slate-500">Kuis akan otomatis berlaku untuk Kelas A, B, C, dst.</span>
                                                </div>
                                            </label>
                                            <label class="flex items-center gap-2.5 p-2.5 border border-slate-200 rounded-xl bg-white cursor-pointer hover:border-indigo-400 transition">
                                                <input type="radio" name="target_scope" value="class" class="text-indigo-600 focus:ring-indigo-500">
                                                <div>
                                                    <span class="block font-bold text-slate-800 text-xs">📌 Khusus {{ $course->section_name ?: 'Kelas Ini' }}</span>
                                                    <span class="block text-[11px] text-slate-500">Kuis khusus/remedial hanya untuk rombel kelas ini.</span>
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

                        <!-- DAFTAR KUIS -->
                        <div class="space-y-4">
                            @forelse ($quizzes as $quiz)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="text-lg font-semibold text-slate-900">
                                                {{ $quiz->title }}
                                            </h3>

                                            <span class="inline-flex items-center rounded-full border {{ $quiz->quiz_type_badge_class }} px-3 py-1 text-xs font-semibold">
                                                {{ $quiz->quiz_type_label }}
                                            </span>
                                        </div>

                                        <p class="mt-2 text-sm text-slate-500">
                                            Time Limit:
                                            <span class="text-slate-700">
                                                {{ $quiz->time_limit ? $quiz->time_limit . ' minutes' : 'No limit' }}
                                            </span>
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500">
                                            Attempts Allowed:
                                            <span class="text-slate-700">
                                                {{ $quiz->max_attempts }}
                                            </span>
                                        </p>

                                        @if ($quiz->isFinal())
                                        <p class="mt-2 text-xs font-extrabold text-emerald-700 flex items-center gap-1">
                                            <span>🏆 Kuis Akhir Penentu Kelulusan Sertifikat Digital & Hash Blockchain.</span>
                                        </p>
                                        @endif
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        <button type="button" onclick="document.getElementById('edit-quiz-form-{{ $quiz->id }}').classList.toggle('hidden')" class="rounded-xl bg-amber-50 border border-amber-200 text-amber-700 px-3.5 py-2 text-xs font-semibold hover:bg-amber-100 transition">
                                            ⚙️ Waktu & Durasi
                                        </button>

                                        <a href="{{ route('lecturer.courses.quizzes.results.index', [$course->id, $quiz->id]) }}"
                                            class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
                                            Lihat Hasil
                                        </a>

                                        <a href="{{ route('lecturer.dashboard', ['tab' => 'questions', 'quiz_id' => $quiz->id]) }}"
                                            class="rounded-xl bg-violet-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-violet-700">
                                            + Kelola Soal ({{ $quiz->questions_count ?? $quiz->questions()->count() }})
                                        </a>
                                        <form method="POST"
                                            action="{{ route('lecturer.courses.quizzes.destroy', [$course->id, $quiz->id]) }}"
                                            onsubmit="return confirm('Yakin ingin menghapus quiz ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div id="edit-quiz-form-{{ $quiz->id }}" class="hidden mt-4 pt-4 border-t border-slate-200">
                                    <form method="POST" action="{{ route('lecturer.courses.quizzes.update', [$course->id, $quiz->id]) }}" class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                        @csrf
                                        @method('PUT')

                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1">Judul Quiz</label>
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
                                            <button type="button" onclick="document.getElementById('edit-quiz-form-{{ $quiz->id }}').classList.add('hidden')" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg">Batal</button>
                                            <button type="submit" class="px-4 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg shadow-sm">Simpan Waktu & Pengaturan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-8 text-center text-slate-500">
                                Belum ada kuis untuk course ini. Klik "+ Buat Kuis Baru" di atas untuk menambahkan.
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- MATERI PEMBELAJARAN -->
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-6 flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-2xl font-semibold text-slate-900">
                                    Learning Materials
                                </h2>

                                <p class="mt-1 text-sm text-slate-500">
                                    Materi pembelajaran yang tersedia di course ini.
                                </p>
                            </div>

                            <a href="{{ route('lecturer.materials.create', $course->id) }}"
                                class="inline-flex items-center rounded-xl bg-violet-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-violet-700 whitespace-nowrap">
                                + Add Material
                            </a>
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
                                    <a href="{{ route('lecturer.materials.show', $material->id) }}"
                                        class="inline-flex rounded-xl bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700">
                                        View
                                    </a>

                                    <a href="{{ route('lecturer.materials.edit', $material->id) }}"
                                        class="inline-flex rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                                        Edit
                                    </a>

                                    <form action="{{ route('lecturer.materials.destroy', $material->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin hapus materi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-8 text-center text-slate-500">
                            Belum ada materi untuk course ini. Klik "+ Add Material" untuk mulai upload.
                        </div>
                        @endif
                    </div>
                </div>

                <!-- DAFTAR MAHASISWA TERDAFTAR -->
                <div class="space-y-6">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-slate-900 mb-5">
                            Mahasiswa Terdaftar
                        </h2>

                        @if ($courseEnrollments->count())
                        <div class="space-y-3">
                            @foreach ($courseEnrollments as $enrollment)
                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            {{ $enrollment->user->name ?? 'Unknown User' }}
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ $enrollment->user->email ?? 'No email' }}
                                        </p>
                                    </div>

                                    <span class="text-sm font-semibold text-indigo-700">
                                        {{ $enrollment->progress_percent ?? 0 }}%
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-500">
                            Belum ada mahasiswa yang terdaftar.
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>