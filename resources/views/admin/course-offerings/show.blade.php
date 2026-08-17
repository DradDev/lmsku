<x-app-layout>
    @php
        $enrollments = $courseOffering->enrollments ?? collect();
        $totalStudents = $enrollments->count();
        $completedStudents = $enrollments->where('status', 'completed')->count();
        $inProgressStudents = $enrollments->where('status', 'in_progress')->count();
        $avgProgress = $totalStudents > 0 ? round($enrollments->avg('progress_percent')) : 0;
        $isTermActive = $courseOffering->academicTerm ? (bool)$courseOffering->academicTerm->is_active : true;
    @endphp

    <div class="py-6" x-data="{ searchQuery: '', statusFilter: 'all' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- HERO HEADER CARD -->
            <div class="bg-white border border-slate-200 shadow-xs rounded-2xl p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <a href="{{ route('admin.course-offerings.index') }}" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 border border-slate-200 flex items-center justify-center font-bold transition flex-shrink-0">
                            ←
                        </a>

                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-widest rounded-md bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ $courseOffering->section_name }}
                                </span>
                                @if($isTermActive)
                                    <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-widest rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        Semester Aktif
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-widest rounded-md bg-slate-100 text-slate-700 border border-slate-200">
                                        Semester Non-Aktif (Arsip)
                                    </span>
                                @endif
                            </div>
                            <h2 class="font-bold text-xl text-slate-900 leading-tight mt-1">
                                {{ $courseOffering->masterCourse->name ?? 'Detail Kelas Penawaran' }}
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Kode: <strong class="text-slate-700">{{ $courseOffering->masterCourse->code ?? '-' }}</strong> • 
                                Semester: <strong class="text-slate-700">{{ $courseOffering->academicTerm->name ?? '-' }}</strong> • 
                                Dosen: <strong class="text-slate-700">{{ $courseOffering->lecturer->name ?? '-' }}</strong>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.course-offerings.edit', $courseOffering) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 transition">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                            <span>Edit Rombel</span>
                        </a>

                        @if($courseOffering->academicTerm)
                            <a href="{{ route('admin.academic-terms.show', $courseOffering->academicTerm) }}"
                               class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                <span>Administrasi Semester</span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- METRIC STATS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600">Total Mahasiswa</span>
                    <div class="flex items-baseline justify-between pt-1">
                        <h3 class="text-3xl font-bold text-slate-900">{{ $totalStudents }}</h3>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 border border-slate-200">
                            Kuota: {{ $courseOffering->capacity ?: 'Unlimited' }}
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500">Terdaftar pada rombel ini</p>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Mahasiswa Selesai</span>
                    <div class="flex items-baseline justify-between pt-1">
                        <h3 class="text-3xl font-bold text-emerald-700">{{ $completedStudents }}</h3>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200">Completed</span>
                    </div>
                    <p class="text-[11px] text-slate-500">Telah lulus perkuliahan</p>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Sedang Belajar</span>
                    <div class="flex items-baseline justify-between pt-1">
                        <h3 class="text-3xl font-bold text-amber-700">{{ $inProgressStudents }}</h3>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-amber-50 text-amber-800 border border-amber-200">In Progress</span>
                    </div>
                    <p class="text-[11px] text-slate-500">Dalam proses pengerjaan</p>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-600">Rata-Rata Progres</span>
                    <div class="flex items-baseline justify-between pt-1">
                        <h3 class="text-3xl font-bold text-indigo-700">{{ $avgProgress }}%</h3>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100">
                            Threshold: {{ $courseOffering->certificate_threshold }}%
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500">Kemajuan materi & kuis</p>
                </div>
            </div>

            <!-- STUDENT ENROLLMENT LIST TABLE -->
            <div class="bg-white border border-slate-200 shadow-xs rounded-2xl overflow-hidden">
                <div class="p-5 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Daftar Mahasiswa & Riwayat Perkuliahan</h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Data riwayat seluruh mahasiswa yang terdaftar di rombel <strong>{{ $courseOffering->section_name }}</strong> ({{ $courseOffering->academicTerm->name ?? 'Semester' }}).
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="text" 
                               x-model="searchQuery" 
                               placeholder="Cari nama / email mahasiswa..." 
                               class="rounded-xl border-slate-300 text-xs focus:border-blue-600 focus:ring-blue-600 p-2.5 w-64">

                        <select x-model="statusFilter" class="rounded-xl border-slate-300 text-xs font-semibold focus:border-blue-600 focus:ring-blue-600 p-2.5">
                            <option value="all">Semua Status</option>
                            <option value="completed">Completed (Lulus)</option>
                            <option value="in_progress">In Progress</option>
                            <option value="not_started">Not Started</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-slate-100/70 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                                <th class="px-5 py-3.5 text-left w-12">#</th>
                                <th class="px-5 py-3.5 text-left">Nama Mahasiswa</th>
                                <th class="px-5 py-3.5 text-left">Email</th>
                                <th class="px-5 py-3.5 text-left">Tanggal Terdaftar</th>
                                <th class="px-5 py-3.5 text-left w-48">Progres Belajar</th>
                                <th class="px-5 py-3.5 text-left">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($enrollments as $enrollment)
                                @php
                                    $student = $enrollment->user;
                                    $progress = $enrollment->progress_percent ?? 0;
                                    $status = $enrollment->status ?? 'not_started';
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition"
                                    x-show="(statusFilter === 'all' || statusFilter === '{{ $status }}') && 
                                            ('{{ strtolower($student->name ?? '') }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($student->email ?? '') }}'.includes(searchQuery.toLowerCase()))">
                                    <td class="px-5 py-3.5 text-slate-400 font-mono">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-800 font-extrabold flex items-center justify-center text-xs flex-shrink-0">
                                                {{ strtoupper(substr($student->name ?? 'M', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900">{{ $student->name ?? 'Unknown Student' }}</p>
                                                <p class="text-[11px] text-slate-400">ID: {{ $student->id ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-5 py-3.5 text-slate-600 font-medium">
                                        {{ $student->email ?? '-' }}
                                    </td>

                                    <td class="px-5 py-3.5 text-slate-600">
                                        {{ $enrollment->created_at ? $enrollment->created_at->format('d M Y, H:i') : '-' }}
                                    </td>

                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center justify-between text-[11px] font-bold mb-1">
                                            <span class="{{ $progress >= ($courseOffering->certificate_threshold ?? 75) ? 'text-emerald-700' : 'text-slate-700' }}">
                                                {{ $progress }}%
                                            </span>
                                            @if($progress >= ($courseOffering->certificate_threshold ?? 75))
                                                <span class="text-[10px] text-emerald-800 font-bold">Lulus Threshold</span>
                                            @endif
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden border border-slate-200">
                                            <div class="h-2 rounded-full {{ $progress >= ($courseOffering->certificate_threshold ?? 75) ? 'bg-emerald-500' : 'bg-blue-600' }}" 
                                                 style="width: {{ min(100, max(0, $progress)) }}%"></div>
                                        </div>
                                    </td>

                                    <td class="px-5 py-3.5">
                                        @if($status === 'completed')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-[11px] font-bold rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                <span>Completed</span>
                                            </span>
                                        @elseif($status === 'in_progress')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-[11px] font-bold rounded-full bg-blue-50 text-blue-800 border border-blue-200">
                                                <span>In Progress</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-[11px] font-bold rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                                <span>Not Started</span>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                        </div>
                                        <h3 class="text-sm font-bold text-slate-800">Belum ada mahasiswa terdaftar</h3>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            Mahasiswa yang melakukan enrollment ke kelas ini akan muncul di sini.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
