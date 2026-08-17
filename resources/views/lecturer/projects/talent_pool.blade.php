<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            COMPRO Talent Pool & Matching Engine
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl shadow-xs flex items-center gap-2 text-sm font-semibold">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <!-- Project Context Header Card -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">
                            <span>Talent Screening & Matching</span>
                            <span class="px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100 text-[11px] font-bold">Active Project</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900">
                            {{ $project->title }}
                        </h3>
                        <p class="text-xs text-slate-500 mt-1 max-w-3xl leading-relaxed">
                            Screening otomatis kompetensi mahasiswa berdasarkan bobot Kompetensi Kuis (50%), Peminatan/Spesialisasi (30%), dan Rekam Jejak Portofolio (20%).
                        </p>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('lecturer.projects.show', $project) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition border border-slate-200">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                            Kembali ke Project
                        </a>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-slate-100 flex flex-wrap gap-2 text-xs">
                    <span class="px-3 py-1 bg-slate-100 text-slate-700 border border-slate-200 rounded-lg font-semibold">
                        Level: {{ ucfirst($project->difficulty_level) }}
                    </span>
                    <span class="px-3 py-1 bg-slate-100 text-slate-700 border border-slate-200 rounded-lg font-semibold">
                        Durasi: {{ $project->duration_days }} Hari
                    </span>
                    <span class="px-3 py-1 bg-slate-100 text-slate-700 border border-slate-200 rounded-lg font-semibold">
                        Kuota: {{ $project->participations()->count() }} / {{ $project->max_students }} Pendaftar
                    </span>

                    @foreach($project->skills as $skill)
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-lg font-semibold">
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
                            Filter dan undang talenta mahasiswa yang relevan untuk mempercepat pembentukan tim project.
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
                                   class="w-full pl-9 pr-3 py-2 text-xs font-semibold rounded-xl border-slate-300 focus:border-indigo-600 focus:ring-indigo-600">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status Undangan</label>
                        <select id="talent-status-filter" onchange="filterTalents()"
                                class="w-full py-2 text-xs font-semibold rounded-xl border-slate-300 focus:border-indigo-600 focus:ring-indigo-600 cursor-pointer">
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
                                class="w-full py-2 text-xs font-semibold rounded-xl border-slate-300 focus:border-indigo-600 focus:ring-indigo-600 cursor-pointer">
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
                                ? 'bg-indigo-50 text-indigo-800 border-indigo-200' 
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

                    <div class="talent-card border border-slate-200 rounded-2xl p-5 hover:border-indigo-300 transition-all flex flex-col justify-between relative bg-white"
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
                                    <span class="text-slate-800 font-bold">{{ $matchScore }}%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                    <div class="h-2 rounded-full {{ $matchScore >= 80 ? 'bg-emerald-600' : ($matchScore >= 60 ? 'bg-indigo-600' : 'bg-slate-500') }}"
                                         style="width: {{ $matchScore }}%"></div>
                                </div>
                            </div>

                            <!-- Skills & Approved Works -->
                            <div class="space-y-2 mb-4">
                                <div class="text-xs text-slate-600">
                                    <span class="font-bold text-slate-800">Kompetensi Kuis:</span>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @forelse($student->skillProfiles->take(3) as $sp)
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 border border-slate-200 rounded-lg text-[11px] font-medium">
                                            {{ $sp->skill->name ?? 'Skill' }} ({{ round($sp->avg_score) }})
                                        </span>
                                        @empty
                                        <span class="text-slate-400 italic text-[11px]">Belum ada hasil kuis</span>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="text-xs text-slate-600 flex items-center justify-between pt-1">
                                    <span class="font-bold text-slate-800">Project Selesai:</span>
                                    <span class="font-bold text-slate-800">{{ $student->completedProjects->count() }} Karya Disetujui</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-3 border-t border-slate-100 flex items-center gap-2">
                            <a href="{{ route('lecturer.students.portfolio', $student) }}"
                               class="flex-1 text-center py-2 px-3 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-xl text-xs font-bold transition border border-slate-200">
                                Portofolio
                            </a>

                            @if($student->invitation_status === 'invited')
                                <span class="py-2 px-3 bg-amber-50 text-amber-800 border border-amber-200 rounded-xl text-xs font-bold cursor-default">
                                    Undangan Terkirim
                                </span>
                            @elseif(in_array($student->invitation_status, ['in_progress', 'development', 'review', 'completed']))
                                <span class="py-2 px-3 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-xl text-xs font-bold cursor-default">
                                    Joined
                                </span>
                            @elseif($student->invitation_status === 'declined')
                                <span class="py-2 px-3 bg-rose-50 text-rose-800 border border-rose-200 rounded-xl text-xs font-bold cursor-default">
                                    Ditolak
                                </span>
                            @else
                                <form action="{{ route('lecturer.projects.invite', [$project, $student]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-xs">
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

    <script>
        function filterTalents() {
            const searchQuery = document.getElementById('talent-search-input').value.toLowerCase().trim();
            const statusFilter = document.getElementById('talent-status-filter').value;
            const scoreFilter = document.getElementById('talent-score-filter').value;

            const cards = document.querySelectorAll('.talent-card');

            cards.forEach(card => {
                const nameData = card.getAttribute('data-name');
                const statusData = card.getAttribute('data-status');
                const scoreData = parseFloat(card.getAttribute('data-score') || 0);

                const matchSearch = !searchQuery || nameData.includes(searchQuery);
                const matchStatus = (statusFilter === 'all' || statusData === statusFilter);

                let matchScore = true;
                if (scoreFilter === 'high') {
                    matchScore = scoreData >= 80;
                } else if (scoreFilter === 'medium') {
                    matchScore = scoreData >= 60 && scoreData < 80;
                }

                if (matchSearch && matchStatus && matchScore) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</x-app-layout>
