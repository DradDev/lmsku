<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.admin-courses-wrap {
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

<div class="admin-courses-wrap max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6" 
     x-data="{ tab: 'all', search: '' }">

    {{-- ALERT NOTIFICATIONS --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 text-xs font-bold shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl flex items-center gap-3 text-xs font-bold shadow-xs">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- TOP HEADER CARD --}}
    <div class="compro-card p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="text-[11px] font-extrabold uppercase tracking-wider text-blue-600 mb-1">
                Portal Admin • COMPRO LMS
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                Master Courses & Kurikulum Akademik
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Pusat pengelolaan mata kuliah induk kurikulum kampus dan katalog program pelatihan mitra vendor.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.master-courses.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                + Master Course Baru
            </a>
        </div>
    </div>

    {{-- FILTER TABS & SEARCH BAR --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-3">
        <div class="flex items-center gap-2 overflow-x-auto pb-1">
            <button type="button" 
                    @click="tab = 'all'" 
                    :class="tab === 'all' ? 'bg-blue-600 text-white font-bold shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 font-semibold border border-slate-200'"
                    class="px-3.5 py-1.5 rounded-xl text-xs transition flex items-center gap-2 whitespace-nowrap">
                <span>Semua Kurikulum</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="tab === 'all' ? 'bg-blue-800 text-white' : 'bg-slate-100 text-slate-700'">
                    {{ $masterCourses->count() + $vendorCourses->count() }}
                </span>
            </button>

            <button type="button" 
                    @click="tab = 'internal'" 
                    :class="tab === 'internal' ? 'bg-blue-600 text-white font-bold shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 font-semibold border border-slate-200'"
                    class="px-3.5 py-1.5 rounded-xl text-xs transition flex items-center gap-2 whitespace-nowrap">
                <span>Internal Kampus</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="tab === 'internal' ? 'bg-blue-800 text-white' : 'bg-slate-100 text-slate-700'">
                    {{ $masterCourses->count() }}
                </span>
            </button>

            <button type="button" 
                    @click="tab = 'vendor'" 
                    :class="tab === 'vendor' ? 'bg-purple-700 text-white font-bold shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 font-semibold border border-slate-200'"
                    class="px-3.5 py-1.5 rounded-xl text-xs transition flex items-center gap-2 whitespace-nowrap">
                <span>Mitra Vendor</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="tab === 'vendor' ? 'bg-purple-900 text-white' : 'bg-slate-100 text-slate-700'">
                    {{ $vendorCourses->count() }}
                </span>
            </button>
        </div>

        <div class="relative w-full sm:w-72">
            <input type="text"
                   x-model="search"
                   placeholder="Cari mata kuliah, kode, vendor..."
                   class="w-full pl-9 pr-3.5 py-1.5 rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs shadow-2xs bg-white">
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            </svg>
        </div>
    </div>

    {{-- CARD-GRID MATA KULIAH ADMIN (2 KOLOM MODERN, BUKAN TABEL PANJANG) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- 1. INTERNAL KAMPUS MASTER COURSES --}}
        @foreach ($masterCourses as $mc)
            @php
                $cleanDesc = preg_replace('/\s+/', ' ', $mc->description ?? '');
                $searchHaystack = strtolower($mc->name . ' ' . ($mc->code ?? '') . ' ' . $cleanDesc . ' ' . ($mc->main_skill->name ?? ''));
                $totalOfferingsCount = $mc->offerings_count ?? ($mc->offerings ? $mc->offerings->count() : 0);
            @endphp
            <div x-show="(tab === 'all' || tab === 'internal') && (search === '' || {{ json_encode($searchHaystack) }}.includes(search.toLowerCase()))"
                 class="compro-card p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-100 text-[11px] font-extrabold">
                            Internal Kampus
                        </span>
                        <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-bold font-mono">
                            {{ $mc->code ?? 'MC-' . $mc->id }}
                        </span>
                    </div>

                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900 leading-snug">
                            {{ $mc->name }}
                        </h2>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                            {{ $mc->description ?: 'Silabus kurikulum induk. Kelola penawaran rombel kelas paralel dan target kompetensi skill.' }}
                        </p>
                    </div>

                    {{-- RINGKASAN METRIK: KELAS ROMBEL & LEVEL --}}
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-xl">
                            <div class="text-[10.5px] font-bold uppercase text-slate-400">Rombel Terbuka</div>
                            <div class="text-base font-extrabold text-slate-900 mt-0.5 font-mono">
                                {{ $totalOfferingsCount }} Kelas
                            </div>
                        </div>
                        <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-xl">
                            <div class="text-[10.5px] font-bold uppercase text-slate-400">Tingkat & KKM</div>
                            <div class="text-base font-extrabold text-blue-600 mt-0.5">
                                {{ $mc->level }} • {{ $mc->certificate_threshold ?? 75 }}%
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TOMBOL AKSI MASTER COURSE --}}
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                    <a href="{{ route('admin.master-courses.show', $mc) }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                        <span>Kelola Rombel & Silabus</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>

                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.master-courses.edit', $mc) }}" 
                           class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                            Edit
                        </a>
                        <form action="{{ route('admin.master-courses.destroy', $mc) }}" method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus Master Course &quot;{{ addslashes($mc->name) }}&quot;?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold rounded-xl transition">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- 2. VENDOR MITRA MASTER COURSES --}}
        @foreach ($vendorCourses as $vc)
            @php
                $cleanDesc = preg_replace('/\s+/', ' ', $vc->description ?? '');
                $searchHaystack = strtolower($vc->name . ' ' . ($vc->code ?? '') . ' ' . $cleanDesc . ' ' . ($vc->user->name ?? ''));
                $totalBatches = $vc->courses ? $vc->courses->count() : 0;
            @endphp
            <div x-show="(tab === 'all' || tab === 'vendor') && (search === '' || {{ json_encode($searchHaystack) }}.includes(search.toLowerCase()))"
                 class="compro-card p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="px-2.5 py-0.5 rounded-md bg-purple-50 text-purple-700 border border-purple-100 text-[11px] font-extrabold">
                            Mitra: {{ $vc->user->name ?? 'Vendor' }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-bold font-mono">
                            {{ $vc->code ?? 'VMC-' . $vc->id }}
                        </span>
                    </div>

                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900 leading-snug">
                            {{ $vc->name }}
                        </h2>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                            {{ $vc->description ?: 'Program sertifikasi industri dan pelatihan profesional mitra vendor.' }}
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
                            <div class="text-[10.5px] font-bold uppercase text-slate-400">Level & KKM</div>
                            <div class="text-base font-extrabold text-slate-900 mt-0.5">
                                {{ $vc->level ?? 'Beginner' }} • {{ $vc->certificate_threshold ?? 75 }}%
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                    <a href="{{ route('admin.master-courses.show', $vc) }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold rounded-xl shadow-xs transition">
                        <span>Lihat Program Vendor</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>

                    <span class="text-[11px] font-bold text-slate-400">Sertifikasi Industri</span>
                </div>
            </div>
        @endforeach

    </div>

</div>
</x-app-layout>