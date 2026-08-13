<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-900 flex items-center justify-center font-extrabold" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    </svg>
                </div>

                <div>
                    <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
                        Projects Management (Mitra Vendor Industri)
                    </h2>
                    <p class="text-xs font-semibold text-slate-700 mt-0.5">
                        Kelola project industri real-world, active listings, dan kecocokan talent mahasiswa terverifikasi.
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <main class="py-6" role="main" aria-label="Manajemen Project Industri Mitra Vendor" x-data="{ tab: 'active' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-5 flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-300 text-emerald-950 rounded-xl shadow-sm font-bold text-xs" role="alert">
                    <svg class="mt-0.5 flex-shrink-0 text-emerald-700" width="20" height="20" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20 6 9 17l-5-5" />
                    </svg>

                    <div>
                        <p class="font-extrabold">Berhasil</p>
                        <p class="text-xs font-semibold mt-0.5 text-emerald-900">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('warning'))
                <div class="mb-5 flex items-start gap-3 p-4 bg-amber-50 border border-amber-300 text-amber-950 rounded-xl shadow-sm font-bold text-xs" role="alert">
                    <svg class="mt-0.5 flex-shrink-0 text-amber-800" width="20" height="20" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 9v4" />
                        <path d="M12 17h.01" />
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                    </svg>

                    <div>
                        <p class="font-extrabold">Catatan Penting</p>
                        <p class="text-xs font-semibold mt-0.5 text-amber-900">{{ session('warning') }}</p>
                    </div>
                </div>
            @endif

            <div class="mb-6 bg-white border border-gray-200 shadow-sm rounded-2xl p-5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-extrabold text-gray-900">
                            Daftar Project Industri Real Client
                        </h3>
                        <p class="text-xs font-semibold text-slate-700 mt-1">
                            Kelola active project terpublikasi atau simpan draft project pada Project Bank.
                        </p>
                    </div>

                    <a href="{{ route('vendor.projects.create') }}"
                       aria-label="Tambah Project Industri Baru"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-purple-700 hover:bg-purple-800 text-white text-xs font-extrabold rounded-xl shadow-sm transition">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 5v14" />
                            <path d="M5 12h14" />
                        </svg>
                        Tambah Project
                    </a>
                </div>
            </div>

            <!-- TABS NAVIGATION (Active Projects vs Project Bank) -->
            <div class="mb-6 border-b border-gray-200 flex gap-4" role="tablist" aria-label="Tab Pilihan Status Project">
                <button type="button"
                        role="tab"
                        :aria-selected="tab === 'active'"
                        aria-controls="tab-active-panel"
                        @click="tab = 'active'"
                        :class="tab === 'active' ? 'border-purple-700 text-purple-900 font-extrabold' : 'border-transparent text-slate-700 hover:text-gray-900 font-bold'"
                        class="pb-3 px-1 border-b-2 text-xs transition-all flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full" :class="tab === 'active' ? 'bg-emerald-600' : 'bg-gray-400'" aria-hidden="true"></span>
                    Active Projects ({{ $activeProjects->count() }})
                </button>

                <button type="button"
                        role="tab"
                        :aria-selected="tab === 'bank'"
                        aria-controls="tab-bank-panel"
                        @click="tab = 'bank'"
                        :class="tab === 'bank' ? 'border-purple-700 text-purple-900 font-extrabold' : 'border-transparent text-slate-700 hover:text-gray-900 font-bold'"
                        class="pb-3 px-1 border-b-2 text-xs transition-all flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full" :class="tab === 'bank' ? 'bg-amber-600' : 'bg-gray-400'" aria-hidden="true"></span>
                    Project Bank ({{ $bankProjects->count() }})
                </button>
            </div>

            <!-- TAB 1: ACTIVE PROJECTS -->
            <div id="tab-active-panel" role="tabpanel" aria-label="Daftar Active Projects Industri" x-show="tab === 'active'">
                <div class="bg-white border border-gray-200 shadow-sm rounded-2xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="px-5 py-4 text-left text-xs font-extrabold text-slate-700 uppercase tracking-wider">Judul Project</th>
                                    <th class="px-5 py-4 text-left text-xs font-extrabold text-slate-700 uppercase tracking-wider">Level</th>
                                    <th class="px-5 py-4 text-left text-xs font-extrabold text-slate-700 uppercase tracking-wider">Durasi</th>
                                    <th class="px-5 py-4 text-left text-xs font-extrabold text-slate-700 uppercase tracking-wider">Status</th>
                                    <th class="px-5 py-4 text-right text-xs font-extrabold text-slate-700 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($activeProjects as $project)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-900 flex items-center justify-center flex-shrink-0" aria-hidden="true">
                                                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                                    </svg>
                                                </div>

                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <h2 class="font-extrabold text-xs text-gray-900">{{ $project->title }}</h2>
                                                    </div>
                                                    <p class="text-[11px] font-semibold text-slate-700">Project industri mitra aktif</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-900 border border-purple-200 text-xs font-extrabold rounded-full">
                                                {{ $project->difficulty_level }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-900 border border-amber-200 text-xs font-extrabold rounded-full">
                                                {{ $project->duration_days }} hari
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-900 border border-emerald-300 text-xs font-extrabold rounded-full">
                                                Active / Published
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('vendor.projects.show', $project) }}" aria-label="Lihat detail project {{ $project->title }}" class="inline-flex items-center px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-slate-800 border border-gray-300 text-xs font-extrabold rounded-xl transition">Detail</a>
                                                <a href="{{ route('vendor.projects.edit', $project) }}" aria-label="Edit project {{ $project->title }}" class="inline-flex items-center px-3 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-900 border border-purple-200 text-xs font-extrabold rounded-xl transition">Edit</a>

                                                <form action="{{ route('vendor.projects.toggle-publish', $project) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" aria-label="Pindahkan project {{ $project->title }} ke bank draft" class="inline-flex items-center px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-300 text-xs font-extrabold rounded-xl transition" title="Pindahkan ke Project Bank (Draft)">Pindah ke Bank</button>
                                                </form>

                                                <form action="{{ route('vendor.projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Yakin hapus project ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" aria-label="Hapus project {{ $project->title }}" class="inline-flex items-center px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-900 border border-rose-300 text-xs font-extrabold rounded-xl transition">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-12 text-center text-slate-700 font-semibold text-xs">
                                            Belum ada project aktif. Project terpublikasi akan tampil di sini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 2: PROJECT BANK (DRAFT / REPOSITORY) -->
            <div id="tab-bank-panel" role="tabpanel" aria-label="Daftar Project Bank / Draft Industri" x-show="tab === 'bank'" style="display: none;">
                <div class="bg-white border border-gray-200 shadow-sm rounded-2xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="px-5 py-4 text-left text-xs font-extrabold text-slate-700 uppercase tracking-wider">Judul Project (Draft)</th>
                                    <th class="px-5 py-4 text-left text-xs font-extrabold text-slate-700 uppercase tracking-wider">Level</th>
                                    <th class="px-5 py-4 text-left text-xs font-extrabold text-slate-700 uppercase tracking-wider">Durasi</th>
                                    <th class="px-5 py-4 text-left text-xs font-extrabold text-slate-700 uppercase tracking-wider">Status</th>
                                    <th class="px-5 py-4 text-right text-xs font-extrabold text-slate-700 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($bankProjects as $project)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-900 flex items-center justify-center flex-shrink-0" aria-hidden="true">
                                                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                                    </svg>
                                                </div>

                                                <div>
                                                    <h2 class="font-extrabold text-xs text-gray-900">{{ $project->title }}</h2>
                                                    <p class="text-[11px] font-semibold text-slate-700">Terarsip di Bank Project</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-slate-800 text-xs font-extrabold rounded-full">
                                                {{ $project->difficulty_level }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-slate-800 text-xs font-extrabold rounded-full">
                                                {{ $project->duration_days }} hari
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-100 text-amber-950 border border-amber-300 text-xs font-extrabold rounded-full">
                                                Draft / Bank
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('vendor.projects.show', $project) }}" aria-label="Lihat detail draft project {{ $project->title }}" class="inline-flex items-center px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-slate-800 border border-gray-300 text-xs font-extrabold rounded-xl transition">Detail</a>
                                                <a href="{{ route('vendor.projects.edit', $project) }}" aria-label="Edit draft project {{ $project->title }}" class="inline-flex items-center px-3 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-900 border border-purple-200 text-xs font-extrabold rounded-xl transition">Edit</a>

                                                <form action="{{ route('vendor.projects.toggle-publish', $project) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" aria-label="Publikasikan project {{ $project->title }}" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-900 border border-emerald-300 text-xs font-extrabold rounded-xl transition" title="Publikasikan ke Active Projects">Publikasikan</button>
                                                </form>

                                                <form action="{{ route('vendor.projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Yakin hapus project ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" aria-label="Hapus draft project {{ $project->title }}" class="inline-flex items-center px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-900 border border-rose-300 text-xs font-extrabold rounded-xl transition">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-12 text-center text-slate-700 font-semibold text-xs">
                                            Belum ada project terarsip di Project Bank.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>
</x-app-layout>
