<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                </svg>
            </div>

            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    Projects Audit & Oversight
                </h2>
                <p class="text-sm text-gray-500">
                    Pengawasan terpusat, audit kompetensi, dan rem moderasi darurat project industri/dosen.
                </p>
            </div>
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

            @if (session('error'))
                <div class="mb-5 flex items-start gap-3 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl shadow-sm">
                    <svg class="mt-0.5 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="15" y1="9" x2="9" y2="15" />
                        <line x1="9" y1="9" x2="15" y2="15" />
                    </svg>
                    <div>
                        <p class="font-semibold">Gagal</p>
                        <p class="text-sm">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- METRIC EXECUTIVE STATS CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Total Project</p>
                    <div class="flex items-baseline justify-between mt-2">
                        <h3 class="text-3xl font-extrabold text-gray-900">{{ $totalProjects }}</h3>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700">{{ $publishedProjects }} Rilis</span>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Vendor Eksternal</p>
                    <div class="flex items-baseline justify-between mt-2">
                        <h3 class="text-3xl font-extrabold text-indigo-600">{{ $vendorProjects }}</h3>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-purple-50 text-purple-700">Mitra Industri</span>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Dosen Internal</p>
                    <div class="flex items-baseline justify-between mt-2">
                        <h3 class="text-3xl font-extrabold text-gray-900">{{ $lecturerProjects }}</h3>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700">Akademik</span>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Partisipasi Mahasiswa</p>
                    <div class="flex items-baseline justify-between mt-2">
                        <h3 class="text-3xl font-extrabold text-emerald-600">{{ $activeParticipations }}</h3>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700">Sedang Aktif</span>
                    </div>
                </div>
            </div>

            <!-- FILTER BAR -->
            <div class="mb-6 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <form method="GET" action="{{ route('admin.projects.index') }}" class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Tipe Provider:</label>
                            <select name="provider_type" onchange="this.form.submit()" class="rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Semua Provider --</option>
                                <option value="lecturer" {{ request('provider_type') === 'lecturer' ? 'selected' : '' }}>Dosen Internal</option>
                                <option value="vendor" {{ request('provider_type') === 'vendor' ? 'selected' : '' }}>Vendor Eksternal</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Status Rilis:</label>
                            <select name="status" onchange="this.form.submit()" class="rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Semua Status --</option>
                                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published (Aktif)</option>
                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft / Suspended</option>
                            </select>
                        </div>
                    </div>

                    <div class="w-full md:w-72">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Cari Judul / Deskripsi:</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." onchange="this.form.submit()" class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </form>
            </div>

            <!-- TABLE OF PROJECTS -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                @if($projects->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-500 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4">Judul & Level</th>
                                    <th class="px-6 py-4">Pembuat / Provider</th>
                                    <th class="px-6 py-4">Main Skill & Tag</th>
                                    <th class="px-6 py-4">Kuota Mahasiswa</th>
                                    <th class="px-6 py-4">Status Rilis</th>
                                    <th class="px-6 py-4 text-center">Aksi Audit & Moderasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($projects as $project)
                                    @php
                                        $creatorRole = $project->user->role ?? 'lecturer';
                                        $isVendor = $creatorRole === 'vendor';
                                    @endphp
                                    <tr class="hover:bg-gray-50/80 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-900 text-base mb-1">
                                                {{ $project->title }}
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                                    {{ $project->difficulty_level ?? 'Beginner' }}
                                                </span>
                                                <span class="text-xs text-gray-400">⏱️ {{ $project->duration_days }} Hari</span>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-800">
                                                {{ $project->user->name ?? 'Unknown' }}
                                            </div>
                                            <div class="mt-1">
                                                @if($isVendor)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                                        🏢 Vendor Eksternal
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                        🎓 Dosen Internal
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-1 mb-1">
                                                @forelse($project->skills as $sk)
                                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-md {{ $sk->pivot->is_main ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-slate-100 text-slate-600' }}">
                                                        {{ $sk->pivot->is_main ? '★ ' : '' }}{{ $sk->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-xs text-gray-400 italic">Tanpa skill</span>
                                                @endforelse
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-900">
                                                {{ $project->participations_count }} / {{ $project->max_students ?? 1 }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                Sisa: {{ max(0, ($project->max_students ?? 1) - $project->participations_count) }} Slot
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            @if($project->is_published)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Published
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Suspended / Draft
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('admin.projects.show', $project) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition">
                                                    👁️ Audit
                                                </a>

                                                <form action="{{ route('admin.projects.toggle-publish', $project) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengubah status rilis project ini?')" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg {{ $project->is_published ? 'bg-amber-50 text-amber-700 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }} transition">
                                                        {{ $project->is_published ? '🛑 Suspend' : '🚀 Rilis' }}
                                                    </button>
                                                </form>

                                                @if($project->participations_count === 0)
                                                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Hapus project ini secara permanen?')" class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">
                                                            🗑️
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
                    <div class="p-12 text-center">
                        <p class="text-base font-bold text-gray-700 mb-1">Belum ada project yang terdaftar.</p>
                        <p class="text-sm text-gray-500">Project yang dibuat oleh Dosen maupun Vendor Luar akan otomatis muncul di sini untuk diaudit.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
