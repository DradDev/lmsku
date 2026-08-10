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

            @if(isset($invitedParticipations) && $invitedParticipations->isNotEmpty())
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-lg text-amber-950 flex items-center gap-2">
                            <span>📩 Undangan Project Untukmu</span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-amber-600 text-white">
                                {{ $invitedParticipations->count() }} Undangan Baru
                            </span>
                        </h4>
                        <span class="text-xs text-amber-800 font-medium">Konfirmasi keikutsertaanmu di bawah ini</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($invitedParticipations as $invitation)
                            @php $invProj = $invitation->project; @endphp
                            @if($invProj)
                                <div class="bg-white border border-amber-200 rounded-xl p-4 shadow-sm flex flex-col justify-between space-y-3">
                                    <div>
                                        <div class="flex items-center justify-between gap-2 mb-1.5">
                                            @if(($invProj->provider_type ?? 'internal') === 'external' || ($invProj->user->role ?? '') === 'vendor')
                                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md bg-purple-100 text-purple-800 border border-purple-200">
                                                    🏢 External: {{ $invProj->user->name ?? 'Vendor' }}
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md bg-blue-50 text-blue-700 border border-blue-200">
                                                    🎓 Internal: {{ $invProj->user->name ?? 'Dosen' }}
                                                </span>
                                            @endif
                                            <span class="text-[11px] text-gray-400 font-medium">Diundang {{ $invitation->created_at->diffForHumans() }}</span>
                                        </div>

                                        <h5 class="font-bold text-base text-gray-900 leading-snug">
                                            {{ $invProj->title }}
                                        </h5>
                                        <p class="text-xs text-gray-600 line-clamp-2 mt-1">
                                            {{ $invProj->description }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
                                        <form action="{{ route('student.projects.accept-invite', $invProj) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition shadow-sm flex items-center justify-center gap-1">
                                                🚀 Terima Undangan
                                            </button>
                                        </form>

                                        <form action="{{ route('student.projects.decline-invite', $invProj) }}" method="POST" onsubmit="return confirm('Tolak undangan project ini?')">
                                            @csrf
                                            <button type="submit" class="px-3 py-2 bg-gray-100 hover:bg-red-50 hover:text-red-700 text-gray-600 font-semibold text-xs rounded-xl transition border border-gray-200">
                                                ✕ Tolak
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

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

                <div class="bg-white shadow-sm border border-gray-100 rounded-2xl p-5 flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="flex justify-between items-start gap-3 mb-3">
                            <div>
                                <div class="mb-1.5">
                                    @if(($project->provider_type ?? 'internal') === 'external' || ($project->user->role ?? '') === 'vendor')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                            🏢 External: {{ $project->user->name ?? 'Vendor' }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            🎓 Internal: {{ $project->user->name ?? 'Dosen' }}
                                        </span>
                                    @endif
                                </div>

                                <h3 class="font-bold text-lg text-gray-900 leading-snug">
                                    {{ $project->title ?? 'Project tidak ditemukan' }}
                                </h3>

                                <p class="text-xs text-gray-500 mt-1">
                                    Diambil pada:
                                    {{ optional($participation->started_at ?? $participation->created_at)->format('d M Y') ?? '-' }}
                                </p>
                            </div>

                            <span class="shrink-0 px-3 py-1 rounded-full text-xs font-bold {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $statusLabels[$status] ?? $status }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                            {{ \Illuminate\Support\Str::limit($project->description ?? '-', 120) }}
                        </p>

                        @if($project && $project->brief_file_url)
                            <div class="mb-4">
                                <a href="{{ $project->brief_file_url }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 font-bold text-xs rounded-xl transition">
                                    📥 Download TOR / Brief PDF
                                </a>
                            </div>
                        @endif

                        <div class="mb-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Progress</span>
                                <span class="font-bold text-indigo-600">{{ $progress }}%</span>
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