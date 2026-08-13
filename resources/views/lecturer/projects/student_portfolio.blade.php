<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Digital Talent Portfolio & Resume
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Student Profile Header Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                    @if($student->avatar_url)
                    <img src="{{ $student->avatar_url }}" alt="" class="w-24 h-24 rounded-full object-cover border-2 border-indigo-500 shadow" onerror="this.style.display='none';">
                    @else
                    <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-indigo-600 to-purple-600 text-white flex items-center justify-center font-black text-3xl shadow">
                        {{ strtoupper(substr($student->name, 0, 1)) }}
                    </div>
                    @endif

                    <div class="flex-1 text-center md:text-left space-y-2">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $student->name }}</h3>
                                <p class="text-sm text-gray-500 font-medium">{{ $student->email }}</p>
                            </div>

                            <a href="javascript:history.back()"
                               class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition self-start md:self-auto">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                                Back
                            </a>
                        </div>

                        <div class="flex flex-wrap gap-2 justify-center md:justify-start pt-1">
                            @if($student->peminatan)
                                <span class="px-3.5 py-1 bg-indigo-50 border border-indigo-200 text-indigo-800 rounded-full text-xs font-bold">
                                    Fokus Minat: {{ $student->peminatan }}
                                </span>
                            @endif

                            @forelse($acquiredSkills as $item)
                                <span class="px-3.5 py-1 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-full text-xs font-bold">
                                    ✓ Kompetensi: {{ $item['skill']->name }}
                                </span>
                            @empty
                                <span class="px-3.5 py-1 bg-slate-100 border border-slate-200 text-slate-700 rounded-full text-xs font-bold">
                                    General Academic Talent
                                </span>
                            @endforelse
                        </div>

                        <div class="pt-3 border-t border-gray-100 flex flex-wrap gap-4 text-xs text-gray-600 justify-center md:justify-start">
                            <div>
                                <span class="font-semibold text-gray-800">Total Joined Projects:</span>
                                <span class="font-bold text-indigo-600 ml-1">{{ $student->joinedProjects->count() }} Projects</span>
                            </div>

                            <div>
                                <span class="font-semibold text-gray-800">Approved Projects:</span>
                                <span class="font-bold text-emerald-600 ml-1">
                                    {{ $student->joinedProjects->filter(fn($p) => in_array($p->pivot->status, ['accepted', 'completed']) || $p->pivot->progress_percent >= 100)->count() }} Works
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 1. PENGELOLAAN KOMPETENSI SKILL RIIL (COURSE-BASED MATRIX) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                <div>
                    <h4 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <span>Course-Based Skill Competency Matrix (Kompetensi)</span>
                    </h4>
                    <p class="text-xs text-gray-500">
                        Matriks penguasaan skill riil mahasiswa yang diperoleh dari course dan kelas perkuliahan yang diikuti.
                    </p>
                </div>

                @if($acquiredSkills->isEmpty())
                <div class="p-6 bg-gray-50 rounded-lg text-center text-sm text-gray-500">
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

                        <div class="border border-gray-200 rounded-xl p-4 bg-slate-50/60 flex flex-col justify-between space-y-3">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <h5 class="font-bold text-sm text-gray-900">{{ $skill->name }}</h5>
                                    @if($hasCert)
                                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 font-extrabold text-[10px] rounded-md border border-emerald-200">
                                            Sertifikat Verified
                                        </span>
                                    @elseif($isCompleted)
                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 font-bold text-[10px] rounded-md">
                                            Course Selesai
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 bg-purple-100 text-purple-800 font-semibold text-[10px] rounded-md">
                                            Terdaftar
                                        </span>
                                    @endif
                                </div>

                                <div class="text-xs text-gray-600">
                                    <span class="font-semibold text-gray-700 block mb-0.5">Mata Kuliah:</span>
                                    <span class="text-gray-900 font-medium">{{ implode(', ', $courses) }}</span>
                                </div>

                                @if($tags->isNotEmpty())
                                    <div class="pt-2 border-t border-gray-200/60 flex flex-wrap gap-1">
                                        @foreach($tags as $tag)
                                            <span class="px-2 py-0.5 bg-white border border-gray-200 text-gray-700 rounded text-[10px] font-medium">
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
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-3">
                <h4 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <span>Student Interest & Specialty Profile (Minat)</span>
                </h4>
                <p class="text-xs text-gray-500">
                    Preferensi minat teknologi dan spesialisasi eksplorasi yang diminati mahasiswa.
                </p>

                <div class="flex flex-wrap gap-2 pt-2">
                    @if($student->peminatan)
                        <span class="px-3.5 py-1.5 bg-indigo-100 text-indigo-900 border border-indigo-200 rounded-lg text-xs font-extrabold">
                            Fokus Minat: {{ $student->peminatan }}
                        </span>
                    @endif

                    @forelse($student->interestProfiles as $ip)
                        <span class="px-3 py-1.5 bg-purple-50 border border-purple-200 text-purple-800 rounded-lg text-xs font-semibold">
                            #{{ $ip->tag->name ?? 'Tag Minat' }}
                        </span>
                    @empty
                        <span class="text-gray-400 italic text-xs block">Belum ada tag minat spesialisasi terdaftar.</span>
                    @endforelse
                </div>
            </div>

            <!-- Completed Projects & Track Record Portfolio -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
                    <span>Verified Project Track Record</span>
                </h4>
                <p class="text-sm text-gray-500 mb-6">
                    List of real-world projects completed and approved by Author/Vendor.
                </p>

                @if($student->joinedProjects->isEmpty())
                <div class="p-6 bg-gray-50 rounded-lg text-center text-sm text-gray-500">
                    This student has not joined any projects yet.
                </div>
                @else
                <div class="space-y-4">
                    @foreach($student->joinedProjects as $project)
                    @php
                    $status = $project->pivot->status;
                    $progress = $project->pivot->progress_percent ?? 0;
                    $statusLabel = match($status) {
                        'completed', 'accepted' => 'Completed / Approved',
                        'review' => 'In Review',
                        'development' => 'In Development',
                        default => 'In Progress',
                    };
                    @endphp

                    <div class="border border-gray-200 rounded-lg p-4 bg-white flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="space-y-1 flex-1">
                            <span class="px-2.5 py-0.5 bg-purple-100 text-purple-800 text-[10px] font-bold rounded-full border border-purple-200">
                                {{ $statusLabel }}
                            </span>
                            <h5 class="font-bold text-base text-gray-900">{{ $project->title }}</h5>
                            <p class="text-xs text-gray-600 line-clamp-1">{{ $project->description }}</p>
                        </div>

                        <div class="text-right">
                            <span class="text-xs font-semibold text-gray-500 block">Progress: {{ $progress }}%</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
