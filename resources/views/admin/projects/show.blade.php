<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- HERO HEADER CARD -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <a href="{{ route('admin.projects.index') }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-lg transition">
                                ← Kembali
                            </a>

                            @if(($project->user->role ?? 'lecturer') === 'vendor')
                                <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-widest rounded-md bg-purple-50 text-purple-700 border border-purple-200">
                                    Mitra Vendor Eksternal
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-widest rounded-md bg-blue-50 text-blue-700 border border-blue-200">
                                    Dosen Akademik Internal
                                </span>
                            @endif

                            @if($project->is_published)
                                <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-widest rounded-md bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    🟢 Status: Published & Aktif
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-widest rounded-md bg-rose-100 text-rose-800 border border-rose-200">
                                    ⛔ Status: Suspended / Draft
                                </span>
                            @endif
                        </div>

                        <h1 class="text-2xl font-extrabold text-slate-900 leading-tight">
                            {{ $project->title }}
                        </h1>
                        <p class="text-xs text-slate-500 mt-1">
                            Dibuat oleh: <strong class="text-slate-800">{{ optional($project->user)->name ?? 'Tidak Diketahui' }}</strong> ({{ optional($project->user)->email ?? '-' }})
                        </p>
                    </div>

                    <!-- MODERATION ACTION BUTTON -->
                    <div class="flex items-center gap-3">
                        <form action="{{ route('admin.projects.toggle-publish', $project) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Ubah status rilis project ini?')" class="px-5 py-3 text-xs font-extrabold rounded-xl shadow-xs transition {{ $project->is_published ? 'bg-rose-600 hover:bg-rose-700 text-white' : 'bg-emerald-600 hover:bg-emerald-700 text-white' }}">
                                {{ $project->is_published ? '⛔ Suspend Project' : '🚀 Publikasikan Project' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- SUCCESS ALERTS -->
            @if (session('success'))
                <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl shadow-sm">
                    <svg class="mt-0.5 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                    <div>
                        <p class="font-semibold text-sm">Berhasil</p>
                        <p class="text-xs mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- LEFT COLUMN: DETAILS & SKILLS -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6 space-y-5">
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900 mb-2">📋 Rincian & Deskripsi Project</h3>
                            <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line">
                                {{ $project->description ?: 'Belum ada rincian deskripsi project.' }}
                            </p>
                        </div>

                        <!-- METRICS GRID -->
                        <div class="grid grid-cols-3 gap-4 pt-4 border-t border-gray-100 text-xs">
                            <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                                <span class="block text-slate-400 font-semibold text-[11px]">Level Kesulitan</span>
                                <strong class="text-slate-900 font-extrabold text-sm mt-0.5 block">{{ $project->difficulty_level ?? 'Beginner' }}</strong>
                            </div>

                            <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                                <span class="block text-slate-400 font-semibold text-[11px]">Estimasi Durasi</span>
                                <strong class="text-indigo-600 font-extrabold text-sm mt-0.5 block">{{ $project->duration_days }} Hari</strong>
                            </div>

                            <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                                <span class="block text-slate-400 font-semibold text-[11px]">Kuota Mahasiswa</span>
                                <strong class="text-emerald-600 font-extrabold text-sm mt-0.5 block">{{ $project->participations_count }} / {{ $project->max_students ?? 1 }} Mhs</strong>
                            </div>
                        </div>
                    </div>

                    <!-- REQUIRED SKILLS & TAGS -->
                    <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6 space-y-4">
                        <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Syarat Main Skill & Tag Kompetensi:</h4>
                        <div class="flex flex-wrap gap-2">
                            @forelse($project->skills as $sk)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold {{ $sk->pivot->is_main ? 'bg-amber-50 text-amber-900 border border-amber-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                    {{ $sk->pivot->is_main ? '★ MAIN SKILL: ' : '' }}{{ $sk->name }}
                                </span>
                            @empty
                                <p class="text-xs text-slate-400 italic">Belum ada skill yang disyaratkan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: ENROLLED PARTICIPANTS ROSTER -->
                <div class="space-y-6">
                    <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6 space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Partisipan Mahasiswa:</h4>
                            <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ $project->participations->count() }} Terdaftar
                            </span>
                        </div>

                        <div class="space-y-2">
                            @forelse($project->participations as $part)
                                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between text-xs">
                                    <div>
                                        <p class="font-bold text-slate-900">{{ optional($part->user)->name ?? 'Mahasiswa' }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">Status: <strong class="text-emerald-700 capitalize">{{ $part->status ?? 'Active' }}</strong></p>
                                    </div>

                                    <span class="font-mono font-extrabold text-teal-700 bg-teal-50 px-2 py-1 rounded-md border border-teal-100">
                                        {{ $part->progress_percent ?? 0 }}%
                                    </span>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic py-2">Belum ada mahasiswa yang mengambil project ini.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
