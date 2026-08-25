<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.material-index-wrap {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #0f172a;
    padding-bottom: 3.5rem;
}

.compro-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}

.item-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 1rem 1.25rem;
    transition: all 0.2s ease;
}

.item-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
}
</style>

<div class="material-index-wrap max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    {{-- ALERT NOTIFICATIONS --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 text-xs font-bold shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- TOP BAR --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ route('lecturer.dashboard') }}"
               class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-900 transition mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali ke Dashboard</span>
            </a>
            <div class="text-[11px] font-extrabold uppercase tracking-wider text-blue-600 mb-0.5">
                Portal Dosen • Repositori Silabus
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                Pustaka & Daftar Materi Pembelajaran
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Kelola seluruh modul silabus induk kurikulum dan materi pengayaan khusus rombel kelas yang Anda ampu.
            </p>
        </div>

        <div class="flex items-center gap-3">
            @if($offerings->isNotEmpty())
                <a href="{{ route('lecturer.materials.create', $offerings->first()->id) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                    + Upload Materi Baru
                </a>
            @endif
        </div>
    </div>

    {{-- FILTER TABS & SEARCH BAR --}}
    <div class="compro-card p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('lecturer.materials.index') }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ !request()->has('filter') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua Materi
            </a>
            <a href="{{ route('lecturer.materials.index', ['filter' => 'master']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ request()->input('filter') === 'master' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                🌐 Materi Induk Kurikulum
            </a>
            <a href="{{ route('lecturer.materials.index', ['filter' => 'class']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ request()->input('filter') === 'class' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                🎯 Materi Khusus Rombel
            </a>
        </div>

        <form method="GET" action="{{ route('lecturer.materials.index') }}" class="flex items-center gap-2">
            @if(request()->has('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
            @endif
            <input type="text" 
                   name="q" 
                   value="{{ request('q') }}" 
                   placeholder="Cari judul materi..." 
                   class="rounded-xl border border-slate-300 px-3 py-1.5 text-xs text-slate-800 placeholder:text-slate-400 focus:border-blue-600 focus:ring-blue-600 w-full sm:w-60">
            <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition">
                Cari
            </button>
        </form>
    </div>

    {{-- LIST OF MATERIALS --}}
    <div class="space-y-3">
        @forelse($materials as $material)
            @php
                $isMaster = ($material->materialable_type === \App\Models\MasterCourse::class);
                $parent = $material->materialable;
                $courseTitle = $isMaster ? ($parent->name ?? 'Mata Kuliah') : ($parent->masterCourse->name ?? ($parent->name ?? 'Mata Kuliah'));
                $sectionName = !$isMaster ? ($parent->section_name ?? null) : null;
            @endphp
            <div class="item-card flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start gap-3.5 min-w-0">
                    <div class="w-10 h-10 rounded-xl {{ $isMaster ? 'bg-blue-50 text-blue-600' : 'bg-indigo-50 text-indigo-600' }} flex items-center justify-center font-bold text-sm shrink-0 mt-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>

                    <div class="min-w-0 space-y-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="text-sm font-bold text-slate-900 truncate">{{ $material->title }}</h3>
                            @if($isMaster)
                                <span class="px-2 py-0.2 rounded-md bg-blue-50 text-blue-700 border border-blue-100 text-[10.5px] font-extrabold">
                                    🌐 Materi Induk Kurikulum
                                </span>
                            @else
                                <span class="px-2 py-0.2 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100 text-[10.5px] font-extrabold">
                                    🎯 Khusus {{ $sectionName ?: 'Rombel Ini' }}
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
                            <span class="font-semibold text-slate-700">{{ $courseTitle }}</span>
                            <span>•</span>
                            <span>Diunggah: {{ optional($material->created_at)->format('d M Y, H:i') ?: '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0 self-start sm:self-center">
                    <a href="{{ route('lecturer.materials.show', $material->id) }}" 
                       class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                        Lihat Modul
                    </a>

                    <a href="{{ route('lecturer.materials.edit', $material->id) }}" 
                       class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold rounded-xl transition">
                        Edit
                    </a>

                    <form action="{{ route('lecturer.materials.destroy', $material->id) }}" 
                          method="POST" 
                          onsubmit="return confirm('{{ $isMaster ? 'PERINGATAN: Materi ini adalah Materi Induk Kurikulum. Menghapusnya akan menghapus materi dari SELURUH rombel kelas. Lanjutkan?' : 'Hapus materi khusus rombel ini?' }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold rounded-xl transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="compro-card p-12 text-center text-xs text-slate-400 space-y-2">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="font-bold text-slate-600">Tidak ada materi pembelajaran yang ditemukan.</div>
                <p>Silakan klik tombol "+ Upload Materi Baru" di atas untuk menambahkan materi ke kelas Anda.</p>
            </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    @if($materials->hasPages())
        <div class="pt-4">
            {{ $materials->links() }}
        </div>
    @endif

</div>
</x-app-layout>