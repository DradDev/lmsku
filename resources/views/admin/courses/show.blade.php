<x-app-layout>
    <div class="py-6" x-data="{ showSuspendModal: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- SUCCESS ALERTS -->
            @if (session('success'))
                <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl shadow-sm">
                    <svg class="mt-0.5 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                    <div>
                        <p class="font-semibold text-sm">Berhasil</p>
                        <p class="text-xs mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- HERO HEADER CARD -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-widest rounded-md bg-purple-50 text-purple-700 border border-purple-200">
                                🏢 Moderasi Course Sertifikasi Vendor
                            </span>

                            @if($course->moderation_status === 'suspended')
                                <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-widest rounded-md bg-rose-100 text-rose-800 border border-rose-200">
                                    ⛔ Status: Dibekukan (Suspended)
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-widest rounded-md bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    🟢 Status: Published & Aktif
                                </span>
                            @endif
                        </div>

                        <h1 class="text-2xl font-extrabold text-slate-900 leading-tight">
                            🎓 {{ $course->name }}
                        </h1>
                        <p class="text-xs text-slate-500 mt-1">
                            Penyedia / Mitra Industri: <strong class="text-purple-700">{{ optional($course->user)->name ?? 'Mitra Vendor' }}</strong> ({{ optional($course->user)->email ?? '-' }})
                        </p>
                    </div>

                    <!-- ACTION BUTTONS: ONLY SUSPEND & PULIHKAN (NO REVISE, ARCHIVE, OR DELETE) FOR VENDOR COURSES -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="{{ route('admin.master-courses.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                            ← Kembali ke Katalog
                        </a>

                        @if($course->user?->role === 'vendor')
                            <!-- VENDOR COURSE MODERATION CONTROLS -->
                            @if($course->moderation_status === 'suspended')
                                <form action="{{ route('admin.courses.approve', $course) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                                        <span>✅ Pulihkan Course (Aktifkan)</span>
                                    </button>
                                </form>
                            @else
                                <!-- SUSPEND BUTTON -->
                                <button type="button" 
                                        @click="showSuspendModal = true"
                                        class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white font-extrabold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                    <span>⛔ Suspend (Bekukan)</span>
                                </button>
                            @endif
                        @else
                            <!-- NON-VENDOR COURSES REGULAR CONTROLS -->
                            <form action="{{ route('admin.courses.toggle-archive', $course) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2.5 {{ $course->is_archived ? 'bg-emerald-600 text-white' : 'bg-amber-100 text-amber-800' }} font-bold text-xs rounded-xl transition">
                                    {{ $course->is_archived ? '🚀 Aktifkan Course' : '📦 Arsipkan Course' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- MODERATION NOTE ALERT BOX -->
            @if($course->moderation_status === 'suspended' && $course->moderation_note)
                <div class="p-4 rounded-2xl border bg-rose-50 border-rose-200 text-rose-800 shadow-xs space-y-1">
                    <div class="flex items-center gap-2 font-extrabold text-xs uppercase tracking-wider">
                        <span>⛔ Catatan Penangguhan (Suspended):</span>
                    </div>
                    <p class="text-xs font-semibold leading-relaxed">
                        "{{ $course->moderation_note }}"
                    </p>
                </div>
            @endif

            <!-- DETAILS CONTENT CARD -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6 space-y-6">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 mb-2">📋 Silabus & Deskripsi Program</h3>
                    <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line">
                        {{ $course->description }}
                    </p>
                </div>

                <!-- METRICS GRID -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 border-t border-gray-100 text-xs">
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        <span class="block text-slate-400 font-semibold text-[11px]">Level Kesulitan</span>
                        <strong class="text-slate-900 font-extrabold text-sm mt-0.5 block">{{ $course->level }}</strong>
                    </div>

                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        <span class="block text-slate-400 font-semibold text-[11px]">Passing Score Sertifikat</span>
                        <strong class="text-emerald-600 font-extrabold text-sm mt-0.5 block">{{ $course->certificate_threshold }}%</strong>
                    </div>

                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        <span class="block text-slate-400 font-semibold text-[11px]">Total Materi</span>
                        <strong class="text-blue-600 font-extrabold text-sm mt-0.5 block">{{ $course->materials->count() }} Modul</strong>
                    </div>

                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        <span class="block text-slate-400 font-semibold text-[11px]">Total Kuis / Ujian</span>
                        <strong class="text-purple-600 font-extrabold text-sm mt-0.5 block">{{ $course->quizzes->count() }} Kuis</strong>
                    </div>
                </div>

                <!-- MATERIALS LIST -->
                <div class="pt-4 border-t border-gray-100">
                    <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-3">Daftar Modul & Materi Pembelajaran:</h4>
                    <div class="space-y-2">
                        @forelse($course->materials as $mat)
                            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-[11px]">
                                        📄
                                    </span>
                                    <span class="font-bold text-slate-800">{{ $mat->title }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic">Belum ada materi pembelajaran yang diunggah mitra vendor.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        <!-- MODAL: SUSPEND (BEKUKAN COURSE VENDOR) -->
        <div x-show="showSuspendModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4"
             style="display: none;">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-md overflow-hidden" @click.away="showSuspendModal = false">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-rose-700">⛔ Bekukan (Suspend) Course Vendor</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Penangguhan sementara course dari katalog publik.</p>
                    </div>
                    <button type="button" @click="showSuspendModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>
                </div>

                <form action="{{ route('admin.courses.suspend', $course) }}" method="POST" class="p-5 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Alasan Penangguhan / Catatan Admin</label>
                        <textarea name="moderation_note" rows="3" class="w-full rounded-xl border-gray-300 focus:border-rose-500 focus:ring-rose-500 text-xs font-medium" placeholder="Jelaskan alasan pembekuan course vendor ini..."></textarea>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2">
                        <button type="button" @click="showSuspendModal = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition">⛔ Bekukan Course</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
