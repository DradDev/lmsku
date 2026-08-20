<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-6">
            
            <!-- HEADER -->
            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                    Admin Panel
                </p>

                <h1 class="text-3xl font-bold text-slate-900">
                    {{ $activeTab === 'project' ? 'Project Results & Certificates' : 'Final Quiz Results' }}
                </h1>
                <p class="mt-2 text-slate-500">
                    {{ $activeTab === 'project' ? 'Monitor kelulusan pengerjaan project, verifikasi integritas sertifikat resmi & penerbitan hash Blockchain.' : 'Monitor kelulusan ujian final, verifikasi integritas sertifikat kelulusan & penerbitan hash Blockchain.' }}
                </p>
            </div>

            <!-- ALERT NOTIFICATIONS -->
            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 shadow-sm flex items-center gap-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700 shadow-sm flex items-center gap-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-700 shadow-sm flex items-center gap-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            {{-- STATS CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Results</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $totalResults }}</p>
                </div>

                <div class="rounded-2xl border border-emerald-200 bg-white p-5 shadow-xs">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Verified Blockchain</p>
                    <p class="mt-3 text-3xl font-bold text-emerald-600">{{ $verifiedCount }}</p>
                </div>

                <div class="rounded-2xl border border-amber-200 bg-white p-5 shadow-xs">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Pending Verification</p>
                    <p class="mt-3 text-3xl font-bold text-amber-500">{{ $pendingCount }}</p>
                </div>
            </div>

            {{-- TABS SWITCHER --}}
            <div class="mb-6 flex border-b border-slate-200 gap-6 text-sm font-bold">
                <a href="{{ route('admin.results.index', ['tab' => 'quiz']) }}"
                   class="pb-3 border-b-2 transition flex items-center gap-2 {{ $activeTab === 'quiz' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    <span>Final Quiz Results ({{ $totalQuizResults }})</span>
                </a>

                <a href="{{ route('admin.results.index', ['tab' => 'project']) }}"
                   class="pb-3 border-b-2 transition flex items-center gap-2 {{ $activeTab === 'project' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                    <span>Project Results ({{ $totalProjectResults }})</span>
                </a>
            </div>

            {{-- SEARCH & STATUS FILTER TOOLBAR --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-4 mb-6 shadow-xs flex flex-col gap-3">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    
                    <!-- STATUS FILTER BUTTONS -->
                    <div class="flex items-center gap-2 flex-wrap" id="status-filter-container">
                        <button type="button" 
                                data-status-target="all"
                                onclick="setStatusFilter('all')"
                                class="status-filter-btn active-status-btn"
                                style="all: unset; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 10px; font-size: 13px; font-weight: 700; background: #0F172A; color: #FFFFFF; transition: all 0.15s ease;">
                            <span>Semua Status</span>
                            <span class="status-badge" style="background: rgba(255,255,255,0.25); color: #FFFFFF; padding: 1px 7px; border-radius: 100px; font-size: 11px; font-weight: 700;">
                                {{ $totalResults }}
                            </span>
                        </button>

                        <button type="button" 
                                data-status-target="pending"
                                onclick="setStatusFilter('pending')"
                                class="status-filter-btn"
                                style="all: unset; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 10px; font-size: 13px; font-weight: 600; background: #F1F5F9; color: #475569; transition: all 0.15s ease;">
                            <span>Menunggu Verifikasi</span>
                            <span class="status-badge" style="background: #FEF3C7; color: #B45309; padding: 1px 7px; border-radius: 100px; font-size: 11px; font-weight: 700;">
                                {{ $pendingCount }}
                            </span>
                        </button>

                        <button type="button" 
                                data-status-target="verified"
                                onclick="setStatusFilter('verified')"
                                class="status-filter-btn"
                                style="all: unset; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 10px; font-size: 13px; font-weight: 600; background: #F1F5F9; color: #475569; transition: all 0.15s ease;">
                            <span>Terverifikasi Blockchain</span>
                            <span class="status-badge" style="background: #DCFCE7; color: #166534; padding: 1px 7px; border-radius: 100px; font-size: 11px; font-weight: 700;">
                                {{ $verifiedCount }}
                            </span>
                        </button>
                    </div>

                    <!-- SEARCH BAR (ROCK-SOLID FIXED HEIGHT & CENTERED ICONS) -->
                    <div style="position: relative; min-width: 280px; flex-grow: 1; max-width: 480px; height: 42px; display: flex; align-items: center;">
                        <div style="position: absolute; left: 14px; top: 0; bottom: 0; margin: auto; height: 18px; width: 18px; color: #94A3B8; pointer-events: none; display: flex; align-items: center; justify-content: center; z-index: 10;">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        </div>
                        <input type="text" 
                               id="result-search-input"
                               autocomplete="off"
                               placeholder="{{ $activeTab === 'project' ? 'Cari mahasiswa, proyek, dosen/vendor, kode, atau hash...' : 'Cari nama mahasiswa, email, mata kuliah, kuis, atau nilai...' }}"
                               style="width: 100%; height: 42px; box-sizing: border-box; padding: 0 40px 0 40px; border: 1.5px solid #CBD5E1; border-radius: 10px; font-size: 13px; color: #0F172A; outline: none; background: #F8FAFC; transition: all 0.15s ease;"
                               onfocus="this.style.background='#FFFFFF'; this.style.borderColor='#2563EB'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.1)'"
                               onblur="this.style.background='#F8FAFC'; this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
                        <button type="button" 
                                id="clear-result-search-btn"
                                title="Hapus pencarian"
                                onclick="clearResultSearch()"
                                style="position: absolute; right: 10px; top: 0; bottom: 0; margin: auto; height: 22px; width: 22px; border-radius: 50%; display: none; align-items: center; justify-content: center; background: #E2E8F0; color: #64748B; border: none; cursor: pointer; padding: 0; z-index: 10; transition: background 0.15s ease;"
                                onmouseover="this.style.background='#CBD5E1'; this.style.color='#0F172A'"
                                onmouseout="this.style.background='#E2E8F0'; this.style.color='#64748B'">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <!-- RESULT COUNTER STATUS -->
                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 12px; color: #64748B; padding-top: 6px; border-top: 1px dashed #F1F5F9;">
                    <div>
                        <span id="result-count-display">Menampilkan <strong>{{ $totalResults }}</strong> dari {{ $totalResults }} hasil</span>
                    </div>
                    <div id="active-filter-indicator" style="display: none;">
                        <span style="background: #EFF6FF; color: #1D4ED8; padding: 2px 8px; border-radius: 6px; font-weight: 600; font-size: 11px;">Filter Aktif</span>
                    </div>
                </div>
            </div>

            {{-- TAB CONTENT 1: FINAL QUIZ RESULTS --}}
            @if($activeTab === 'quiz')
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <h2 class="text-xl font-semibold text-slate-900">Antrean Verifikasi Sertifikat & Blockchain</h2>
                                <p class="mt-1 text-sm text-slate-500">
                                    Hanya menampilkan peserta yang <strong>lulus nilai minimum (Passing Grade Threshold ≥ 75)</strong> — siap di-approve Admin & dicatat ke Blockchain.
                                </p>
                            </div>
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-xl text-xs font-bold self-start sm:self-auto">
                                Filter Nilai Minimum (Lulus Threshold)
                            </span>
                        </div>
                    </div>

                    @if($results->count())
                        <div class="divide-y divide-slate-200" id="results-list-container">
                            @foreach ($results as $result)
                                @php
                                    $cert = $result->certificate_record;
                                    $searchData = strtolower(
                                        ($result->user->name ?? '') . ' ' . 
                                        ($result->user->email ?? '') . ' ' . 
                                        ($result->quiz->course->name ?? '') . ' ' . 
                                        ($result->quiz->title ?? '') . ' ' . 
                                        $result->score . ' ' . 
                                        ($cert->credential_code ?? '') . ' ' . 
                                        ($result->blockchain_hash ?? '') . ' ' . 
                                        ($result->is_verified ? 'verified terverifikasi blockchain' : 'pending menunggu approval')
                                    );
                                @endphp
                                <div class="result-item px-6 py-5 hover:bg-slate-50 transition"
                                     data-status="{{ $result->is_verified ? 'verified' : 'pending' }}"
                                     data-search="{{ $searchData }}">
                                    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2 flex-wrap mb-2">
                                                <h3 class="text-lg font-bold text-slate-900">
                                                    {{ $result->quiz->title ?? 'Quiz' }}
                                                </h3>

                                                @if($result->is_verified)
                                                    <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-extrabold text-emerald-800">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>
                                                        <span>Verified Blockchain</span>
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-100 px-3 py-1 text-xs font-extrabold text-amber-800">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                                        <span>Pending Admin Approval</span>
                                                    </span>
                                                @endif

                                                @if($cert && $cert->credential_code)
                                                    <span class="font-mono text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-2.5 py-0.5 rounded-md">
                                                        {{ $cert->credential_code }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 text-sm mt-3">
                                                <div>
                                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Mahasiswa</p>
                                                    <p class="font-bold text-slate-900 mt-0.5">{{ $result->user->name ?? 'Unknown Student' }}</p>
                                                    <p class="text-xs text-slate-500">{{ $result->user->email ?? '-' }}</p>
                                                </div>

                                                <div>
                                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Mata Kuliah</p>
                                                    <p class="font-semibold text-slate-800 mt-0.5">{{ $result->quiz->course->name ?? '-' }}</p>
                                                    <p class="text-xs text-slate-500">Kelas Akademik</p>
                                                </div>

                                                <div>
                                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Waktu Submit & Nilai</p>
                                                    <p class="font-semibold text-slate-800 mt-0.5">
                                                        {{ optional($result->created_at)->format('d M Y, H:i') }}
                                                    </p>
                                                    <p class="text-xs text-emerald-600 font-bold">Skor Final: {{ $result->score }} (Lulus)</p>
                                                </div>

                                                <div>
                                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Status Validasi</p>
                                                    @if($result->is_verified)
                                                        <p class="text-xs font-mono text-slate-700 truncate mt-0.5" title="{{ $result->blockchain_hash }}">
                                                            Hash: {{ substr($result->blockchain_hash ?? '0x', 0, 16) }}...
                                                        </p>
                                                        <p class="text-[11px] text-emerald-700 font-bold">Terverifikasi {{ optional($result->completed_at ?? $result->updated_at)->format('d/m/Y H:i') }}</p>
                                                    @else
                                                        <p class="text-xs text-amber-700 font-medium mt-0.5">Menunggu Hash Admin</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 xl:justify-end">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <!-- DETAIL HASIL BUTTON -->
                                                <a href="{{ route('admin.results.show', $result->id) }}"
                                                   class="inline-flex items-center gap-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 px-3.5 py-2 text-xs font-bold text-white transition shadow-sm"
                                                   title="Lihat rincian jawaban dan skor mahasiswa">
                                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                                    <span>Detail Hasil</span>
                                                </a>

                                                @if(!$result->is_verified)
                                                    <!-- APPROVE BUTTON -->
                                                    <form method="POST" action="{{ route('admin.results.verify', $result->id) }}"
                                                          onsubmit="return confirm('Approve sertifikat & catat data kelulusan {{ addslashes($result->user->name ?? 'Mahasiswa') }} ke Blockchain?');">
                                                        @csrf
                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-3.5 py-2 text-xs font-extrabold text-white transition shadow-sm">
                                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                                            <span>Approve & Catat ke Blockchain</span>
                                                        </button>
                                                    </form>
                                                @else
                                                    <!-- CEK INTEGRITAS BUTTON -->
                                                    <form method="POST" action="{{ route('admin.results.integrity', $result->id) }}">
                                                        @csrf
                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center gap-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 px-3.5 py-2 text-xs font-bold transition shadow-sm"
                                                            title="Verifikasi keaslian hash data di Blockchain">
                                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                                            <span>Cek Integritas Data</span>
                                                        </button>
                                                    </form>

                                                    <!-- LIHAT SERTIFIKAT BUTTON -->
                                                    @php
                                                        $courseParam = $cert?->course_offering_id ?? $result->quiz?->course_id ?? $result->quiz?->master_course_id;
                                                    @endphp
                                                    @if($courseParam)
                                                        <a href="{{ route('student.certificate.show', $courseParam) }}" target="_blank"
                                                           class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-50 text-emerald-800 text-xs font-extrabold px-3.5 py-2 border border-emerald-200 hover:bg-emerald-100 transition shadow-sm"
                                                           title="Buka Sertifikat Kelulusan Resmi">
                                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                                                            <span>Lihat Sertifikat</span>
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-6 py-12 text-center">
                            <div class="mx-auto max-w-md">
                                <h3 class="text-lg font-semibold text-slate-900">Belum ada hasil quiz</h3>
                                <p class="mt-2 text-sm text-slate-500">
                                    Result quiz mahasiswa akan muncul di sini setelah ada submission.
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- DYNAMIC EMPTY STATE -->
                    <div id="no-matching-results" class="px-6 py-12 text-center" style="display: none;">
                        <div class="mx-auto max-w-md flex flex-col items-center">
                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Hasil Tidak Ditemukan</h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Tidak ada data hasil kelulusan yang cocok dengan kriteria kata kunci atau filter status yang dipilih.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- TAB CONTENT 2: PROJECT RESULTS --}}
            @if($activeTab === 'project')
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-slate-200 px-6 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <h2 class="text-xl font-semibold text-slate-900">Project Completions & Blockchain Certifications</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Antrean verifikasi integritas data pengerjaan project mahasiswa & penerbitan sertifikat resmi berbasis Blockchain.
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-full text-xs font-extrabold">
                                {{ $verifiedProjectCount }} Terverifikasi Blockchain
                            </span>
                            <span class="px-3 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded-full text-xs font-extrabold">
                                {{ $pendingProjectCount }} Menunggu Verifikasi
                            </span>
                        </div>
                    </div>

                    @if($projectParticipations->count())
                        <div class="divide-y divide-slate-200" id="results-list-container">
                            @foreach ($projectParticipations as $part)
                                @php
                                    $cert = $part->certificate_record;
                                    $isVerified = $part->is_verified;
                                    $isPending = $part->is_pending;
                                    $searchData = strtolower(
                                        ($part->user->name ?? '') . ' ' . 
                                        ($part->user->email ?? '') . ' ' . 
                                        ($part->project->title ?? '') . ' ' . 
                                        ($part->project->creator->name ?? '') . ' ' . 
                                        ($part->project->creator->institution->name ?? '') . ' ' . 
                                        ($cert->credential_code ?? '') . ' ' . 
                                        ($cert->blockchain_hash ?? '') . ' ' . 
                                        ($isVerified ? 'verified terverifikasi blockchain' : ($isPending ? 'pending menunggu verifikasi' : 'in progress'))
                                    );
                                @endphp
                                <div class="result-item px-6 py-5 hover:bg-slate-50 transition"
                                     data-status="{{ $isVerified ? 'verified' : ($isPending ? 'pending' : 'other') }}"
                                     data-search="{{ $searchData }}">
                                    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2 flex-wrap mb-2">
                                                <h3 class="text-lg font-bold text-slate-900">
                                                    {{ $part->project->title ?? 'Project' }}
                                                </h3>

                                                @if($isVerified)
                                                    <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-extrabold text-emerald-800">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>
                                                        <span>Verified Blockchain</span>
                                                    </span>
                                                @elseif($isPending)
                                                    <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-100 px-3 py-1 text-xs font-extrabold text-amber-800">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                                        <span>Pending Admin Verification</span>
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                                        <span>In Progress / Review</span>
                                                    </span>
                                                @endif

                                                @if($cert && $cert->credential_code)
                                                    <span class="font-mono text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-2.5 py-0.5 rounded-md">
                                                        {{ $cert->credential_code }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 text-sm mt-3">
                                                <div>
                                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Mahasiswa</p>
                                                    <p class="font-bold text-slate-900 mt-0.5">{{ $part->user->name ?? 'Unknown Student' }}</p>
                                                    <p class="text-xs text-slate-500">{{ $part->user->email ?? '-' }}</p>
                                                </div>

                                                <div>
                                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Pembuat Project</p>
                                                    <p class="font-semibold text-slate-800 mt-0.5">{{ $part->project->creator->name ?? 'Lecturer' }}</p>
                                                    <p class="text-xs text-slate-500">{{ $part->project->creator->institution->name ?? ($part->project->provider_type === 'external' ? 'Mitra Vendor' : 'Dosen Akademik') }}</p>
                                                </div>

                                                <div>
                                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Waktu Selesai & Nilai</p>
                                                    <p class="font-semibold text-slate-800 mt-0.5">
                                                        {{ optional($part->completed_at ?? $part->created_at)->format('d M Y, H:i') }}
                                                    </p>
                                                    <p class="text-xs text-emerald-600 font-bold">Progres: 100% (Selesai)</p>
                                                </div>

                                                <div>
                                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Status Validasi</p>
                                                    @if($isVerified)
                                                        <p class="text-xs font-mono text-slate-700 truncate mt-0.5" title="{{ $cert->blockchain_hash }}">
                                                            Hash: {{ substr($cert->blockchain_hash, 0, 16) }}...
                                                        </p>
                                                        <p class="text-[11px] text-emerald-700 font-bold">Terverifikasi {{ optional($cert->verified_at)->format('d/m/Y H:i') }}</p>
                                                    @else
                                                        <p class="text-xs text-amber-700 font-medium mt-0.5">Menunggu Hash Admin</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 xl:justify-end">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <!-- DETAIL PORTOFOLIO BUTTON -->
                                                <a href="{{ route('lecturer.students.portfolio', $part->user_id) }}" target="_blank"
                                                   class="inline-flex items-center gap-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 px-3.5 py-2 text-xs font-bold text-white transition shadow-sm"
                                                   title="Lihat portofolio & riwayat proyek mahasiswa">
                                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                                    <span>Detail Portofolio</span>
                                                </a>

                                                @if(!$isVerified)
                                                    <!-- APPROVE BUTTON -->
                                                    <form method="POST" action="{{ route('admin.results.project.verify', $part->id) }}"
                                                          onsubmit="return confirm('Verifikasi integritas sertifikat project {{ addslashes($part->user->name ?? 'Mahasiswa') }} dan terbitkan Hash Blockchain?');">
                                                        @csrf
                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-3.5 py-2 text-xs font-extrabold text-white transition shadow-sm">
                                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                                            <span>Approve & Catat ke Blockchain</span>
                                                        </button>
                                                    </form>
                                                @else
                                                    <!-- CEK INTEGRITAS BUTTON -->
                                                    <form method="POST" action="{{ route('admin.results.project.integrity', $part->id) }}">
                                                        @csrf
                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center gap-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 px-3.5 py-2 text-xs font-bold transition shadow-sm"
                                                            title="Verifikasi keaslian hash data di Blockchain">
                                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                                            <span>Cek Integritas Data</span>
                                                        </button>
                                                    </form>

                                                    <!-- LIHAT SERTIFIKAT BUTTON -->
                                                    @if($part->project_id)
                                                        <a href="{{ route('student.certificate.project.show', $part->project_id) }}" target="_blank"
                                                           class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-50 text-emerald-800 text-xs font-extrabold px-3 py-2 border border-emerald-200 hover:bg-emerald-100 transition shadow-sm"
                                                           title="Buka Sertifikat Kelulusan Resmi">
                                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                                                            <span>Lihat Sertifikat</span>
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-6 py-12 text-center">
                            <div class="mx-auto max-w-md">
                                <h3 class="text-lg font-semibold text-slate-900">Belum ada partisipasi project</h3>
                                <p class="mt-2 text-sm text-slate-500">
                                    Daftar partisipasi project mahasiswa akan muncul di sini setelah ada pendaftaran project.
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- DYNAMIC EMPTY STATE -->
                    <div id="no-matching-results" class="px-6 py-12 text-center" style="display: none;">
                        <div class="mx-auto max-w-md flex flex-col items-center">
                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Hasil Tidak Ditemukan</h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Tidak ada data pengerjaan project yang cocok dengan kriteria kata kunci atau filter status yang dipilih.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- JAVASCRIPT SEARCH & STATUS FILTER ENGINE -->
    <script>
        let currentActiveStatus = 'all';

        function setStatusFilter(status) {
            currentActiveStatus = status;

            // Update button styles
            document.querySelectorAll('.status-filter-btn').forEach(btn => {
                const target = btn.getAttribute('data-status-target');
                const badge = btn.querySelector('.status-badge');

                if (target === status) {
                    btn.style.background = '#0F172A';
                    btn.style.color = '#FFFFFF';
                    btn.style.fontWeight = '700';
                    if (badge) {
                        badge.style.background = 'rgba(255,255,255,0.25)';
                        badge.style.color = '#FFFFFF';
                    }
                } else {
                    btn.style.background = '#F1F5F9';
                    btn.style.color = '#475569';
                    btn.style.fontWeight = '600';
                    if (badge) {
                        if (target === 'pending') {
                            badge.style.background = '#FEF3C7';
                            badge.style.color = '#B45309';
                        } else if (target === 'verified') {
                            badge.style.background = '#DCFCE7';
                            badge.style.color = '#166534';
                        } else {
                            badge.style.background = '#E2E8F0';
                            badge.style.color = '#334155';
                        }
                    }
                }
            });

            applyResultFilters();
        }

        function clearResultSearch() {
            const searchInput = document.getElementById('result-search-input');
            if (searchInput) {
                searchInput.value = '';
                const clearBtn = document.getElementById('clear-result-search-btn');
                if (clearBtn) clearBtn.style.display = 'none';
                applyResultFilters();
                searchInput.focus();
            }
        }

        function applyResultFilters() {
            const searchInput = document.getElementById('result-search-input');
            const rawSearch = (searchInput ? searchInput.value : '') || '';
            const searchTokens = rawSearch.trim().toLowerCase().split(/\s+/).filter(Boolean);
            const clearBtn = document.getElementById('clear-result-search-btn');

            if (clearBtn) {
                clearBtn.style.display = rawSearch.length > 0 ? 'inline-flex' : 'none';
            }

            const items = document.querySelectorAll('.result-item');
            let visibleCount = 0;

            items.forEach(item => {
                const itemStatus = (item.getAttribute('data-status') || '').toLowerCase();
                const itemSearch = (item.getAttribute('data-search') || '').toLowerCase();

                const matchesStatus = (currentActiveStatus === 'all' || itemStatus === currentActiveStatus);
                const matchesSearch = searchTokens.length === 0 || searchTokens.every(token => itemSearch.includes(token));

                if (matchesStatus && matchesSearch) {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Update result counter display
            const countDisplay = document.getElementById('result-count-display');
            if (countDisplay) {
                countDisplay.innerHTML = `Menampilkan <strong>${visibleCount}</strong> dari ${items.length} hasil`;
            }

            // Update active filter badge
            const activeFilterBadge = document.getElementById('active-filter-indicator');
            if (activeFilterBadge) {
                activeFilterBadge.style.display = (currentActiveStatus !== 'all' || searchTokens.length > 0) ? 'block' : 'none';
            }

            // Dynamic Empty State display
            const noMatchElem = document.getElementById('no-matching-results');
            if (noMatchElem) {
                if (visibleCount === 0 && items.length > 0) {
                    noMatchElem.style.display = 'block';
                } else {
                    noMatchElem.style.display = 'none';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('result-search-input');
            if (searchInput) {
                searchInput.addEventListener('input', applyResultFilters);
                searchInput.addEventListener('keyup', applyResultFilters);
                searchInput.addEventListener('change', applyResultFilters);
            }
        });
    </script>
</x-app-layout>
