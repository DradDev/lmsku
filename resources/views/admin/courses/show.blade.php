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

            @if (session('error'))
                <div class="flex items-start gap-3 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl shadow-sm">
                    <svg class="mt-0.5 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <div>
                        <p class="font-semibold text-sm">Peringatan</p>
                        <p class="text-xs mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- TOP NAV -->
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('admin.master-courses.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-bold text-xs rounded-xl shadow-xs transition">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    <span>Kembali ke Katalog Master Course</span>
                </a>

                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-slate-500">
                        Kode Master: <strong class="text-purple-900">{{ $course->masterCourse->code ?? 'VMC-' . $course->id }}</strong>
                    </span>
                </div>
            </div>

            <!-- HERO HEADER CARD -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                            <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-widest rounded-md bg-purple-50 text-purple-700 border border-purple-200">
                                🏢 Program Sertifikasi Mitra Vendor
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

                            <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-widest rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200">
                                {{ $course->main_skill->name ?? optional($course->category)->name ?? 'Sertifikasi Industri' }}
                            </span>
                        </div>

                        <h1 class="text-2xl font-extrabold text-slate-900 leading-tight">
                            🎓 {{ $course->name }}
                        </h1>
                        <p class="text-xs text-slate-500 mt-1">
                            Penyedia / Mitra Industri: <strong class="text-purple-700">{{ optional($course->user)->name ?? 'Mitra Vendor' }}</strong> ({{ optional($course->user)->email ?? '-' }})
                        </p>
                    </div>

                    <!-- ACTION BUTTONS: MODERATION CONTROLS -->
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($course->user?->role === 'vendor')
                            @if($course->moderation_status === 'suspended')
                                <form action="{{ route('admin.courses.approve', $course) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                                        <span>✅ Pulihkan Course (Aktifkan)</span>
                                    </button>
                                </form>
                            @else
                                <button type="button" 
                                        @click="showSuspendModal = true"
                                        class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white font-extrabold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                    <span>⛔ Suspend (Bekukan)</span>
                                </button>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- METRICS STRIP -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-5 border-t border-slate-100 text-xs">
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                        <span class="block text-slate-500 font-semibold text-[11px]">Total Angkatan Batch</span>
                        <strong class="text-purple-900 font-extrabold text-base mt-0.5 block">{{ $allBatches->count() }} Batch</strong>
                    </div>

                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                        <span class="block text-slate-500 font-semibold text-[11px]">Peserta Batch Ini ({{ $course->batch_name }})</span>
                        <strong class="text-slate-900 font-extrabold text-base mt-0.5 block">{{ $course->enrollments->count() }} Mahasiswa</strong>
                    </div>

                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                        <span class="block text-slate-500 font-semibold text-[11px]">Mahasiswa Lulus (Completed)</span>
                        <strong class="text-emerald-600 font-extrabold text-base mt-0.5 block">{{ $completedCount }} Mahasiswa</strong>
                    </div>

                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                        <span class="block text-slate-500 font-semibold text-[11px]">Threshold Kelulusan</span>
                        <strong class="text-indigo-600 font-extrabold text-base mt-0.5 block">{{ $course->certificate_threshold ?? 75 }}%</strong>
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

            <!-- SECTION 1: DAFTAR SELURUH ANGKATAN BATCH -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">
                            🏷️ Daftar Seluruh Angkatan Batch Terdaftar
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Daftar seluruh intake cohort yang dibuka oleh mitra vendor di bawah kurikulum ini.
                        </p>
                    </div>
                    <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs font-extrabold rounded-full">
                        {{ $allBatches->count() }} Angkatan
                    </span>
                </div>

                <div class="overflow-x-auto border border-slate-200 rounded-xl">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 uppercase text-[10.5px]">
                            <tr>
                                <th class="p-3">Nama Angkatan Batch</th>
                                <th class="p-3">Status Pendaftaran</th>
                                <th class="p-3">Durasi & Periode</th>
                                <th class="p-3">Threshold Sertifikat</th>
                                <th class="p-3">Total Peserta</th>
                                <th class="p-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($allBatches as $batchItem)
                                @php
                                    $isCurrent = $batchItem->id === $course->id;
                                    $bStdCount = $batchItem->enrollments ? $batchItem->enrollments->count() : 0;
                                @endphp
                                <tr class="{{ $isCurrent ? 'bg-purple-50/60 font-semibold' : 'hover:bg-slate-50' }}">
                                    <td class="p-3">
                                        <div class="flex items-center gap-2">
                                            <span class="font-extrabold text-slate-900">{{ $batchItem->batch_name ?: 'Batch ' . $loop->iteration }}</span>
                                            @if($isCurrent)
                                                <span class="px-2 py-0.5 text-[9.5px] font-extrabold bg-purple-600 text-white rounded-md">
                                                    Sedang Ditampilkan
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-3">
                                        @if(!$batchItem->is_archived)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                ✓ Terbuka (Aktif)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                                📦 Draft / Arsip
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-slate-600">
                                        <span>{{ $batchItem->duration_weeks ?? 4 }} Minggu</span>
                                        @if($batchItem->start_date || $batchItem->end_date)
                                            <span class="text-[10.5px] text-slate-400 block">
                                                {{ $batchItem->start_date ? \Carbon\Carbon::parse($batchItem->start_date)->format('d M Y') : '-' }} &bull; {{ $batchItem->end_date ? \Carbon\Carbon::parse($batchItem->end_date)->format('d M Y') : 'Selesai' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-3 font-extrabold text-purple-900">
                                        {{ $batchItem->certificate_threshold ?? 75 }}%
                                    </td>
                                    <td class="p-3">
                                        <span class="font-bold text-slate-800">{{ $bStdCount }} Mahasiswa</span>
                                    </td>
                                    <td class="p-3 text-right">
                                        @if(!$isCurrent)
                                            <a href="{{ route('admin.courses.show', $batchItem->id) }}" 
                                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 font-bold rounded-lg border border-purple-200 transition text-[11px]">
                                                👥 Lihat Mahasiswa Batch Ini
                                            </a>
                                        @else
                                            <span class="text-[11px] font-bold text-purple-600">
                                                ✓ Tampil di Bawah
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-slate-400 italic">Belum ada angkatan batch terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SECTION 2: DAFTAR MAHASISWA YANG MENGAMBIL BATCH INI -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">
                            🎓 Mahasiswa Terdaftar pada Angkatan: <span class="text-purple-700">{{ $course->batch_name }}</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Daftar riwayat peserta, tanggal pendaftaran, progres materi, dan status kelulusan sertifikasi.
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold">
                            Total: {{ $course->enrollments->count() }} Mahasiswa
                        </span>
                        <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                            Lulus: {{ $completedCount }}
                        </span>
                    </div>
                </div>

                @if($course->enrollments->count() > 0)
                    <div class="overflow-x-auto border border-slate-200 rounded-xl">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 uppercase text-[10.5px]">
                                <tr>
                                    <th class="p-3">Mahasiswa</th>
                                    <th class="p-3">Tanggal Terdaftar</th>
                                    <th class="p-3">Status Belajar</th>
                                    <th class="p-3">Progres Belajar</th>
                                    <th class="p-3 text-right">Kelulusan Sertifikat</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($course->enrollments as $enr)
                                    @php
                                        $std = $enr->user;
                                        $prog = $enr->progress_percent ?? 0;
                                        $threshold = $course->certificate_threshold ?? 75;
                                        $isPassed = $prog >= $threshold;
                                    @endphp
                                    <tr class="hover:bg-slate-50">
                                        <td class="p-3">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-800 font-bold flex items-center justify-center text-xs flex-shrink-0">
                                                    {{ strtoupper(substr($std->name ?? 'M', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <span class="font-bold text-slate-900 block">{{ $std->name ?? 'Unknown Student' }}</span>
                                                    <span class="text-[11px] text-slate-400">{{ $std->email ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-3 text-slate-600">
                                            {{ $enr->created_at ? $enr->created_at->format('d M Y H:i') : '-' }}
                                        </td>
                                        <td class="p-3">
                                            @if($enr->status === 'completed')
                                                <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-100 text-emerald-800">
                                                    Completed
                                                </span>
                                            @elseif($enr->status === 'in_progress')
                                                <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-purple-100 text-purple-800">
                                                    In Progress
                                                </span>
                                            @else
                                                <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-slate-200 text-slate-700">
                                                    Not Started
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-3">
                                            <div class="w-36">
                                                <div class="flex items-center justify-between text-[10.5px] font-bold mb-1">
                                                    <span class="{{ $isPassed ? 'text-emerald-700' : 'text-slate-600' }}">{{ $prog }}%</span>
                                                    <span class="text-slate-400 font-normal">Min: {{ $threshold }}%</span>
                                                </div>
                                                <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                                    <div class="h-1.5 rounded-full {{ $isPassed ? 'bg-emerald-500' : 'bg-purple-600' }}" style="width: {{ min(100, max(0, $prog)) }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-3 text-right">
                                            @if($isPassed)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold text-[11px]">
                                                    <span>🏅</span> Memenuhi Syarat
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-500 font-semibold text-[11px]">
                                                    Belum Memenuhi
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-500">
                        Belum ada mahasiswa yang mendaftar pada angkatan <strong>{{ $course->batch_name }}</strong> ini.
                    </div>
                @endif
            </div>

            <!-- SECTION 3: SILABUS MATERI & BANK KUIS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- MODUL MATERI -->
                <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-extrabold text-slate-900">
                            📚 Modul Materi Kurikulum ({{ $course->materials->count() }})
                        </h3>
                    </div>
                    <div class="space-y-2">
                        @forelse($course->materials as $mat)
                            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">
                                        📄
                                    </span>
                                    <div>
                                        <span class="font-bold text-slate-800 block">{{ $mat->title }}</span>
                                        <span class="text-[10.5px] text-slate-400">{{ optional($mat->created_at)->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic">Belum ada modul materi pembelajaran.</p>
                        @endforelse
                    </div>
                </div>

                <!-- BANK KUIS -->
                <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-extrabold text-slate-900">
                            📝 Bank Kuis & Evaluasi ({{ $course->quizzes->count() }})
                        </h3>
                    </div>
                    <div class="space-y-2">
                        @forelse($course->quizzes as $qz)
                            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between text-xs">
                                <div>
                                    <span class="font-bold text-slate-800 block">{{ $qz->title }}</span>
                                    <span class="text-[10.5px] text-slate-500">
                                        Tipe: <strong>{{ ucfirst($qz->quiz_type ?? 'daily') }}</strong> &bull; Durasi: <strong>{{ $qz->time_limit ? $qz->time_limit . ' mnt' : 'Unlimited' }}</strong> &bull; Soal: <strong>{{ $qz->questions ? $qz->questions->count() : 0 }} butir</strong>
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic">Belum ada kuis evaluasi.</p>
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
