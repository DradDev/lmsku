<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <span>📁 Project Real Client & Industri</span>
            </h2>

            <a href="{{ route('vendor.projects.create') }}"
               class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl transition shadow-sm">
                + Publikasikan Project Baru
            </a>
        </div>
    </x-slot>

    <div class="py-6" x-data="{ tab: 'active' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
            <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-xl shadow-sm font-semibold text-xs flex items-center gap-2">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <!-- Executive Status Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div @click="tab = 'active'" class="cursor-pointer bg-white rounded-2xl border border-gray-200 p-4 flex items-center justify-between shadow-sm hover:border-emerald-500 transition">
                    <div>
                        <div class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Aktif Dipublikasikan (Terbuka)</div>
                        <div class="text-2xl font-black text-emerald-600 mt-0.5">{{ $activeProjects->count() }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold">
                        🟢
                    </div>
                </div>

                <div @click="tab = 'bank'" class="cursor-pointer bg-white rounded-2xl border border-gray-200 p-4 flex items-center justify-between shadow-sm hover:border-amber-500 transition">
                    <div>
                        <div class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Project Bank (Draft Internal)</div>
                        <div class="text-2xl font-black text-amber-600 mt-0.5">{{ $bankProjects->count() }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-bold">
                        🔴
                    </div>
                </div>

                <div @click="tab = 'all'" class="cursor-pointer bg-white rounded-2xl border border-gray-200 p-4 flex items-center justify-between shadow-sm hover:border-purple-500 transition">
                    <div>
                        <div class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Total Semua Project</div>
                        <div class="text-2xl font-black text-gray-900 mt-0.5">{{ $allProjects->count() }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg font-bold">
                        💼
                    </div>
                </div>
            </div>

            <!-- TAB FILTER BAR -->
            <div class="border-b border-gray-200 flex gap-4">
                <button type="button" @click="tab = 'active'"
                        :class="tab === 'active' ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                        class="pb-3 px-1 border-b-2 text-xs transition-all flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full" :class="tab === 'active' ? 'bg-emerald-500' : 'bg-gray-300'"></span>
                    Active Projects ({{ $activeProjects->count() }})
                </button>

                <button type="button" @click="tab = 'bank'"
                        :class="tab === 'bank' ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                        class="pb-3 px-1 border-b-2 text-xs transition-all flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full" :class="tab === 'bank' ? 'bg-amber-500' : 'bg-gray-300'"></span>
                    Project Bank / Drafts ({{ $bankProjects->count() }})
                </button>

                <button type="button" @click="tab = 'all'"
                        :class="tab === 'all' ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                        class="pb-3 px-1 border-b-2 text-xs transition-all flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full" :class="tab === 'all' ? 'bg-purple-600' : 'bg-gray-300'"></span>
                    Semua Project ({{ $allProjects->count() }})
                </button>
            </div>

            <!-- TAB 1: ACTIVE PROJECTS -->
            <div x-show="tab === 'active'" x-transition>
                @if ($activeProjects->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-200 p-10 text-center space-y-3 shadow-sm">
                    <p class="text-xs text-gray-500 font-medium">Belum ada project aktif dipublikasikan. Aktifkan project dari Project Bank.</p>
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($activeProjects as $project)
                        @include('vendor.projects._project_card', ['project' => $project])
                    @endforeach
                </div>
                @endif
            </div>

            <!-- TAB 2: BANK / DRAFT PROJECTS -->
            <div x-show="tab === 'bank'" x-transition>
                @if ($bankProjects->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-200 p-10 text-center space-y-3 shadow-sm">
                    <p class="text-xs text-gray-500 font-medium">Tidak ada draft project tersimpan.</p>
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($bankProjects as $project)
                        @include('vendor.projects._project_card', ['project' => $project])
                    @endforeach
                </div>
                @endif
            </div>

            <!-- TAB 3: ALL PROJECTS -->
            <div x-show="tab === 'all'" x-transition>
                @if ($allProjects->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center space-y-3 shadow-sm">
                    <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center mx-auto text-2xl font-bold">
                        🏢
                    </div>
                    <h3 class="font-extrabold text-base text-gray-900">Belum Ada Project Industri</h3>
                    <p class="text-xs text-gray-500 max-w-md mx-auto">
                        Publikasikan pekerjaan nyata atau tantangan proyek dari klien Anda untuk merekrut talenta mahasiswa terbaik.
                    </p>
                    <a href="{{ route('vendor.projects.create') }}" class="inline-block px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                        + Publikasikan Project Pertama
                    </a>
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($allProjects as $project)
                        @include('vendor.projects._project_card', ['project' => $project])
                    @endforeach
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
