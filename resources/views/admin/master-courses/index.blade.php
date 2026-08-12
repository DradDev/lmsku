<x-app-layout>
    <div class="py-8 bg-slate-50/50 min-h-screen" x-data="{ tab: 'all', search: '' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- PAGE HERO HEADER CARD -->
            <div class="bg-white border border-slate-200/80 shadow-xs rounded-2xl p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold shadow-xs flex-shrink-0">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                            </svg>
                        </div>

                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-700 bg-blue-50 border border-blue-100 px-2.5 py-0.5 rounded-md">
                                Pustaka Kurikulum Induk
                            </span>
                            <h1 class="text-xl font-bold text-slate-900 tracking-tight mt-0.5">
                                Katalog Master Course & Sertifikasi Vendor
                            </h1>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Pengelolaan templat mata kuliah kurikulum kampus dan program sertifikasi mitra industri.
                            </p>
                        </div>
                    </div>

                    <!-- PROMINENT ADD NEW MASTER COURSE BUTTON -->
                    <div>
                        <a href="{{ route('admin.master-courses.create') }}" 
                           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs font-bold rounded-xl shadow-xs transition whitespace-nowrap">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                            <span>+ Master Course Baru</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- SUCCESS / ERROR ALERTS -->
            @if (session('success'))
                <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold shadow-xs">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-emerald-600 flex-shrink-0"><path d="M20 6 9 17l-5-5"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-center gap-3 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-semibold shadow-xs">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-rose-600 flex-shrink-0"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- EXECUTIVE METRICS CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col justify-between space-y-3">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Pustaka Kurikulum</span>
                    <div class="flex items-baseline justify-between">
                        <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $masterCourses->count() + $vendorCourses->count() }}</span>
                        <span class="text-[11px] font-bold text-slate-700 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-md">Katalog LMS</span>
                    </div>
                </div>

                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col justify-between space-y-3">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Internal Kampus</span>
                    <div class="flex items-baseline justify-between">
                        <span class="text-3xl font-extrabold text-blue-700 tracking-tight">{{ $masterCourses->count() }}</span>
                        <span class="text-[11px] font-bold text-blue-700 bg-blue-50 border border-blue-100 px-2 py-0.5 rounded-md">Akademik</span>
                    </div>
                </div>

                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col justify-between space-y-3">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Mitra Vendor (Sertifikasi)</span>
                    <div class="flex items-baseline justify-between">
                        <span class="text-3xl font-extrabold text-purple-700 tracking-tight">{{ $vendorCourses->count() }}</span>
                        <span class="text-[11px] font-bold text-purple-700 bg-purple-50 border border-purple-100 px-2 py-0.5 rounded-md">Bootcamp</span>
                    </div>
                </div>
            </div>

            <!-- FILTER & SEARCH ROW -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <!-- TAB NAVIGATION -->
                <div class="flex gap-1.5 overflow-x-auto">
                    <button type="button" 
                            @click="tab = 'all'" 
                            :class="tab === 'all' ? 'bg-slate-900 text-white font-bold' : 'bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold'"
                            class="px-4 py-2 rounded-xl text-xs transition flex items-center gap-2 whitespace-nowrap">
                        <span>Semua Course</span>
                        <span class="px-2 py-0.5 rounded-md text-[10px]" :class="tab === 'all' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'">
                            {{ $masterCourses->count() + $vendorCourses->count() }}
                        </span>
                    </button>

                    <button type="button" 
                            @click="tab = 'internal'" 
                            :class="tab === 'internal' ? 'bg-blue-600 text-white font-bold' : 'bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold'"
                            class="px-4 py-2 rounded-xl text-xs transition flex items-center gap-2 whitespace-nowrap">
                        <span>Internal Kampus</span>
                        <span class="px-2 py-0.5 rounded-md text-[10px]" :class="tab === 'internal' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'">
                            {{ $masterCourses->count() }}
                        </span>
                    </button>

                    <button type="button" 
                            @click="tab = 'vendor'" 
                            :class="tab === 'vendor' ? 'bg-purple-700 text-white font-bold' : 'bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold'"
                            class="px-4 py-2 rounded-xl text-xs transition flex items-center gap-2 whitespace-nowrap">
                        <span>Sertifikasi Vendor</span>
                        <span class="px-2 py-0.5 rounded-md text-[10px]" :class="tab === 'vendor' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'">
                            {{ $vendorCourses->count() }}
                        </span>
                    </button>
                </div>

                <!-- LIVE SEARCH INPUT -->
                <div class="relative w-full sm:w-80">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </div>

                    <input type="text"
                           x-model="search"
                           placeholder="Cari nama course, kode, vendor..."
                           class="w-full pl-9 pr-4 py-1.5 rounded-xl border-slate-200 text-xs font-medium focus:border-slate-400 focus:ring-0 shadow-2xs">
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
                        $badgeClass = $levelBadges[$mc->level] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                    @endphp

                    <div x-show="(tab === 'all' || tab === 'internal') && (search === '' || '{{ addslashes($searchHaystack) }}'.includes(search.toLowerCase()))"
                         class="bg-white border border-slate-200/80 hover:border-slate-300 rounded-2xl p-5 shadow-xs transition flex flex-col justify-between group space-y-4">
                        <div class="space-y-3">
                            <!-- Header Badges Row -->
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-700 bg-blue-50 border border-blue-100 px-2.5 py-0.5 rounded-md">
                                    Internal Kampus
                                </span>

                                <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">
                                    {{ $mc->code ?? 'MC-' . $mc->id }}
                                </span>
                            </div>

                            <!-- Course Title & Description -->
                            <h3 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug">
                                <a href="{{ route('admin.master-courses.show', $mc) }}">
                                    {{ $mc->name }}
                                </a>
                            </h3>

                            @if($mc->description)
                                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                    {{ $mc->description }}
                                </p>
                            @endif

                            <!-- Meta Info Badges Row -->
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md border {{ $badgeClass }}">
                                    {{ $mc->level }}
                                </span>

                                <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md">
                                    {{ $mc->category->name ?? 'Umum' }}
                                </span>

                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">
                                    Threshold {{ $mc->certificate_threshold ?? 75 }}%
                                </span>
                            </div>

                            <!-- Skills & Tags Preview Chips -->
                            @if($mc->skills->count() > 0 || $mc->tags->count() > 0)
                                <div class="pt-3 border-t border-slate-100 flex flex-wrap gap-1">
                                    @foreach($mc->skills as $sk)
                                        <span class="px-2 py-0.5 bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-bold rounded-md">
                                            Main: {{ $sk->name }}
                                        </span>
                                    @endforeach

                                    @foreach($mc->tags as $tg)
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 border border-slate-200 text-[10px] font-semibold rounded-md">
                                            #{{ $tg->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Card Footer Action Buttons -->
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                            <span class="text-[11px] text-slate-400 font-semibold">
                                {{ $mc->materials->count() }} Materi • {{ $mc->quizzes->count() }} Kuis
                            </span>

                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.master-courses.show', $mc) }}" 
                                   class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                                    Detail Silabus
                                </a>

                                <a href="{{ route('admin.master-courses.edit', $mc) }}" 
                                   class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-xs font-bold rounded-xl transition">
                                    Edit
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
                        $badgeClass = $levelBadges[$vc->level] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                    @endphp

                    <div x-show="(tab === 'all' || tab === 'vendor') && (search === '' || '{{ addslashes($searchHaystack) }}'.includes(search.toLowerCase()))"
                         class="bg-white border border-purple-200/80 hover:border-purple-300 rounded-2xl p-5 shadow-xs transition flex flex-col justify-between group space-y-4">
                        <div class="space-y-3">
                            <!-- Header Badges Row -->
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-purple-700 bg-purple-50 border border-purple-100 px-2.5 py-0.5 rounded-md">
                                    Sertifikasi Vendor
                                </span>

                                <span class="font-mono text-xs font-bold text-purple-900 bg-purple-50 px-2 py-0.5 rounded-md border border-purple-100">
                                    {{ $vc->batch_name ?? 'Batch 1' }}
                                </span>
                            </div>

                            <!-- Course Title & Vendor Name -->
                            <h3 class="font-bold text-sm text-slate-900 group-hover:text-purple-700 transition-colors line-clamp-2 leading-snug">
                                <a href="{{ route('admin.courses.show', $vc) }}">
                                    {{ $vc->name }}
                                </a>
                            </h3>

                            <p class="text-xs font-bold text-purple-700">
                                Provider: {{ $vendorName }}
                            </p>

                            @if($vc->description)
                                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                    {{ $vc->description }}
                                </p>
                            @endif

                            <!-- Meta Info Badges Row -->
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md border {{ $badgeClass }}">
                                    {{ $vc->level }}
                                </span>

                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">
                                    Threshold {{ $vc->certificate_threshold ?? 75 }}%
                                </span>
                            </div>
                        </div>

                        <!-- Card Footer Action Buttons -->
                        <div class="pt-3 border-t border-purple-100 flex items-center justify-between gap-2">
                            <span class="text-[11px] text-purple-600 font-semibold">
                                {{ $vc->materials->count() }} Materi • {{ $vc->quizzes->count() }} Kuis
                            </span>

                            <a href="{{ route('admin.courses.show', $vc) }}" 
                               class="px-3.5 py-1.5 bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                Detail Vendor
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>

        </div>
    </div>
</x-app-layout>
