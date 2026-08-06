<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📄 Digital Talent Portfolio & Resume
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

                        @php
                            $topSkillProfile = $student->skillProfiles->sortByDesc('highest_score')->first();
                            $hasCompetency = $topSkillProfile && $topSkillProfile->skill;
                        @endphp

                        @if($hasCompetency)
                            <span class="px-4 py-1.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-full text-xs font-bold self-center md:self-start">
                                Verified Specialization: {{ $topSkillProfile->skill->name }}
                            </span>
                        @else
                            <span class="px-4 py-1.5 bg-amber-50 border border-amber-200 text-amber-800 rounded-full text-xs font-bold self-center md:self-start">
                                Initial Interest: {{ $student->peminatan ?? 'General' }} (Competency Pending)
                            </span>
                        @endif

                        <div class="pt-3 border-t border-gray-100 flex flex-wrap gap-4 text-xs text-gray-600 justify-center md:justify-start">
                            <div>
                                <span class="font-semibold text-gray-800">Total Joined Projects:</span>
                                <span class="font-bold text-indigo-600 ml-1">{{ $student->joinedProjects->count() }} Projects</span>
                            </div>

                            <div>
                                <span class="font-semibold text-gray-800">Approved Projects:</span>
                                <span class="font-bold text-emerald-600 ml-1">
                                    {{ $student->joinedProjects->filter(fn($p) => $p->pivot->status === 'accepted' && $p->pivot->progress_percent >= 100)->count() }} Works
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skill Competency Profiles Grid -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
                    <span>📊 Skill Competency Profile (Skill Scores)</span>
                </h4>
                <p class="text-sm text-gray-500 mb-6">
                    Student skill competency scores evaluated from quizzes and completed projects.
                </p>

                @if($student->skillProfiles->isEmpty())
                <div class="p-6 bg-gray-50 rounded-lg text-center text-sm text-gray-500">
                    This student does not have skill competency score data yet.
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($student->skillProfiles as $sp)
                    @php
                    $score = round($sp->avg_score);
                    $barColor = $score >= 80 ? 'bg-emerald-500' : ($score >= 60 ? 'bg-indigo-600' : 'bg-amber-500');
                    @endphp

                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 hover:bg-white transition">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-bold text-sm text-gray-900">{{ $sp->skill->name ?? 'Skill' }}</span>
                            <span class="font-black text-sm text-indigo-600">{{ $score }} / 100</span>
                        </div>

                        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                            <div class="{{ $barColor }} h-2.5 rounded-full transition-all" style="width: {{ $score }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Student Interest Profile Tags Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
                    <span>🏷️ Student Interest & Specialty Tags</span>
                </h4>
                <p class="text-sm text-gray-500 mb-4">
                    Interest tags and specializations selected by the student in project exploration.
                </p>

                <div class="flex flex-wrap gap-2">
                    <span class="px-3.5 py-1.5 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-full text-xs font-bold shadow-sm">
                        Initial Registered Interest: {{ $student->peminatan ?? 'Embedded Systems & Robotics' }}
                    </span>

                    @forelse($student->interestProfiles as $ip)
                    <span class="px-3 py-1.5 bg-purple-50 border border-purple-200 text-purple-800 rounded-full text-xs font-semibold flex items-center gap-1.5">
                        <span>#{{ $ip->tag->name ?? 'Tag' }}</span>
                        <span class="px-1.5 py-0.2 bg-purple-200 text-purple-900 rounded-full text-[10px] font-extrabold">{{ round($ip->interest_score ?? 50) }}</span>
                    </span>
                    @empty
                    <span class="text-gray-400 italic text-xs">No specific interest tags registered yet.</span>
                    @endforelse
                </div>
            </div>

            <!-- Completed Projects & Track Record Portfolio -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
                    <span>📁 Verified Project Track Record</span>
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
                    $isApproved = ($status === 'completed' || $status === 'accepted') || $progress >= 100;
                    $statusLabel = match($status) {
                        'completed' => 'Completed',
                        'review' => 'In Review',
                        'development' => 'Development',
                        default => 'In Progress',
                    };
                    $statusClass = match($status) {
                        'completed' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                        'review' => 'bg-purple-100 text-purple-800 border border-purple-200',
                        'development' => 'bg-blue-100 text-blue-800 border border-blue-200',
                        default => 'bg-amber-100 text-amber-800 border border-amber-200',
                    };
                    @endphp

                    <div class="border border-gray-200 rounded-xl p-5 hover:border-indigo-300 transition bg-white flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <h5 class="font-bold text-gray-900 text-base">{{ $project->title }}</h5>
                                @if($isApproved)
                                <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full border border-emerald-300 flex items-center gap-1">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                    Verified Portfolio
                                </span>
                                @endif
                            </div>

                            <p class="text-xs text-gray-500 line-clamp-1">
                                {{ $project->description }}
                            </p>

                            <div class="flex flex-wrap gap-2 pt-2">
                                <span class="text-xs text-gray-600 font-medium">Level: {{ $project->difficulty_level }}</span>
                                <span class="text-xs text-gray-400">•</span>
                                <span class="text-xs text-gray-600 font-medium">Duration: {{ $project->duration_days }} Days</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 border-t md:border-t-0 pt-3 md:pt-0 border-gray-100">
                            <div class="text-right">
                                <span class="text-xs text-gray-500 block">Completion Progress</span>
                                <span class="font-bold text-sm text-indigo-600">{{ $progress }}%</span>
                            </div>

                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
