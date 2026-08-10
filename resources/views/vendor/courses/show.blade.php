<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-6 space-y-6" x-data="{ showMatForm: false, showQuizForm: false }">

            {{-- ALERTS --}}
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

            {{-- HEADER NAVIGATION --}}
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('vendor.courses.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-700 transition">
                    ← Kembali ke Daftar Sertifikasi
                </a>

                <div class="flex items-center gap-3">
                    <a href="{{ route('vendor.courses.edit', $course) }}" 
                       class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                        ✏️ Edit Course
                    </a>

                    <form action="{{ route('vendor.courses.toggle-archive', $course) }}" method="POST" onsubmit="return confirm('Ubah status aktif course ini?')">
                        @csrf
                        <button type="submit" class="rounded-xl border px-4 py-2 text-xs font-bold transition {{ $course->is_archived ? 'border-emerald-200 text-emerald-700 bg-emerald-50 hover:bg-emerald-100' : 'border-amber-200 text-amber-700 bg-amber-50 hover:bg-amber-100' }}">
                            {{ $course->is_archived ? '🚀 Aktifkan Course' : '📦 Arsipkan ke Draft Bank' }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- HERO DETAIL BANNER --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-6">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                            Portal Author Mitra Vendor &bull; Manajemen Sertifikasi Industri
                        </p>

                        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">
                            {{ $course->name }}
                        </h1>

                        <p class="mt-3 max-w-3xl text-slate-500 leading-relaxed text-sm">
                            {{ $course->description ?: 'Pengelolaan modul pelatihan, kuis evaluasi, serta penentuan threshold sertifikat industri mahasiswa.' }}
                        </p>

                        <div class="mt-4 flex flex-wrap gap-2 text-xs">
                            <span class="rounded-full bg-slate-100 px-3.5 py-1 font-bold text-slate-700">
                                Level: {{ $course->level ?? 'Beginner' }}
                            </span>

                            <span class="rounded-full bg-purple-50 px-3.5 py-1 font-bold text-purple-700 border border-purple-200">
                                Kategori: {{ optional($course->category)->name ?? 'General' }}
                            </span>

                            <span class="rounded-full bg-emerald-50 px-3.5 py-1 font-bold text-emerald-700 border border-emerald-200">
                                Threshold Sertifikat: {{ $course->certificate_threshold ?? 75 }}%
                            </span>

                            <span class="rounded-full {{ $course->is_archived ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }} px-3.5 py-1 font-bold">
                                Status: {{ $course->is_archived ? '🔴 Archived / Draft Bank' : '🟢 Aktif Dipublikasikan' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- THRESHOLD SETTING CARD --}}
            <div class="rounded-3xl border border-indigo-200 bg-gradient-to-br from-indigo-50/50 via-white to-indigo-50/30 p-6 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                            <span>⚡ Threshold Kelulusan Sertifikat Industri</span>
                            <span class="text-xs font-bold text-indigo-600 bg-indigo-100 px-2.5 py-0.5 rounded-full">Otonomi Vendor</span>
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

            {{-- STATISTIK COURSE GRID --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Student</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $students->count() }}</p>
                    <p class="mt-1 text-xs text-slate-500">Mahasiswa terdaftar</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Materials</p>
                    <p class="mt-2 text-3xl font-bold text-purple-600">{{ $materials->count() }}</p>
                    <p class="mt-1 text-xs text-slate-500">Modul / file pembelajaran</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Quizzes</p>
                    <p class="mt-2 text-3xl font-bold text-indigo-600">{{ $quizzes->count() }}</p>
                    <p class="mt-1 text-xs text-slate-500">Kuis harian & kuis akhir</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Target Skills</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-600">{{ $course->skills->count() }}</p>
                    <p class="mt-1 text-xs text-slate-500">Kompetensi diuji</p>
                </div>
            </div>

            {{-- MANAGEMENT 2 COLUMNS: MATERIALS & QUIZZES | ENROLLED STUDENTS --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- Left Column: Materials & Quizzes Builder (7 Cols) --}}
                <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-6">
                    
                    {{-- Section 1: Materials --}}
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h3 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                                <span>📚 Modul Pembelajaran</span>
                                <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2.5 py-0.5 rounded-full">{{ $materials->count() }} Files</span>
                            </h3>
                            <button type="button" @click="showMatForm = !showMatForm" class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                                <span x-text="showMatForm ? '× Tutup Form' : '+ Upload Modul Materi'"></span>
                            </button>
                        </div>

                        {{-- Upload Material Form --}}
                        <div x-show="showMatForm" x-transition class="p-4 bg-purple-50/70 border border-purple-200 rounded-2xl space-y-3">
                            <form action="{{ route('vendor.materials.store', $course) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Materi Pembelajaran</label>
                                    <input type="text" name="title" required placeholder="Contoh: Modul 1 - Setup Cloud Architecture"
                                           class="w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-2.5 bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Berkas File (PDF/DOCX/PPTX/MP4)</label>
                                    <input type="file" name="file" required accept=".pdf,.docx,.pptx,.mp4,.zip"
                                           class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-100 file:text-purple-700">
                                </div>
                                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                                    🚀 Unggah Modul Sekarang
                                </button>
                            </form>
                        </div>

                        <div class="space-y-2">
                            @forelse($materials as $mat)
                                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 flex items-center justify-between hover:bg-slate-100/80 transition">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-lg">📄</span>
                                        <div>
                                            <span class="font-bold text-slate-900 block">{{ $mat->title }}</span>
                                            <span class="text-[11px] text-slate-400 font-normal">Tersedia untuk Mahasiswa</span>
                                        </div>
                                    </div>
                                    <form action="{{ route('vendor.materials.destroy', $mat) }}" method="POST" onsubmit="return confirm('Hapus materi ini?')" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-bold px-2 py-1 bg-rose-50 rounded-lg">🗑️ Hapus</button>
                                    </form>
                                </div>
                            @empty
                                <div class="p-6 text-center bg-slate-50 border border-dashed border-slate-200 rounded-2xl text-xs text-slate-400">
                                    Belum ada modul pembelajaran diunggah. Gunakan tombol di atas untuk menambah materi.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Section 2: Quizzes --}}
                    <div class="space-y-4 pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h3 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                                <span>📝 Kuis Evaluasi Kelulusan</span>
                                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-full">{{ $quizzes->count() }} Kuis</span>
                            </h3>
                            <button type="button" @click="showQuizForm = !showQuizForm" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                                <span x-text="showQuizForm ? '× Tutup Form' : '+ Buat Kuis Baru'"></span>
                            </button>
                        </div>

                        {{-- Create Quiz Form --}}
                        <div x-show="showQuizForm" x-transition class="p-4 bg-indigo-50/70 border border-indigo-200 rounded-2xl space-y-3">
                            <form action="{{ route('vendor.quizzes.store', $course) }}" method="POST" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Kuis Evaluasi</label>
                                    <input type="text" name="title" required placeholder="Contoh: Kuis Sertifikasi Final Cloud Computing"
                                           class="w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-2.5 bg-white">
                                </div>

                                <div class="grid grid-cols-3 gap-2">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Tipe Kuis</label>
                                        <select name="quiz_type" class="w-full border-slate-300 rounded-xl text-xs p-2 bg-white">
                                            <option value="final" selected>Ujian Final</option>
                                            <option value="weekly">Mingguan</option>
                                            <option value="daily">Harian</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Durasi (Menit)</label>
                                        <input type="number" name="time_limit" value="30" required min="1" class="w-full border-slate-300 rounded-xl text-xs p-2 bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Max Percobaan</label>
                                        <input type="number" name="max_attempts" value="3" required min="1" class="w-full border-slate-300 rounded-xl text-xs p-2 bg-white">
                                    </div>
                                </div>

                                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                                    💾 Buat Kuis & Kelola Soal
                                </button>
                            </form>
                        </div>

                        <div class="space-y-2">
                            @forelse($quizzes as $qz)
                                <div class="p-4 bg-indigo-50/60 border border-indigo-200 rounded-2xl text-xs font-bold text-indigo-950 flex items-center justify-between">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm">📝</span>
                                            <span class="text-sm font-extrabold text-slate-900">{{ $qz->title }}</span>
                                            <span class="text-[10px] bg-indigo-200 text-indigo-900 px-2 py-0.5 rounded-md font-black">{{ $qz->questions->count() }} Soal</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 font-normal mt-0.5">Durasi: {{ $qz->time_limit }} Menit | Tipe: {{ ucfirst($qz->quiz_type) }} | Max Attempt: {{ $qz->max_attempts }}x</p>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('vendor.quizzes.show', $qz) }}" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                                            ✏️ Kelola Soal
                                        </a>
                                        <form action="{{ route('vendor.quizzes.destroy', $qz) }}" method="POST" onsubmit="return confirm('Hapus kuis ini beserta seluruh soalnya?')" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-600 hover:text-rose-800 font-bold bg-rose-50 rounded-xl">🗑️</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center bg-slate-50 border border-dashed border-slate-200 rounded-2xl text-xs text-slate-400">
                                    Belum ada kuis kelulusan dibuat. Gunakan tombol di atas untuk membuat kuis.
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                {{-- Right Column: Enrolled Students List (5 Cols) --}}
                <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                            <span>👨‍🎓 Mahasiswa Terdaftar</span>
                            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full">{{ $students->count() }} Mhs</span>
                        </h3>
                    </div>

                    <div class="space-y-2.5 max-h-[500px] overflow-y-auto pr-1">
                        @forelse($students as $std)
                            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between text-xs hover:bg-slate-100/80 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-black flex items-center justify-center text-xs">
                                        {{ strtoupper(substr($std->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 block">{{ $std->name }}</span>
                                        <span class="text-[11px] text-slate-400">{{ $std->email }}</span>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 font-extrabold text-[10px] rounded-lg">
                                    Enrolled
                                </span>
                            </div>
                        @empty
                            <div class="p-8 text-center bg-slate-50 border border-dashed border-slate-200 rounded-2xl text-xs text-slate-400">
                                Belum ada mahasiswa terdaftar pada course sertifikasi ini.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
