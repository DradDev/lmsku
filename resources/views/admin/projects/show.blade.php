<x-app-layout>
    <div class="py-8 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- PAGE HEADER CARD -->
            <div class="bg-white border border-slate-200/80 shadow-xs rounded-2xl p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-start gap-3.5">
                        <a href="{{ route('admin.projects.index') }}" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 flex items-center justify-center font-bold transition flex-shrink-0">
                            ←
                        </a>

                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                @if(($project->user->role ?? 'lecturer') === 'vendor')
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-purple-700 bg-purple-50 border border-purple-100 px-2.5 py-0.5 rounded-md">
                                        Mitra Vendor Eksternal
                                    </span>
                                @else
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-700 bg-indigo-50 border border-indigo-100 px-2.5 py-0.5 rounded-md">
                                        Dosen Internal Akademik
                                    </span>
                                @endif

                                @if($project->is_published)
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-50 border border-emerald-100 px-2.5 py-0.5 rounded-md">
                                        Published & Aktif
                                    </span>
                                @else
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-rose-700 bg-rose-50 border border-rose-100 px-2.5 py-0.5 rounded-md">
                                        Suspended / Draft
                                    </span>
                                @endif
                            </div>

                            <h1 class="text-xl font-bold text-slate-900 leading-tight">
                                {{ $project->title }}
                            </h1>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Pembuat: <strong class="text-slate-800">{{ optional($project->user)->name ?? 'Tidak Diketahui' }}</strong> ({{ optional($project->user)->email ?? '-' }})
                            </p>
                        </div>
                    </div>

                    <!-- MODERATION ACTION BUTTON -->
                    <div class="flex items-center gap-3">
                        <form action="{{ route('admin.projects.toggle-publish', $project) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Ubah status rilis project ini?')" class="px-5 py-2.5 text-xs font-bold rounded-xl shadow-xs transition {{ $project->is_published ? 'bg-rose-600 hover:bg-rose-700 text-white' : 'bg-emerald-600 hover:bg-emerald-700 text-white' }}">
                                {{ $project->is_published ? 'Suspend Project' : 'Publikasikan Project' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- SUCCESS ALERTS -->
            @if (session('success'))
                <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold shadow-xs">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-emerald-600 flex-shrink-0"><path d="M20 6 9 17l-5-5"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- LEFT COLUMN: DETAILS & SKILLS -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white border border-slate-200/80 shadow-xs rounded-2xl p-6 space-y-5">
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Rincian & Deskripsi Project</h3>
                            <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line">
                                {{ $project->description ?: 'Belum ada rincian deskripsi project.' }}
                            </p>
                        </div>

                        <!-- METRICS GRID -->
                        <div class="grid grid-cols-3 gap-4 pt-4 border-t border-slate-100 text-xs">
                            <div class="bg-slate-50/80 p-3.5 rounded-xl border border-slate-100">
                                <span class="block text-slate-400 font-semibold text-[10px] uppercase tracking-wider">Level Kesulitan</span>
                                <strong class="text-slate-900 font-bold text-sm mt-0.5 block">{{ $project->difficulty_level ?? 'Beginner' }}</strong>
                            </div>

                            <div class="bg-slate-50/80 p-3.5 rounded-xl border border-slate-100">
                                <span class="block text-slate-400 font-semibold text-[10px] uppercase tracking-wider">Estimasi Durasi</span>
                                <strong class="text-indigo-700 font-bold text-sm mt-0.5 block">{{ $project->duration_days }} Hari</strong>
                            </div>

                            <div class="bg-slate-50/80 p-3.5 rounded-xl border border-slate-100">
                                <span class="block text-slate-400 font-semibold text-[10px] uppercase tracking-wider">Kuota Mahasiswa</span>
                                <strong class="text-teal-700 font-bold text-sm mt-0.5 block">{{ $project->participations_count }} / {{ $project->max_students ?? 1 }} Mhs</strong>
                            </div>
                        </div>
                    </div>

                    <!-- REQUIRED SKILLS & TAGS -->
                    <div class="bg-white border border-slate-200/80 shadow-xs rounded-2xl p-6 space-y-3">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Syarat Main Skill & Tag Kompetensi:</h4>
                        <div class="flex flex-wrap gap-2">
                            @forelse($project->skills as $sk)
                                <span class="inline-flex items-center text-xs font-semibold rounded-lg px-3 py-1.5 {{ $sk->pivot->is_main ? 'bg-amber-50 text-amber-900 border border-amber-200 font-bold' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                    {{ $sk->pivot->is_main ? 'Main Skill: ' : '' }}{{ $sk->name }}
                                </span>
                            @empty
                                <p class="text-xs text-slate-400 italic">Belum ada skill yang disyaratkan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: ENROLLED PARTICIPANTS ROSTER -->
                <div class="space-y-6">
                    <div class="bg-white border border-slate-200/80 shadow-xs rounded-2xl p-6 space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Partisipan Mahasiswa</h4>
                            <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md">
                                {{ $project->participations->count() }} Terdaftar
                            </span>
                        </div>

                        <div class="space-y-2">
                            @forelse($project->participations as $part)
                                <div class="p-3 bg-slate-50/80 border border-slate-200/80 rounded-xl flex items-center justify-between text-xs">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ optional($part->user)->name ?? 'Mahasiswa' }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">Status: <span class="text-slate-700 font-semibold capitalize">{{ $part->status ?? 'Active' }}</span></p>
                                    </div>

                                    <span class="font-mono font-bold text-slate-700 bg-white px-2 py-1 rounded-md border border-slate-200 text-[11px]">
                                        {{ $part->progress_percent ?? 0 }}%
                                    </span>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic py-2">Belum ada mahasiswa yang mendaftar project ini.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
