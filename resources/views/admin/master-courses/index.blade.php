<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
            </div>

            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    Katalog Master Course & Sertifikasi (Terpadu)
                </h2>
                <p class="text-sm text-gray-500">
                    Pengawasan terpusat katalog mata kuliah kurikulum Dosen dan course sertifikasi Mitra Vendor Industri.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6" x-data="{ tab: 'all' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- SUCCESS / ERROR ALERTS -->
            @if (session('success'))
                <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl shadow-sm">
                    <svg class="mt-0.5 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5" />
                    </svg>
                    <div>
                        <p class="font-semibold text-sm">Berhasil</p>
                        <p class="text-xs mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl shadow-sm">
                    <svg class="mt-0.5 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="15" y1="9" x2="9" y2="15" />
                        <line x1="9" y1="9" x2="15" y2="15" />
                    </svg>
                    <div>
                        <p class="font-semibold text-sm">Gagal</p>
                        <p class="text-xs mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- HERO HEADER ACTION CARD -->
            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-5 md:p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-widest rounded-md bg-blue-50 text-blue-700 border border-blue-100">
                                Pusat Katalog Terpadu
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">
                            Kelola Katalog Pembelajaran Akademik & Sertifikasi Industri
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">
                            Kelola mata kuliah induk kurikulum Dosen (Gerbang 3NF) atau pantau rincian sertifikasi dari Mitra Vendor.
                        </p>
                    </div>

                    <a href="{{ route('admin.master-courses.create') }}" 
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition whitespace-nowrap">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                        Tambah Master Course Akademik
                    </a>
                </div>
            </div>

            <!-- 4 EXECUTIVE METRIC CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Total Katalog Master Course</span>
                    <div class="flex items-baseline justify-between pt-1">
                        <h3 class="text-3xl font-extrabold text-gray-900">{{ $masterCourses->count() + $vendorCourses->count() }}</h3>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-gray-100 text-gray-700">Katalog LMS</span>
                    </div>
                    <p class="text-[11px] text-gray-400">Internal Kampus & Vendor</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600">🏛️ Internal Kampus (Dosen)</span>
                    <div class="flex items-baseline justify-between pt-1">
                        <h3 class="text-3xl font-extrabold text-blue-600">{{ $masterCourses->count() }}</h3>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-blue-50 text-blue-700">Akademik</span>
                    </div>
                    <p class="text-[11px] text-blue-500">Mata kuliah kurikulum kampus</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-purple-600">🏢 Mitra Vendor (Sertifikasi)</span>
                    <div class="flex items-baseline justify-between pt-1">
                        <h3 class="text-3xl font-extrabold text-purple-600">{{ $vendorCourses->count() }}</h3>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-purple-50 text-purple-700">Bootcamp</span>
                    </div>
                    <p class="text-[11px] text-purple-500">Course sertifikasi industri</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">🎯 Offerings & Batches</span>
                    <div class="flex items-baseline justify-between pt-1">
                        <h3 class="text-3xl font-extrabold text-emerald-600">{{ $masterCourses->sum('offerings_count') + $vendorCourses->count() }}</h3>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700">Aktif</span>
                    </div>
                    <p class="text-[11px] text-emerald-500">Kelas paralel & angkatan batch</p>
                </div>
            </div>

            <!-- TAB NAVIGATION (ALL / INTERNAL / VENDOR) -->
            <div class="border-b border-gray-200 flex gap-4">
                <button type="button" 
                        @click="tab = 'all'" 
                        :class="tab === 'all' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                        class="pb-3 px-1 border-b-2 text-sm transition-all flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full" :class="tab === 'all' ? 'bg-blue-600' : 'bg-gray-300'"></span>
                    Semua Master Course ({{ $masterCourses->count() + $vendorCourses->count() }})
                </button>

                <button type="button" 
                        @click="tab = 'internal'" 
                        :class="tab === 'internal' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                        class="pb-3 px-1 border-b-2 text-sm transition-all flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full" :class="tab === 'internal' ? 'bg-blue-500' : 'bg-gray-300'"></span>
                    🏛️ Internal Kampus ({{ $masterCourses->count() }})
                </button>

                <button type="button" 
                        @click="tab = 'vendor'" 
                        :class="tab === 'vendor' ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                        class="pb-3 px-1 border-b-2 text-sm transition-all flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full" :class="tab === 'vendor' ? 'bg-purple-600' : 'bg-gray-300'"></span>
                    🏢 Sertifikasi Vendor ({{ $vendorCourses->count() }})
                </button>
            </div>

            <!-- UNIFIED CATALOG TABLE CARD -->
            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <th class="px-5 py-4">Tipe Provider</th>
                                <th class="px-5 py-4">Kode / Batch</th>
                                <th class="px-5 py-4">Nama Master Course / Sertifikasi</th>
                                <th class="px-5 py-4">Level</th>
                                <th class="px-5 py-4">Kategori</th>
                                <th class="px-5 py-4">Kelas / Batches</th>
                                <th class="px-5 py-4 text-right">Aksi Utama</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">

                            <!-- 1. ACADEMIC INTERNAL MASTER COURSES -->
                            @foreach ($masterCourses as $mc)
                                <tr x-show="tab === 'all' || tab === 'internal'" class="hover:bg-gray-50 transition">
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 border border-blue-100 rounded-full text-xs font-bold">
                                            🏛️ Internal Kampus
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200">
                                            {{ $mc->code ?? 'MC-' . $mc->id }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <a href="{{ route('admin.master-courses.show', $mc) }}" class="font-extrabold text-gray-900 hover:text-blue-600 transition">
                                            {{ $mc->name }}
                                        </a>
                                        @if($mc->description)
                                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">
                                                {{ $mc->description }}
                                            </p>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        @php
                                            $levelBadges = [
                                                'Beginner' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'Intermediate' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'Advanced' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            ];
                                            $badgeClass = $levelBadges[$mc->level] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $badgeClass }}">
                                            {{ $mc->level }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-md">
                                            {{ $mc->category->name ?? 'Umum' }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-sky-50 text-sky-800 text-xs font-bold rounded-full">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                                            {{ $mc->offerings_count }} Kelas
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('admin.master-courses.show', $mc) }}" 
                                           class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                                            Buka Gerbang Matkul
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                            <!-- 2. VENDOR CERTIFICATION MASTER COURSES -->
                            @foreach ($vendorCourses as $vc)
                                <tr x-show="tab === 'all' || tab === 'vendor'" class="hover:bg-purple-50/30 transition bg-purple-50/10">
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-800 border border-purple-200 rounded-full text-xs font-bold">
                                            🏢 Mitra Vendor ({{ $vc->user->name ?? 'External' }})
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="font-extrabold text-xs text-purple-900 bg-purple-100 px-2.5 py-1 rounded-md border border-purple-200">
                                            {{ $vc->batch_name ?? 'Batch 1 - 2026' }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <a href="{{ route('vendor.courses.show', $vc) }}" class="font-extrabold text-purple-950 hover:text-purple-700 transition">
                                            {{ $vc->name }}
                                        </a>
                                        @if($vc->description)
                                            <p class="text-xs text-purple-800 mt-0.5 line-clamp-1">
                                                {{ $vc->description }}
                                            </p>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        @php
                                            $levelBadges = [
                                                'Beginner' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'Intermediate' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'Advanced' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            ];
                                            $badgeClass = $levelBadges[$vc->level] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $badgeClass }}">
                                            {{ $vc->level }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-md">
                                            {{ $vc->category->name ?? 'Sertifikasi Vendor' }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-800 text-xs font-bold rounded-full border border-emerald-100">
                                            Threshold: {{ $vc->certificate_threshold ?? 75 }}%
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('vendor.courses.show', $vc) }}" 
                                           class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="m10 15 5-3-5-3v6z"/></svg>
                                            Inspeksi Vendor
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                            @if ($masterCourses->isEmpty() && $vendorCourses->isEmpty())
                                <tr>
                                    <td colspan="7" class="px-5 py-12 text-center text-gray-400 font-medium">
                                        Belum ada Master Course atau Course Sertifikasi Vendor terdaftar.
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
