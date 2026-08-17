<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    </svg>
                </div>

                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">
                        Projects Management (Mitra Vendor Industri)
                    </h2>
                    <p class="text-sm text-gray-500">
                        Kelola project industri real-world, active listings, dan kecocokan talent mahasiswa terverifikasi.
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6" x-data="{ tab: 'active' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-5 flex items-start gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl shadow-sm">
                    <svg class="mt-0.5 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5" />
                    </svg>

                    <div>
                        <p class="font-semibold">Berhasil</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('warning'))
                <div class="mb-5 flex items-start gap-3 p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl shadow-sm">
                    <svg class="mt-0.5 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 9v4" />
                        <path d="M12 17h.01" />
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                    </svg>

                    <div>
                        <p class="font-semibold">Catatan Penting</p>
                        <p class="text-sm">{{ session('warning') }}</p>
                    </div>
                </div>
            @endif

            <div class="mb-6 bg-white border border-gray-100 shadow-sm rounded-2xl p-5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">
                            Daftar Project Industri Real Client
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Kelola active project terpublikasi atau simpan draft project pada Project Bank.
                        </p>
                    </div>

                    <a href="{{ route('vendor.projects.create') }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14" />
                            <path d="M5 12h14" />
                        </svg>
                        Tambah Project
                    </a>
                </div>
            </div>

            <!-- TABS NAVIGATION (Active Projects vs Project Bank) -->
            <div class="mb-6 border-b border-gray-200 flex gap-4">
                <button type="button"
                        @click="tab = 'active'"
                        :class="tab === 'active' ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                        class="pb-3 px-1 border-b-2 text-sm transition-all flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full" :class="tab === 'active' ? 'bg-green-500' : 'bg-gray-300'"></span>
                    Active Projects ({{ $activeProjects->count() }})
                </button>

                <button type="button"
                        @click="tab = 'bank'"
                        :class="tab === 'bank' ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                        class="pb-3 px-1 border-b-2 text-sm transition-all flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full" :class="tab === 'bank' ? 'bg-amber-500' : 'bg-gray-300'"></span>
                    Project Bank ({{ $bankProjects->count() }})
                </button>
            </div>

            <!-- TAB 1: ACTIVE PROJECTS -->
            <div x-show="tab === 'active'">
                <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Judul Project</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Level</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Durasi</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-5 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($activeProjects as $project)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center flex-shrink-0">
                                                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                                    </svg>
                                                </div>

                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <p class="font-semibold text-gray-800">{{ $project->title }}</p>
                                                    </div>
                                                    <p class="text-sm text-gray-400">Project industri mitra aktif</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-700 text-sm font-semibold rounded-full">
                                                {{ $project->difficulty_level }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 text-orange-700 text-sm font-semibold rounded-full">
                                                {{ $project->duration_days }} hari
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 text-green-700 text-sm font-semibold rounded-full">
                                                Active / Published
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('vendor.projects.show', $project) }}" class="inline-flex items-center px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 text-xs font-semibold rounded-xl transition">Detail</a>
                                                <a href="{{ route('vendor.projects.edit', $project) }}" class="inline-flex items-center px-3 py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-semibold rounded-xl transition">Edit</a>

                                                <form action="{{ route('vendor.projects.toggle-publish', $project) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 text-xs font-semibold rounded-xl transition" title="Pindahkan ke Project Bank (Draft)">Pindah ke Bank</button>
                                                </form>

                                                <form action="{{ route('vendor.projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Yakin hapus project ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-semibold rounded-xl transition">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-12 text-center text-gray-400 font-medium">
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
            <div x-show="tab === 'bank'">
                <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-amber-50/50 border-b border-amber-100">
                                    <th class="px-5 py-4 text-left text-xs font-bold text-amber-900 uppercase tracking-wider">Judul (Bank Project)</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-amber-900 uppercase tracking-wider">Level</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-amber-900 uppercase tracking-wider">Durasi</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-amber-900 uppercase tracking-wider">Status</th>
                                    <th class="px-5 py-4 text-right text-xs font-bold text-amber-900 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($bankProjects as $project)
                                    <tr class="hover:bg-amber-50/20 transition">
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center flex-shrink-0">
                                                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                                    </svg>
                                                </div>

                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <p class="font-semibold text-gray-800">{{ $project->title }}</p>
                                                    </div>
                                                    <p class="text-sm text-gray-400">Draft / Repository Bank Project</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-700 text-sm font-semibold rounded-full">
                                                {{ $project->difficulty_level }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 text-orange-700 text-sm font-semibold rounded-full">
                                                {{ $project->duration_days }} hari
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-800 border border-amber-200 text-sm font-semibold rounded-full">
                                                Project Bank (Draft)
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('vendor.projects.show', $project) }}" class="inline-flex items-center px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 text-xs font-semibold rounded-xl transition">Detail</a>
                                                <a href="{{ route('vendor.projects.edit', $project) }}" class="inline-flex items-center px-3 py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-semibold rounded-xl transition">Edit</a>

                                                <form action="{{ route('vendor.projects.toggle-publish', $project) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl transition shadow-xs">Publish ke Active</button>
                                                </form>

                                                <form action="{{ route('vendor.projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Yakin hapus project ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-semibold rounded-xl transition">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-12 text-center text-gray-400 font-medium">
                                            Belum ada project di Project Bank. Project bertipe Draft atau un-publish akan tersimpan di sini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
