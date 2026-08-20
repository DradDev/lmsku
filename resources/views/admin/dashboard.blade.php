<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.admin-dash-wrap {
    min-height: 100vh;
    background: #f8fafc;
    color: #0f172a;
    padding: 2.25rem 0 5rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.admin-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 1.75rem;
}

/* HEADER AREA */
.header-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 1.75rem 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    margin-bottom: 1.75rem;
}

.header-eyebrow {
    font-size: 11px;
    font-weight: 800;
    color: #2563eb;
    text-transform: uppercase;
    letter-spacing: 0.75px;
    margin-bottom: 4px;
}

.header-title {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.4px;
    line-height: 1.25;
}

/* METRICS STRIP */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 1rem;
    margin-bottom: 1.75rem;
}

@media (min-width: 640px) {
    .metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .metrics-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

.metric-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    transition: transform 0.2s, box-shadow 0.2s;
}

.metric-card:hover {
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
}

.metric-label {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.metric-value {
    font-size: 26px;
    font-weight: 800;
    color: #0f172a;
    margin-top: 4px;
    line-height: 1.2;
}

/* MAIN CONTENT GRID */
.workspace-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.75rem;
}

@media (min-width: 1024px) {
    .workspace-grid {
        grid-template-columns: 2fr 1fr;
    }
}

/* SECTION CARDS */
.card-panel {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}

/* TAB NAVIGATION */
.tab-btn {
    padding: 8px 16px;
    font-size: 12.5px;
    font-weight: 700;
    border-radius: 12px;
    transition: all 0.2s;
    cursor: pointer;
    border: none;
}

.tab-btn.active {
    background: #0f172a;
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.15);
}

.tab-btn.inactive {
    background: #f1f5f9;
    color: #64748b;
}

.tab-btn.inactive:hover {
    background: #e2e8f0;
    color: #0f172a;
}
</style>

