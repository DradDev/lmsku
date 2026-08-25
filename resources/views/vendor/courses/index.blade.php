<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.vendor-courses-wrap {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #0f172a;
    padding-bottom: 3.5rem;
}

.compro-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    transition: box-shadow 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
}

.compro-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
    transform: translateY(-2px);
}
</style>

<div class="vendor-courses-wrap max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6" 
     x-data="{ tab: 'active', search: '' }">

    {{-- ALERT NOTIFICATIONS --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 text-xs font-bold shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- TOP HEADER CARD --}}
    <div class="compro-card p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="text-[11px] font-extrabold uppercase tracking-wider text-purple-700 mb-1">
                Portal Mitra Vendor • COMPRO LMS
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                Kursus Industri & Pelatihan Vendor
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Kelola kurikulum industri, batch pelatihan sertifikasi, dan evaluasi mahasiswa peserta.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('vendor.courses.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold rounded-xl shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                + Buat Kursus Industri
            </a>
        </div>
    </div>

    {{-- FILTER TABS & SEARCH BAR --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-3">
        <div class="flex items-center gap-2">
            <button type="button" 
                    @click="tab = 'active'" 
                    :class="tab === 'active' ? 'bg-purple-700 text-white font-bold shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 font-semibold border border-slate-200'"
                    class="px-3.5 py-1.5 rounded-xl text-xs transition flex items-center gap-2 whitespace-nowrap">
                <span>Kursus Aktif</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="tab === 'active' ? 'bg-purple-900 text-white' : 'bg-slate-100 text-slate-700'">
                    {{ $activeMasterCourses->count() }}
                </span>
            </button>

            @if($archivedMasterCourses->isNotEmpty())
                <button type="button" 
                        @click="tab = 'archived'" 
                        :class="tab === 'archived' ? 'bg-purple-700 text-white font-bold shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 font-semibold border border-slate-200'"
                        class="px-3.5 py-1.5 rounded-xl text-xs transition flex items-center gap-2 whitespace-nowrap">
                    <span>Arsip</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="tab === 'archived' ? 'bg-purple-900 text-white' : 'bg-slate-100 text-slate-700'">
                        {{ $archivedMasterCourses->count() }}
                    </span>
                </button>
            @endif
        </div>

        <div class="relative w-full sm:w-72">
            <input type="text"
                   x-model="search"
                   placeholder="Cari kursus industri, kode..."
                   class="w-full pl-9 pr-3.5 py-1.5 rounded-xl border-slate-300 focus:border-purple-600 focus:ring-purple-600 text-xs shadow-2xs bg-white">
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            </svg>
        </div>
    </div>

    {{-- CARD-GRID KURSUS INDUSTRI (2 KOLOM MODERN) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse ($masterCourses as $mc)
            @php
                $isArchived = $mc->courses->isNotEmpty() && $mc->courses->every(fn($c) => (bool)$c->is_archived);
                $tabCategory = $isArchived ? 'archived' : 'active';
                $searchHaystack = strtolower($mc->name . ' ' . ($mc->code ?? '') . ' ' . ($mc->description ?? ''));
                $totalBatches = $mc->courses ? $mc->courses->count() : 0;
                $totalStudents = $mc->courses ? $mc->courses->sum(fn($c) => $c->enrollments ? $c->enrollments->count() : 0) : 0;
                $firstCourseId = $mc->courses->first()?->id ?? $mc->id;
            @endphp
            <div x-show="(tab === '{{ $tabCategory }}') && (search === '' || '{{ addslashes($searchHaystack) }}'.includes(search.toLowerCase()))"
                 class="compro-card p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="px-2.5 py-0.5 rounded-md bg-purple-50 text-purple-700 border border-purple-100 text-[11px] font-extrabold">
                            Sertifikasi Industri
                        </span>
                        <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-bold font-mono">
                            {{ $mc->code ?? 'VMC-' . $mc->id }}
                        </span>
                    </div>

                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900 leading-snug">
                            {{ $mc->name }}
                        </h2>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                            {{ $mc->description ?: 'Program pelatihan sertifikasi industri mitra vendor.' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-xl">
                            <div class="text-[10.5px] font-bold uppercase text-slate-400">Batch Pelatihan</div>
                            <div class="text-base font-extrabold text-purple-700 mt-0.5 font-mono">
                                {{ $totalBatches }} Batch
                            </div>
                        </div>
                        <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-xl">
                            <div class="text-[10.5px] font-bold uppercase text-slate-400">Total Peserta</div>
                            <div class="text-base font-extrabold text-slate-900 mt-0.5 font-mono">
                                {{ $totalStudents }} Peserta
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                    <a href="{{ route('vendor.courses.show', $firstCourseId) }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold rounded-xl shadow-xs transition">
                        <span>Kelola Kursus & Batch</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>

                    <a href="{{ route('vendor.courses.edit', $firstCourseId) }}" 
                       class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                        Edit
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-2 compro-card p-12 text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-800">Belum Ada Kursus Industri</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto">
                    Buat program pelatihan atau kursus sertifikasi industri baru untuk mahasiswa.
                </p>
            </div>
        @endforelse
    </div>

</div>
</x-app-layout>