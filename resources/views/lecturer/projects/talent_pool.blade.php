<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span>🎯 COMPRO Talent Pool & Matching Engine</span>
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
            <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-lg shadow-sm flex items-center gap-2">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <!-- Project Context Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">
                            <span>COMPRO System · Match Engine</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ $project->title }}
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Student Search & Talent Screening based on Skill Competency Weight (50%), Specialization (30%), and Track Record (20%).
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('lecturer.projects.show', $project) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                            Back to Project
                        </a>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap gap-2 text-xs">
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full font-medium">
                        Level: {{ $project->difficulty_level }}
                    </span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full font-medium">
                        Duration: {{ $project->duration_days }} Days
                    </span>
                    @foreach($project->skills as $skill)
                    <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full font-medium">
                        Skill: {{ $skill->name }}
                    </span>
                    @endforeach
                </div>
            </div>

            <!-- Student Talent Pool Grid -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <h4 class="text-lg font-bold text-gray-900">
                            Student Talent Ranking List
                        </h4>
                        <p class="text-sm text-gray-500">
                            Automatically sorted by highest match score against this project's qualifications.
                        </p>
                    </div>

                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs font-bold self-start sm:self-auto">
                        Total {{ $students->count() }} Registered Talents
                    </span>
                </div>

                @if($students->isEmpty())
                <div class="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                    <p class="text-gray-500 text-sm">No student talent data available in the system yet.</p>
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($students as $index => $student)
                    @php
                    $matchScore = $student->match_score;
                    $badgeBg = $matchScore >= 80 ? 'bg-emerald-500 text-white' : ($matchScore >= 60 ? 'bg-indigo-600 text-white' : 'bg-amber-500 text-white');
                    @endphp

                    <div class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition-all flex flex-col justify-between relative bg-white">
                        <div>
                            <!-- Peringkat & Match Badge -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-bold text-gray-400">
                                    #{{ $index + 1 }} Talent Rank
                                </span>

                                <span class="px-3 py-1 rounded-full text-xs font-black tracking-wide shadow-sm {{ $badgeBg }}">
                                    🎯 {{ $matchScore }}% MATCH
                                </span>
                            </div>

                            <!-- Student Info -->
                            <div class="flex items-center gap-3 mb-4">
                                @if($student->avatar_url)
                                <img src="{{ $student->avatar_url }}" alt="" class="w-12 h-12 rounded-full object-cover border border-gray-200" onerror="this.style.display='none';">
                                @else
                                <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-600 to-purple-600 text-white flex items-center justify-center font-bold text-lg shadow-sm">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                @endif

                                <div>
                                    <h5 class="font-bold text-gray-900 leading-tight">
                                        {{ $student->name }}
                                    </h5>
                                    <p class="text-xs text-gray-500">
                                        Specialization: <span class="font-semibold text-gray-700">{{ $student->peminatan ?? 'General' }}</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Skills & Portfolio Summary -->
                            <div class="space-y-2 mb-4">
                                <div class="text-xs text-gray-600">
                                    <span class="font-semibold text-gray-800">Top Competency:</span>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @forelse($student->skillProfiles->take(3) as $sp)
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-[11px]">
                                            {{ $sp->skill->name ?? 'Skill' }} ({{ round($sp->avg_score) }})
                                        </span>
                                        @empty
                                        <span class="text-gray-400 italic text-[11px]">No quiz/skill data yet</span>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="text-xs text-gray-600">
                                    <span class="font-semibold text-gray-800">Completed Projects:</span>
                                    <span class="font-bold text-indigo-600 ml-1">{{ $student->completedProjects->count() }} Approved Works</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-3 border-t border-gray-100 flex items-center gap-2">
                            <a href="{{ route('lecturer.students.portfolio', $student) }}" target="_blank" class="flex-1 text-center py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition">
                                View Portfolio
                            </a>

                            @if($student->is_already_invited)
                            <span class="py-2 px-3 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-bold cursor-default">
                                Joined / Invited
                            </span>
                            @else
                            <form action="{{ route('lecturer.projects.invite', [$project, $student]) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                    + Invite Talent
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
