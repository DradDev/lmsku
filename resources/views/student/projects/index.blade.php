<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Daftar Project
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Pilih project yang sesuai dengan minat dan kemampuan kamu.
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-5 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Project Tersedia
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Lihat daftar project yang dapat kamu ambil.
                    </p>
                </div>

                <a href="{{ route('student.projects.my') }}"
                   class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition">
                    Project Saya
                </a>
            </div>

            @if ($projects->isEmpty())
                <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-10 text-center">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Belum ada project tersedia
                    </h3>

                    <p class="text-sm text-gray-500 mt-2">
                        Project yang sudah dipublish oleh lecturer akan muncul di halaman ini.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($projects as $project)
                        @php
                            $joinedCount = $project->participations_count ?? 0;
                            $maxStudents = $project->max_students ?? 1;
                            $isFull = $joinedCount >= $maxStudents;
                            $alreadyJoined = in_array($project->id, $joinedProjectIds);
                        @endphp

                        <div class="bg-white border border-gray-100 shadow-sm hover:shadow-md transition rounded-2xl overflow-hidden flex flex-col">
                            <div class="p-5 flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="font-semibold text-lg text-gray-800 leading-snug">
                                            {{ $project->title }}
                                        </h3>

                                        <p class="text-sm text-gray-500 mt-1">
                                            Project pembelajaran
                                        </p>
                                    </div>

                                    @if ($alreadyJoined)
                                        <span class="shrink-0 px-2.5 py-1 bg-green-50 text-green-700 text-xs font-medium rounded-full">
                                            Diambil
                                        </span>
                                    @elseif ($isFull)
                                        <span class="shrink-0 px-2.5 py-1 bg-red-50 text-red-700 text-xs font-medium rounded-full">
                                            Penuh
                                        </span>
                                    @else
                                        <span class="shrink-0 px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-full">
                                            Tersedia
                                        </span>
                                    @endif
                                </div>

                                <p class="text-sm text-gray-600 leading-relaxed mt-4">
                                    {{ \Illuminate\Support\Str::limit($project->description, 120) }}
                                </p>

                                <div class="mt-5 space-y-3 text-sm">
                                    <div class="flex justify-between gap-4">
                                        <span class="text-gray-500">Level</span>
                                        <span class="font-medium text-gray-800">
                                            {{ $project->difficulty_level }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between gap-4">
                                        <span class="text-gray-500">Durasi</span>
                                        <span class="font-medium text-gray-800">
                                            {{ $project->duration_days }} hari
                                        </span>
                                    </div>

                                    <div class="flex justify-between gap-4">
                                        <span class="text-gray-500">Kuota</span>
                                        <span class="font-medium {{ $isFull ? 'text-red-600' : 'text-gray-800' }}">
                                            {{ $joinedCount }}/{{ $maxStudents }} student
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('student.projects.show', $project) }}"
                                       class="inline-flex items-center justify-center px-3 py-2 bg-white hover:bg-gray-100 text-gray-700 border border-gray-200 text-sm font-medium rounded-lg transition">
                                        Detail
                                    </a>

                                    @if ($alreadyJoined)
                                        <span class="inline-flex items-center justify-center px-3 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg">
                                            Sudah Diambil
                                        </span>
                                    @elseif ($isFull)
                                        <button type="button"
                                                class="inline-flex items-center justify-center px-3 py-2 bg-gray-200 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed"
                                                disabled>
                                            Kuota Penuh
                                        </button>
                                    @else
                                        <form action="{{ route('student.projects.join', $project) }}" method="POST">
                                            @csrf

                                            <button type="submit"
                                                    class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                                                Ambil Project
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>