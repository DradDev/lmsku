<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <span>📩 Undangan Project (Project Invitations)</span>
            </h2>

            <div class="flex items-center gap-2">
                <a href="{{ route('student.projects.index') }}"
                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                    ← Katalog Project
                </a>

                <a href="{{ route('student.projects.my') }}"
                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                    Project Saya
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-xl shadow-sm flex items-center gap-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-red-100 border border-red-300 text-red-800 rounded-xl shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Header Info Card -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">
                        📩 Undangan Pengerjaan Project Industri & Dosen
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Tinjau dan konfirmasi undangan bergabung yang dikirimkan oleh Dosen Akademik atau Mitra Vendor Industri.
                    </p>
                </div>

                <span class="shrink-0 px-4 py-2 bg-amber-100 text-amber-900 rounded-full font-bold text-sm border border-amber-200">
                    {{ $invitedParticipations->count() }} Undangan Pending
                </span>
            </div>

            @if ($invitedParticipations->isEmpty())
                <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-12 text-center">
                    <div class="mx-auto w-16 h-16 rounded-full bg-amber-50 flex items-center justify-center mb-4 text-3xl">
                        📬
                    </div>

                    <h3 class="text-lg font-bold text-gray-800">
                        Belum Ada Undangan Project Aktif
                    </h3>

                    <p class="text-sm text-gray-500 mt-2">
                        Undangan dari Dosen atau Mitra Vendor Industri akan muncul secara terpisah di halaman ini.
                    </p>

                    <div class="mt-6 flex justify-center gap-3">
                        <a href="{{ route('student.projects.index') }}"
                           class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition shadow-sm">
                            🚀 Jelajahi Katalog Project
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($invitedParticipations as $invitation)
                        @php
                            $project = $invitation->project;
                        @endphp

                        @if ($project)
                            <div class="bg-white border border-amber-200 shadow-sm hover:shadow-md transition rounded-2xl p-5 flex flex-col justify-between space-y-4">
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between gap-2">
                                        @if(($project->provider_type ?? 'internal') === 'external' || ($project->user->role ?? '') === 'vendor')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                                🏢 External: {{ $project->user->name ?? 'Vendor' }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                🎓 Internal: {{ $project->user->name ?? 'Dosen' }}
                                            </span>
                                        @endif

                                        <span class="text-[11px] text-gray-400 font-medium">
                                            {{ $invitation->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    <h4 class="font-bold text-lg text-gray-900 leading-snug">
                                        {{ $project->title }}
                                    </h4>

                                    <p class="text-sm text-gray-600 line-clamp-3 leading-relaxed">
                                        {{ $project->description }}
                                    </p>

                                    <div class="space-y-1.5 text-xs text-gray-500 pt-2 border-t border-gray-100">
                                        <div class="flex items-center justify-between">
                                            <span>Level Kesulitan:</span>
                                            <span class="font-bold text-gray-700 capitalize">{{ $project->difficulty_level }}</span>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <span>Estimasi Durasi:</span>
                                            <span class="font-bold text-gray-700">{{ $project->duration_days }} Hari</span>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <span>Kuota Peserta:</span>
                                            <span class="font-bold text-gray-700">{{ $project->participations()->count() }} / {{ $project->max_students }} Pendaftar</span>
                                        </div>
                                    </div>

                                    @if($project->brief_file_url)
                                        <div class="pt-2">
                                            <a href="{{ $project->brief_file_url }}" target="_blank"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 font-bold text-xs rounded-xl transition w-full justify-center">
                                                📥 Download TOR Brief (PDF)
                                            </a>
                                        </div>
                                    @endif

                                    @if($project->benefits)
                                        <div class="p-3 bg-indigo-50/60 border border-indigo-100 rounded-xl text-xs text-indigo-900">
                                            <span class="font-bold block mb-0.5">🎁 Benefits:</span>
                                            <span class="line-clamp-2 text-indigo-800">{{ $project->benefits }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="pt-4 border-t border-gray-100 flex items-center gap-2">
                                    <form action="{{ route('student.projects.accept-invite', $project) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit"
                                                class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition shadow-sm flex items-center justify-center gap-1">
                                            🚀 Terima Undangan
                                        </button>
                                    </form>

                                    <form action="{{ route('student.projects.decline-invite', $project) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak undangan project ini?')">
                                        @csrf
                                        <button type="submit"
                                                class="px-4 py-2.5 bg-gray-100 hover:bg-red-50 hover:text-red-700 text-gray-600 font-bold text-xs rounded-xl transition border border-gray-200">
                                            ✕ Tolak
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
