<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8 font-sans">
        <div class="max-w-7xl mx-auto space-y-6">

            @if(session('success'))
                <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold shadow-xs">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="flex items-center gap-3 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-bold shadow-xs">
                    <svg class="w-5 h-5 text-rose-600 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <!-- TOP NAV & HEADER -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <a href="{{ route('vendor.courses.show', $course->id) }}"
                       class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-900 transition mb-2">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                        <span>Kembali ke Detail Angkatan</span>
                    </a>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                        Pengelolaan Pengajuan Retake Kuis
                    </h1>
                    <p class="text-xs text-slate-500 mt-1">
                        Program: <strong class="text-slate-800">{{ $course->masterCourse->name ?? ($course->name ?? 'Course') }}</strong>
                        &bull; Angkatan: <span class="px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 font-bold">{{ $course->batch_name }}</span>
                        &bull; Threshold: <span class="font-semibold text-slate-700">{{ $course->certificate_threshold ?? 75 }}%</span>
                    </p>
                </div>

                @php
                    $pendingCount = $retakeRequests->where('status', 'pending')->count();
                    $approvedCount = $retakeRequests->where('status', 'approved')->count();
                    $rejectedCount = $retakeRequests->where('status', 'rejected')->count();
                @endphp

                <!-- BULK APPROVE BUTTON -->
                @if($pendingCount > 0)
                    <form action="{{ route('vendor.courses.quizzes.retake.bulk-approve', $course->id) }}" method="POST"
                          onsubmit="return confirm('Apakah Anda yakin ingin menyetujui seluruh {{ $pendingCount }} permintaan retake yang pending pada angkatan ini?');">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition whitespace-nowrap">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Setujui Semua Pending ({{ $pendingCount }})</span>
                        </button>
                    </form>
                @endif
            </div>

            <!-- STATS STRIP -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 bg-white border border-slate-200 rounded-2xl shadow-xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Pengajuan</div>
                    <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ $retakeRequests->count() }}</div>
                </div>
                <div class="p-4 bg-white border border-slate-200 rounded-2xl shadow-xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Menunggu Persetujuan</div>
                    <div class="text-2xl font-extrabold text-amber-700 mt-1">{{ $pendingCount }}</div>
                </div>
                <div class="p-4 bg-white border border-slate-200 rounded-2xl shadow-xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Telah Disetujui</div>
                    <div class="text-2xl font-extrabold text-emerald-700 mt-1">{{ $approvedCount }}</div>
                </div>
                <div class="p-4 bg-white border border-slate-200 rounded-2xl shadow-xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-rose-600">Ditolak</div>
                    <div class="text-2xl font-extrabold text-rose-700 mt-1">{{ $rejectedCount }}</div>
                </div>
            </div>

            <!-- MAIN DATA TABLE CARD -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-4">
                <!-- TOOLBAR -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pb-4 border-b border-slate-100">
                    <div class="relative w-full sm:w-80">
                        <input type="text" id="vendor_retake_table_search" placeholder="Cari nama peserta atau kuis..."
                               oninput="filterVendorRetakeTable()"
                               class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-purple-500 transition">
                        <svg class="absolute left-3 top-2.5 text-slate-400" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </div>

                    <div class="flex items-center gap-1.5 w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0">
                        <button type="button" onclick="setVendorRetakeFilter('all')" id="vrf_btn_all" class="retake-tab-btn px-3 py-1.5 text-xs font-bold rounded-xl bg-purple-700 text-white transition">
                            Semua ({{ $retakeRequests->count() }})
                        </button>
                        <button type="button" onclick="setVendorRetakeFilter('pending')" id="vrf_btn_pending" class="retake-tab-btn px-3 py-1.5 text-xs font-bold rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                            Pending ({{ $pendingCount }})
                        </button>
                        <button type="button" onclick="setVendorRetakeFilter('approved')" id="vrf_btn_approved" class="retake-tab-btn px-3 py-1.5 text-xs font-bold rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                            Disetujui ({{ $approvedCount }})
                        </button>
                        <button type="button" onclick="setVendorRetakeFilter('rejected')" id="vrf_btn_rejected" class="retake-tab-btn px-3 py-1.5 text-xs font-bold rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                            Ditolak ({{ $rejectedCount }})
                        </button>
                    </div>
                </div>

                <!-- TABLE -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/70 text-slate-500 font-bold uppercase tracking-wider text-[10.5px]">
                                <th class="py-3 px-4 rounded-l-xl">Peserta Mahasiswa</th>
                                <th class="py-3 px-4">Kuis & Alasan</th>
                                <th class="py-3 px-4">Waktu Pengajuan</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-right rounded-r-xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($retakeRequests as $req)
                                <tr class="vendor-retake-row hover:bg-slate-50/80 transition"
                                    data-search="{{ strtolower($req->user->name ?? '') }} {{ strtolower($req->user->email ?? '') }} {{ strtolower($req->quiz->title ?? '') }}"
                                    data-status="{{ $req->status }}">
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-800 font-extrabold flex items-center justify-center text-xs flex-shrink-0">
                                                {{ strtoupper(substr($req->user->name ?? 'M', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-900">{{ $req->user->name ?? 'Mahasiswa' }}</div>
                                                <div class="text-[11px] text-slate-400">{{ $req->user->email ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-purple-950">{{ $req->quiz->title ?? 'Quiz' }}</div>
                                        <div class="text-[11px] text-slate-500 mt-0.5 line-clamp-1 italic">
                                            {{ $req->reason ?: 'Pengajuan ujian ulang karena nilai di bawah threshold.' }}
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap text-slate-500">
                                        <div>{{ $req->created_at ? $req->created_at->format('d M Y H:i') : '-' }}</div>
                                        @if($req->reviewed_at)
                                            <div class="text-[10px] text-slate-400 mt-0.5">Ditinjau: {{ $req->reviewed_at->format('d M Y H:i') }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if($req->status === 'approved')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10.5px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                                Disetujui
                                            </span>
                                        @elseif($req->status === 'rejected')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10.5px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                                Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10.5px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        @if($req->status === 'pending')
                                            <div class="inline-flex items-center gap-1.5">
                                                <form action="{{ route('vendor.quizzes.retake.approve', $req->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-xs transition" title="Setujui dan beri +1 kesempatan mengerjakan kuis">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                                        <span>Setujui</span>
                                                    </button>
                                                </form>
                                                <form action="{{ route('vendor.quizzes.retake.reject', $req->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white hover:bg-rose-50 text-rose-700 border border-rose-200 font-bold rounded-xl transition" title="Tolak pengajuan retake">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                                        <span>Tolak</span>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-[11px] text-slate-400 font-medium">Selesai Ditinjau</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 font-medium">
                                        Belum ada pengajuan retake kuis untuk angkatan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="vendor_retake_table_empty_search" class="hidden py-8 text-center text-xs text-slate-400 font-medium">
                    Tidak ditemukan pengajuan retake yang cocok dengan pencarian / filter.
                </div>
            </div>

        </div>
    </div>

    <script>
        let currentVendorFilter = 'all';

        function setVendorRetakeFilter(status) {
            currentVendorFilter = status;
            document.querySelectorAll('.retake-tab-btn').forEach(btn => {
                btn.className = 'retake-tab-btn px-3 py-1.5 text-xs font-bold rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition';
            });
            const activeBtn = document.getElementById('vrf_btn_' + status);
            if (activeBtn) {
                activeBtn.className = 'retake-tab-btn px-3 py-1.5 text-xs font-bold rounded-xl bg-purple-700 text-white transition';
            }
            filterVendorRetakeTable();
        }

        function filterVendorRetakeTable() {
            const query = (document.getElementById('vendor_retake_table_search')?.value || '').toLowerCase().trim();
            const rows = document.querySelectorAll('.vendor-retake-row');
            let matchCount = 0;

            rows.forEach(row => {
                const searchData = row.getAttribute('data-search') || '';
                const rowStatus = row.getAttribute('data-status') || '';

                const matchesQuery = !query || searchData.includes(query);
                const matchesStatus = currentVendorFilter === 'all' || rowStatus === currentVendorFilter;

                if (matchesQuery && matchesStatus) {
                    row.style.display = '';
                    matchCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const emptyMsg = document.getElementById('vendor_retake_table_empty_search');
            if (emptyMsg) {
                emptyMsg.classList.toggle('hidden', matchCount > 0 || rows.length === 0);
            }
        }
    </script>
</x-app-layout>
