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

            {{-- ==================== QUIZZES TAB (100% PARITY DENGAN LECTURER DASHBOARD) ==================== --}}
            @if($tab === 'quizzes')
                <div class="space-y-6">

                    {{-- Form Batch Builder Question (Sama Persis Lecturer) --}}
                    <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6 space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-gray-100 pb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <span>📝 Form Tambah / Import Soal Massal (Questions Builder)</span>
                                </h3>
                                <p class="text-xs text-gray-500">
                                    Tambahkan soal kuis secara sekaligus atau pilih Quiz Target untuk menambah bank soal kuis Anda.
                                </p>
                            </div>

                            <button type="button" id="add-question" class="px-4 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 text-xs font-semibold rounded-xl transition flex items-center gap-1.5 self-start sm:self-auto">
                                <span>+ Tambah Nomor Soal</span>
                            </button>
                        </div>

                        <form method="POST" action="{{ route('vendor.questions.store') }}" class="space-y-6">
                            @csrf

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">Quiz Target</label>
                                <select name="quiz_id" id="quiz_id_select" onchange="syncQuizFilter(this.value)" class="w-full rounded-xl border border-gray-200 bg-gray-50/70 p-3 text-sm font-medium text-gray-900 focus:border-purple-500 focus:bg-white focus:outline-none transition" required>
                                    <option value="">-- Pilih Quiz Target --</option>
                                    @forelse($quizzes as $quiz)
                                        <option value="{{ $quiz->id }}" data-question-count="{{ $quiz->questions_count ?? 0 }}" {{ (string) old('quiz_id', request('quiz_id')) === (string) $quiz->id ? 'selected' : '' }}>
                                            {{ $quiz->title }} — {{ $quiz->course->name ?? 'Course' }} ({{ $quiz->questions_count ?? 0 }} Soal Ada)
                                            @if($quiz->quiz_type === 'final')
                                                (Final Quiz Penentu Sertifikat)
                                            @endif
                                        </option>
                                    @empty
                                        <option value="">Belum ada quiz tersedia</option>
                                    @endforelse
                                </select>
                                <p class="text-xs text-gray-400 mt-1">Pilih Quiz untuk mengelola atau menambah soal pada Quiz tersebut.</p>
                            </div>

                            <div id="questions-wrapper" class="space-y-4">
                                <div class="question-card p-4 sm:p-5 bg-gray-50/80 border border-gray-200/80 rounded-2xl space-y-4" data-index="0">
                                    <div class="flex items-center justify-between border-b border-gray-200/60 pb-3">
                                        <span class="question-card-title text-sm font-bold text-gray-800 flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                                            Nomor Soal 1
                                        </span>
                                        <button type="button" class="remove-question hidden px-3 py-1 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold rounded-lg hover:bg-rose-100 transition">
                                            Remove
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Question Type</label>
                                            <input type="hidden" name="questions[0][question_type]" class="question-type" value="multiple_choice">
                                            <div class="w-full rounded-xl border border-gray-200 bg-gray-100 p-2.5 text-xs font-semibold text-gray-700">Multiple Choice</div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Difficulty</label>
                                            <select name="questions[0][difficulty]" class="w-full rounded-xl border border-gray-200 bg-white p-2.5 text-xs font-medium text-gray-800 focus:border-purple-500 focus:outline-none">
                                                <option value="easy">Easy</option>
                                                <option value="medium" selected>Medium</option>
                                                <option value="hard">Hard</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Bidang / Skill Utama</label>
                                            <select name="questions[0][main_skill_id]" class="question-main-skill w-full rounded-xl border border-gray-200 bg-white p-2.5 text-xs font-medium text-gray-800 focus:border-purple-500 focus:outline-none" data-question-index="0">
                                                <option value="">-- Pilih Bidang Utama --</option>
                                                @foreach($mainSkills as $mainSkill)
                                                    <option value="{{ $mainSkill->id }}">{{ $mainSkill->name }}</option>
                                                @endforeach
                                            </select>
                                            <p class="text-[11px] text-gray-400 mt-1">Contoh: Software, ML / AI, Jaringan.</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Detail Skill yang Diuji</label>
                                            @foreach($mainSkills as $mainSkill)
                                                <div class="question-skill-detail-group hidden" data-question-index="0" data-parent-id="{{ $mainSkill->id }}">
                                                    <div class="bg-white border border-gray-200 rounded-xl p-3 space-y-2">
                                                        <p class="text-xs font-bold text-gray-800">Detail {{ $mainSkill->name }}</p>
                                                        @forelse($mainSkill->children as $childSkill)
                                                            <label class="flex items-center gap-2 text-xs text-gray-700 cursor-pointer">
                                                                <input type="checkbox" name="questions[0][skill_ids][]" value="{{ $childSkill->id }}" class="rounded text-purple-600 focus:ring-purple-500">
                                                                {{ $childSkill->name }}
                                                            </label>
                                                        @empty
                                                            <p class="text-[11px] text-gray-400">Belum ada detail skill untuk bidang ini.</p>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Question Text</label>
                                        <textarea name="questions[0][question]" rows="3" class="w-full rounded-xl border border-gray-200 bg-white p-3 text-xs text-gray-900 focus:border-purple-500 focus:outline-none" placeholder="Tuliskan pertanyaan soal di sini..." required></textarea>
                                    </div>

                                    <div class="mc-fields bg-white border border-gray-200 rounded-xl p-4 space-y-3">
                                        <span class="block text-xs font-bold uppercase tracking-wider text-gray-500">Answer Options</span>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            @foreach(['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'] as $key => $label)
                                                <div class="flex items-center gap-2">
                                                    <div class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                                        {{ $label }}
                                                    </div>
                                                    <input type="text" name="questions[0][option_{{ $key }}]" placeholder="Opsi {{ $label }}" class="option-input w-full rounded-xl border border-gray-200 p-2 text-xs focus:border-purple-500 focus:outline-none">
                                                </div>
                                            @endforeach
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Correct Answer</label>
                                            <select name="questions[0][correct_answer]" class="correct-answer w-full rounded-xl border border-gray-200 bg-gray-50 p-2.5 text-xs font-medium text-gray-800 focus:border-purple-500 focus:outline-none">
                                                <option value="">Select correct answer</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                                <option value="D">D</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($quizzes->isEmpty())
                                <div class="p-3 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl text-xs font-medium">
                                    Anda belum memiliki quiz. Buat quiz terlebih dahulu sebelum menambahkan question.
                                </div>
                            @endif

                            <div class="flex items-center justify-end gap-3 pt-2">
                                <button type="button" id="add-question-bottom" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition">
                                    + Tambah Nomor Soal
                                </button>
                                <button type="submit" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded-xl shadow-sm transition disabled:opacity-50" {{ $quizzes->isEmpty() ? 'disabled' : '' }}>
                                    Save All Questions
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Questions List Grouped by Quiz --}}
                    <div class="space-y-5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 border border-gray-200 rounded-2xl shadow-sm">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                                <h3 class="text-base font-bold text-gray-800">All Questions by Quiz</h3>
                            </div>

                            <div class="flex flex-col sm:flex-row items-center gap-2">
                                <select id="filter_quiz_display" onchange="filterQuestionsBySelectedQuiz(this.value)" class="w-full sm:w-auto rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500 text-xs font-semibold text-gray-800 py-2 px-3 shadow-sm cursor-pointer">
                                    <option value="all">-- Filter Semua Quiz --</option>
                                    @foreach($quizzes as $q)
                                        <option value="{{ $q->id }}" {{ (string) request('quiz_id') === (string) $q->id ? 'selected' : '' }}>
                                            {{ $q->title }} ({{ $q->course->name ?? 'Course' }})
                                        </option>
                                    @endforeach
                                </select>

                                <input type="text" id="search_question_input" oninput="searchQuestionsText()" placeholder="🔍 Cari teks soal..." class="w-full sm:w-48 rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500 text-xs py-2 px-3">
                            </div>
                        </div>

                        @forelse($groupedQuestions as $quizId => $quizQuestions)
                            @php
                                $quizRef = $quizQuestions->first()?->quiz;
                                $mcCountPerQuiz = $quizQuestions->count();
                                $isSelectedQuiz = (string) request('quiz_id') === (string) $quizId;
                            @endphp

                            <div class="quiz-card-group bg-white border shadow-sm rounded-2xl p-5 space-y-4 transition {{ $isSelectedQuiz ? 'border-purple-400 ring-2 ring-purple-500/20 bg-purple-50/20' : 'border-gray-100' }}" data-quiz-id="{{ $quizId }}" data-quiz-title="{{ strtolower($quizRef->title ?? '') }}">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-gray-100 pb-3">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-base font-bold text-gray-900">{{ $quizRef->title ?? 'Quiz' }}</h4>
                                            <span class="quiz-selected-badge px-2 py-0.5 bg-purple-600 text-white text-[11px] font-bold rounded-full {{ $isSelectedQuiz ? '' : 'hidden' }}">★ Quiz Terpilih</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ $quizRef->course->name ?? '-' }} • {{ $quizQuestions->count() }} question(s) • Durasi: <span class="font-semibold text-purple-700">{{ $quizRef->time_limit ? $quizRef->time_limit . ' Menit' : 'Tanpa Batas' }}</span>
                                            @if(($quizRef->quiz_type ?? '') === 'final')
                                                • Final Quiz
                                            @endif
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-2 self-start sm:self-auto">
                                        <a href="{{ route('vendor.quizzes.show', $quizId) }}" class="px-3 py-1.5 bg-purple-50 border border-purple-200 text-purple-700 hover:bg-purple-100 text-xs font-semibold rounded-xl transition">
                                            ⚙️ Builder Kuis
                                        </a>
                                        <span class="px-2.5 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">
                                            {{ $mcCountPerQuiz }} Multiple Choice
                                        </span>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    @foreach($quizQuestions->values() as $index => $question)
                                        <div class="question-item-block p-4 bg-gray-50/70 border border-gray-200/70 rounded-xl space-y-3" data-question-text="{{ strtolower($question->question) }}">
                                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                                <div class="space-y-2">
                                                    <div class="flex flex-wrap items-center gap-2">
                                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-gray-200 text-gray-700">
                                                            No. {{ $index + 1 }}
                                                        </span>
                                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-purple-100 text-purple-700">
                                                            Multiple Choice
                                                        </span>
                                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800">
                                                            {{ ucfirst($question->difficulty ?? 'medium') }}
                                                        </span>
                                                    </div>
                                                    <p class="text-xs font-medium text-gray-800 leading-relaxed">{{ $question->question }}</p>
                                                </div>

                                                <div class="flex items-center gap-2 self-end sm:self-auto flex-shrink-0">
                                                    <a href="{{ route('vendor.questions.edit', $question->id) }}"
                                                       class="px-3 py-1 bg-white border border-gray-200 hover:bg-gray-50 text-blue-600 text-xs font-semibold rounded-lg transition">
                                                        Edit
                                                    </a>
                                                    <form method="POST" action="{{ route('vendor.questions.destroy', $question->id) }}" onsubmit="return confirm('Yakin ingin menghapus soal ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="px-3 py-1 bg-rose-50 border border-rose-200 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-lg transition">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1">
                                                @foreach(['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'] as $key => $label)
                                                    @php $isCorrect = strtoupper($question->correct_answer) === $label; @endphp
                                                    <div class="flex items-center gap-2 p-2 rounded-lg border text-xs {{ $isCorrect ? 'bg-emerald-50 border-emerald-200 text-emerald-900 font-medium' : 'bg-white border-gray-200 text-gray-600' }}">
                                                        <span class="w-5 h-5 rounded-md flex items-center justify-center font-bold text-[11px] flex-shrink-0 {{ $isCorrect ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                                            {{ $label }}
                                                        </span>
                                                        <span class="truncate">{{ $question->{'option_' . $key} }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="p-10 text-center border-2 border-dashed border-gray-200 rounded-xl bg-gray-50/50">
                                <p class="text-xs text-gray-400 font-medium">Belum ada soal yang dibuat.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        let questionIndex = document.querySelectorAll('.question-card').length;

                        const wrapper = document.getElementById('questions-wrapper');
                        const addBtnTop = document.getElementById('add-question');
                        const addBtnBottom = document.getElementById('add-question-bottom');

                        function refreshQuestionSkillDetails(selectElement) {
                            const questionIndexValue = selectElement.dataset.questionIndex;
                            const selectedParentId = selectElement.value;

                            const groups = document.querySelectorAll(
                                `.question-skill-detail-group[data-question-index="${questionIndexValue}"]`
                            );

                            groups.forEach(function (group) {
                                if (group.dataset.parentId === selectedParentId) {
                                    group.classList.remove('hidden');
                                } else {
                                    group.classList.add('hidden');

                                    group.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
                                        checkbox.checked = false;
                                    });
                                }
                            });
                        }

                        function setupCard(card) {
                            const mcFields = card.querySelector('.mc-fields');
                            const optionInputs = card.querySelectorAll('.option-input');
                            const correctAnswer = card.querySelector('.correct-answer');
                            const removeBtn = card.querySelector('.remove-question');
                            const mainSkillSelect = card.querySelector('.question-main-skill');

                            if (mcFields) mcFields.classList.remove('hidden');
                            optionInputs.forEach(input => { input.required = true; });
                            if (correctAnswer) correctAnswer.required = true;

                            if (mainSkillSelect) {
                                mainSkillSelect.addEventListener('change', function () {
                                    refreshQuestionSkillDetails(mainSkillSelect);
                                });

                                refreshQuestionSkillDetails(mainSkillSelect);
                            }

                            if (removeBtn) {
                                removeBtn.addEventListener('click', function () {
                                    card.remove();
                                    renumberCards();
                                });
                            }
                        }

                        function renumberCards() {
                            const quizSelect = document.getElementById('quiz_id_select');
                            let offset = 0;
                            if (quizSelect && quizSelect.selectedIndex >= 0) {
                                const selectedOpt = quizSelect.options[quizSelect.selectedIndex];
                                if (selectedOpt && selectedOpt.dataset.questionCount) {
                                    offset = parseInt(selectedOpt.dataset.questionCount) || 0;
                                }
                            }

                            document.querySelectorAll('.question-card').forEach((card, index) => {
                                card.dataset.index = index;
                                const titleEl = card.querySelector('.question-card-title');
                                if (titleEl) {
                                    const actualNo = offset + index + 1;
                                    const continuationBadge = offset > 0 ? ` <span class="text-[11px] font-semibold text-purple-600 bg-purple-100 px-2 py-0.5 rounded-full ml-2">Melanjutkan dari ${offset} Soal Ada</span>` : '';
                                    titleEl.innerHTML = `<span class="w-2 h-2 rounded-full bg-purple-600"></span> Nomor Soal ${actualNo}${continuationBadge}`;
                                }

                                const removeBtn = card.querySelector('.remove-question');
                                if (removeBtn) {
                                    removeBtn.classList.toggle('hidden', index === 0);
                                }

                                card.querySelectorAll('input, textarea, select').forEach(input => {
                                    if (input.name) {
                                        input.name = input.name.replace(/questions\[\d+\]/, `questions[${index}]`);
                                    }
                                });

                                const mainSkillSelect = card.querySelector('.question-main-skill');
                                if (mainSkillSelect) {
                                    mainSkillSelect.dataset.questionIndex = index;
                                }

                                card.querySelectorAll('.question-skill-detail-group').forEach(group => {
                                    group.dataset.questionIndex = index;
                                });
                            });

                            questionIndex = document.querySelectorAll('.question-card').length;
                        }

                        function clearNewCardValues(newCard) {
                            newCard.querySelectorAll('textarea').forEach(textarea => {
                                textarea.value = '';
                            });

                            newCard.querySelectorAll('input').forEach(input => {
                                if (input.type === 'checkbox' || input.type === 'radio') {
                                    input.checked = false;
                                } else if (input.classList.contains('question-type')) {
                                    input.value = 'multiple_choice';
                                } else {
                                    input.value = '';
                                }
                            });

                            newCard.querySelectorAll('select').forEach(select => {
                                if (select.classList.contains('correct-answer')) {
                                    select.value = '';
                                } else {
                                    select.selectedIndex = 0;
                                }
                            });

                            const mcFields = newCard.querySelector('.mc-fields');
                            if (mcFields) mcFields.classList.remove('hidden');
                            newCard.querySelectorAll('.question-skill-detail-group').forEach(group => {
                                group.classList.add('hidden');
                            });
                        }

                        function addQuestion() {
                            const firstCard = document.querySelector('.question-card');
                            if (!firstCard) return;
                            const newCard = firstCard.cloneNode(true);

                            newCard.dataset.index = questionIndex;
                            clearNewCardValues(newCard);

                            wrapper.appendChild(newCard);
                            renumberCards();
                            setupCard(newCard);
                        }

                        if (addBtnTop) {
                            addBtnTop.addEventListener('click', addQuestion);
                        }

                        if (addBtnBottom) {
                            addBtnBottom.addEventListener('click', addQuestion);
                        }

                        function syncQuizFilter(val) {
                            const filterDisplay = document.getElementById('filter_quiz_display');
                            if (filterDisplay) {
                                filterDisplay.value = val || 'all';
                            }
                            renumberCards();
                            applyQuizAndSearchFilters();
                        }

                        function filterQuestionsBySelectedQuiz(val) {
                            const quizSelectTop = document.getElementById('quiz_id_select');
                            if (quizSelectTop && val !== 'all') {
                                quizSelectTop.value = val;
                            }
                            renumberCards();
                            applyQuizAndSearchFilters();
                        }

                        function searchQuestionsText() {
                            applyQuizAndSearchFilters();
                        }

                        function searchQuestionsText() {
                            applyQuizAndSearchFilters();
                        }

                        function applyQuizAndSearchFilters() {
                            const selectedQuizId = document.getElementById('filter_quiz_display') ? document.getElementById('filter_quiz_display').value : 'all';
                            const searchText = document.getElementById('search_question_input') ? document.getElementById('search_question_input').value.toLowerCase().trim() : '';

                            const quizGroups = document.querySelectorAll('.quiz-card-group');

                            quizGroups.forEach(group => {
                                const groupQuizId = group.getAttribute('data-quiz-id');
                                const matchesQuiz = (selectedQuizId === 'all') || (groupQuizId === selectedQuizId);

                                const badge = group.querySelector('.quiz-selected-badge');
                                if (badge) {
                                    if (selectedQuizId !== 'all' && groupQuizId === selectedQuizId) {
                                        badge.classList.remove('hidden');
                                    } else {
                                        badge.classList.add('hidden');
                                    }
                                }

                                const questionItems = group.querySelectorAll('.question-item-block');
                                let visibleQuestionCount = 0;

                                questionItems.forEach(item => {
                                    const qText = item.getAttribute('data-question-text') || '';
                                    const matchesSearch = !searchText || qText.includes(searchText);

                                    if (matchesSearch) {
                                        item.style.display = 'block';
                                        visibleQuestionCount++;
                                    } else {
                                        item.style.display = 'none';
                                    }
                                });

                                if (matchesQuiz && (visibleQuestionCount > 0 || !searchText)) {
                                    group.style.display = 'block';
                                } else {
                                    group.style.display = 'none';
                                }
                            });
                        }

                        window.syncQuizFilter = syncQuizFilter;
                        window.filterQuestionsBySelectedQuiz = filterQuestionsBySelectedQuiz;
                        window.searchQuestionsText = searchQuestionsText;

                        document.querySelectorAll('.question-card').forEach(setupCard);
                        renumberCards();
                        applyQuizAndSearchFilters();
                    });
                </script>
            @endif

        </div>
    </div>
</x-app-layout>
