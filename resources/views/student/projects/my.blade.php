<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Active Projects
            </h2>
        </div>
    </x-slot>

    @php
    $statusLabels = [
    'in_progress' => 'In Progress',
    'development' => 'Development',
    'review' => 'Review',
    'completed' => 'Done',
    ];

    $statusColors = [
    'in_progress' => 'bg-yellow-100 text-yellow-800',
    'development' => 'bg-blue-100 text-blue-800',
    'review' => 'bg-purple-100 text-purple-800',
    'completed' => 'bg-green-100 text-green-800',
    ];
    @endphp

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
            <div class="p-4 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="p-4 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
            @endif

            <div class="bg-white shadow rounded p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            Project COMPRO yang Kamu Ikuti
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Pantau progress dan pengumpulan tautan pengerjaan project kamu.
                        </p>
                    </div>

                    <a href="{{ route('student.projects.index') }}"
                        class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm transition shadow-sm">
                        + Cari Project Baru
                    </a>
                </div>
            </div>

            @if ($participations->isEmpty())
            <div class="bg-white shadow rounded p-8 text-center">
                <div class="mx-auto w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center mb-4">
                    <span class="text-2xl">📁</span>
                </div>

                <h3 class="text-lg font-semibold text-gray-900">
                    Belum ada project yang kamu ambil
                </h3>

                <p class="text-sm text-gray-500 mt-2">
                    Ambil project terlebih dahulu untuk mulai mengerjakan dan melacak progress.
                </p>

                <a href="{{ route('student.projects.index') }}"
                    class="inline-block mt-5 px-4 py-2 bg-blue-600 text-white rounded text-sm">
                    Lihat Daftar Project
                </a>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($participations as $participation)
                @php
                $project = $participation->project;
                $status = $participation->status ?? 'in_progress';
                $progress = $participation->progress_percent ?? 0;
                @endphp

                <div class="bg-white shadow rounded p-5 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start gap-3 mb-3">
                            <div>
                                <h3 class="font-bold text-lg text-gray-900">
                                    {{ $project->title ?? 'Project tidak ditemukan' }}
                                </h3>

                                <p class="text-xs text-gray-500 mt-1">
                                    Diambil pada:
                                    {{ optional($participation->started_at ?? $participation->created_at)->format('d M Y') ?? '-' }}
                                </p>
                            </div>

                            <span class="shrink-0 px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $statusLabels[$status] ?? $status }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 mb-4">
                            {{ \Illuminate\Support\Str::limit($project->description ?? '-', 120) }}
                        </p>

                        <div class="mb-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Progress</span>
                                <span>{{ $progress }}%</span>
                            </div>

                            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div class="h-3 rounded-full {{ $progress >= 100 ? 'bg-green-600' : 'bg-blue-600' }}"
                                    style="width: {{ $progress }}%">
                                </div>
                            </div>
                        </div>

                        <div class="text-sm text-gray-700 space-y-1 mb-4">
                            <p>
                                <strong>Level:</strong>
                                {{ $project->difficulty_level ?? '-' }}
                            </p>

                            <p>
                                <strong>Durasi:</strong>
                                {{ $project->duration_days ?? '-' }} hari
                            </p>

                            <p>
                                <strong>Last Activity:</strong>
                                {{ optional($participation->last_activity_at)->format('d M Y H:i') ?? '-' }}
                            </p>
                        </div>

                        @if ($project && $project->skills->isNotEmpty())
                        <div class="mb-4">
                            <p class="text-xs font-semibold text-gray-500 mb-2">
                                Skills
                            </p>

                            <div class="flex flex-wrap gap-1">
                                @foreach ($project->skills->take(3) as $skill)
                                <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs">
                                    {{ $skill->name }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if ($project && $project->tags->isNotEmpty())
                        <div class="mb-4">
                            <p class="text-xs font-semibold text-gray-500 mb-2">
                                Tags
                            </p>

                            <div class="flex flex-wrap gap-1">
                                @foreach ($project->tags->take(3) as $tag)
                                <span class="px-2 py-1 bg-green-50 text-green-700 rounded text-xs">
                                    {{ $tag->name }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t flex flex-wrap gap-2">
                        @if ($project)
                        <a href="{{ route('student.projects.show', $project) }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded text-sm">
                            Detail / Update
                        </a>
                        @endif

                        @if ($status !== 'completed' && $project)
                        <form action="{{ route('student.projects.complete', $project) }}" method="POST"
                            onsubmit="return confirm('Tandai project ini sebagai selesai?')">
                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded text-sm">
                                Tandai Selesai
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
</x-app-layout>