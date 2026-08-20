<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center shadow-sm flex-shrink-0 font-bold text-lg">
                    🏢
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">
                        Vendor Partner Dashboard
                    </h2>
                    <p class="text-sm text-gray-500">
                        Kelola Program Pelatihan Industri, Sertifikasi Mahasiswa, dan Proyek Real-Client.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('vendor.courses.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/>
                    </svg>
                    Kelola Course Sertifikasi
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

            @if(session('error'))
                <div class="flex items-start gap-3 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl shadow-sm text-sm font-medium">
                    <svg class="mt-0.5 flex-shrink-0 text-rose-600" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                    <div>
                        <p class="font-bold">Pemberitahuan</p>
                        <p class="mt-0.5 text-rose-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- Compact Tabs Nav --}}
            <div class="flex items-center gap-1.5 p-1.5 bg-white border border-gray-200 rounded-2xl shadow-sm overflow-x-auto">
                <a href="{{ route('vendor.dashboard', ['tab' => 'overview']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition whitespace-nowrap {{ $tab === 'overview' ? 'bg-purple-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    Overview
                </a>
                <a href="{{ route('vendor.dashboard', ['tab' => 'materials']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition whitespace-nowrap {{ $tab === 'materials' ? 'bg-purple-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14,2 14,8 20,8"/>
                    </svg>
                    Learning Materials ({{ $materials->count() }})
                </a>
            </div>

            {{-- ==================== OVERVIEW TAB ==================== --}}
            @if($tab === 'overview')
                <!-- Executive Stat Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4 hover:shadow-md transition">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold">
                            🎓
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Course Sertifikasi</span>
                            <span class="text-2xl font-black text-gray-900">{{ $totalCourses }}</span>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4 hover:shadow-md transition">
                        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl font-bold">
                            📁
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Project Industri</span>
                            <span class="text-2xl font-black text-gray-900">{{ $totalProjects }}</span>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4 hover:shadow-md transition">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                            👨‍🎓
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Mahasiswa Enrolled</span>
                            <span class="text-2xl font-black text-gray-900">{{ $totalEnrolledStudents }}</span>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4 hover:shadow-md transition">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                            🎯
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Talent Rekrutmen</span>
                            <span class="text-2xl font-black text-gray-900">{{ $totalProjectStudents }}</span>
                        </div>
                    </div>
                </div>

                <!-- Main Management 2-Column Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Left Card: Industry Certified Courses -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <div>
                                <h3 class="font-extrabold text-base text-gray-900 flex items-center gap-2">
                                    <span>🎓 Course Sertifikasi Industri</span>
                                </h3>
                                <p class="text-xs text-gray-500">Pelatihan kompetensi & sertifikasi profesional mitra.</p>
                            </div>
                            <a href="{{ route('vendor.courses.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                                Lihat Semua →
                            </a>
                        </div>

                        @if($courses->isEmpty())
                            <div class="p-6 bg-gray-50 border border-dashed border-gray-200 rounded-xl text-center space-y-2">
                                <p class="text-xs text-gray-500 font-medium">Belum ada course sertifikasi industri yang dibuat.</p>
                                <a href="{{ route('vendor.courses.create') }}" class="inline-block px-3 py-1.5 bg-indigo-600 text-white font-bold text-xs rounded-lg">
                                    + Buat Course Pertama
                                </a>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($courses->take(4) as $course)
                                    <div class="p-3.5 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-between hover:bg-gray-100/80 transition">
                                        <div>
                                            <h4 class="font-bold text-xs text-gray-900">{{ $course->name }}</h4>
                                            <p class="text-[11px] text-gray-500 mt-0.5">
                                                Level: {{ $course->level }} | 👥 {{ $course->students_count }} Mahasiswa
                                            </p>
                                        </div>
                                        <a href="{{ route('vendor.courses.show', $course) }}" class="px-3 py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-bold text-xs rounded-lg transition">
                                            Kelola →
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Right Card: Industry Real Projects -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <div>
                                <h3 class="font-extrabold text-base text-gray-900 flex items-center gap-2">
                                    <span>📁 Project Real Client Industri</span>
                                </h3>
                                <p class="text-xs text-gray-500">Studi kasus nyata industri & seleksi talenta.</p>
                            </div>
                            <a href="{{ route('vendor.projects.index') }}" class="text-xs font-bold text-purple-600 hover:text-purple-800">
                                Lihat Semua →
                            </a>
                        </div>

                        @if($projects->isEmpty())
                            <div class="p-6 bg-gray-50 border border-dashed border-gray-200 rounded-xl text-center space-y-2">
                                <p class="text-xs text-gray-500 font-medium">Belum ada project industri dipublikasikan.</p>
                                <a href="{{ route('vendor.projects.create') }}" class="inline-block px-3 py-1.5 bg-purple-600 text-white font-bold text-xs rounded-lg">
                                    + Publikasikan Project
                                </a>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($projects->take(4) as $proj)
                                    <div class="p-3.5 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-between hover:bg-gray-100/80 transition">
                                        <div>
                                            <h4 class="font-bold text-xs text-gray-900">{{ $proj->title }}</h4>
                                            <p class="text-[11px] text-gray-500 mt-0.5">
                                                Tipe: {{ ucfirst($proj->type ?? 'General') }} | 🎯 {{ $proj->participations_count }} Pelamar
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('vendor.projects.talent-pool', $proj) }}" class="px-2.5 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-100 font-bold text-xs rounded-lg transition">
                                                Talent Pool
                                            </a>
                                            <a href="{{ route('vendor.projects.show', $proj) }}" class="px-2.5 py-1.5 bg-purple-50 text-purple-700 hover:bg-purple-100 font-bold text-xs rounded-lg transition">
                                                Detail →
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>
            @endif

            {{-- ==================== MATERIALS TAB ==================== --}}
            @if($tab === 'materials')
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                    <h3 class="font-extrabold text-base text-gray-900 border-b border-gray-100 pb-3 flex items-center justify-between">
                        <span>📑 Daftar Seluruh Modul Pembelajaran Vendor</span>
                        <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2.5 py-0.5 rounded-full">{{ $materials->count() }} Modul</span>
                    </h3>

                    <div class="space-y-3">
                        @forelse($materials as $mat)
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-between hover:bg-gray-100/80 transition">
                                <div class="flex items-start gap-3">
                                    <span class="text-xl mt-0.5">📄</span>
                                    <div class="space-y-1">
                                        <h4 class="font-bold text-xs text-gray-900">{{ $mat->title }}</h4>
                                        <p class="text-[11px] text-gray-500">Program Sertifikasi: <strong class="text-gray-700">{{ optional($mat->course)->name ?? 'General' }}</strong></p>
                                        <div class="flex flex-wrap items-center gap-1.5 pt-0.5">
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Diterapkan pada:</span>
                                            @if($mat->is_all_classes)
                                                <span class="px-2 py-0.5 text-[10px] font-bold bg-purple-50 text-purple-700 rounded-md border border-purple-200">
                                                    Semua Batch
                                                </span>
                                            @elseif(!empty($mat->assigned_offerings) && $mat->assigned_offerings->isNotEmpty())
                                                @foreach($mat->assigned_offerings as $assignedBatch)
                                                    <span class="px-2 py-0.5 text-[10px] font-semibold bg-white text-slate-700 rounded-md border border-slate-200 shadow-2xs">
                                                        {{ $assignedBatch->section_name ?: 'Batch ' . $assignedBatch->id }}
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
                                <a href="{{ route('vendor.courses.show', $mat->course_offering_id ?? ($mat->master_course_id ?? 1)) }}" class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                                    Buka Course →
                                </a>
                            </div>
                        @empty
                            <div class="p-8 text-center bg-gray-50 border border-dashed border-gray-200 rounded-2xl text-xs text-gray-400">
                                Belum ada modul pembelajaran diunggah. Buka salah satu Course untuk mengunggah materi.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
