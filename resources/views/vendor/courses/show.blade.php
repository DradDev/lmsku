<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                🎓 {{ $course->name }}
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('vendor.courses.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold text-xs rounded-xl">
                    ← Kembali
                </a>
                <a href="{{ route('vendor.courses.edit', $course) }}" class="px-4 py-2 bg-indigo-600 text-white font-bold text-xs rounded-xl">
                    ✏️ Edit Course
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
            <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-xl shadow-sm font-semibold text-xs">
                {{ session('success') }}
            </div>
            @endif

            <!-- Hero Detail Card -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 bg-purple-100 text-purple-800 font-extrabold text-xs rounded-md">
                            🏢 Vendor Certified: {{ Auth::user()->name }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold {{ $course->is_archived ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200' }}">
                            {{ $course->is_archived ? '🔴 Draft Bank (Archived)' : '🟢 Active' }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <form action="{{ route('vendor.courses.toggle-archive', $course) }}" method="POST">
                            @csrf
                            @if($course->is_archived)
                                <button type="submit" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full text-xs font-bold transition">
                                    🚀 Aktifkan Kembali Course
                                </button>
                            @else
                                <button type="submit" class="px-3 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded-full text-xs font-bold transition">
                                    📦 Arsipkan ke Draft Bank
                                </button>
                            @endif
                        </form>

                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 font-bold text-xs rounded-full">
                            Passing Grade Kuis: {{ $course->certificate_threshold }}
                        </span>
                    </div>
                </div>

                <p class="text-xs text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ $course->description }}
                </p>
            </div>

            <!-- Management 2 Columns: Materials & Enrolled Students -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Materials & Quizzes Section -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5" x-data="{ showMatForm: false, showQuizForm: false }">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <h3 class="font-extrabold text-base text-gray-900">📚 Modul Materi & Kuis Kelulusan</h3>
                    </div>

                    <!-- Materi Pembelajaran -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-xs text-gray-700 uppercase tracking-wider">📑 Materi Pelatihan:</h4>
                            <button type="button" @click="showMatForm = !showMatForm" class="px-2.5 py-1 bg-purple-50 text-purple-700 hover:bg-purple-100 font-bold text-xs rounded-lg transition">
                                <span x-text="showMatForm ? '× Tutup Form' : '+ Upload Materi'"></span>
                            </button>
                        </div>

                        <!-- Upload Material Form -->
                        <div x-show="showMatForm" x-transition class="p-4 bg-purple-50/70 border border-purple-200 rounded-xl space-y-3">
                            <form action="{{ route('vendor.materials.store', $course) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Judul Materi Pembelajaran</label>
                                    <input type="text" name="title" required placeholder="Contoh: Modul 1 - Setup Environment Cloud"
                                           class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-2.5 bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Berkas File (PDF/DOCX/PPTX/MP4)</label>
                                    <input type="file" name="file" required accept=".pdf,.docx,.pptx,.mp4,.zip"
                                           class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-100 file:text-purple-700">
                                </div>
                                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                                    🚀 Upload Materi
                                </button>
                            </form>
                        </div>

                        @forelse($course->materials as $mat)
                            <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 flex items-center justify-between hover:bg-gray-100/80 transition">
                                <div class="flex items-center gap-2">
                                    <span class="text-base">📄</span>
                                    <span>{{ $mat->title }}</span>
                                </div>
                                <form action="{{ route('vendor.materials.destroy', $mat) }}" method="POST" onsubmit="return confirm('Hapus materi ini?')" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-bold">🗑️ Hapus</button>
                                </form>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 italic">Belum ada materi pembelajaran diunggah.</p>
                        @endforelse

                        <!-- Kuis Kelulusan -->
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <h4 class="font-bold text-xs text-gray-700 uppercase tracking-wider">📝 Kuis Evaluasi Sertifikasi:</h4>
                            <button type="button" @click="showQuizForm = !showQuizForm" class="px-2.5 py-1 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-bold text-xs rounded-lg transition">
                                <span x-text="showQuizForm ? '× Tutup Form' : '+ Buat Kuis'"></span>
                            </button>
                        </div>

                        <!-- Create Quiz Form -->
                        <div x-show="showQuizForm" x-transition class="p-4 bg-indigo-50/70 border border-indigo-200 rounded-xl space-y-3">
                            <form action="{{ route('vendor.quizzes.store', $course) }}" method="POST" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Judul Kuis Evaluasi</label>
                                    <input type="text" name="title" required placeholder="Contoh: Kuis Sertifikasi Dasar Cloud Computing"
                                           class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-2.5 bg-white">
                                </div>

                                <div class="grid grid-cols-3 gap-2">
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-700 mb-1">Tipe Kuis</label>
                                        <select name="quiz_type" class="w-full border-gray-300 rounded-xl text-xs p-2 bg-white">
                                            <option value="final" selected>Ujian Final</option>
                                            <option value="weekly">Mingguan</option>
                                            <option value="daily">Harian</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-700 mb-1">Durasi (Menit)</label>
                                        <input type="number" name="time_limit" value="30" required min="1" class="w-full border-gray-300 rounded-xl text-xs p-2 bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-700 mb-1">Max Percobaan</label>
                                        <input type="number" name="max_attempts" value="3" required min="1" class="w-full border-gray-300 rounded-xl text-xs p-2 bg-white">
                                    </div>
                                </div>

                                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                                    💾 Buat Kuis Baru
                                </button>
                            </form>
                        </div>

                        @forelse($course->quizzes as $qz)
                            <div class="p-3.5 bg-indigo-50/80 border border-indigo-200 rounded-xl text-xs font-bold text-indigo-900 flex items-center justify-between">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span>📝 {{ $qz->title }}</span>
                                        <span class="text-[10px] bg-indigo-200 text-indigo-800 px-2 py-0.5 rounded font-black">{{ $qz->questions->count() }} Soal</span>
                                    </div>
                                    <span class="text-[10.5px] text-gray-500 font-normal">Waktu: {{ $qz->time_limit }} Menit | Tipe: {{ ucfirst($qz->quiz_type) }}</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('vendor.quizzes.show', $qz) }}" class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition">
                                        ✏️ Kelola Soal
                                    </a>
                                    <form action="{{ route('vendor.quizzes.destroy', $qz) }}" method="POST" onsubmit="return confirm('Hapus kuis ini beserta seluruh soalnya?')" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-bold">🗑️</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 italic">Belum ada kuis kelulusan dibuat.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Enrolled Students Section -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <h3 class="font-extrabold text-base text-gray-900">👨‍🎓 Enrolled Students ({{ $course->students->count() }})</h3>
                    </div>

                    <div class="space-y-2">
                        @forelse($course->students as $std)
                            <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-between text-xs">
                                <span class="font-bold text-gray-900">{{ $std->name }}</span>
                                <span class="text-gray-500">{{ $std->email }}</span>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 italic">Belum ada mahasiswa terdaftar pada course sertifikasi ini.</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
