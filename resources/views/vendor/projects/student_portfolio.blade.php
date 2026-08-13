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

                        <div class="flex flex-wrap gap-2 justify-center md:justify-start pt-1">
                            @forelse($acquiredSkills as $item)
                                <span class="px-3.5 py-1 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-full text-xs font-bold">
                                    ✓ {{ $item['skill']->name }}
                                </span>
                            @empty
                                <span class="px-3.5 py-1 bg-slate-100 border border-slate-200 text-slate-700 rounded-full text-xs font-bold">
                                    General Talent
                                </span>
                            @endforelse
                        </div>

                        <div class="pt-3 border-t border-gray-100 flex flex-wrap gap-4 text-xs text-gray-600 justify-center md:justify-start">
                            <div>
                                <span class="font-semibold text-gray-800">Total Joined Projects:</span>
                                <span class="font-bold text-indigo-600 ml-1">{{ $student->joinedProjects->count() }} Projects</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skill Competency Profiles Grid (No Percentages) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-4">
                <div>
                    <h4 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <span>Course-Based Skill Competency Matrix</span>
                    </h4>
                    <p class="text-xs text-gray-500">Skill kompetensi riil mahasiswa berbasis course akademik dan sertifikasi yang telah diambil.</p>
                </div>

                @if($acquiredSkills->isEmpty())
                <div class="p-6 bg-gray-50 rounded-xl text-center text-xs text-gray-500">
                    Belum ada data skill terdaftar dari course.
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
                                <h5 class="font-bold text-xs text-gray-900">{{ $skill->name }}</h5>
                                @if($hasCert)
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 font-extrabold text-[10px] rounded-md border border-emerald-200">
                                        Verified
                                    </span>
                                @elseif($isCompleted)
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-800 font-bold text-[10px] rounded-md">
                                        Selesai
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 bg-purple-100 text-purple-800 font-semibold text-[10px] rounded-md">
                                        Terdaftar
                                    </span>
                                @endif
                            </div>

                            <div class="text-xs text-gray-600">
                                <span class="font-semibold text-gray-700 block mb-0.5">Course:</span>
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

        </div>
    </div>
</x-app-layout>
