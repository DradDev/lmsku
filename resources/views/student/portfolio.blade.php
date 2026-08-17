<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    Digital Talent Portfolio & Academic Resume
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Pengelolaan Minat & Matriks Kompetensi Digital Terverifikasi Mahasiswa
                </p>
            </div>

            <a href="{{ route('student.dashboard') }}"
               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition self-start sm:self-auto">
                ← Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    @php
        $totalCourses = $student->enrollments->count();
        $totalProjects = $student->joinedProjects->count();
        $verifiedCertificates = $certificates->where('status', 'verified')->count();
        $totalAcquiredSkills = $acquiredSkills->count();
    @endphp

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- 1. STUDENT EXECUTIVE PROFILE HEADER BANNER -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
                <div class="flex flex-col lg:flex-row items-center lg:items-start gap-6">
                    @if($student->avatar_url)
                        <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}"
                             class="w-24 h-24 md:w-28 md:h-28 rounded-2xl object-cover border-2 border-purple-500 shadow-sm"
                             onerror="this.style.display='none';">
                    @else
                        <div class="w-24 h-24 md:w-28 md:h-28 rounded-2xl bg-gradient-to-tr from-purple-600 to-indigo-600 text-white flex items-center justify-center font-black text-4xl shadow-sm flex-shrink-0">
                            {{ strtoupper(substr($student->name, 0, 1)) }}
                        </div>
                    @endif

                    <div class="flex-1 text-center lg:text-left space-y-3 w-full">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-4">
                            <div>
                                <h3 class="text-2xl md:text-3xl font-extrabold text-gray-900 leading-tight">
                                    {{ $student->name }}
                                </h3>
                                <p class="text-sm text-gray-500 font-medium mt-0.5">
                                    {{ $student->email }}
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-1.5 justify-center sm:justify-end">
                                @if($student->peminatan)
                                    <span class="px-3.5 py-1 bg-indigo-50 text-indigo-800 border border-indigo-200 rounded-xl text-xs font-bold">
                                        Fokus Minat: {{ $student->peminatan }}
                                    </span>
                                @endif
                                
                                @foreach($acquiredSkills->take(2) as $item)
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-xl text-xs font-bold">
                                        ✓ Kompetensi: {{ $item['skill']->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Quick Executive Metrics Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
                            <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-xl text-center lg:text-left">
                                <span class="text-[11px] font-bold text-slate-500 block uppercase">Course Diikuti</span>
                                <span class="text-xl font-black text-slate-800 mt-0.5 block">{{ $totalCourses }} Course</span>
                            </div>

                            <div class="p-3.5 bg-purple-50/70 border border-purple-100 rounded-xl text-center lg:text-left">
                                <span class="text-[11px] font-bold text-purple-600 block uppercase">Project Diambil</span>
                                <span class="text-xl font-black text-purple-900 mt-0.5 block">{{ $totalProjects }} Project</span>
                            </div>

                            <div class="p-3.5 bg-emerald-50/70 border border-emerald-100 rounded-xl text-center lg:text-left">
                                <span class="text-[11px] font-bold text-emerald-700 block uppercase">Sertifikat Kelulusan</span>
                                <span class="text-xl font-black text-emerald-900 mt-0.5 block">{{ $verifiedCertificates }} Sertifikat</span>
                            </div>

                            <div class="p-3.5 bg-indigo-50/70 border border-indigo-100 rounded-xl text-center lg:text-left">
                                <span class="text-[11px] font-bold text-indigo-700 block uppercase">Kompetensi Skill</span>
                                <span class="text-xl font-black text-indigo-950 mt-0.5 block">{{ $totalAcquiredSkills }} Main Skill</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. SERTIFIKAT KELULUSAN TERVERIFIKASI (DIGITAL CERTIFICATES & BLOCKCHAIN) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-4">
                    <div>
                        <h4 class="text-xl font-black text-gray-900">
                            Sertifikat Kelulusan Terverifikasi (Digital Credentials)
                        </h4>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Daftar sertifikat resmi yang telah diverifikasi oleh Administrator LMS dan terekam dalam sistem kelulusan.
                        </p>
                    </div>

                    <a href="{{ route('student.certificate.index') }}"
                       class="text-xs font-bold text-purple-700 hover:text-purple-900 underline self-start sm:self-auto">
                        Kelola Semua Sertifikat →
                    </a>
                </div>

                @if($certificates->isEmpty())
                    <div class="p-8 bg-slate-50 border border-slate-200/80 rounded-xl text-center space-y-2">
                        <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center mx-auto">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M8.21 13.89 7 23l5-3 5 3-1.21-9.12"/></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700">Belum ada sertifikat terverifikasi</p>
                        <p class="text-xs text-slate-500 max-w-md mx-auto">
                            Selesaikan Final Quiz pada Course Anda hingga mencapai nilai passing grade threshold untuk membuka dan mengunduh sertifikat resmi.
                        </p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($certificates as $cert)
                            @php
                                $mc = $cert->courseOffering?->masterCourse ?? $cert->course;
                                $title = $mc->name ?? ($cert->project->title ?? 'Sertifikat Kompetensi');
                                $provider = $cert->courseOffering?->lecturer->name ?? ($cert->course?->user->name ?? 'Tim Akademik COMPRO');
                                $isVerified = $cert->status === 'verified';
                            @endphp

                            <div class="border border-gray-200 hover:border-purple-300 rounded-xl p-5 bg-white shadow-xs space-y-3 transition flex flex-col justify-between">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $isVerified ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                                            {{ $isVerified ? 'Verified Certificate' : 'Pending Verification' }}
                                        </span>

                                        @if($cert->score)
                                            <span class="px-2 py-0.5 bg-purple-100 text-purple-800 font-extrabold text-[10px] rounded-md">
                                                Nilai Kelulusan: {{ $cert->score }}
                                            </span>
                                        @endif
                                    </div>

                                    <h5 class="font-extrabold text-base text-gray-900 leading-snug">
                                        {{ $title }}
                                    </h5>

                                    <p class="text-xs text-gray-500">
                                        Penerbit / Instructor: <span class="font-bold text-gray-700">{{ $provider }}</span>
                                    </p>

                                    @if($cert->blockchain_hash)
                                        <div class="p-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] text-slate-600 font-mono truncate">
                                            Hash: {{ $cert->blockchain_hash }}
                                        </div>
                                    @endif
                                </div>

                                <div class="pt-3 border-t border-gray-100 flex items-center justify-between gap-2 text-xs">
                                    <span class="text-gray-400 font-medium">
                                        {{ optional($cert->completed_at ?? $cert->created_at)->format('d M Y') }}
                                    </span>

                                    @if($cert->course_id)
                                        <a href="{{ route('student.certificate.show', $cert->course_id) }}" target="_blank"
                                           class="px-3.5 py-1.5 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition shadow-xs">
                                            Lihat Sertifikat
                                        </a>
                                    @elseif($cert->project_id)
                                        <a href="{{ route('student.certificate.project.show', $cert->project_id) }}" target="_blank"
                                           class="px-3.5 py-1.5 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition shadow-xs">
                                            Lihat Sertifikat Project
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 3. PENGELOLAAN KOMPETENSI SKILL RIIL (COURSE-BASED MULTI-SKILL MATRIX) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-5">
                <div class="border-b border-gray-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h4 class="text-xl font-black text-gray-900">
                            Pengelolaan Matriks Kompetensi Skill (Course-Based)
                        </h4>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Matriks penguasaan skill riil Mahasiswa yang diperoleh dan terverifikasi dari mata kuliah yang telah diambil.
                        </p>
                    </div>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-lg text-xs font-bold self-start sm:self-auto">
                        Output Pembelajaran Riil
                    </span>
                </div>

                @if($acquiredSkills->isEmpty())
                    <div class="p-8 bg-slate-50 border border-slate-200/80 rounded-xl text-center space-y-2">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center mx-auto">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700">Belum ada kompetensi terdaftar dari course</p>
                        <p class="text-xs text-slate-500 max-w-md mx-auto">
                            Kompetensi skill akan otomatis terbentuk dan bertambah pada matriks ini begitu Anda mendaftar dan mempelajari Course.
                        </p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($acquiredSkills as $item)
                            @php
                                $skill = $item['skill'];
                                $courses = array_unique($item['courses']);
                                $tags = $item['tags'];
                                $hasCert = $item['has_verified_cert'];
                                $isCompleted = $item['is_completed'];
                            @endphp

                            <div class="border border-gray-200 hover:border-purple-300 rounded-2xl p-5 bg-slate-50/50 hover:bg-white transition flex flex-col justify-between space-y-4 shadow-xs">
                                <div class="space-y-3">
                                    <!-- Main Skill Header & Status Badge -->
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <span class="text-[10px] font-bold text-purple-700 uppercase tracking-wider block">Main Skill Kompetensi</span>
                                            <h5 class="font-extrabold text-base text-gray-900 leading-tight mt-0.5">
                                                {{ $skill->name }}
                                            </h5>
                                        </div>

                                        @if($hasCert)
                                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-full font-extrabold text-[10px] whitespace-nowrap shadow-2xs">
                                                ✓ Sertifikat Terverifikasi
                                            </span>
                                        @elseif($isCompleted)
                                            <span class="px-2.5 py-1 bg-blue-100 text-blue-800 border border-blue-200 rounded-full font-bold text-[10px] whitespace-nowrap">
                                                Course Selesai
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 bg-purple-100 text-purple-800 border border-purple-200 rounded-full font-semibold text-[10px] whitespace-nowrap">
                                                Dalam Pembelajaran
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Diperoleh Dari Course -->
                                    <div class="space-y-1 pt-1">
                                        <span class="text-[11px] font-bold text-gray-500 uppercase block">Diperoleh Dari Course:</span>
                                        <ul class="space-y-1">
                                            @foreach($courses as $cName)
                                                <li class="text-xs font-semibold text-gray-800 flex items-center gap-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-600 flex-shrink-0"></span>
                                                    <span>{{ $cName }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <!-- Sub-Tag Spesialisasi -->
                                    @if($tags->isNotEmpty())
                                        <div class="space-y-1.5 pt-2 border-t border-gray-100">
                                            <span class="text-[11px] font-bold text-gray-500 uppercase block">Tag Kompetensi:</span>
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach($tags as $tag)
                                                    <span class="px-2.5 py-1 bg-white border border-purple-200 text-purple-900 rounded-lg text-[11px] font-semibold">
                                                        #{{ $tag->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="pt-2 text-right">
                                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100">
                                        Kompetensi Siap Proyek
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 4. PENGELOLAAN PROFIL MINAT & PREFERENSI TEKNOLOGI (INTEREST MANAGEMENT) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-5">
                <div class="border-b border-gray-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h4 class="text-xl font-black text-gray-900">
                            Pengelolaan Profil Minat & Preferensi Teknologi
                        </h4>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Preferensi kecenderungan minat dan eksplorasi bidang teknologi yang disukai Mahasiswa.
                        </p>
                    </div>
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-800 border border-indigo-200 rounded-lg text-xs font-bold self-start sm:self-auto">
                        Input Preferensi Mahasiswa
                    </span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    <!-- Main Focus Interest Card -->
                    <div class="lg:col-span-4 p-5 bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-100 rounded-2xl space-y-3">
                        <span class="text-[10px] font-extrabold text-indigo-700 uppercase tracking-wider block">Fokus Minat Utamanya:</span>
                        <h5 class="text-lg font-black text-indigo-950">
                            {{ $student->peminatan ?? 'Teknologi Informasi & Software Engineering' }}
                        </h5>
                        <p class="text-xs text-indigo-800 leading-relaxed">
                            Preferensi awal ini digunakan sistem untuk merekomendasikan katalog Course dan memberikan pembotot kecocokan (*Match Score*) pada Proyek Industri.
                        </p>
                    </div>

                    <!-- Interest Specialty Tags Grid -->
                    <div class="lg:col-span-8 space-y-3">
                        <span class="text-[11px] font-bold text-gray-500 uppercase block">Tag Minat Spesialisasi Yang Diikuti:</span>
                        <div class="flex flex-wrap gap-2">
                            @forelse($interestTags as $item)
                                <div class="px-3.5 py-2 bg-white border border-indigo-200 hover:border-indigo-400 rounded-xl text-xs font-bold text-indigo-900 shadow-2xs flex items-center gap-2 transition">
                                    <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                                    <span>#{{ $item['tag']->name ?? 'Tag Minat' }}</span>
                                    <span class="text-[10px] font-medium text-gray-500">({{ $item['skill_name'] }})</span>
                                </div>
                            @empty
                                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-500 italic w-full">
                                    Belum ada tag minat spesialisasi terdaftar. Pilihan minat dapat disesuaikan pada pengaturan profil Anda.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. TRACK RECORD PROJECT INDUSTRI & AKADEMIK (PROJECTS TAKEN) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-4">
                    <div>
                        <h4 class="text-xl font-black text-gray-900">
                            Track Record Project Industri & Real-World Works
                        </h4>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Daftar project industri dan tugas akademis yang telah diambil, dikerjakan, dan diselesaikan.
                        </p>
                    </div>

                    <a href="{{ route('student.projects.my') }}"
                       class="text-xs font-bold text-purple-700 hover:text-purple-900 underline self-start sm:self-auto">
                        Kelola Project Saya →
                    </a>
                </div>

                @if($student->joinedProjects->isEmpty())
                    <div class="p-8 bg-slate-50 border border-slate-200/80 rounded-xl text-center space-y-2">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center mx-auto">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700">Belum ada project yang diambil</p>
                        <p class="text-xs text-slate-500 max-w-md mx-auto">
                            Jelajahi Katalog Project Industri Mitra Vendor & Dosen untuk mulai mengambil tantangan pengerjaan project real client.
                        </p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($student->joinedProjects as $project)
                            @php
                                $status = $project->pivot->status ?? 'in_progress';
                                $progress = $project->pivot->progress_percent ?? 0;
                                $provType = $project->provider_type ?? (($project->user->role ?? '') === 'vendor' ? 'external' : 'internal');
                                
                                $statusLabel = match($status) {
                                    'completed', 'accepted' => 'Selesai / Approved',
                                    'review' => 'Review Client',
                                    'development' => 'Development',
                                    default => 'In Progress',
                                };

                                $statusBadge = match($status) {
                                    'completed', 'accepted' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                                    'review' => 'bg-purple-100 text-purple-800 border border-purple-200',
                                    'development' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                    default => 'bg-amber-100 text-amber-800 border border-amber-200',
                                };
                            @endphp

                            <div class="border border-gray-200 hover:border-purple-300 rounded-xl p-5 bg-white shadow-xs space-y-3 transition flex flex-col justify-between">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold {{ $provType === 'external' ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                            {{ $provType === 'external' ? 'External: Mitra Vendor' : 'Internal: Dosen Akademik' }}
                                        </span>

                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $statusBadge }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>

                                    <h5 class="font-extrabold text-base text-gray-900 leading-snug">
                                        {{ $project->title }}
                                    </h5>

                                    <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed">
                                        {{ $project->description }}
                                    </p>

                                    <div class="pt-2">
                                        <div class="flex justify-between text-xs text-gray-600 font-semibold mb-1">
                                            <span>Pengerjaan Progress</span>
                                            <span class="text-purple-700 font-bold">{{ $progress }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                            <div class="h-2 rounded-full {{ $progress >= 100 ? 'bg-emerald-500' : 'bg-purple-600' }}" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-gray-100 flex items-center justify-between gap-2 text-xs">
                                    <span class="text-gray-500 font-medium">
                                        Level: {{ ucfirst($project->difficulty_level) }} ({{ $project->duration_days }} Hari)
                                    </span>

                                    <a href="{{ route('student.projects.show', $project) }}"
                                       class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg transition">
                                        Detail & Submit
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 6. DAFTAR COURSE & PELATIHAN YANG DIIKUTI (ENROLLED COURSES) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-4">
                    <div>
                        <h4 class="text-xl font-black text-gray-900">
                            Mata Kuliah & Course Sertifikasi Yang Diikuti
                        </h4>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Daftar course akademik dan sertifikasi industri tempat Anda terdaftar.
                        </p>
                    </div>

                    <a href="{{ route('student.courses.index') }}"
                       class="text-xs font-bold text-purple-700 hover:text-purple-900 underline self-start sm:self-auto">
                        Lihat Semua Course →
                    </a>
                </div>

                @if($student->enrollments->isEmpty())
                    <div class="p-8 bg-slate-50 border border-slate-200/80 rounded-xl text-center space-y-2">
                        <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center mx-auto">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700">Belum ada course yang diikuti</p>
                        <p class="text-xs text-slate-500 max-w-md mx-auto">
                            Daftar pada kelas perkuliahan atau course sertifikasi vendor untuk mulai belajar dan membuka akses project.
                        </p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($student->enrollments as $enrollment)
                            @php
                                $offering = $enrollment->courseOffering;
                                $courseObj = $offering ?? $enrollment->course;
                                $courseName = $offering?->masterCourse?->name ?? ($courseObj?->name ?? 'Course LMS');
                                $lecturerName = $offering?->lecturer->name ?? ($enrollment->course?->user->name ?? 'Pengajar LMS');
                                $termName = $offering?->academicTerm?->name ?? 'Semester Aktif';
                                $progress = $enrollment->progress_percent ?? 0;
                            @endphp

                            <div class="border border-gray-200 hover:border-purple-300 rounded-xl p-5 bg-white shadow-xs space-y-3 transition flex flex-col justify-between">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            {{ $termName }}
                                        </span>

                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 font-bold text-[10px] rounded-md">
                                            Progress Pembelajaran: {{ $progress }}%
                                        </span>
                                    </div>

                                    <h5 class="font-extrabold text-base text-gray-900 leading-snug">
                                        {{ $courseName }}
                                    </h5>

                                    <p class="text-xs text-gray-500">
                                        Pengajar / Lecturer: <span class="font-bold text-gray-700">{{ $lecturerName }}</span>
                                    </p>
                                </div>

                                <div class="pt-3 border-t border-gray-100 flex items-center justify-between gap-2 text-xs">
                                    <span class="text-gray-400 font-medium">
                                        Terdaftar: {{ optional($enrollment->started_at ?? $enrollment->created_at)->format('d M Y') }}
                                    </span>

                                    <a href="{{ route('student.courses.show', $courseObj->id ?? 1) }}"
                                       class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition shadow-xs">
                                        Masuk Kelas
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
