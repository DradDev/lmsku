<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                COMPRO Talent Screening Engine (Vendor)
            </h2>

            <div class="flex items-center gap-2">
                <a href="{{ route('vendor.projects.show', $project) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition border border-slate-200">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Detail Project
                </a>

                <a href="{{ route('vendor.projects.index') }}"
                   class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition border border-slate-200">
                    Daftar Project
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $students = $recommendedStudents ?? collect();
    @endphp

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if (session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl shadow-xs flex items-center gap-2 text-xs font-semibold">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if (session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl shadow-xs text-xs font-semibold">
                {{ session('error') }}
            </div>
            @endif

            <!-- Project Context Header Card -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-6 space-y-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-2.5 py-0.5 bg-purple-50 text-purple-700 border border-purple-100 rounded-md text-[11px] font-bold uppercase">
                                Industry Project
                            </span>
                            <span class="text-xs text-slate-400 font-medium">·</span>
                            <span class="text-xs text-slate-500 font-semibold">Tingkat: {{ ucfirst($project->difficulty_level ?? 'Intermediate') }}</span>
                            <span class="text-xs text-slate-400 font-medium">·</span>
                            <span class="text-xs text-slate-500 font-semibold">Durasi: {{ $project->duration_days ?? 30 }} Hari</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 leading-tight">
                            {{ $project->title }}
                        </h3>
                    </div>

                    <div class="text-right self-start md:self-auto">
                        <span class="text-xs text-slate-400 uppercase font-bold tracking-wider block">Status Project</span>
                        <span class="font-bold text-sm {{ $project->is_published ? 'text-emerald-700' : 'text-slate-500' }}">
                            {{ $project->is_published ? 'Aktif & Terbuka' : 'Draft' }}
                        </span>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center gap-2 text-xs">
                    <span class="px-3 py-1 bg-slate-100 text-slate-700 border border-slate-200 rounded-lg font-semibold">
                        Kuota: {{ $project->participations()->count() }} / {{ $project->max_students ?? 1 }} Pendaftar
                    </span>

                    @foreach($project->skills as $skill)
                    <span class="px-3 py-1 bg-purple-50 text-purple-700 border border-purple-100 rounded-lg font-semibold">
                        Syarat: {{ $skill->name }} {{ $skill->pivot->is_main ? '(Utama)' : '' }}
                    </span>
                    @endforeach
                </div>
            </div>

            <!-- Talent Screening & Interactive Filter Bar -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-6 space-y-5">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                    <div>
                        <h4 class="text-lg font-bold text-slate-900">
                            Student Talent Screening & Invite Control
                        </h4>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Filter dan undang talenta mahasiswa yang relevan untuk kebutuhan project industri mitra.
                        </p>
                    </div>

                    <span class="px-3 py-1 bg-slate-100 text-slate-700 border border-slate-200 rounded-full text-xs font-bold self-start md:self-auto">
                        Total {{ $students->count() }} Terdaftar
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Cari Nama / Peminatan</label>
                        <div class="relative">
                            <input type="text" id="talent-search-input" onkeyup="filterTalents()" placeholder="Ketik nama mahasiswa..."
                                   class="w-full pl-9 pr-3 py-2 text-xs font-semibold rounded-xl border-slate-300 focus:border-purple-600 focus:ring-purple-600">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status Undangan</label>
                        <select id="talent-status-filter" onchange="filterTalents()"
                                class="w-full py-2 text-xs font-semibold rounded-xl border-slate-300 focus:border-purple-600 focus:ring-purple-600 cursor-pointer">
                            <option value="all">-- Semua Status Undangan --</option>
                            <option value="none">Belum Diundang (Available)</option>
                            <option value="invited">Undangan Terkirim (Pending)</option>
                            <option value="joined">Sudah Bergabung (Joined)</option>
                            <option value="declined">Undangan Ditolak</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tingkat Match Score</label>
                        <select id="talent-score-filter" onchange="filterTalents()"
                                class="w-full py-2 text-xs font-semibold rounded-xl border-slate-300 focus:border-purple-600 focus:ring-purple-600 cursor-pointer">
                            <option value="all">-- Semua Score Match --</option>
                            <option value="high">High Match (≥ 80%)</option>
                            <option value="medium">Medium Match (60% - 79%)</option>
                        </select>
                    </div>
                </div>

                @if($students->isEmpty())
                <div class="text-center py-12 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <p class="text-slate-500 text-sm">Belum ada data talenta mahasiswa di sistem.</p>
                </div>
                @else
                <div id="talent-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 pt-2">
                    @foreach($students as $index => $student)
                    @php
                        $matchScore = $student->match_score;
                        $scoreBadgeClass = $matchScore >= 80 
                            ? 'bg-emerald-50 text-emerald-800 border-emerald-200' 
                            : ($matchScore >= 60 
                                ? 'bg-purple-50 text-purple-800 border-purple-200' 
                                : 'bg-slate-100 text-slate-800 border-slate-200');
                        $invStatus = $student->invitation_status ?? 'none';
                        if (in_array($invStatus, ['in_progress', 'development', 'review', 'completed'])) {
                            $invStatusGroup = 'joined';
                        } elseif ($invStatus === 'invited') {
                            $invStatusGroup = 'invited';
                        } elseif ($invStatus === 'declined') {
                            $invStatusGroup = 'declined';
                        } else {
                            $invStatusGroup = 'none';
                        }
                    @endphp

                    <div class="talent-card border border-slate-200 rounded-2xl p-5 hover:border-purple-300 transition-all flex flex-col justify-between relative bg-white"
                         data-name="{{ strtolower($student->name) }} {{ strtolower($student->peminatan ?? '') }}"
                         data-status="{{ $invStatusGroup }}"
                         data-score="{{ $matchScore }}">

                        <div>
                            <!-- Header Card: Rank & Match Score -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                    #{{ $index + 1 }} Kandidat
                                </span>

                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $scoreBadgeClass }}">
                                    {{ $matchScore }}% Match
                                </span>
                            </div>

                            <!-- Student Info & Avatar -->
                            <div class="flex items-center gap-3.5 mb-4">
                                @if($student->avatar_url)
                                    <img src="{{ $student->avatar_url }}" alt="" class="w-12 h-12 rounded-full object-cover object-center border border-slate-200 shadow-xs" onerror="this.style.display='none';">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-base border border-slate-200 shadow-xs">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                @endif

                                <div>
                                    <h5 class="font-bold text-sm text-slate-900 leading-snug">
                                        {{ $student->name }}
                                    </h5>
                                    <p class="text-xs text-slate-500 font-medium">
                                        Fokus: <span class="font-semibold text-slate-700">{{ $student->peminatan ?? 'General Track' }}</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Match Score Progress Bar -->
                            <div class="mb-4 bg-slate-50 p-2.5 rounded-xl border border-slate-100 space-y-1.5">
                                <div class="flex justify-between text-[11px] font-semibold text-slate-600">
                                    <span>Skor Kualifikasi</span>
                                    <span class="font-bold text-slate-900">{{ $matchScore }} / 100</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-1.5 rounded-full {{ $matchScore >= 80 ? 'bg-emerald-600' : ($matchScore >= 60 ? 'bg-purple-600' : 'bg-slate-500') }}"
                                         style="width: {{ $matchScore }}%"></div>
                                </div>
                            </div>

                            <!-- Highlights: Verified Competencies -->
                            <div class="space-y-1.5 mb-4 text-xs">
                                <div class="flex items-center justify-between text-slate-600">
                                    <span class="text-slate-500">Skill Riil (Course):</span>
                                    <span class="font-semibold text-slate-900">
                                        {{ $student->skillProfiles->count() }} Terdaftar
                                    </span>
                                </div>

                                <div class="flex items-center justify-between text-slate-600">
                                    <span class="text-slate-500">Project Selesai:</span>
                                    <span class="font-semibold text-slate-900">
                                        {{ $student->completedProjects->count() }} Disetujui
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions & Invitation Triggers -->
                        <div class="pt-3 border-t border-slate-100 flex items-center gap-2">
                            <a href="{{ route('vendor.students.portfolio', $student) }}"
                               class="flex-1 text-center py-2 px-3 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold text-xs rounded-xl transition border border-slate-200">
                                Portofolio
                            </a>

                            @if($student->invitation_status === 'invited')
                                <span class="py-2 px-3.5 bg-amber-50 text-amber-800 border border-amber-200 font-bold text-xs rounded-xl">
                                    Undangan Terkirim
                                </span>
                            @elseif(in_array($student->invitation_status, ['in_progress', 'development', 'review', 'completed']))
                                <span class="py-2 px-3.5 bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold text-xs rounded-xl">
                                    Joined
                                </span>
                            @else
                                <form action="{{ route('vendor.projects.invite', [$project, $student]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="py-2 px-3.5 bg-purple-700 hover:bg-purple-800 text-white font-bold text-xs rounded-xl transition shadow-xs">
                                        + Invite Talent
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Client-Side Live Filter Script -->
    <script>
    function filterTalents() {
        const searchInput = document.getElementById('talent-search-input').value.toLowerCase();
        const statusFilter = document.getElementById('talent-status-filter').value;
        const scoreFilter = document.getElementById('talent-score-filter').value;
        const cards = document.querySelectorAll('.talent-card');

        cards.forEach(card => {
            const name = card.getAttribute('data-name');
            const status = card.getAttribute('data-status');
            const score = parseInt(card.getAttribute('data-score')) || 0;

            const matchesSearch = !searchInput || name.includes(searchInput);
            const matchesStatus = statusFilter === 'all' || status === statusFilter;
            
            let matchesScore = true;
            if (scoreFilter === 'high') {
                matchesScore = score >= 80;
            } else if (scoreFilter === 'medium') {
                matchesScore = score >= 60 && score < 80;
            }

            if (matchesSearch && matchesStatus && matchesScore) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }
    </script>
</x-app-layout>
