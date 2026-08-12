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
                    Katalog Master Course & Sertifikasi (Pusat Pengelolaan)
                </h2>
                <p class="text-sm text-gray-500">
                    Pusat pengelolaan kurikulum induk, pembukaan kelas rombel per-semester, dan sertifikasi industri.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6" x-data="{ tab: 'all', search: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- SUCCESS / ERROR ALERTS -->
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

            <!-- HERO HEADER & SEMESTER SELECTOR BAR -->
            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-5 md:p-6 space-y-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-widest rounded-md bg-blue-50 text-blue-700 border border-blue-100">
                                Pusat Katalog Master Course
                            </span>
                            @if($selectedTerm?->is_active)
                                <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-widest rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    🟢 Semester Berjalan (Aktif)
                                </span>
                            @endif
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">
                            Kelola Master Course, Penawaran Rombel & Dosen Pengampu
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Pilih semester di sebelah kanan untuk meninjau rombel yang aktif dibuka pada semester tersebut.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <div class="relative">
                            <select onchange="window.location.href='?term_id=' + this.value" 
                                    class="w-full appearance-none bg-slate-50 border border-slate-300 focus:border-blue-500 focus:ring-blue-500 font-extrabold text-slate-800 text-xs py-2.5 pl-4 pr-10 rounded-xl shadow-xs cursor-pointer">
                                @foreach($academicTerms as $term)
                                    <option value="{{ $term->id }}" @selected($selectedTerm?->id === $term->id)>
                                        {{ $term->is_active ? '🟢 [AKTIF]' : '⚪ [NON-AKTIF]' }} {{ $term->name }} ({{ $term->academic_year ?? 'Akademik' }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                            </div>
                        </div>

                        <a href="{{ route('admin.master-courses.create') }}" 
                           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition whitespace-nowrap">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                            + Master Course Baru
                        </a>
                    </div>
                </div>
            </div>

            <!-- EXECUTIVE METRICS CARDS -->
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
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">📌 Rombel Dibuka di {{ $selectedTerm->name ?? 'Semester' }}</span>
                    <div class="flex items-baseline justify-between pt-1">
                        <h3 class="text-3xl font-extrabold text-emerald-600">{{ $masterCourses->sum(fn($mc) => $mc->offerings->count()) }}</h3>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700">Aktif</span>
                    </div>
                    <p class="text-[11px] text-emerald-600">Rombel paralel semester ini</p>
                </div>
            </div>

            <!-- CONTROLS ROW: SEARCH INPUT & TABS -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-200 pb-4">
                <!-- TAB NAVIGATION -->
                <div class="flex gap-2 overflow-x-auto pb-1">
                    <button type="button" 
                            @click="tab = 'all'" 
                            :class="tab === 'all' ? 'bg-blue-600 text-white font-bold shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100 font-semibold border border-gray-200'"
                            class="px-4 py-2 rounded-xl text-xs transition flex items-center gap-2 whitespace-nowrap">
                        <span>🌐 Semua Course</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px]" :class="tab === 'all' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700'">
                            {{ $masterCourses->count() + $vendorCourses->count() }}
                        </span>
                    </button>

                    <button type="button" 
                            @click="tab = 'internal'" 
                            :class="tab === 'internal' ? 'bg-blue-600 text-white font-bold shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100 font-semibold border border-gray-200'"
                            class="px-4 py-2 rounded-xl text-xs transition flex items-center gap-2 whitespace-nowrap">
                        <span>🏛️ Internal Kampus</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px]" :class="tab === 'internal' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700'">
                            {{ $masterCourses->count() }}
                        </span>
                    </button>

                    <button type="button" 
                            @click="tab = 'vendor'" 
                            :class="tab === 'vendor' ? 'bg-purple-700 text-white font-bold shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100 font-semibold border border-gray-200'"
                            class="px-4 py-2 rounded-xl text-xs transition flex items-center gap-2 whitespace-nowrap">
                        <span>🏢 Sertifikasi Vendor</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px]" :class="tab === 'vendor' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700'">
                            {{ $vendorCourses->count() }}
                        </span>
                    </button>
                </div>

                <!-- LIVE SEARCH INPUT BOX -->
                <div class="relative w-full sm:w-80">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                    </div>

                    <input type="text"
                           x-model="search"
                           placeholder="Ketik cari nama course, kode, vendor..."
                           class="w-full pl-10 pr-4 py-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs shadow-sm bg-white">
                </div>
            </div>

            <!-- CARDS GRID LAYOUT -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- 1. ACADEMIC INTERNAL MASTER COURSES CARDS -->
                @foreach ($masterCourses as $mc)
                    @php
                        $searchHaystack = strtolower($mc->name . ' ' . ($mc->code ?? '') . ' ' . ($mc->description ?? '') . ' ' . ($mc->category->name ?? ''));
                        $levelBadges = [
                            'Beginner' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'Intermediate' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'Advanced' => 'bg-rose-50 text-rose-700 border-rose-200',
                        ];
                        $badgeClass = $levelBadges[$mc->level] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                        $termOfferingsCount = $mc->offerings->count();
                    @endphp

                    <div x-show="(tab === 'all' || tab === 'internal') && (search === '' || '{{ addslashes($searchHaystack) }}'.includes(search.toLowerCase()))"
                         class="bg-white border {{ $termOfferingsCount > 0 ? 'border-teal-200 hover:border-teal-400' : 'border-gray-200 hover:border-blue-300' }} rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                        <div>
                            <!-- Header Badges Row -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 border border-blue-100 rounded-full text-xs font-bold">
                                    🏛️ Internal Kampus
                                </span>

                                <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200">
                                    {{ $mc->code ?? 'MC-' . $mc->id }}
                                </span>
                            </div>

                            <!-- Course Title & Description -->
                            <h3 class="font-extrabold text-base text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug">
                                <a href="{{ route('admin.master-courses.show', [$mc, 'term_id' => $selectedTerm->id]) }}">
                                    {{ $mc->name }}
                                </a>
                            </h3>

                            @if($mc->description)
                                <p class="text-xs text-gray-500 mt-2 line-clamp-2 leading-relaxed">
                                    {{ $mc->description }}
                                </p>
                            @endif

                            <!-- Semester Offering Status Badge -->
                            <div class="mt-3">
                                @if($termOfferingsCount > 0)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-extrabold rounded-lg">
                                        🟢 Dibuka di {{ $selectedTerm->name ?? 'Semester Ini' }} ({{ $termOfferingsCount }} Rombel)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 text-slate-500 border border-slate-200 text-xs font-bold rounded-lg">
                                        ⚪ Belum Dibuka di {{ $selectedTerm->name ?? 'Semester Ini' }}
                                    </span>
                                @endif
                            </div>

                            <!-- Meta Info Badges Row -->
                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $badgeClass }}">
                                    {{ $mc->level }}
                                </span>

                                <span class="inline-flex items-center px-2.5 py-0.5 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-md border border-indigo-100">
                                    📁 {{ $mc->category->name ?? 'Umum' }}
                                </span>
                            </div>

                            <!-- Skills & Tags Preview Chips -->
                            @if($mc->skills->count() > 0 || $mc->tags->count() > 0)
                                <div class="mt-4 pt-3 border-t border-gray-100 flex flex-wrap gap-1.5">
                                    @foreach($mc->skills as $sk)
                                        <span class="px-2 py-0.5 bg-amber-50 text-amber-800 border border-amber-200 text-[11px] font-bold rounded-md">
                                            ⚡ {{ $sk->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Card Footer Action Buttons -->
                        <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between gap-2">
                            <span class="text-[11px] text-gray-400 font-semibold">
                                {{ $mc->materials->count() }} Materi • {{ $mc->quizzes->count() }} Kuis
                            </span>

                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.master-courses.show', [$mc, 'term_id' => $selectedTerm->id]) }}" 
                                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                    <span>📂 Kelola Rombel & Dosen</span>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- 2. VENDOR CERTIFICATION COURSES CARDS -->
                @foreach ($vendorCourses as $vc)
                    @php
                        $vendorName = $vc->user->name ?? 'Mitra Vendor';
                        $searchHaystack = strtolower($vc->name . ' ' . ($vc->batch_name ?? '') . ' ' . $vendorName . ' ' . ($vc->category->name ?? ''));
                        $levelBadges = [
                            'Beginner' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'Intermediate' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'Advanced' => 'bg-rose-50 text-rose-700 border-rose-200',
                        ];
                        $badgeClass = $levelBadges[$vc->level] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                    @endphp

                    <div x-show="(tab === 'all' || tab === 'vendor') && (search === '' || '{{ addslashes($searchHaystack) }}'.includes(search.toLowerCase()))"
                         class="bg-white border border-purple-200 hover:border-purple-400 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                        <div>
                            <!-- Header Badges Row -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-700 border border-purple-200 rounded-full text-xs font-bold">
                                    🏢 Sertifikasi Vendor
                                </span>

                                <span class="font-mono text-xs font-bold text-purple-900 bg-purple-100 px-2.5 py-1 rounded-md border border-purple-200">
                                    🏷️ {{ $vc->batch_name ?? 'Batch 1' }}
                                </span>
                            </div>

                            <!-- Course Title & Vendor Name -->
                            <h3 class="font-extrabold text-base text-gray-900 group-hover:text-purple-700 transition-colors line-clamp-2 leading-snug">
                                <a href="{{ route('vendor.courses.show', $vc) }}">
                                    {{ $vc->name }}
                                </a>
                            </h3>

                            <p class="text-xs font-bold text-purple-700 mt-1 flex items-center gap-1">
                                <span>🏢 {{ $vendorName }}</span>
                            </p>

                            @if($vc->description)
                                <p class="text-xs text-gray-500 mt-2 line-clamp-2 leading-relaxed">
                                    {{ $vc->description }}
                                </p>
                            @endif

                            <!-- Meta Info Badges Row -->
                            <div class="mt-4 flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $badgeClass }}">
                                    {{ $vc->level }}
                                </span>

                                <span class="inline-flex items-center px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-md border border-emerald-200">
                                    Threshold {{ $vc->certificate_threshold ?? 75 }}%
                                </span>
                            </div>
                        </div>

                        <!-- Card Footer Action Buttons -->
                        <div class="mt-5 pt-4 border-t border-purple-100 flex items-center justify-between gap-2">
                            <span class="text-[11px] text-purple-600 font-bold">
                                {{ $vc->materials->count() }} Materi • {{ $vc->quizzes->count() }} Kuis
                            </span>

                            <a href="{{ route('vendor.courses.show', $vc) }}" 
                               class="inline-flex items-center gap-1.5 px-3 py-2 bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                <span>Detail Vendor</span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>

        </div>
    </div>
</x-app-layout>
