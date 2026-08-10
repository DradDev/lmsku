<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <span>📁 Detail Project & Managing Dashboard</span>
            </h2>

            <div class="flex items-center gap-2">
                <a href="{{ route('vendor.projects.index') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                    ← Kembali
                </a>

                <a href="{{ route('vendor.projects.edit', $project) }}"
                   class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                    ✏️ Edit Project
                </a>
            </div>
        </div>
    </x-slot>

    @php
    $joinedCount = $project->participations->count();
    $maxStudents = $project->max_students ?? 1;
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

            <!-- 2-Column Responsive Dashboard Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                <!-- LEFT COLUMN: Main Overview & Activities (8 Cols) -->
                <div class="lg:col-span-8 space-y-6">

                    <!-- Hero Executive Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-5">
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="px-2.5 py-0.5 text-xs font-extrabold uppercase rounded-md bg-purple-100 text-purple-800 border border-purple-200">
                                    🏢 External: {{ Auth::user()->name }}
                                </span>

                                <span class="px-2.5 py-0.5 rounded-md text-xs font-bold {{ $project->is_published ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                    {{ $project->is_published ? '🟢 Published' : '🔴 Draft' }}
                                </span>

                                <span class="px-2.5 py-0.5 bg-gray-100 text-gray-700 rounded-md text-xs font-semibold">
                                    Level: {{ ucfirst($project->difficulty_level) }}
                                </span>
                            </div>

                            <h3 class="text-2xl font-black text-gray-900 leading-tight">
                                {{ $project->title }}
                            </h3>
                        </div>

                        <!-- Description -->
                        <div class="pt-3 border-t border-gray-100 space-y-2">
                            <h4 class="font-bold text-sm text-gray-900 uppercase tracking-wide">
                                📋 Deskripsi & Deliverables Project Client
                            </h4>
                            <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">
                                {{ $project->description }}
                            </p>
                        </div>

                        <!-- Benefits & Brief File Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                            @if($project->benefits)
                                <div class="p-3.5 bg-indigo-50/70 border border-indigo-100 rounded-xl space-y-1">
                                    <h5 class="font-bold text-xs text-indigo-900 uppercase tracking-wider">
                                        🎁 Benefits & Insentif Mahasiswa
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
                                            📄 Berkas TOR / Client Brief
                                        </h5>
                                        <p class="text-xs text-emerald-800 mt-0.5">Berkas instruksi resmi pengerjaan project.</p>
                                    </div>
                                    <a href="{{ $project->brief_file_url }}" target="_blank"
                                       class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg transition shadow-sm">
                                        📥 Download TOR Brief (PDF/ZIP)
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
                                    👥 Student Talent & Progress Monitor
                                </h3>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Pantau progress pengerjaan mahasiswa yang terdaftar dalam project ini.
                                </p>
                            </div>

                            <span class="px-3 py-1 bg-indigo-50 text-indigo-800 border border-indigo-100 rounded-full text-xs font-black self-start sm:self-auto">
                                {{ $joinedCount }} / {{ $maxStudents }} Mahasiswa Terdaftar
                            </span>
                        </div>

                        @if ($project->participations->isEmpty())
                        <div class="border border-gray-200 rounded-xl p-8 bg-gray-50 text-center space-y-3">
                            <div class="w-12 h-12 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center mx-auto text-xl">
                                👨‍💻
                            </div>
                            <p class="text-gray-600 text-sm font-semibold">
                                Belum ada mahasiswa yang terdaftar pada project industri ini.
                            </p>
                            <p class="text-xs text-gray-500">
                                Gunakan widget <strong>Talent Screening Engine</strong> di sebelah kanan untuk merekrut mahasiswa.
                            </p>
                        </div>
                        @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                                <thead>
                                    <tr class="bg-gray-50 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        <th class="p-3 border-b border-gray-200">Mahasiswa</th>
                                        <th class="p-3 border-b border-gray-200">Status</th>
                                        <th class="p-3 border-b border-gray-200">Progress</th>
                                        <th class="p-3 border-b border-gray-200">Aktivitas Terakhir</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($project->participations as $participation)
                                    <tr>
                                        <td class="p-3 align-top">
                                            <div class="font-bold text-gray-900">{{ $participation->user->name ?? '-' }}</div>
                                            <div class="text-xs text-gray-500">{{ $participation->user->email ?? '-' }}</div>
                                        </td>

                                        <td class="p-3 align-top">
                                            <span class="inline-block px-2.5 py-1 rounded-md text-xs font-bold bg-blue-100 text-blue-800">
                                                {{ ucfirst($participation->status) }}
                                            </span>
                                        </td>

                                        <td class="p-3 align-top min-w-[160px]">
                                            <div class="flex justify-between text-xs text-gray-600 font-semibold mb-1">
                                                <span>Progress</span>
                                                <span>{{ $participation->progress_percent ?? 0 }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                                <div class="h-2 rounded-full bg-purple-600" style="width: {{ $participation->progress_percent ?? 0 }}%"></div>
                                            </div>
                                        </td>

                                        <td class="p-3 align-top text-xs text-gray-600 font-medium">
                                            {{ optional($participation->last_activity_at)->format('d M Y H:i') ?? '-' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>

                </div>

                <!-- RIGHT COLUMN: Sidebar Controls & Talent Match Preview (4 Cols) -->
                <div class="lg:col-span-4 space-y-6">

                    <!-- Quick Actions Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 space-y-3">
                        <h4 class="font-extrabold text-sm text-gray-900 uppercase tracking-wider">
                            ⚡ Quick Controls
                        </h4>

                        <a href="{{ route('vendor.projects.talent-pool', $project) }}"
                           class="w-full py-3 px-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl text-xs font-black flex items-center justify-center gap-2 shadow-sm transition">
                            <span>🎯 Talent Screening Engine</span>
                        </a>

                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <a href="{{ route('vendor.projects.edit', $project) }}"
                               class="text-center py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                                ✏️ Edit Project
                            </a>

                            <a href="{{ route('vendor.projects.index') }}"
                               class="text-center py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                                ← Kembali
                            </a>
                        </div>
                    </div>

                    <!-- Skills & Tags Qualifications Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 space-y-4">
                        <h4 class="font-extrabold text-sm text-gray-900 uppercase tracking-wider">
                            ⭐ Qualification Requirements
                        </h4>

                        <div class="p-3.5 bg-indigo-50 border border-indigo-100 rounded-xl space-y-1">
                            <span class="text-[10px] font-extrabold uppercase text-indigo-600 block">Primary Skill Requirement</span>
                            <div class="font-black text-sm text-indigo-950">
                                ⭐ {{ $mainSkill->name ?? 'General Skill' }}
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
