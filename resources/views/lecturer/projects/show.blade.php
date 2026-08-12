<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <span>Detail Project & Managing Dashboard</span>
            </h2>

            <div class="flex items-center gap-2">
                <a href="{{ route('lecturer.projects.index') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Kembali ke Daftar
                </a>

                <a href="{{ route('lecturer.projects.edit', $project) }}"
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                    Edit Project
                </a>
            </div>
        </div>
    </x-slot>

    @php
    $statusLabels = [
        'in_progress' => 'In Progress',
        'development' => 'Development',
        'review' => 'Review',
        'completed' => 'Done',
    ];

    $statusColors = [
        'in_progress' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
        'development' => 'bg-blue-100 text-blue-800 border-blue-200',
        'review' => 'bg-purple-100 text-purple-800 border-purple-200',
        'completed' => 'bg-green-100 text-green-800 border-green-200',
    ];

    $joinedCount = $project->participations->count();
    $maxStudents = $project->max_students ?? 1;
    $isFull = $joinedCount >= $maxStudents;

    $comments = $project->comments ?? collect();
    @endphp

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
            <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-xl shadow-sm flex items-center gap-2">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
            @endif

            @if (session('error'))
            <div class="p-4 bg-red-100 border border-red-300 text-red-700 rounded-xl shadow-sm">
                {{ session('error') }}
            </div>
            @endif

            @if (isset($hasCourseForSkill) && !$hasCourseForSkill)
            <div class="p-4 bg-amber-50 border border-amber-200 text-amber-900 rounded-xl space-y-1 shadow-sm">
                <div class="flex items-center gap-2 font-bold text-amber-900">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-amber-600"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y3="13"/><line x1="12" y1="17" x2="12.01" y3="17"/></svg>
                    <span>Peringatan: Belum Ada Course Aktif untuk Main Skill ({{ $mainSkill->name ?? 'Skill' }})</span>
                </div>
                <p class="text-xs leading-relaxed text-amber-800">
                    Project ini membutuhkan kompetensi <strong>{{ $mainSkill->name ?? 'Skill Utama' }}</strong>, namun saat ini belum ada Course aktif di sistem yang menguji skill tersebut. Mahasiswa belum bisa memenuhi prasyarat kompetensi project ini sampai Course terkait dibuat.
                </p>
            </div>
            @endif

            <!-- 2-Column Responsive Dashboard Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                <!-- LEFT COLUMN: Main Overview & Activities (8 Cols) -->
                <div class="lg:col-span-8 space-y-6">

                    <!-- Hero Executive Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-5">
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                @if(($project->provider_type ?? 'internal') === 'external' || ($project->user->role ?? '') === 'vendor')
                                    <span class="px-2.5 py-0.5 text-xs font-extrabold uppercase rounded-md bg-purple-100 text-purple-800 border border-purple-200">
                                        External: {{ $project->user->name ?? 'Vendor' }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 text-xs font-extrabold uppercase rounded-md bg-blue-50 text-blue-700 border border-blue-200">
                                        Internal: {{ $project->user->name ?? 'Dosen' }}
                                    </span>
                                @endif

                                <span class="px-2.5 py-0.5 rounded-md text-xs font-bold {{ $project->is_published ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                    {{ $project->is_published ? 'Published' : 'Draft' }}
                                </span>

                                <span class="px-2.5 py-0.5 bg-gray-100 text-gray-700 rounded-md text-xs font-semibold">
                                    Level: {{ ucfirst($project->difficulty_level) }}
                                </span>

                                <span class="px-2.5 py-0.5 bg-gray-100 text-gray-700 rounded-md text-xs font-semibold">
                                    Durasi: {{ $project->duration_days }} Hari
                                </span>
                            </div>

                            <h3 class="text-2xl font-black text-gray-900 leading-tight">
                                {{ $project->title }}
                            </h3>
                        </div>

                        <!-- Description -->
                        <div class="pt-3 border-t border-gray-100 space-y-2">
                            <h4 class="font-bold text-sm text-gray-900 uppercase tracking-wide">
                                Deskripsi & Deliverables Project
                            </h4>
                            <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">
                                {{ $project->description }}
                            </p>
                        </div>

                        <!-- Benefits & Brief File Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                            @if($project->benefits)
                                <div class="p-3.5 bg-indigo-50/70 border border-indigo-100 rounded-xl space-y-1">
                                    <h5 class="font-bold text-xs text-indigo-900 uppercase tracking-wider flex items-center gap-1">
                                        <span>Benefits & Insentif Mahasiswa</span>
                                    </h5>
                                    <p class="text-xs text-indigo-900 font-medium leading-relaxed">
                                        {{ $project->benefits }}
                                    </p>
                                </div>
                            @endif

                            @if($project->brief_file_url)
                                <div class="p-3.5 bg-emerald-50/70 border border-emerald-100 rounded-xl space-y-2 flex flex-col justify-between">
                                    <div>
                                        <h5 class="font-bold text-xs text-emerald-900 uppercase tracking-wider">
                                            Berkas Acuan TOR / Brief
                                        </h5>
                                        <p class="text-xs text-emerald-800 mt-0.5">Berkas instruksi resmi pengerjaan project.</p>
                                    </div>
                                    <a href="{{ $project->brief_file_url }}" target="_blank"
                                       class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg transition shadow-sm">
                                        Download TOR Brief (PDF/ZIP)
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Student Project Participants & Progress Card -->
                    <div class="bg-white shadow-sm rounded-2xl border border-gray-200 p-6 space-y-5">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4">
                            <div>
                                <h3 class="font-extrabold text-xl text-gray-900">
                                    Student Participants & Progress Monitor
                                </h3>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Pantau progress dan catatan pengerjaan mahasiswa yang terdaftar dalam project ini.
                                </p>
                            </div>

                            <span class="px-3 py-1 bg-indigo-50 text-indigo-800 border border-indigo-100 rounded-full text-xs font-black self-start sm:self-auto">
                                {{ $joinedCount }} / {{ $maxStudents }} Mahasiswa Terdaftar
                            </span>
                        </div>

                        @if ($project->participations->isEmpty())
                        <div class="border border-gray-200 rounded-xl p-8 bg-gray-50 text-center space-y-3">
                            <p class="text-gray-600 text-sm font-semibold">
                                Belum ada mahasiswa yang terdaftar pada project ini.
                            </p>
                            <p class="text-xs text-gray-500">
                                Gunakan widget <strong>Talent Screening Engine</strong> di sebelah kanan untuk mengundang mahasiswa potensial.
                            </p>
                        </div>
                        @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                                <thead>
                                    <tr class="bg-gray-50 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        <th class="p-3 border.b border-gray-200">Mahasiswa</th>
                                        <th class="p-3 border-b border-gray-200">Status</th>
                                        <th class="p-3 border-b border-gray-200">Progress</th>
                                        <th class="p-3 border-b border-gray-200">Aktivitas Terakhir</th>
                                        <th class="p-3 border-b border-gray-200">Catatan Revisi</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($project->participations as $participation)
                                    @php
                                        $latestHistory = $participation->statusHistories->sortByDesc('created_at')->first();
                                        $status = $participation->status;
                                        $progress = $participation->progress_percent ?? 0;
                                    @endphp

                                    <tr>
                                        <td class="p-3 align-top">
                                            <div class="font-bold text-gray-900">
                                                {{ $participation->user->name ?? 'Unknown User' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $participation->user->email ?? '-' }}
                                            </div>
                                        </td>

                                        <td class="p-3 align-top">
                                            <span class="inline-block px-2.5 py-1 rounded-md text-xs font-bold border {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">
                                                {{ $statusLabels[$status] ?? ucfirst($status) }}
                                            </span>
                                        </td>

                                        <td class="p-3 align-top min-w-[160px]">
                                            <div class="flex justify-between text-xs text-gray-600 font-semibold mb-1">
                                                <span>Progress</span>
                                                <span>{{ $progress }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                                <div class="h-2.5 rounded-full {{ $progress >= 100 ? 'bg-emerald-500' : 'bg-indigo-600' }}"
                                                     style="width: {{ $progress }}%"></div>
                                            </div>
                                        </td>

                                        <td class="p-3 align-top text-xs text-gray-600 font-medium">
                                            {{ optional($participation->last_activity_at)->format('d M Y H:i') ?? '-' }}
                                        </td>

                                        <td class="p-3 align-top text-xs">
                                            @if ($latestHistory && $latestHistory->note)
                                                <p class="text-gray-800 font-medium leading-relaxed">
                                                    {{ $latestHistory->note }}
                                                </p>
                                                <p class="text-[10px] text-gray-400 mt-1">
                                                    {{ $latestHistory->created_at->format('d M Y H:i') }}
                                                </p>
                                            @else
                                                <span class="text-gray-400 italic">Belum ada catatan</span>
                                            @endif
                                        </td>
                                    </tr>

                                    @if ($participation->statusHistories->isNotEmpty())
                                    <tr>
                                        <td colspan="5" class="p-3 bg-gray-50/80 border-t border-gray-100">
                                            <details class="group">
                                                <summary class="cursor-pointer font-bold text-xs text-indigo-700 hover:text-indigo-900 flex items-center gap-1">
                                                    <span>Lihat Riwayat Progress & Status ({{ $participation->statusHistories->count() }})</span>
                                                </summary>

                                                <div class="mt-3 space-y-2 pl-2">
                                                    @foreach ($participation->statusHistories->sortByDesc('created_at') as $history)
                                                    <div class="border border-gray-200 rounded-xl p-2.5 bg-white text-xs space-y-1">
                                                        <div class="flex items-center justify-between font-semibold text-gray-700">
                                                            <div>
                                                                <span>{{ $statusLabels[$history->old_status] ?? $history->old_status ?? '-' }}</span>
                                                                <span class="mx-1">→</span>
                                                                <span class="text-indigo-700 font-bold">{{ $statusLabels[$history->new_status] ?? $history->new_status }}</span>
                                                            </div>
                                                            <span class="text-[10px] text-gray-400">{{ $history->created_at->format('d M Y H:i') }}</span>
                                                        </div>
                                                        <div class="text-gray-500">
                                                            Progress: {{ $history->old_progress_percent ?? 0 }}% → <span class="font-bold text-gray-800">{{ $history->new_progress_percent ?? 0 }}%</span>
                                                        </div>
                                                        @if ($history->note)
                                                        <p class="text-gray-700 italic pt-1 border-t border-gray-100">"{{ $history->note }}"</p>
                                                        @endif
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </details>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>

                    <!-- Project Comments & Feedback Card -->
                    <div class="bg-white shadow-sm rounded-2xl border border-gray-200 p-6 space-y-4">
                        <div>
                            <h3 class="font-extrabold text-xl text-gray-900 mb-1">
                                Discussion & Direct Feedback
                            </h3>
                            <p class="text-xs text-gray-500">
                                Berikan arahan, bimbingan, atau umpan balik resmi pengerjaan project kepada mahasiswa.
                            </p>
                        </div>

                        <form action="{{ route('projects.comments.store', $project) }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="hidden" name="comment_type" value="feedback">

                            <textarea
                                name="comment"
                                class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl w-full p-3 text-xs font-medium"
                                rows="3"
                                placeholder="Tulis instruksi atau umpan balik untuk mahasiswa..."
                                required>{{ old('comment') }}</textarea>

                            @error('comment')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror

                            <button type="submit"
                                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition shadow-sm">
                                Kirim Umpan Balik
                            </button>
                        </form>

                        <div class="space-y-3 pt-2">
                            @forelse ($comments->sortByDesc('created_at') as $comment)
                            <div class="border border-gray-200 rounded-xl p-4 bg-gray-50/80 space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-xs text-gray-900">{{ $comment->user->name ?? 'Unknown User' }}</span>
                                    <span class="text-[10px] text-gray-400 font-medium">
                                        {{ ucfirst(str_replace('_', ' ', $comment->comment_type)) }} · {{ $comment->created_at->format('d M Y H:i') }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-700 whitespace-pre-line leading-relaxed">
                                    {{ $comment->comment }}
                                </p>
                            </div>
                            @empty
                            <p class="text-xs text-gray-400 italic">Belum ada komentar pada project ini.</p>
                            @endforelse
                        </div>
                    </div>

                </div>

                <!-- RIGHT COLUMN: Sidebar Controls & Talent Match Preview (4 Cols) -->
                <div class="lg:col-span-4 space-y-6">

                    <!-- Quick Actions Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 space-y-3">
                        <h4 class="font-extrabold text-sm text-gray-900 uppercase tracking-wider">
                            Quick Controls
                        </h4>

                        <a href="{{ route('lecturer.projects.talent-pool', $project) }}"
                           class="w-full py-3 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl text-xs font-black flex items-center justify-center gap-2 shadow-sm transition">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="m10 15 5-3-5-3v6z"/></svg>
                            <span>Buka Talent Screening Engine</span>
                        </a>

                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <a href="{{ route('lecturer.projects.edit', $project) }}"
                               class="text-center py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                                Edit Project
                            </a>

                            <a href="{{ route('lecturer.projects.index') }}"
                               class="text-center py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                                ← Kembali
                            </a>
                        </div>
                    </div>

                    <!-- Skills & Tags Qualifications Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 space-y-4">
                        <h4 class="font-extrabold text-sm text-gray-900 uppercase tracking-wider">
                            Qualification Requirements
                        </h4>

                        <!-- Main Skill Card -->
                        <div class="p-3.5 bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-100 rounded-xl space-y-1.5">
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-600 block">Primary Skill Requirement</span>
                            @forelse ($project->skills as $skill)
                                @if($skill->pivot->is_main)
                                    <div class="font-black text-sm text-indigo-950 flex items-center gap-1.5">
                                        <span>{{ $skill->name }}</span>
                                    </div>
                                @endif
                            @empty
                                <span class="text-xs text-gray-400 italic">No main skill specified.</span>
                            @endforelse
                        </div>

                        <!-- Specialty Tags -->
                        <div>
                            <span class="text-xs font-bold text-gray-700 block mb-1.5">Specialty Tags:</span>
                            <div class="flex flex-wrap gap-1.5">
                                @forelse ($project->tags as $tag)
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold rounded-lg text-xs">
                                        #{{ $tag->name }}
                                    </span>
                                @empty
                                    <span class="text-xs text-gray-400 italic">No specialty tags.</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Compact AI Talent Match Screening Widget (Sidebar Version) -->
                    <div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-purple-950 rounded-2xl p-5 shadow-md text-white space-y-4 border border-indigo-800/50">
                        <div class="flex items-center justify-between border-b border-indigo-800/60 pb-3">
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-indigo-400 block">COMPRO Screening</span>
                                <h4 class="font-black text-base text-white">Top Candidate Match</h4>
                            </div>

                            <a href="{{ route('lecturer.projects.talent-pool', $project) }}"
                               class="text-[11px] font-bold text-indigo-300 hover:text-white underline">
                                Lihat Semua →
                            </a>
                        </div>

                        @if(!isset($recommendedStudents) || $recommendedStudents->isEmpty())
                            <p class="text-xs text-indigo-200 italic">Belum ada mahasiswa yang memenuhi kualifikasi talent pool.</p>
                        @else
                            <div class="space-y-3">
                                @foreach($recommendedStudents as $index => $recStudent)
                                    @php
                                        $mScore = $recStudent->match_score;
                                        $scoreColor = $mScore >= 80 ? 'bg-emerald-500 text-white' : ($mScore >= 60 ? 'bg-indigo-400 text-white' : 'bg-amber-400 text-amber-950');
                                    @endphp

                                    <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-xl p-3 space-y-2.5">
                                        <div class="flex items-center justify-between gap-2">
                                            <div class="flex items-center gap-2">
                                                @if($recStudent->avatar_url)
                                                    <img src="{{ $recStudent->avatar_url }}" class="w-8 h-8 rounded-full object-cover border border-white/20">
                                                @else
                                                    <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-black text-xs border border-white/20">
                                                        {{ strtoupper(substr($recStudent->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div class="overflow-hidden">
                                                    <h5 class="font-bold text-xs text-white truncate max-w-[110px]">{{ $recStudent->name }}</h5>
                                                    <span class="text-[10px] text-indigo-200 font-medium block truncate max-w-[110px]">{{ $recStudent->peminatan ?? 'General' }}</span>
                                                </div>
                                            </div>

                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $scoreColor }}">
                                                {{ $mScore }}%
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-1.5 pt-1 border-t border-white/10">
                                            <a href="{{ route('lecturer.students.portfolio', $recStudent) }}" target="_blank"
                                               class="flex-1 text-center py-1 px-2 bg-white/20 hover:bg-white/30 text-white font-semibold text-[10px] rounded-md transition">
                                                Portofolio
                                            </a>

                                            @if($recStudent->invitation_status === 'invited')
                                                <span class="py-1 px-2 bg-amber-400/20 text-amber-200 font-bold text-[10px] rounded-md">
                                                    Pending
                                                </span>
                                            @elseif(in_array($recStudent->invitation_status, ['in_progress', 'development', 'review', 'completed']))
                                                <span class="py-1 px-2 bg-emerald-400/20 text-emerald-200 font-bold text-[10px] rounded-md">
                                                    Joined
                                                </span>
                                            @else
                                                <form action="{{ route('lecturer.projects.invite', [$project, $recStudent]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="py-1 px-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-[10px] rounded-md transition shadow-sm">
                                                        + Invite
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

        </div>
    </div>
</x-app-layout>