<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <span>Digital Talent Portfolio & Resume</span>
            </h2>
            <a href="javascript:history.back()" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold text-xs rounded-xl hover:bg-gray-200 transition">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Student Profile Header Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-indigo-600 to-purple-600 text-white flex items-center justify-center font-black text-3xl shadow">
                        {{ strtoupper(substr($student->name, 0, 1)) }}
                    </div>

                    <div class="flex-1 text-center md:text-left space-y-2">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $student->name }}</h3>
                                <p class="text-sm text-gray-500 font-medium">{{ $student->email }}</p>
                            </div>
                        </div>

                        @php
                            $topSkillProfile = $student->skillProfiles->sortByDesc('highest_score')->first();
                            $hasCompetency = $topSkillProfile && $topSkillProfile->skill;
                        @endphp

                        @if($hasCompetency)
                            <span class="inline-block px-4 py-1.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-full text-xs font-bold">
                                Verified Specialization: {{ $topSkillProfile->skill->name }}
                            </span>
                        @else
                            <span class="inline-block px-4 py-1.5 bg-amber-50 border border-amber-200 text-amber-800 rounded-full text-xs font-bold">
                                Peminatan: {{ $student->peminatan ?? 'General' }}
                            </span>
                        @endif

                        <div class="pt-3 border-t border-gray-100 flex flex-wrap gap-4 text-xs text-gray-600 justify-center md:justify-start">
                            <div>
                                <span class="font-semibold text-gray-800">Total Joined Projects:</span>
                                <span class="font-bold text-indigo-600 ml-1">{{ $student->joinedProjects->count() }} Projects</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skill Competency Profiles Grid -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-4">
                <div>
                    <h4 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <span>Skill Competency Profile</span>
                    </h4>
                    <p class="text-xs text-gray-500">Skor kompetensi skill mahasiswa berbasis kuis sertifikasi & karya project.</p>
                </div>

                @if($student->skillProfiles->isEmpty())
                <div class="p-6 bg-gray-50 rounded-xl text-center text-xs text-gray-500">
                    Belum ada data skor kompetensi skill terdaftar.
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($student->skillProfiles as $sp)
                    @php
                    $score = round($sp->avg_score);
                    $barColor = $score >= 80 ? 'bg-emerald-500' : ($score >= 60 ? 'bg-indigo-600' : 'bg-amber-500');
                    @endphp

                    <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-bold text-xs text-gray-900">{{ $sp->skill->name ?? 'Skill' }}</span>
                            <span class="font-black text-xs text-indigo-600">{{ $score }} / 100</span>
                        </div>

                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div class="{{ $barColor }} h-2 rounded-full" style="width: {{ $score }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
