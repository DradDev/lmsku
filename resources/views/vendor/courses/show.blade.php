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

    $courseStudents = $students ?? collect();
    $completedStudentCount = $courseStudents->filter(fn($s) => ($s->pivot->status ?? '') === 'completed')->count();
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
                <div class="flex items-center justify-between mb-4">
                    <a href="{{ route('vendor.courses.index') }}"
                        class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-700 transition">
                        ← Kembali ke Daftar Sertifikasi
                    </a>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('vendor.courses.edit', $course) }}" 
                           class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                            ✏️ Edit Course
                        </a>

                        <form action="{{ route('vendor.courses.toggle-archive', $course) }}" method="POST" onsubmit="return confirm('Ubah status aktif course ini?')">
                            @csrf
                            <button type="submit" class="rounded-xl border px-4 py-2 text-sm font-semibold transition {{ $course->is_archived ? 'border-emerald-200 text-emerald-700 bg-emerald-50 hover:bg-emerald-100' : 'border-amber-200 text-amber-700 bg-amber-50 hover:bg-amber-100' }}">
                                {{ $course->is_archived ? 'Aktifkan Course' : 'Arsipkan Course' }}
                            </button>
                        </form>
                    </div>
                </div>

                <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-6">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-purple-600 mb-2">
                            Portal Author Mitra Vendor &bull; Manajemen Sertifikasi Industri
                        </p>

                        <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                            {{ $course->name }}
                        </h1>

                        <p class="mt-3 max-w-3xl text-slate-500 leading-7">
                            {{ $course->description ?: 'Pengelolaan materi modul, kuis evaluasi, serta penentuan threshold sertifikat industri mahasiswa.' }}
                        </p>

                        <div class="mt-4 flex flex-wrap gap-2 text-sm">
                            <span class="rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-700">
                                Level: {{ $course->level ?? 'Beginner' }}
                            </span>

                            <span class="rounded-full bg-purple-50 px-3 py-1 font-semibold text-purple-700 border border-purple-200">
                                Kategori: {{ optional($course->category)->name ?? 'General' }}
                            </span>

                            <span class="rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-700 border border-emerald-200">
                                Threshold Sertifikat: {{ $course->certificate_threshold ?? 75 }}%
                            </span>

                            <span class="rounded-full {{ $course->is_archived ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }} px-3.5 py-1 font-semibold">
                                Status: {{ $course->is_archived ? '🔴 Archived / Draft Bank' : '🟢 Aktif Dipublikasikan' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD PENGATURAN THRESHOLD SANGAT PRAKTIS LANGSUNG DI VIEW -->
            <div class="rounded-3xl border border-purple-200 bg-gradient-to-br from-purple-50/50 via-white to-purple-50/30 p-6 shadow-sm mb-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                            <span>⚡ Threshold Kelulusan Sertifikat Industri</span>
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
                            <input type="hidden" name="level" value="{{ $course->level }}">
                            <input type="hidden" name="category_id" value="{{ $course->category_id }}">
                            <input type="hidden" name="description" value="{{ $course->description }}">

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
                                    Bank Kuis Sertifikasi
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

                        <!-- FORM INLINE BUAT KUIS BARU -->
                        <div id="create-quiz-form-vendor" class="hidden mb-6 p-5 bg-purple-50/50 border border-purple-200 rounded-2xl transition">
                            <h3 class="text-sm font-extrabold text-purple-900 mb-3 flex items-center gap-2">
                                📝 Form Buat Kuis Sertifikasi Baru
                            </h3>
                            <form method="POST" action="{{ route('vendor.quizzes.store', $course->id) }}" class="space-y-4 text-xs">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Judul Kuis</label>
                                        <input type="text" name="title" placeholder="Contoh: Kuis Akhir - Final Certification Exam" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold bg-white" required>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Tipe Kuis Sertifikasi</label>
                                        <select name="quiz_type" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-bold text-purple-900 bg-white" required>
                                            <option value="final" selected>🏆 Kuis Akhir (Final Quiz / Penentu Sertifikat)</option>
                                            <option value="weekly">📅 Kuis Mingguan / Evaluasi Bab</option>
                                            <option value="daily">📝 Kuis Biasa / Harian (Section Quiz)</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Durasi Pengerjaan (Menit)</label>
                                        <input type="number" name="time_limit" value="30" min="1" placeholder="Contoh: 30" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold bg-white" required>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">Maksimal Percobaan (Attempts)</label>
                                        <input type="number" name="max_attempts" value="3" min="1" max="100" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-semibold bg-white" required>
                                    </div>
                                </div>

                                <div class="flex justify-end gap-2 pt-2 border-t border-purple-200">
                                    <button type="button" onclick="document.getElementById('create-quiz-form-vendor').classList.add('hidden')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                                    <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl text-xs shadow-md">Simpan Kuis & Lanjut Buat Soal</button>
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

                                                <span class="inline-flex items-center rounded-full border border-purple-200 bg-purple-50 text-purple-800 px-3 py-1 text-xs font-semibold">
                                                    {{ ucfirst($quiz->quiz_type ?? 'Quiz') }}
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
                                                    {{ $quiz->max_attempts }}x
                                                </span>
                                            </p>

                                            @if (($quiz->quiz_type ?? '') === 'final')
                                                <p class="mt-2 text-xs font-extrabold text-emerald-700 flex items-center gap-1">
                                                    <span>🏆 Kuis Akhir Penentu Kelulusan Sertifikat Digital & Hash Blockchain.</span>
                                                </p>
                                            @endif
                                        </div>

                                        <div class="flex flex-wrap gap-2">
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
                                📑 Form Upload Modul Pembelajaran Baru
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
                                    <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-sm transition">🚀 Upload Materi</button>
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
