<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <span>📁 Project Real Client & Industri</span>
            </h2>

            <a href="{{ route('vendor.projects.create') }}"
               class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl transition shadow-sm">
                + Publikasikan Project Baru
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
            <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-xl shadow-sm font-semibold text-xs">
                {{ session('success') }}
            </div>
            @endif

            @if ($allProjects->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center space-y-3 shadow-sm">
                <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center mx-auto text-2xl font-bold">
                    🏢
                </div>
                <h3 class="font-extrabold text-base text-gray-900">Belum Ada Project Industri</h3>
                <p class="text-xs text-gray-500 max-w-md mx-auto">
                    Publikasikan pekerjaan nyata atau tantangan proyek dari klien Anda untuk merekrut talenta mahasiswa terbaik.
                </p>
                <a href="{{ route('vendor.projects.create') }}" class="inline-block px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                    + Publikasikan Project Pertama
                </a>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($allProjects as $project)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex flex-col justify-between space-y-4 hover:shadow-md transition">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 bg-purple-100 text-purple-800 border border-purple-200 text-[10px] font-black uppercase rounded-md">
                                🏢 {{ Auth::user()->name }}
                            </span>
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-700 text-[10px] font-bold rounded-md">
                                {{ ucfirst($project->difficulty_level) }}
                            </span>
                        </div>

                        <h3 class="font-extrabold text-base text-gray-900 leading-snug">
                            {{ $project->title }}
                        </h3>

                        <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed">
                            {{ $project->description }}
                        </p>
                    </div>

                    <div class="pt-3 border-t border-gray-100 space-y-3">
                        <div class="flex items-center justify-between text-xs text-gray-600 font-semibold">
                            <span>Kuota Mahasiswa:</span>
                            <span class="px-2 py-0.5 bg-indigo-100 text-indigo-800 font-black rounded-md">{{ $project->participations->count() }}/{{ $project->max_students }}</span>
                        </div>

                        <div class="flex items-center justify-between gap-2 pt-1">
                            <a href="{{ route('vendor.projects.talent-pool', $project) }}"
                               class="py-2 px-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold text-xs rounded-xl shadow-sm hover:from-indigo-700 hover:to-purple-700 transition">
                                🎯 Talent Screening
                            </a>
                            <a href="{{ route('vendor.projects.show', $project) }}"
                               class="flex-1 text-center py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">
                                Detail & Progress
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
