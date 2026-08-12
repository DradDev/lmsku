<x-app-layout>
    <div class="py-8 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- PAGE HEADER CARD -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold shadow-xs flex-shrink-0">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                            </svg>
                        </div>

                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-600 bg-indigo-50 border border-indigo-100 px-2.5 py-0.5 rounded-md">
                                Modul Pengawasan Admin
                            </span>
                            <h1 class="text-xl font-bold text-slate-900 tracking-tight mt-0.5">
                                Audit & Moderasi Project
                            </h1>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Kelola publikasi, pengawasan syarat kompetensi, dan status keikutsertaan mahasiswa pada project industri & dosen.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SUCCESS & ERROR ALERTS -->
            @if (session('success'))
                <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold shadow-xs">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-emerald-600 flex-shrink-0"><path d="M20 6 9 17l-5-5"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-center gap-3 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-semibold shadow-xs">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-rose-600 flex-shrink-0"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- EXECUTIVE METRICS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col justify-between space-y-3">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Project</span>
                    <div class="flex items-baseline justify-between">
                        <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalProjects }}</span>
                        <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-md">{{ $publishedProjects }} Rilis</span>
                    </div>
                </div>

                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col justify-between space-y-3">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Mitra Industri / Vendor</span>
                    <div class="flex items-baseline justify-between">
                        <span class="text-3xl font-extrabold text-purple-700 tracking-tight">{{ $vendorProjects }}</span>
                        <span class="text-[11px] font-bold text-purple-700 bg-purple-50 border border-purple-100 px-2 py-0.5 rounded-md">Eksternal</span>
                    </div>
                </div>

                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col justify-between space-y-3">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Dosen Internal</span>
                    <div class="flex items-baseline justify-between">
                        <span class="text-3xl font-extrabold text-indigo-700 tracking-tight">{{ $lecturerProjects }}</span>
                        <span class="text-[11px] font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md">Akademik</span>
                    </div>
                </div>

                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col justify-between space-y-3">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Partisipasi Mahasiswa</span>
                    <div class="flex items-baseline justify-between">
                        <span class="text-3xl font-extrabold text-teal-700 tracking-tight">{{ $activeParticipations }}</span>
                        <span class="text-[11px] font-bold text-teal-700 bg-teal-50 border border-teal-100 px-2 py-0.5 rounded-md">Aktif</span>
                    </div>
                </div>
            </div>

            <!-- FILTER TOOLBAR -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
                <form method="GET" action="{{ route('admin.projects.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-slate-500">Provider:</span>
                            <select name="provider_type" onchange="this.form.submit()" class="rounded-xl border-slate-200 text-xs font-semibold focus:border-slate-400 focus:ring-0 py-1.5 px-3">
                                <option value="">Semua Provider</option>
                                <option value="lecturer" {{ request('provider_type') === 'lecturer' ? 'selected' : '' }}>Dosen Internal</option>
                                <option value="vendor" {{ request('provider_type') === 'vendor' ? 'selected' : '' }}>Vendor Eksternal</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-slate-500">Status:</span>
                            <select name="status" onchange="this.form.submit()" class="rounded-xl border-slate-200 text-xs font-semibold focus:border-slate-400 focus:ring-0 py-1.5 px-3">
                                <option value="">Semua Status</option>
                                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published (Aktif)</option>
                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Suspended / Draft</option>
                            </select>
                        </div>
                    </div>

                    <div class="w-full md:w-72">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul project..." onchange="this.form.submit()" class="w-full rounded-xl border-slate-200 text-xs font-medium focus:border-slate-400 focus:ring-0 py-1.5 px-3">
                    </div>
                </form>
            </div>

            <!-- PROJECTS TABLE CARD -->
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                @if($projects->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50/80 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5">Judul & Level</th>
                                    <th class="px-6 py-3.5">Penyedia (Provider)</th>
                                    <th class="px-6 py-3.5">Syarat Skill</th>
                                    <th class="px-6 py-3.5">Kuota Mahasiswa</th>
                                    <th class="px-6 py-3.5">Status Rilis</th>
                                    <th class="px-6 py-3.5 text-right">Aksi Moderasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($projects as $project)
                                    @php
                                        $creatorRole = $project->user->role ?? 'lecturer';
                                        $isVendor = $creatorRole === 'vendor';
                                    @endphp
                                    <tr class="hover:bg-slate-50/60 transition">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-900 text-sm leading-tight mb-1">
                                                <a href="{{ route('admin.projects.show', $project) }}" class="hover:text-indigo-600 transition">
                                                    {{ $project->title }}
                                                </a>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-md">
                                                    {{ $project->difficulty_level ?? 'Beginner' }}
                                                </span>
                                                <span class="text-[11px] text-slate-400">Durasi: {{ $project->duration_days }} Hari</span>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-slate-800">
                                                {{ $project->user->name ?? 'Tidak Diketahui' }}
                                            </div>
                                            <div class="mt-1">
                                                @if($isVendor)
                                                    <span class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider text-purple-700 bg-purple-50 border border-purple-100 px-2 py-0.5 rounded-md">
                                                        Vendor Eksternal
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md">
                                                        Dosen Internal
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-1">
                                                @forelse($project->skills as $sk)
                                                    <span class="text-[10px] font-semibold rounded-md px-2 py-0.5 {{ $sk->pivot->is_main ? 'bg-amber-50 text-amber-800 border border-amber-200 font-bold' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                                        {{ $sk->pivot->is_main ? 'Main: ' : '' }}{{ $sk->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-[11px] text-slate-400 italic">Tanpa syarat</span>
                                                @endforelse
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-900">
                                                {{ $project->participations_count }} / {{ $project->max_students ?? 1 }} Mhs
                                            </div>
                                            <div class="text-[11px] text-slate-400">
                                                Sisa: {{ max(0, ($project->max_students ?? 1) - $project->participations_count) }} Slot
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            @if($project->is_published)
                                                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Published
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2.5 py-1 rounded-full">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Suspended
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <a href="{{ route('admin.projects.show', $project) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] rounded-lg transition">
                                                    <span>Detail</span>
                                                </a>

                                                <form action="{{ route('admin.projects.toggle-publish', $project) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('Ubah status rilis project ini?')" class="inline-flex items-center px-3 py-1.5 font-bold text-[11px] rounded-lg transition {{ $project->is_published ? 'bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200' : 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs' }}">
                                                        {{ $project->is_published ? 'Suspend' : 'Publikasikan' }}
                                                    </button>
                                                </form>

                                                @if($project->participations_count === 0)
                                                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Hapus project ini secara permanen?')" class="p-1.5 text-slate-400 hover:text-rose-600 bg-slate-50 hover:bg-rose-50 rounded-lg transition" title="Hapus Project">
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center space-y-2">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center mx-auto">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800">Belum Ada Project Terdaftar</h4>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">
                            Project yang dipublikasikan oleh Dosen maupun Mitra Vendor akan muncul di sini untuk diaudit.
                        </p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
