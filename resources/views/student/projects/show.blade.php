<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Project Details
        </h2>
    </x-slot>

    @php
        $errors = $errors ?? new \Illuminate\Support\ViewErrorBag();
        $statusLabels = [
            'in_progress' => 'In Progress',
            'development' => 'Development',
            'review' => 'Review',
            'completed' => 'Done',
        ];

        $statusColors = [
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            'development' => 'bg-blue-100 text-blue-800',
            'review' => 'bg-purple-100 text-purple-800',
            'completed' => 'bg-green-100 text-green-800',
        ];

        $currentStatus = $participation?->status;
        $currentProgress = $participation?->progress_percent ?? 0;

        $joinedCount = $project->participations()->count();
        $maxStudents = $project->max_students ?? 1;
        $isFull = $joinedCount >= $maxStudents;

        $comments = $project->comments ?? collect();
        $isEligible = $eligibility['is_eligible'] ?? false;
        $creator = $project->creator ?? $project->user;
        $creatorName = $creator?->name ?? 'Pembimbing';
        $institutionName = $creator?->institution?->name ?? null;
    @endphp

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-xl font-medium">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-red-100 text-red-700 rounded-xl font-medium">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 bg-red-100 text-red-700 rounded-xl">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- 1. PROJECT HEADER & GENERAL OVERVIEW -->
            <div class="bg-white shadow-sm border border-gray-200 rounded-2xl p-6">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                            @if(($project->provider_type ?? 'internal') === 'external' || ($project->user->role ?? '') === 'vendor')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                    Mitra Industri: {{ $creatorName }}
                                    @if($institutionName)
                                        • {{ $institutionName }}
                                    @endif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    Akademik: {{ $creatorName }}
                                </span>
                            @endif

                            @if(!$participation && !$isEligible)
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-200">
                                    🔒 Mode Preview (Terkunci)
                                </span>
                            @endif
                        </div>

                        <h3 class="font-bold text-2xl text-gray-900">
                            {{ $project->title }}
                        </h3>

                        <div class="mt-3 flex flex-wrap gap-2 text-sm">
                            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700">
                                Level: <strong>{{ ucfirst($project->difficulty_level) }}</strong>
                            </span>

                            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700">
                                Durasi: <strong>{{ $project->duration_days }} Hari</strong>
                            </span>

                            <span class="px-3 py-1 rounded-full {{ $isFull ? 'bg-red-100 text-red-700 font-bold' : 'bg-green-100 text-green-700' }}">
                                Kuota: {{ $joinedCount }}/{{ $maxStudents }} Mahasiswa
                            </span>

                            @if ($participation)
                                <span class="px-3 py-1 rounded-full font-bold {{ $statusColors[$currentStatus] ?? 'bg-gray-100 text-gray-700' }}">
                                    Status Anda: {{ $statusLabels[$currentStatus] ?? $currentStatus }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-2 shrink-0">
                        <a href="{{ route('student.projects.index') }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                            Kembali ke Katalog
                        </a>

                        @if($project->brief_file_url)
                            @if($participation || $isEligible)
                                <a href="{{ $project->brief_file_url }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition shadow-sm">
                                    Download TOR / Brief Project (PDF)
                                </a>
                            @else
                                <div class="px-3 py-1.5 bg-slate-100 border border-slate-200 text-slate-500 font-semibold rounded-xl text-xs flex items-center gap-1.5 cursor-not-allowed"
                                     title="Selesaikan matkul prasyarat untuk mengunduh TOR proyek">
                                    🔒 Dokumen TOR Terkunci
                                </div>
                            @endif
                        @endif

                        @if ($participation)
                            <a href="{{ route('student.projects.my') }}"
                               class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded-xl text-sm transition">
                                My Projects
                            </a>
                        @endif
                    </div>
                </div>

                <div class="mt-6">
                    <h4 class="font-semibold text-gray-900 mb-2">
                        Deskripsi & Latar Belakang Proyek
                    </h4>

                    <p class="text-gray-700 whitespace-pre-line leading-relaxed text-sm">
                        {{ $project->description }}
                    </p>

                    @if($project->benefits)
                        <div class="mt-4 p-4 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-900 text-xs">
                            <span class="font-bold text-sm block mb-1">Benefits & Capaian Mahasiswa:</span>
                            <p class="text-indigo-800 font-medium">{{ $project->benefits }}</p>
                        </div>
                    @endif
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2 text-sm">
                            Syarat Kompetensi Utama (Primary Skills)
                        </h4>

                        @forelse ($project->skills as $skill)
                            <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-700 font-bold rounded-full text-xs mr-1 mb-2 border border-indigo-100">
                                ⚡ {{ $skill->name }}
                            </span>
                        @empty
                            <p class="text-xs text-gray-500">
                                Terbuka untuk umum (General Skill).
                            </p>
                        @endforelse
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2 text-sm">
                            Kategori & Topik Khusus (Tags)
                        </h4>

                        @forelse ($project->tags as $tag)
                            <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-700 font-medium rounded-full text-xs mr-1 mb-2">
                                #{{ $tag->name }}
                            </span>
                        @empty
                            <p class="text-xs text-gray-500">
                                Tidak ada tag khusus.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- 2. PARTICIPATION STATUS OR PREREQUISITE ELIGIBILITY BOX -->
            @if ($participation)
                <div class="bg-white shadow-sm border border-gray-200 rounded-2xl p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">
                        <div>
                            <h4 class="font-semibold text-lg text-gray-900">
                                Progres Pengerjaan Proyek Anda
                            </h4>
                            <p class="text-sm text-gray-500 mt-1">
                                Perbarui status tahap pengerjaan proyek Anda secara berkala.
                            </p>
                        </div>

                        <div class="text-left md:text-right">
                            <p class="text-xs text-gray-500">Status Saat Ini</p>
                            <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-bold {{ $statusColors[$currentStatus] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $statusLabels[$currentStatus] ?? $currentStatus }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-6">
                        <div class="flex justify-between text-xs font-bold text-gray-600 mb-2">
                            <span>Kemajuan Penyelesaian</span>
                            <span class="text-indigo-600 font-extrabold">{{ $currentProgress }}%</span>
                        </div>

                        <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden border border-gray-200">
                            <div class="h-3 rounded-full bg-gradient-to-r from-indigo-500 to-indigo-600"
                                 style="width: {{ $currentProgress }}%">
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('student.projects.update-progress', $project) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold mb-2 text-xs text-gray-700">
                                    Update Status Tahapan
                                </label>

                                <select name="status"
                                        class="border border-gray-300 rounded-xl w-full p-2.5 text-xs font-semibold"
                                        required>
                                    <option value="in_progress" @selected($currentStatus === 'in_progress')>In Progress (25%)</option>
                                    <option value="development" @selected($currentStatus === 'development')>Development (50%)</option>
                                    <option value="review" @selected($currentStatus === 'review')>Review (75%)</option>
                                    <option value="completed" @selected($currentStatus === 'completed')>Selesai / Done (100%)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-bold mb-2 text-xs text-gray-700">
                                    Catatan Perkembangan Proyek
                                </label>

                                <textarea name="note"
                                          class="border border-gray-300 rounded-xl w-full p-2.5 text-xs"
                                          rows="3"
                                          placeholder="Contoh: Modul utama sudah selesai, sedang integrasi sensor..."></textarea>
                            </div>
                        </div>

                        <div class="mt-5">
                            <button type="submit"
                                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition shadow-sm">
                                Simpan Progres
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <!-- MODE PRA-PENDAFTARAN (ELIGIBLE VS TERKUNCI) -->
                <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-200">
                    <h4 class="font-bold text-lg text-gray-900 mb-2">
                        Syarat Kelayakan & Pendaftaran Proyek
                    </h4>

                    @if(!$isEligible)
                        <!-- ROADMAP PRASYARAT KETIKA PROYEK TERKUNCI -->
                        <div class="p-5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-950 space-y-4 mb-5">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 bg-amber-200 text-amber-900 rounded-lg text-xs font-extrabold">
                                    🔒 PROYEK TERKUNCI
                                </span>
                                <span class="text-xs font-bold text-amber-900">
                                    Anda belum memenuhi prasyarat kompetensi untuk mengambil proyek ini.
                                </span>
                            </div>

                            <div class="space-y-2 text-xs border-t border-amber-200/70 pt-3">
                                <div class="flex items-start gap-2">
                                    <span class="font-black {{ ($eligibility['has_main_skill'] ?? false) ? 'text-emerald-700' : 'text-amber-800' }}">
                                        {{ ($eligibility['has_main_skill'] ?? false) ? '✓' : '✗' }}
                                    </span>
                                    <div>
                                        <span class="font-bold">Skill Kompetensi:</span> {{ $eligibility['main_skill_name'] ?? 'Skill' }}
                                    </div>
                                </div>

                                <div class="flex items-start gap-2">
                                    <span class="font-black {{ ($eligibility['has_verified_certificate'] ?? false) ? 'text-emerald-700' : 'text-amber-800' }}">
                                        {{ ($eligibility['has_verified_certificate'] ?? false) ? '✓' : '✗' }}
                                    </span>
                                    <div>
                                        <span class="font-bold">Sertifikat Kelulusan:</span> Wajib memiliki sertifikat kelulusan Course terkait (Lulus Final Quiz / Sertifikat Vendor).
                                    </div>
                                </div>
                            </div>

                            @if(isset($prerequisiteCourses) && $prerequisiteCourses->count() > 0)
                                <div class="pt-2 border-t border-amber-200/70">
                                    <p class="text-xs font-bold text-amber-950 mb-2">
                                        🎓 Rekomendasi Mata Kuliah Prasyarat yang Perlu Diambil:
                                    </p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        @foreach($prerequisiteCourses as $pc)
                                            <div class="p-3 bg-white border border-amber-200 rounded-xl flex items-center justify-between gap-2">
                                                <div>
                                                    <div class="font-bold text-xs text-slate-800">{{ $pc->name }}</div>
                                                    <div class="text-[11px] text-slate-500">{{ $pc->category->name ?? 'Kompetensi' }}</div>
                                                </div>
                                                <a href="{{ route('student.courses.index') }}" class="px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-[11px] rounded-lg transition shrink-0">
                                                    Ambil Matkul →
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <span class="text-xs text-slate-500">
                                <strong>Kuota Mahasiswa:</strong> {{ $joinedCount }}/{{ $maxStudents }} terisi
                            </span>

                            <button type="button"
                                    class="px-5 py-2.5 bg-slate-200 text-slate-500 font-bold text-xs rounded-xl cursor-not-allowed border border-slate-300"
                                    disabled title="Selesaikan matkul prasyarat di atas untuk membuka pendaftaran">
                                🔒 Pendaftaran Terkunci
                            </button>
                        </div>
                    @else
                        <!-- STATUS ELIGIBLE -->
                        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs mb-5 space-y-2">
                            <div class="font-bold text-sm flex items-center gap-2">
                                <span class="px-2 py-0.5 bg-emerald-200 text-emerald-950 rounded-md text-[10px] font-extrabold">✓ ELIGIBLE</span>
                                <span>Selamat! Anda memenuhi seluruh syarat kompetensi proyek ini.</span>
                            </div>
                            <p class="text-emerald-800">
                                Anda telah memiliki kompetensi skill <strong>{{ $eligibility['main_skill_name'] ?? '' }}</strong> dan sertifikat kelulusan yang valid.
                            </p>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="{{ $isFull ? 'text-red-600 font-bold text-xs' : 'text-gray-600 text-xs' }}">
                                <strong>Kuota Mahasiswa:</strong> {{ $joinedCount }}/{{ $maxStudents }} terisi
                            </span>

                            @if ($isFull)
                                <button type="button"
                                        class="px-5 py-2.5 bg-gray-300 text-gray-600 font-bold text-xs rounded-xl cursor-not-allowed"
                                        disabled>
                                    Kuota Penuh
                                </button>
                            @else
                                <form action="{{ route('student.projects.join', $project) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition shadow-sm">
                                        ✨ Ambil Project Ini
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            <!-- 3. COMMENTS & DISCUSSION SECTION -->
            <div class="bg-white shadow-sm border border-gray-200 rounded-2xl p-6">
                <h4 class="font-semibold text-lg text-gray-900 mb-1">
                    Diskusi & Pengumpulan Hasil Proyek
                </h4>

                <p class="text-xs text-gray-500 mb-4">
                    Gunakan ruang diskusi ini untuk berkonsultasi dengan Dosen/Vendor mitra dan mengirimkan tautan pengerjaan tugas (GitHub / Google Drive).
                </p>

                @if ($participation)
                    <form action="{{ route('projects.comments.store', $project) }}" method="POST" class="mb-6">
                        @csrf
                        <input type="hidden" name="comment_type" value="comment">

                        <textarea name="comment"
                                  class="border border-gray-300 rounded-xl w-full p-3 text-xs"
                                  rows="3"
                                  placeholder="Tuliskan pertanyaan, update progres, atau kirimkan link hasil tugas proyek..."
                                  required>{{ old('comment') }}</textarea>

                        @error('comment')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror

                        <button type="submit"
                                class="mt-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition">
                            Kirim Diskusi
                        </button>
                    </form>
                @else
                    <div class="mb-6 p-4 bg-slate-50 border border-slate-200 text-slate-500 rounded-xl text-xs text-center">
                        🔒 Kolom diskusi & pengumpulan link proyek hanya terbuka bagi mahasiswa yang sudah diterima dalam proyek ini.
                    </div>
                @endif

                <div class="space-y-3">
                    @forelse ($comments->sortByDesc('created_at') as $comment)
                        <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                            <div class="flex justify-between gap-3">
                                <div>
                                    <p class="font-bold text-xs text-gray-900">
                                        {{ $comment->user->name ?? 'User' }}
                                    </p>
                                    <p class="text-[10px] text-gray-500">
                                        {{ $comment->created_at->format('d M Y H:i') }}
                                    </p>
                                </div>
                            </div>

                            <p class="text-gray-700 mt-2 whitespace-pre-line text-xs">
                                {{ $comment->comment }}
                            </p>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 text-center py-2">
                            Belum ada diskusi pada project ini.
                        </p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>