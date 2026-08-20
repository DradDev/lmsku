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

                    <div class="flex items-center gap-2">
                        @if(isset($invitedParticipations) && $invitedParticipations->isNotEmpty())
                            <a href="{{ route('student.projects.invitations') }}"
                               class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition shadow-sm animate-pulse">
                                <span>Undangan Project</span>
                                <span class="px-2 py-0.5 rounded-full bg-white text-amber-900 text-xs font-black">
                                    {{ $invitedParticipations->count() }}
                                </span>
                            </a>
                        @else
                            <a href="{{ route('student.projects.invitations') }}"
                               class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                                <span>Undangan Project</span>
                            </a>
                        @endif

                        <a href="{{ route('student.projects.index') }}"
                            class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm transition shadow-sm">
                            + Cari Project Baru
                        </a>
                    </div>
                </div>
            </div>

            @if ($participations->isEmpty())
            <div class="bg-white shadow rounded p-8 text-center">
                <div class="mx-auto w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
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
                $deadline = $participation->started_at ? $participation->started_at->copy()->addDays($project->duration_days ?? 30) : null;
                $isOverdue = $deadline && now()->gt($deadline) && $status !== 'completed';
                $daysLeft = $deadline ? (int) now()->diffInDays($deadline, false) : null;
                @endphp

                <div class="bg-white shadow-sm border border-gray-100 rounded-2xl p-5 flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="flex justify-between items-start gap-3 mb-3">
                            <div>
                                <div class="mb-1.5">
                                    @if(($project->provider_type ?? 'internal') === 'external' || ($project->user->role ?? '') === 'vendor')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                            External: {{ $project->user->name ?? 'Vendor' }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            Internal: {{ $project->user->name ?? 'Dosen' }}
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

                            <div class="flex flex-col items-end gap-1">
                                <span class="shrink-0 px-3 py-1 rounded-full text-xs font-bold {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $statusLabels[$status] ?? $status }}
                                </span>
                                @if($status !== 'completed' && $deadline)
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $isOverdue ? 'bg-red-50 text-red-700 border border-red-200' : ($daysLeft <= 3 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-50 text-slate-600') }}">
                                        @if($isOverdue)
                                            ⚠️ Lewat Tenggat
                                        @else
                                            ⏳ Sisa {{ max(0, $daysLeft) }} Hari
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>

                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                            {{ \Illuminate\Support\Str::limit($project->description ?? '-', 120) }}
                        </p>

                        @if($project && $project->brief_file_url)
                            <div class="mb-4">
                                <a href="{{ $project->brief_file_url }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 font-bold text-xs rounded-xl transition">
                                    Download TOR / Brief PDF
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
                                <strong>Durasi & Batas Waktu:</strong>
                                {{ $project->duration_days ?? '-' }} hari 
                                @if($deadline)
                                    <span class="text-xs text-gray-500">(Batas: {{ $deadline->format('d M Y') }})</span>
                                @endif
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
                                <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-semibold">
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
                                <span class="px-2 py-1 bg-green-50 text-green-700 rounded text-xs font-semibold">
                                    #{{ $tag->name }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t flex flex-wrap items-center justify-between gap-2">
                        @if ($project)
                            @if($status === 'completed')
                                <a href="{{ route('student.projects.show', $project) }}"
                                    class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs transition">
                                    Buka Riwayat
                                </a>
                                <a href="{{ route('student.certificate.index') }}"
                                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-sm">
                                    Sertifikat Proyek
                                </a>
                            @else
                                <a href="{{ route('student.projects.show', $project) }}"
                                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition shadow-sm">
                                    Update Progres
                                </a>

                                <form action="{{ route('student.projects.complete', $project) }}" method="POST"
                                    onsubmit="return confirm('Tandai project ini sebagai selesai?')">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                        class="px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 font-bold rounded-xl text-xs transition">
                                        Tandai Selesai
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</x-app-layout>