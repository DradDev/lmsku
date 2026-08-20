<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shadow-sm flex-shrink-0 font-bold text-lg">
                    👨‍🏫
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">
                        Portal Dosen & Pengajaran
                    </h2>
                    <p class="text-sm text-gray-500">
                        Kelola kelas akademik, silabus materi, bank kuis, dan proyek mahasiswa.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('lecturer.courses.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/>
                    </svg>
                    Kelola Kelas Saya
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Alerts --}}
            @if(session('success'))
                <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-sm text-sm font-medium">
                    <svg class="mt-0.5 flex-shrink-0 text-emerald-600" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                    <div>
                        <p class="font-bold">Berhasil</p>
                        <p class="mt-0.5 text-emerald-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl shadow-sm text-sm">
                    <div class="flex items-center gap-2 font-bold mb-1 text-rose-900">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        Mohon periksa kembali kesalahan berikut:
                    </div>
                    <ul class="list-disc pl-6 space-y-1 text-rose-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Compact Tabs Nav --}}
            <div class="flex items-center gap-1.5 p-1.5 bg-white border border-gray-200 rounded-2xl shadow-sm overflow-x-auto">
                <a href="{{ route('lecturer.dashboard', ['tab' => 'overview']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition whitespace-nowrap {{ $tab === 'overview' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    Overview
                </a>
                <a href="{{ route('lecturer.dashboard', ['tab' => 'materials']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition whitespace-nowrap {{ $tab === 'materials' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14,2 14,8 20,8"/>
                    </svg>
                    Learning Materials ({{ $materials->count() }})
                </a>
            </div>

            {{-- ==================== OVERVIEW TAB ==================== --}}
            @if($tab === 'overview')
                {{-- Compact Stat Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-4 flex items-center gap-4 hover:shadow-md transition">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 text-xl font-bold">
                            🏫
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Kelas / Rombel</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-0.5">{{ $offerings->count() }}</h3>
                            <p class="text-xs text-gray-500">Rombel diampu</p>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-4 flex items-center gap-4 hover:shadow-md transition">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0 text-xl font-bold">
                            👥
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Mahasiswa</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-0.5">{{ $totalStudents }}</h3>
                            <p class="text-xs text-gray-500">Mahasiswa terdaftar</p>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-4 flex items-center gap-4 hover:shadow-md transition">
                        <div class="w-12 h-12 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center flex-shrink-0 text-xl font-bold">
                            📄
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Materials</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-0.5">{{ $materials->count() }}</h3>
                            <p class="text-xs text-gray-500">Materi diunggah</p>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-4 flex items-center gap-4 hover:shadow-md transition">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 text-xl font-bold">
                            📝
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Kuis</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-0.5">{{ $quizzes->count() }}</h3>
                            <p class="text-xs text-gray-500">Kuis kurikulum</p>
                        </div>
                    </div>
                </div>

                {{-- Two Column Panels --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Quick Actions --}}
                    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-5 space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                Quick Actions
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="{{ route('lecturer.courses.index') }}"
                               class="p-3.5 bg-gray-50 hover:bg-blue-50/60 border border-gray-100 hover:border-blue-200 rounded-xl transition flex items-center gap-3 group">
                                <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Kelas & Kuis</p>
                                    <p class="text-sm font-semibold text-gray-800 mt-0.5">Kelola Kelas Saya</p>
                                </div>
                            </a>

                            <a href="{{ route('lecturer.projects.index') }}"
                               class="p-3.5 bg-gray-50 hover:bg-purple-50/60 border border-gray-100 hover:border-purple-200 rounded-xl transition flex items-center gap-3 group">
                                <div class="w-9 h-9 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-purple-600 uppercase tracking-wider">Proyek Industri</p>
                                    <p class="text-sm font-semibold text-gray-800 mt-0.5">Bimbingan Proyek</p>
                                </div>
                            </a>

                            <a href="{{ route('lecturer.dashboard', ['tab' => 'materials']) }}"
                               class="p-3.5 bg-gray-50 hover:bg-amber-50/60 border border-gray-100 hover:border-amber-200 rounded-xl transition flex items-center gap-3 group">
                                <div class="w-9 h-9 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Materi Modul</p>
                                    <p class="text-sm font-semibold text-gray-800 mt-0.5">Daftar Modul Diunggah</p>
                                </div>
                            </a>

                            <a href="{{ route('lecturer.courses.index') }}"
                               class="p-3.5 bg-gray-50 hover:bg-emerald-50/60 border border-gray-100 hover:border-emerald-200 rounded-xl transition flex items-center gap-3 group">
                                <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Kelola Rombel</p>
                                    <p class="text-sm font-semibold text-gray-800 mt-0.5">Class Switcher & Nilai</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Recent Courses / Classes Diampu --}}
                    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-5 space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                                Kelas & Rombel yang Diampu
                            </h3>
                            <a href="{{ route('lecturer.courses.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition">
                                Lihat Semua →
                            </a>
                        </div>

                        <div class="space-y-3">
                            @forelse($offerings->take(5) as $offering)
                                <div class="p-3.5 bg-gray-50/80 border border-gray-100 rounded-xl hover:bg-gray-50 transition flex items-center justify-between gap-3">
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h4 class="font-bold text-xs text-gray-900">
                                                {{ $offering->masterCourse->name ?? ($offering->name ?? 'Course') }}
                                            </h4>
                                            @if($offering->section_name)
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-indigo-100 text-indigo-700">
                                                    {{ $offering->section_name }}
                                                </span>
                                            @endif
                                            @if($offering->academicTerm)
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $offering->academicTerm->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-600' }}">
                                                    {{ $offering->academicTerm->name }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-gray-500 mt-1">
                                            👥 {{ $offering->enrollments ? $offering->enrollments->count() : 0 }} Mahasiswa &bull; KKM: {{ $offering->certificate_threshold ?? 75 }}%
                                        </p>
                                    </div>

                                    <a href="{{ route('lecturer.courses.show', $offering->id) }}"
                                       class="px-3 py-1.5 bg-white border border-gray-200 hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-200 text-gray-700 font-bold text-xs rounded-xl shadow-xs transition whitespace-nowrap">
                                        Kelola →
                                    </a>
                                </div>
                            @empty
                                <div class="p-8 text-center border-2 border-dashed border-gray-200 rounded-xl bg-gray-50/50">
                                    <p class="text-xs text-gray-400">Belum ada kelas yang ditugaskan kepada Anda.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            {{-- ==================== MATERIALS TAB ==================== --}}
            @if($tab === 'materials')
                <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-5 space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-100 pb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                                Learning Materials
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">
                                Seluruh modul materi pembelajaran yang telah Anda unggah pada mata kuliah diampu.
                            </p>
                        </div>

                        <a href="{{ route('lecturer.courses.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-sm transition self-start sm:self-auto">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14"/>
                                <path d="M5 12h14"/>
                            </svg>
                            + Kelola Materi via Kelas
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($materials as $material)
                            <div class="p-4 bg-gray-50/70 border border-gray-100 rounded-xl hover:border-gray-200 hover:bg-white hover:shadow-sm transition flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 mt-0.5 font-bold">
                                        📄
                                    </div>
                                    <div class="space-y-1">
                                        <h4 class="font-bold text-sm text-gray-900">{{ $material->title ?? 'Untitled Material' }}</h4>
                                        @if(!empty($material->description))
                                            <p class="text-xs text-gray-500 leading-relaxed">{{ $material->description }}</p>
                                        @endif
                                        <div class="flex flex-wrap items-center gap-2 text-[11px] text-gray-500">
                                            @if($material->course)
                                                <span>Course: <strong class="text-gray-700">{{ $material->course->name }}</strong></span>
                                                <span>&bull;</span>
                                            @endif
                                            <span>Diunggah: {{ optional($material->created_at)->format('d M Y') ?: '-' }}</span>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-1.5 pt-1">
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Diterapkan pada:</span>
                                            @if($material->is_all_classes)
                                                <span class="px-2 py-0.5 text-[10px] font-bold bg-blue-50 text-blue-700 rounded-md border border-blue-200">
                                                    Semua Kelas Rombel
                                                </span>
                                            @elseif(!empty($material->assigned_offerings) && $material->assigned_offerings->isNotEmpty())
                                                @foreach($material->assigned_offerings as $assignedOff)
                                                    <span class="px-2 py-0.5 text-[10px] font-semibold bg-white text-slate-700 rounded-md border border-slate-200 shadow-2xs">
                                                        {{ $assignedOff->section_name ?: 'Rombel ' . $assignedOff->id }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="px-2 py-0.5 text-[10px] font-semibold bg-slate-100 text-slate-600 rounded-md">
                                                    Umum
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 self-end sm:self-auto flex-shrink-0">
                                    <a href="{{ route('lecturer.materials.show', $material->id) }}"
                                       class="px-3 py-1.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-semibold rounded-lg shadow-sm transition">
                                        View
                                    </a>
                                    <a href="{{ route('lecturer.materials.edit', $material->id) }}"
                                       class="px-3 py-1.5 bg-blue-50 border border-blue-200 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg transition">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('lecturer.materials.destroy', $material->id) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus materi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 bg-rose-50 border border-rose-200 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-lg transition">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-10 text-center border-2 border-dashed border-gray-200 rounded-xl bg-gray-50/50 space-y-2">
                                <svg class="mx-auto text-gray-300" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14,2 14,8 20,8"/>
                                </svg>
                                <p class="text-xs text-gray-500 font-medium">Belum ada learning material yang dibuat.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>