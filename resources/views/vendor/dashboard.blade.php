<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-lg shadow-sm flex-shrink-0">
                    🏢
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">
                        Vendor & Industry Partner Portal
                    </h2>
                    <p class="text-sm text-gray-500">
                        Kelola sertifikasi industri, publikasikan project real client, dan rekrut talenta mahasiswa.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('vendor.courses.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/>
                    </svg>
                    + Buat Course Sertifikasi
                </a>

                <a href="{{ route('vendor.projects.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                    </svg>
                    + Publikasikan Project
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

            {{-- Compact Tabs Nav (Identical to Lecturer Dashboard) --}}
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
                <a href="{{ route('vendor.dashboard', ['tab' => 'quizzes']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition whitespace-nowrap {{ $tab === 'quizzes' ? 'bg-purple-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    Kuis & Evaluasi ({{ $quizzes->count() }})
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
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">📄</span>
                                    <div>
                                        <h4 class="font-bold text-xs text-gray-900">{{ $mat->title }}</h4>
                                        <p class="text-[11px] text-gray-500">Terhubung ke Course: {{ optional($mat->course)->name ?? 'General' }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('vendor.courses.show', $mat->course_id) }}" class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
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

            {{-- ==================== QUIZZES TAB ==================== --}}
            @if($tab === 'quizzes')
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                    <h3 class="font-extrabold text-base text-gray-900 border-b border-gray-100 pb-3 flex items-center justify-between">
                        <span>📝 Daftar Kuis & Soal Evaluasi Kelulusan Vendor</span>
                        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-full">{{ $quizzes->count() }} Kuis</span>
                    </h3>

                    <div class="space-y-3">
                        @forelse($quizzes as $qz)
                            <div class="p-4 bg-indigo-50/70 border border-indigo-200 rounded-xl flex items-center justify-between hover:bg-indigo-100/60 transition">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-xs text-gray-900">📝 {{ $qz->title }}</span>
                                        <span class="text-[10px] bg-indigo-200 text-indigo-900 px-2 py-0.5 rounded font-black">{{ $qz->questions_count }} Soal</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500 mt-1">Course: {{ optional($qz->course)->name ?? '-' }} | Durasi: {{ $qz->time_limit }} Menit | Tipe: {{ ucfirst($qz->quiz_type) }}</p>
                                </div>

                                <a href="{{ route('vendor.quizzes.show', $qz) }}" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                                    ✏️ Kelola Soal →
                                </a>
                            </div>
                        @empty
                            <div class="p-8 text-center bg-gray-50 border border-dashed border-gray-200 rounded-2xl text-xs text-gray-400">
                                Belum ada kuis evaluasi dibuat. Buka salah satu Course untuk membuat kuis.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