<div class="admin-dash-wrap">
    <div class="admin-container">

        {{-- NOTIFIKASI SUCCESS --}}
        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-xs text-xs font-bold">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-600 text-white text-xs">✓</span>
                <div>
                    <strong>Berhasil:</strong> {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- 1. TOP HEADER & QUICK ACTIONS -->
        <div class="header-card">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                <div>
                    <p class="header-eyebrow">LMS Command Center &bull; Administrator Portal</p>
                    <h1 class="header-title">Pusat Kendali & Pemantauan Akademik</h1>
                    <div class="flex flex-wrap items-center gap-2 mt-2">
                        @if($activeTerm)
                            <span class="px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-800 border border-blue-200 text-xs font-bold">
                                Periode Aktif: {{ $activeTerm->name }}
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-md bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold">
                                Belum Ada Semester Aktif
                            </span>
                        @endif
                        <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold">
                            Strict 3NF Database
                        </span>
                    </div>
                </div>

                <!-- QUICK ACTIONS -->
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('admin.master-courses.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                        + Master Course Baru
                    </a>
                    <a href="{{ route('admin.course-offerings.create') }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition">
                        + Buka Rombel Kelas
                    </a>
                    <a href="{{ route('admin.academic-terms.index') }}" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl border border-slate-300 transition">
                        Kelola Semester
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl border border-slate-300 transition inline-flex items-center gap-1.5">
                        <span>Persetujuan User</span>
                        @if($pendingUsersCount > 0)
                            <span class="px-1.5 py-0.2 rounded-full bg-amber-500 text-white text-[10px] font-extrabold">
                                {{ $pendingUsersCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. EMPAT KARTU METRIK UTAMA -->
        <div class="metrics-grid">
            <!-- 1. PENGGUNA TERDAFTAR -->
            <div class="metric-card">
                <div class="flex items-center justify-between">
                    <span class="metric-label">Pengguna Terdaftar</span>
                    @if($pendingUsersCount > 0)
                        <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10.5px] font-extrabold">
                            {{ $pendingUsersCount }} Pending
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10.5px] font-bold">
                            All Approved
                        </span>
                    @endif
                </div>
                <div class="metric-value text-blue-600">{{ $totalUsers }}</div>
                <p class="mt-2 text-[11px] text-slate-500 font-medium truncate">
                    <strong>{{ $studentCount }}</strong> Mhs &bull; <strong>{{ $lecturerCount }}</strong> Dosen &bull; <strong>{{ $vendorCount }}</strong> Mitra
                </p>
            </div>

            <!-- 2. KURIKULUM & ROMBEL -->
            <div class="metric-card">
                <div class="flex items-center justify-between">
                    <span class="metric-label">Kurikulum Master</span>
                    <span class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200 text-[10.5px] font-bold">
                        {{ $totalActiveOfferings }} Rombel Aktif
                    </span>
                </div>
                <div class="metric-value text-indigo-600">{{ $totalMasterCourses }}</div>
                <p class="mt-2 text-[11px] text-slate-500 font-medium truncate">
                    <strong>{{ $academicCoursesCount }}</strong> Akademik &bull; <strong>{{ $vendorCoursesCount }}</strong> Industri Mitra
                </p>
            </div>

            <!-- 3. AKTIVITAS BELAJAR -->
            <div class="metric-card">
                <div class="flex items-center justify-between">
                    <span class="metric-label">Aktivitas Belajar</span>
                    <span class="px-2 py-0.5 rounded-full bg-purple-50 text-purple-700 border border-purple-200 text-[10.5px] font-bold">
                        Rata-rata: {{ $resultStats['average_score'] }} Pts
                    </span>
                </div>
                <div class="metric-value text-purple-700">{{ $totalEnrollments }}</div>
                <p class="mt-2 text-[11px] text-slate-500 font-medium truncate">
                    Total pendaftaran di seluruh kelas aktif
                </p>
            </div>

            <!-- 4. KELULUSAN & BLOCKCHAIN -->
            <div class="metric-card">
                <div class="flex items-center justify-between">
                    <span class="metric-label">Kelulusan & Blockchain</span>
                    @if($resultStats['unverified'] > 0)
                        <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10.5px] font-extrabold">
                            {{ $resultStats['unverified'] }} Antrean
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10.5px] font-bold">
                            100% Verified
                        </span>
                    @endif
                </div>
                <div class="metric-value text-emerald-600">{{ $resultStats['verified'] }}</div>
                <p class="mt-2 text-[11px] text-slate-500 font-medium truncate">
                    Dari total <strong>{{ $resultStats['total'] }}</strong> hasil Final Quiz
                </p>
            </div>
        </div>

        <!-- 3. MAIN WORKSPACE (2fr KIRI vs 1fr KANAN) -->
        <div class="workspace-grid">
            
            <!-- KOLOM KIRI (2fr): UNIFIED ACTION CENTER DENGAN TAB -->
            <div class="card-panel space-y-4">
                
                <!-- TAB SWITCHER HEADER -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <button type="button" id="tab-btn-blockchain" onclick="switchActionTab('blockchain')" class="tab-btn active">
                            Verifikasi Hash Blockchain ({{ $resultStats['total'] }})
                        </button>
                        <button type="button" id="tab-btn-users" onclick="switchActionTab('users')" class="tab-btn inactive">
                            Persetujuan Akun ({{ $pendingUsersCount }})
                        </button>
                    </div>

                    <a href="{{ route('admin.results.index') }}" id="tab-link-more" class="text-xs font-bold text-blue-600 hover:underline">
                        Lihat Semua Laporan →
                    </a>
                </div>

                <!-- TAB PANE 1: VERIFIKASI HASH BLOCKCHAIN -->
                <div id="tab-content-blockchain" class="space-y-4">
                    <!-- SEARCH & FILTER ROW -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('admin.dashboard', ['result_status' => 'all', 'search' => $search]) }}"
                               class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $resultStatus === 'all' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                Semua ({{ $resultStats['total'] }})
                            </a>
                            <a href="{{ route('admin.dashboard', ['result_status' => 'unverified', 'search' => $search]) }}"
                               class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $resultStatus === 'unverified' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-800 border border-amber-200 hover:bg-amber-100' }}">
                                Antrean ({{ $resultStats['unverified'] }})
                            </a>
                            <a href="{{ route('admin.dashboard', ['result_status' => 'verified', 'search' => $search]) }}"
                               class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $resultStatus === 'verified' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100' }}">
                                Terverifikasi ({{ $resultStats['verified'] }})
                            </a>
                        </div>

                        <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                            <input type="hidden" name="result_status" value="{{ $resultStatus }}">
                            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama / mata kuliah..." class="rounded-xl border-slate-300 text-xs py-1.5 px-3 focus:border-blue-600 focus:ring-blue-600 w-44">
                            <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-300 transition">
                                Cari
                            </button>
                        </form>
                    </div>

                    <!-- TABEL VERIFIKASI -->
                    <div class="overflow-x-auto border border-slate-200 rounded-2xl bg-white">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-slate-600 font-extrabold uppercase text-[10px] border-b border-slate-200 tracking-wider">
                                <tr>
                                    <th class="px-4 py-3">Mahasiswa</th>
                                    <th class="px-4 py-3">Mata Kuliah</th>
                                    <th class="px-4 py-3">Nilai</th>
                                    <th class="px-4 py-3">Hash Blockchain</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($results as $result)
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="px-4 py-3">
                                            <div class="font-bold text-slate-900">{{ $result->user->name ?? '-' }}</div>
                                            <div class="text-[10.5px] text-slate-400">{{ $result->user->email ?? '-' }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-slate-700 font-semibold">
                                            {{ $result->quiz->course->name ?? ($result->quiz->masterCourse->name ?? '-') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-0.5 rounded-md font-extrabold text-[11px] {{ $result->score >= 75 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                                {{ $result->score }} Pts
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 font-mono text-[10.5px] text-slate-500">
                                            {{ $result->blockchain_hash ?: 'Belum Dicatat' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($result->is_verified)
                                                <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 text-[10.5px] font-bold">
                                                    Verified
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-800 text-[10.5px] font-bold">
                                                    Unverified
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            @if (!$result->is_verified)
                                                <form method="POST" action="{{ route('admin.results.verify', $result->id) }}">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg shadow-xs transition">
                                                        Verifikasi
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-emerald-700 font-bold">Tercatat</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-slate-400 text-xs">
                                            Tidak ada data hasil kuis yang sesuai dengan kriteria filter.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB PANE 2: PERSETUJUAN PENGGUNA BARU (PENDING APPROVALS) -->
                <div id="tab-content-users" class="hidden space-y-4">
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-slate-500">
                            Daftar calon pengguna yang melakukan registrasi dan membutuhkan persetujuan Admin.
                        </p>
                        <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-blue-600 hover:underline">
                            Buka Manajemen User →
                        </a>
                    </div>

                    <div class="overflow-x-auto border border-slate-200 rounded-2xl bg-white">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-slate-600 font-extrabold uppercase text-[10px] border-b border-slate-200 tracking-wider">
                                <tr>
                                    <th class="px-4 py-3">Nama Pengguna</th>
                                    <th class="px-4 py-3">Email</th>
                                    <th class="px-4 py-3">Peran (Role)</th>
                                    <th class="px-4 py-3">Institusi</th>
                                    <th class="px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($pendingUsers as $pUser)
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="px-4 py-3 font-bold text-slate-900">
                                            {{ $pUser->name }}
                                        </td>
                                        <td class="px-4 py-3 text-slate-600">
                                            {{ $pUser->email }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-0.5 rounded-md text-[10.5px] font-extrabold uppercase {{ $pUser->role === 'lecturer' ? 'bg-indigo-100 text-indigo-800' : ($pUser->role === 'vendor' ? 'bg-purple-100 text-purple-800' : 'bg-slate-100 text-slate-700') }}">
                                                {{ $pUser->role }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-slate-600">
                                            {{ $pUser->institution->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('admin.users.show', $pUser->id) }}" class="px-3 py-1 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-lg transition">
                                                Tinjau & Setujui
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-slate-400 text-xs">
                                            Semua pendaftaran akun telah disetujui. Tidak ada antrean pending.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN (1fr): PANEL SNAPSHOT TERPADU -->
            <div class="card-panel space-y-5">
                
                <!-- 1. STATUS SEMESTER AKADEMIK -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="font-extrabold text-xs uppercase tracking-wider text-slate-500">Semester Akademik</h2>
                        <a href="{{ route('admin.academic-terms.index') }}" class="text-[11px] font-bold text-blue-600 hover:underline">
                            Kelola →
                        </a>
                    </div>

                    @if($activeTerm)
                        <div class="p-3 bg-blue-50/70 border border-blue-200 rounded-xl space-y-1 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-blue-950">{{ $activeTerm->name }}</span>
                                <span class="px-1.5 py-0.5 rounded bg-blue-600 text-white text-[9.5px] font-extrabold">Aktif</span>
                            </div>
                            <div class="text-[11px] text-blue-800 space-y-0.5 pt-1">
                                <p>Periode: <strong>{{ $activeTerm->start_date ? \Carbon\Carbon::parse($activeTerm->start_date)->format('d M Y') : '-' }}</strong> s/d <strong>{{ $activeTerm->end_date ? \Carbon\Carbon::parse($activeTerm->end_date)->format('d M Y') : '-' }}</strong></p>
                                <p>Rombel Terbuka: <strong>{{ $totalActiveOfferings }} Rombel Kelas</strong></p>
                            </div>
                        </div>
                    @else
                        <div class="p-3 bg-slate-50 border border-dashed border-slate-200 rounded-xl text-center text-xs text-slate-400">
                            Tidak ada semester aktif saat ini.
                        </div>
                    @endif
                </div>

                <div class="border-t border-slate-100"></div>

                <!-- 2. DISTRIBUSI PENGGUNA -->
                <div>
                    <h2 class="font-extrabold text-xs uppercase tracking-wider text-slate-500 mb-2">Komposisi Pengguna</h2>

                    @php
                        $mhsPercent = $totalUsers > 0 ? round(($studentCount / $totalUsers) * 100) : 0;
                        $dosenPercent = $totalUsers > 0 ? round(($lecturerCount / $totalUsers) * 100) : 0;
                        $vendorPercent = $totalUsers > 0 ? round(($vendorCount / $totalUsers) * 100) : 0;
                    @endphp

                    <div class="space-y-2 text-xs">
                        <div>
                            <div class="flex justify-between text-[11px] font-semibold text-slate-600 mb-1">
                                <span>Mahasiswa ({{ $studentCount }})</span>
                                <span>{{ $mhsPercent }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $mhsPercent }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-[11px] font-semibold text-slate-600 mb-1">
                                <span>Dosen ({{ $lecturerCount }})</span>
                                <span>{{ $dosenPercent }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $dosenPercent }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-[11px] font-semibold text-slate-600 mb-1">
                                <span>Mitra Industri ({{ $vendorCount }})</span>
                                <span>{{ $vendorPercent }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-purple-600 h-1.5 rounded-full" style="width: {{ $vendorPercent }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100"></div>

                <!-- 3. PINTASAN MANAJEMEN SISTEM -->
                <div>
                    <h2 class="font-extrabold text-xs uppercase tracking-wider text-slate-500 mb-2">Pintasan Sistem</h2>
                    <div class="space-y-1 text-xs font-bold">
                        <a href="{{ route('admin.skills.index') }}" class="flex items-center justify-between p-2 hover:bg-slate-50 text-slate-700 hover:text-blue-700 rounded-lg transition">
                            <span>Manajemen Skill & Kompetensi</span>
                            <span>→</span>
                        </a>
                        <a href="{{ route('admin.courses.index') }}" class="flex items-center justify-between p-2 hover:bg-slate-50 text-slate-700 hover:text-blue-700 rounded-lg transition">
                            <span>Audit & Moderasi Kelas</span>
                            <span>→</span>
                        </a>
                        <a href="{{ route('admin.projects.index') }}" class="flex items-center justify-between p-2 hover:bg-slate-50 text-slate-700 hover:text-blue-700 rounded-lg transition">
                            <span>Tinjauan Proyek Mahasiswa</span>
                            <span>→</span>
                        </a>
                        <a href="{{ route('blockchain.verify.index') }}" class="flex items-center justify-between p-2 hover:bg-slate-50 text-slate-700 hover:text-blue-700 rounded-lg transition">
                            <span>Verifikasi Blockchain Publik</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

<script>
function switchActionTab(tab) {
    const btnBlockchain = document.getElementById('tab-btn-blockchain');
    const btnUsers = document.getElementById('tab-btn-users');
    const contentBlockchain = document.getElementById('tab-content-blockchain');
    const contentUsers = document.getElementById('tab-content-users');
    const linkMore = document.getElementById('tab-link-more');

    if (tab === 'blockchain') {
        btnBlockchain.classList.add('active');
        btnBlockchain.classList.remove('inactive');
        btnUsers.classList.remove('active');
        btnUsers.classList.add('inactive');

        contentBlockchain.classList.remove('hidden');
        contentUsers.classList.add('hidden');

        if (linkMore) {
            linkMore.href = "{{ route('admin.results.index') }}";
            linkMore.innerText = "Lihat Semua Laporan →";
        }
    } else {
        btnUsers.classList.add('active');
        btnUsers.classList.remove('inactive');
        btnBlockchain.classList.remove('active');
        btnBlockchain.classList.add('inactive');

        contentUsers.classList.remove('hidden');
        contentBlockchain.classList.add('hidden');

        if (linkMore) {
            linkMore.href = "{{ route('admin.users.index') }}";
            linkMore.innerText = "Buka Manajemen User →";
        }
    }
}
</script>
</x-app-layout>
