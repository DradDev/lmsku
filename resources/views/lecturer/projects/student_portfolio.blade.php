<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Digital Talent Portfolio & Resume
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Student Profile Header Card -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                    @if($student->avatar_url)
                    <img src="{{ $student->avatar_url }}" alt="" class="w-20 h-20 rounded-full object-cover object-center border border-slate-200 shadow-xs" onerror="this.style.display='none';">
                    @else
                    <div class="w-20 h-20 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-2xl shadow-xs">
                        {{ strtoupper(substr($student->name, 0, 1)) }}
                    </div>
                    @endif

                    <div class="flex-1 text-center md:text-left space-y-2">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                            <div>
                                <h3 class="text-2xl font-bold text-slate-900">{{ $student->name }}</h3>
                                <p class="text-xs text-slate-500 font-medium">{{ $student->email }}</p>
                            </div>

                            <a href="javascript:history.back()"
                               class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition border border-slate-200 self-start md:self-auto">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                                Kembali
                            </a>
                        </div>

                        <div class="flex flex-wrap gap-2 justify-center md:justify-start pt-1">
                            @if($student->peminatan)
                                <span class="px-3 py-0.5 bg-indigo-50 border border-indigo-200 text-indigo-800 rounded-full text-xs font-semibold">
                                    Fokus Minat: {{ $student->peminatan }}
                                </span>
                            @endif

                            @forelse($acquiredSkills as $item)
                                <span class="px-3 py-0.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-full text-xs font-semibold">
                                    Kompetensi: {{ $item['skill']->name }}
                                </span>
                            @empty
                                <span class="px-3 py-0.5 bg-slate-100 border border-slate-200 text-slate-700 rounded-full text-xs font-semibold">
                                    General Academic Talent
                                </span>
                            @endforelse
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex flex-wrap gap-4 text-xs text-slate-600 justify-center md:justify-start">
                            <div>
                                <span class="font-semibold text-slate-700">Total Joined Projects:</span>
                                <span class="font-bold text-slate-900 ml-1">{{ $student->joinedProjects->count() }} Projects</span>
                            </div>

                            <div>
                                <span class="font-semibold text-slate-700">Approved Projects:</span>
                                <span class="font-bold text-slate-900 ml-1">
                                    {{ $student->joinedProjects->filter(fn($p) => in_array($p->pivot->status, ['accepted', 'completed']) || $p->pivot->progress_percent >= 100)->count() }} Works
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 1. PENGELOLAAN KOMPETENSI SKILL RIIL (COURSE-BASED MATRIX) -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-6 space-y-4">
                <div>
                    <h4 class="text-base font-bold text-slate-900">
                        Course-Based Skill Competency Matrix (Kompetensi)
                    </h4>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Matriks penguasaan skill riil mahasiswa yang diperoleh dari course dan kelas perkuliahan yang diikuti.
                    </p>
                </div>

                @if($acquiredSkills->isEmpty())
                <div class="p-6 bg-slate-50 rounded-xl text-center text-xs text-slate-500 border border-slate-200">
                    Mahasiswa ini belum memiliki data skill terdaftar dari course.
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($acquiredSkills as $item)
                        @php
                            $skill = $item['skill'];
                            $courses = array_unique($item['courses']);
                            $tags = $item['tags'];
                            $hasCert = $item['has_verified_cert'];
                            $isCompleted = $item['is_completed'];
                        @endphp

                        <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/50 flex flex-col justify-between space-y-3">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <h5 class="font-bold text-sm text-slate-900">{{ $skill->name }}</h5>
                                    @if($hasCert)
                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-800 font-bold text-[10px] rounded-md border border-emerald-200">
                                            Sertifikat Terverifikasi
                                        </span>
                                    @elseif($isCompleted)
                                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-800 font-bold text-[10px] rounded-md border border-indigo-200">
                                            Course Selesai
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 font-medium text-[10px] rounded-md border border-slate-200">
                                            Terdaftar
                                        </span>
                                    @endif
                                </div>

                                <div class="text-xs text-slate-600">
                                    <span class="font-semibold text-slate-700 block mb-0.5">Mata Kuliah:</span>
                                    <span class="text-slate-800">{{ implode(', ', $courses) }}</span>
                                </div>

                                @if($tags->isNotEmpty())
                                    <div class="pt-2 border-t border-slate-200 flex flex-wrap gap-1">
                                        @foreach($tags as $tag)
                                            <span class="px-2 py-0.5 bg-white border border-slate-200 text-slate-700 rounded text-[10px] font-medium">
                                                #{{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- 2. PENGELOLAAN PROFIL MINAT (INTEREST PROFILE) -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-6 space-y-3">
                <h4 class="text-base font-bold text-slate-900">
                    Student Interest & Specialty Profile (Minat)
                </h4>
                <p class="text-xs text-slate-500">
                    Preferensi minat teknologi dan spesialisasi eksplorasi yang diminati mahasiswa.
                </p>

                <div class="flex flex-wrap gap-2 pt-1">
                    @if($student->peminatan)
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-900 border border-indigo-200 rounded-lg text-xs font-bold">
                            Fokus Minat: {{ $student->peminatan }}
                        </span>
                    @endif

                    @forelse($student->interestProfiles as $ip)
                        <span class="px-3 py-1 bg-slate-100 border border-slate-200 text-slate-700 rounded-lg text-xs font-semibold">
                            #{{ $ip->tag->name ?? 'Tag Minat' }}
                        </span>
                    @empty
                        <span class="text-slate-400 italic text-xs block">Belum ada tag minat spesialisasi terdaftar.</span>
                    @endforelse
                </div>
            </div>

            <!-- Completed Projects & Track Record Portfolio -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-6">
                <h4 class="text-base font-bold text-slate-900 mb-1">
                    Verified Project Track Record
                </h4>
                <p class="text-xs text-slate-500 mb-5">
                    Daftar project nyata yang telah diselesaikan dan diverifikasi oleh Dosen/Vendor.
                </p>

                @if($student->joinedProjects->isEmpty())
                <div class="p-6 bg-slate-50 rounded-xl text-center text-xs text-slate-500 border border-slate-200">
                    Mahasiswa ini belum memiliki catatan project yang diikuti.
                </div>
                @else
                <div class="space-y-3">
                    @foreach($student->joinedProjects as $project)
                    @php
                    $status = $project->pivot->status;
                    $progress = $project->pivot->progress_percent ?? 0;
                    $statusLabel = match($status) {
                        'completed', 'accepted' => 'Selesai & Disetujui',
                        'review' => 'Dalam Review',
                        'development' => 'Pengerjaan',
                        default => 'Dalam Proses',
                    };
                    @endphp

                    <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="space-y-1 flex-1">
                            <span class="px-2.5 py-0.5 bg-slate-100 text-slate-700 text-[10px] font-bold rounded-full border border-slate-200">
                                {{ $statusLabel }}
                            </span>
                            <h5 class="font-bold text-sm text-slate-900">{{ $project->title }}</h5>
                            <p class="text-xs text-slate-600 line-clamp-1">{{ $project->description }}</p>
                        </div>

                        <div class="text-right">
                            <span class="text-xs font-semibold text-slate-600 block">Progress: {{ $progress }}%</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
