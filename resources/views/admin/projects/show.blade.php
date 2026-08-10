<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.projects.index') }}" class="w-9 h-9 rounded-xl bg-gray-100 text-gray-600 hover:bg-gray-200 flex items-center justify-center transition">
                    ←
                </a>
                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">
                        Detail Audit Project: {{ $project->title }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        Audit kriteria kompetensi, kelayakan partisipasi, dan moderasi rilis.
                    </p>
                </div>
            </div>

            <form action="{{ route('admin.projects.toggle-publish', $project) }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengubah status rilis project ini?')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm shadow-sm transition {{ $project->is_published ? 'bg-amber-100 text-amber-900 border border-amber-300 hover:bg-amber-200' : 'bg-emerald-600 text-white hover:bg-emerald-700' }}">
                    {{ $project->is_published ? '🛑 Suspend (Sembunyikan dari Student)' : '🚀 Publikasikan Project' }}
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-5 flex items-start gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl shadow-sm">
                    <svg class="mt-0.5 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5" />
                    </svg>
                    <div>
                        <p class="font-semibold">Berhasil</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- LEFT COLUMN: PROJECT DETAILS & SKILL REQUIREMENTS -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-4">
                            @if(($project->user->role ?? 'lecturer') === 'vendor')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                    🏢 Vendor Eksternal Mitra
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    🎓 Dosen Akademik Internal
                                </span>
                            @endif

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                                {{ $project->difficulty_level ?? 'Beginner' }}
                            </span>
                        </div>

                        <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $project->title }}</h3>
                        <p class="text-sm text-gray-600 leading-relaxed mb-6 whitespace-pre-line">{{ $project->description ?: 'Tidak ada deskripsi rincian.' }}</p>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100 text-xs">
                            <div>
                                <span class="block text-gray-400 font-medium">Pembuat / Provider:</span>
                                <span class="font-bold text-gray-800 text-sm">{{ $project->user->name ?? 'Unknown' }}</span>
                            </div>

                            <div>
                                <span class="block text-gray-400 font-medium">Estimasi Durasi:</span>
                                <span class="font-bold text-gray-800 text-sm">{{ $project->duration_days }} Hari</span>
                            </div>

                            <div>
                                <span class="block text-gray-400 font-medium">Maksimal Mahasiswa:</span>
                                <span class="font-bold text-gray-800 text-sm">{{ $project->max_students ?? 1 }} Orang</span>
                            </div>
                        </div>
                    </div>

                    <!-- REQUIRED SKILLS & TAGS -->
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                        <h4 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <span>🎯 Syarat Main Skill & Tag Kompetensi</span>
                        </h4>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Main Skill & Skill Syarat:</label>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($project->skills as $sk)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold {{ $sk->pivot->is_main ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-slate-100 text-slate-700' }}">
                                            {{ $sk->pivot->is_main ? '★ MAIN SKILL: ' : '' }}{{ $sk->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400 italic">Belum ada skill yang disyaratkan.</span>
                                    @endforelse
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Tag Spesialisasi:</label>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($project->tags as $tag)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            #{{ $tag->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400 italic">Tanpa tag spesialisasi.</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: ENROLLED STUDENTS AUDIT -->
                <div class="space-y-6">
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                        <h4 class="text-base font-bold text-gray-800 mb-4 flex items-center justify-between">
                            <span>👥 Daftar Mahasiswa Terdaftar</span>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-slate-100 text-slate-700">
                                {{ $project->participations->count() }} / {{ $project->max_students ?? 1 }}
                            </span>
                        </h4>

                        <div class="divide-y divide-gray-100">
                            @forelse($project->participations as $part)
                                <div class="py-3 flex items-center justify-between gap-3">
                                    <div>
                                        <p class="font-bold text-sm text-gray-900">{{ $part->user->name ?? 'Unknown Student' }}</p>
                                        <p class="text-xs text-gray-500">{{ $part->user->email ?? '-' }}</p>
                                        <div class="mt-1 text-[11px] text-gray-400">
                                            Progress: <span class="font-bold text-indigo-600">{{ $part->progress_percent }}%</span>
                                        </div>
                                    </div>

                                    <div>
                                        @if($part->status === 'completed')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">Selesai</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">Aktif</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-xs text-gray-400 italic">
                                    Belum ada mahasiswa yang mengambil project ini.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
