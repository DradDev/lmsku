<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    Digital Talent Portfolio & Academic Resume
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Portofolio kompetensi digital terverifikasi, sertifikat kelulusan, dan rekam jejak project real-world.
                </p>
            </div>

            <a href="{{ route('student.dashboard') }}"
               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition self-start sm:self-auto">
                ← Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    @php
        $topSkillProfile = $student->skillProfiles->sortByDesc('highest_score')->first();
        $hasCompetency = $topSkillProfile && $topSkillProfile->skill;
        $totalCourses = $student->enrollments->count();
        $totalProjects = $student->joinedProjects->count();
        $verifiedCertificates = $certificates->where('status', 'verified')->count();
        $highestScore = $topSkillProfile ? round($topSkillProfile->highest_score ?? $topSkillProfile->avg_score) : 0;
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

                            @if($hasCompetency)
                                <span class="px-4 py-2 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-xl text-xs font-extrabold self-center sm:self-auto">
                                    Verified Specialization: {{ $topSkillProfile->skill->name }}
                                </span>
                            @else
                                <span class="px-4 py-2 bg-amber-50 text-amber-800 border border-amber-200 rounded-xl text-xs font-bold self-center sm:self-auto">
                                    Peminatan: {{ $student->peminatan ?? 'Teknologi Komputer & Software' }}
                                </span>
                            @endif
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
                                <span class="text-[11px] font-bold text-indigo-700 block uppercase">Skor Kompetensi Utama</span>
                                <span class="text-xl font-black text-indigo-950 mt-0.5 block">{{ $highestScore }} / 100</span>
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
                                                Nilai: {{ $cert->score }}/100
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

            <!-- 3. TRACK RECORD PROJECT INDUSTRI & AKADEMIK (PROJECTS TAKEN) -->
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

            <!-- 4. DAFTAR COURSE & PELATIHAN YANG DIIKUTI (ENROLLED COURSES) -->
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
                                            Progress: {{ $progress }}%
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

            <!-- 5. PROFIL KOMPETENSI SKILL & TAGS SPESIALISASI -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Left Column: Skill Competency Score Profiles (7 Cols) -->
                <div class="lg:col-span-7 bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-4">
                    <div>
                        <h4 class="text-xl font-black text-gray-900">
                            Skor Kompetensi Skill Terverifikasi
                        </h4>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Hasil evaluasi otomatis penguasaan skill berdasarkan akumulasi kuis dan karya project.
                        </p>
                    </div>

                    @if($student->skillProfiles->isEmpty())
                        <div class="p-6 bg-slate-50 border border-slate-200/80 rounded-xl text-center text-xs text-gray-500">
                            Belum ada skor kompetensi skill terdaftar.
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($student->skillProfiles as $sp)
                                @php
                                    $score = round($sp->avg_score);
                                    $barColor = $score >= 80 ? 'bg-emerald-500' : ($score >= 60 ? 'bg-indigo-600' : 'bg-amber-500');
                                @endphp

                                <div class="border border-gray-100 rounded-xl p-3.5 bg-slate-50/70 hover:bg-white transition space-y-1.5">
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="font-extrabold text-gray-900">{{ $sp->skill->name ?? 'Skill' }}</span>
                                        <span class="font-black text-indigo-700">{{ $score }} / 100</span>
                                    </div>

                                    <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                        <div class="{{ $barColor }} h-2.5 rounded-full transition-all" style="width: {{ $score }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Right Column: Specialty & Interest Tags (5 Cols) -->
                <div class="lg:col-span-5 bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-4">
                    <div>
                        <h4 class="text-xl font-black text-gray-900">
                            Tag Minat & Spesialisasi
                        </h4>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Fokus spesialisasi dan minat teknologi yang diminati dalam pencocokan project.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-2 pt-1">
                        <span class="px-3.5 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl text-xs font-bold shadow-xs">
                            Peminatan: {{ $student->peminatan ?? 'Teknologi Komputer' }}
                        </span>

                        @forelse($student->interestProfiles as $ip)
                            <span class="px-3 py-1.5 bg-purple-50 border border-purple-200 text-purple-800 rounded-xl text-xs font-bold flex items-center gap-1.5">
                                <span>#{{ $ip->tag->name ?? 'Tag' }}</span>
                                <span class="px-1.5 py-0.5 bg-purple-200 text-purple-950 rounded-md text-[10px] font-black">{{ round($ip->interest_score ?? 50) }}</span>
                            </span>
                        @empty
                            <span class="text-gray-400 italic text-xs block py-2">Belum ada tag spesialisasi terdaftar.</span>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
