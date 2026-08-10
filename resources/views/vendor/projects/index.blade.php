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
                        Project Real Client & Industri
                    </h2>
                    <p class="text-sm text-gray-500">
                        Kelola active project terpublikasi atau simpan draft project pada Project Bank Mitra Vendor.
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6" x-data="{ tab: 'active' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-5 flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl shadow-sm">
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

            <div class="mb-6 bg-white border border-gray-100 shadow-sm rounded-2xl p-5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">
                            Daftar Project Real Client
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
                        Tambah Project Baru
                    </a>
                </div>
            </div>

            <!-- TABS NAVIGATION (Active Projects vs Project Bank) -->
            <div class="mb-6 border-b border-gray-200 flex gap-4">
                <button type="button"
                        @click="tab = 'active'"
                        :class="tab === 'active' ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                        class="pb-3 px-1 border-b-2 text-sm transition-all flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full" :class="tab === 'active' ? 'bg-emerald-500' : 'bg-gray-300'"></span>
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
                                    <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tipe & Kuota</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status Publikasi</th>
                                    <th class="px-5 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi Kelola</th>
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
                                                    <a href="{{ route('vendor.projects.show', $project) }}" class="font-bold text-gray-900 hover:text-purple-600 text-sm">
                                                        {{ $project->title }}
                                                    </a>
                                                    <p class="text-xs text-gray-500 mt-0.5">Dibuat {{ $project->created_at ? $project->created_at->format('d M Y') : '-' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 text-xs font-semibold text-gray-700">
                                            {{ ucfirst($project->difficulty_level) }}
                                        </td>
                                        <td class="px-5 py-4 text-xs text-gray-600">
                                            <span class="font-semibold text-gray-900">{{ ucfirst($project->type ?? 'General') }}</span> &bull; {{ $project->participations->count() }}/{{ $project->max_students ?? 1 }} Mhs
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                🟢 Published
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('vendor.projects.talent-pool', $project) }}" class="px-3 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-100 font-bold text-xs rounded-xl transition">
                                                    🎯 Talent Pool
                                                </a>
                                                <a href="{{ route('vendor.projects.show', $project) }}" class="px-3 py-1.5 bg-purple-50 text-purple-700 hover:bg-purple-100 font-bold text-xs rounded-xl transition">
                                                    Detail →
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-8 text-center text-sm text-gray-500">
                                            Belum ada project aktif yang dipublikasikan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 2: PROJECT BANK (DRAFTS) -->
            <div x-show="tab === 'bank'">
                <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Judul Draft</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Level</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-5 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($bankProjects as $project)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-5 py-4">
                                            <div class="font-bold text-gray-900 text-sm">{{ $project->title }}</div>
                                        </td>
                                        <td class="px-5 py-4 text-xs font-semibold text-gray-700">
                                            {{ ucfirst($project->difficulty_level) }}
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                                🔴 Draft Bank
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-right">
                                            <a href="{{ route('vendor.projects.show', $project) }}" class="px-3 py-1.5 bg-gray-100 text-gray-700 font-bold text-xs rounded-xl hover:bg-gray-200 transition">
                                                Pratinjau & Edit →
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-5 py-8 text-center text-sm text-gray-500">
                                            Belum ada draft project di Project Bank.
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
