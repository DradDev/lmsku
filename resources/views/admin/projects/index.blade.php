<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- PAGE HERO HEADER CARD -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-bold shadow-md shadow-indigo-500/20 flex-shrink-0">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                            </svg>
                        </div>

                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-widest rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    Portal Pengawasan & Moderasi
                                </span>
                            </div>
                            <h2 class="font-extrabold text-xl text-slate-900 leading-tight mt-0.5">
                                Audit Project Industri & Akademik
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Pengawasan terpusat, audit kriteria kompetensi, kelayakan partisipasi mahasiswa, dan kontrol rilis project.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SUCCESS & ERROR ALERTS -->
            @if (session('success'))
                <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl shadow-sm">
                    <svg class="mt-0.5 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                    <div>
                        <p class="font-semibold text-sm">Berhasil</p>
                        <p class="text-xs mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl shadow-sm">
                    <svg class="mt-0.5 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <div>
                        <p class="font-semibold text-sm">Gagal</p>
                        <p class="text-xs mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- METRIC EXECUTIVE STATS CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Project</span>
                    <div class="flex items-baseline justify-between pt-1">
                        <h3 class="text-3xl font-extrabold text-slate-900">{{ $totalProjects }}</h3>
                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100">{{ $publishedProjects }} Published</span>
                    </div>
                    <p class="text-[11px] text-slate-500">Project terdaftar di sistem</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-purple-600">Vendor Eksternal</span>
                    <div class="flex items-baseline justify-between pt-1">
                        <h3 class="text-3xl font-extrabold text-purple-700">{{ $vendorProjects }}</h3>
                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-md bg-purple-50 text-purple-700 border border-purple-100">Mitra Industri</span>
                    </div>
                    <p class="text-[11px] text-purple-500">Project sertifikasi industri</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600">Dosen Internal</span>
                    <div class="flex items-baseline justify-between pt-1">
                        <h3 class="text-3xl font-extrabold text-blue-700">{{ $lecturerProjects }}</h3>
                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-100">Akademik</span>
                    </div>
                    <p class="text-[11px] text-blue-500">Project perkuliahan kampus</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Partisipasi Mahasiswa</span>
                    <div class="flex items-baseline justify-between pt-1">
                        <h3 class="text-3xl font-extrabold text-emerald-700">{{ $activeParticipations }}</h3>
                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100">Sedang Aktif</span>
                    </div>
                    <p class="text-[11px] text-emerald-600">Total mahasiswa bergabung</p>
                </div>
            </div>

            <!-- FILTER & SEARCH BAR -->
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <form method="GET" action="{{ route('admin.projects.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">Tipe Provider:</label>
                            <select name="provider_type" onchange="this.form.submit()" class="rounded-xl border-gray-300 text-xs font-bold focus:border-indigo-600 focus:ring-indigo-600">
                                <option value="">Semua Provider</option>
                                <option value="lecturer" {{ request('provider_type') === 'lecturer' ? 'selected' : '' }}>Dosen Internal</option>
                                <option value="vendor" {{ request('provider_type') === 'vendor' ? 'selected' : '' }}>Vendor Eksternal</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">Status Rilis:</label>
                            <select name="status" onchange="this.form.submit()" class="rounded-xl border-gray-300 text-xs font-bold focus:border-indigo-600 focus:ring-indigo-600">
                                <option value="">Semua Status</option>
                                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published (Aktif)</option>
                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft / Suspended</option>
                            </select>
                        </div>
                    </div>

                    <div class="w-full md:w-80">
                        <label class="block text-xs font-bold text-slate-500 mb-1">Cari Judul / Deskripsi:</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci pencarian..." onchange="this.form.submit()" class="w-full rounded-xl border-gray-300 text-xs font-medium focus:border-indigo-600 focus:ring-indigo-600">
                    </div>
                </form>
            </div>

            <!-- TABLE OF PROJECTS -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                @if($projects->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 border-b border-gray-200 text-slate-500 font-bold uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Judul Project & Level</th>
                                    <th class="px-6 py-4">Pembuat / Provider</th>
                                    <th class="px-6 py-4">Syarat Skill & Tag</th>
                                    <th class="px-6 py-4">Partisipan / Kuota</th>
                                    <th class="px-6 py-4">Status Moderasi</th>
                                    <th class="px-6 py-4 text-center">Aksi Audit & Moderasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($projects as $project)
                                    @php
                                        $creatorRole = $project->user->role ?? 'lecturer';
                                        $isVendor = $creatorRole === 'vendor';
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="px-6 py-4">
                                            <div class="font-extrabold text-slate-900 text-sm mb-1">
                                                {{ $project->title }}
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                                                    {{ $project->difficulty_level ?? 'Beginner' }}
                                                </span>
                                                <span class="text-[11px] text-slate-400 font-medium">Durasi: {{ $project->duration_days }} Hari</span>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-800">
                                                {{ $project->user->name ?? 'Tidak Diketahui' }}
                                            </div>
                                            <div class="mt-1">
                                                @if($isVendor)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-purple-50 text-purple-700 border border-purple-200">
                                                        Vendor Eksternal
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                                                        Dosen Internal
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-1">
                                                @forelse($project->skills as $sk)
                                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-md {{ $sk->pivot->is_main ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                                        {{ $sk->pivot->is_main ? 'Main: ' : '' }}{{ $sk->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-xs text-slate-400 italic">Tanpa syarat skill</span>
                                                @endforelse
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="font-extrabold text-slate-900">
                                                {{ $project->participations_count }} / {{ $project->max_students ?? 1 }} Mhs
                                            </div>
                                            <div class="text-[11px] text-slate-400 font-medium">
                                                Sisa Kuota: {{ max(0, ($project->max_students ?? 1) - $project->participations_count) }} Slot
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            @if($project->is_published)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    🟢 Published
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                    ⛔ Suspended
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <a href="{{ route('admin.projects.show', $project) }}" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs rounded-xl border border-indigo-100 transition">
                                                    Audit Detail
                                                </a>

                                                <form action="{{ route('admin.projects.toggle-publish', $project) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('Ubah status rilis project ini?')" class="px-3 py-1.5 font-bold text-xs rounded-xl transition {{ $project->is_published ? 'bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200' : 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs' }}">
                                                        {{ $project->is_published ? 'Suspend' : 'Publikasikan' }}
                                                    </button>
                                                </form>

                                                @if($project->participations_count === 0)
                                                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Hapus project ini secara permanen?')" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition" title="Hapus Project">
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
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto text-xl font-bold">
                            📂
                        </div>
                        <h4 class="text-base font-extrabold text-slate-800">Belum Ada Project Terdaftar</h4>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">
                            Project yang dipublikasikan oleh Dosen maupun Mitra Vendor akan muncul di sini untuk diaudit oleh Admin.
                        </p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
