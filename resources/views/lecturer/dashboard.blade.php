<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shadow-sm flex-shrink-0">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                        <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                        <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">
                        Author Dashboard
                    </h2>
                    <p class="text-sm text-gray-500">
                        Kelola materi pembelajaran, kuis, soal, dan pengajaran Anda.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('lecturer.courses.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14"/>
                        <path d="M5 12h14"/>
                    </svg>
                    New Course
                </a>
                <a href="{{ route('lecturer.courses.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-semibold rounded-xl shadow-sm transition">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/>
                    </svg>
                    Manage Courses
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
                    Learning Materials
                </a>
                <a href="{{ route('lecturer.dashboard', ['tab' => 'questions']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition whitespace-nowrap {{ $tab === 'questions' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    Questions & Builder
                </a>
            </div>

            {{-- ==================== OVERVIEW TAB ==================== --}}
            @if($tab === 'overview')
                {{-- Compact Stat Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-4 flex items-center gap-4 hover:shadow-md transition">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14,2 14,8 20,8"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Materials</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-0.5">{{ $materials->count() }}</h3>
                            <p class="text-xs text-gray-500">Materi diunggah</p>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-4 flex items-center gap-4 hover:shadow-md transition">
                        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                                <line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Questions</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-0.5">{{ $questions->count() }}</h3>
                            <p class="text-xs text-gray-500">Soal dibuat</p>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-4 flex items-center gap-4 hover:shadow-md transition">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 11 12 14 22 4"/>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Multiple Choice</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-0.5">{{ $questions->where('question_type', 'multiple_choice')->count() }}</h3>
                            <p class="text-xs text-gray-500">Pilihan ganda</p>
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
                                    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Courses</p>
                                    <p class="text-sm font-semibold text-gray-800 mt-0.5">Kelola Kursus & Materi</p>
                                </div>
                            </a>

                            <a href="{{ route('lecturer.dashboard', ['tab' => 'questions']) }}"
                               class="p-3.5 bg-gray-50 hover:bg-purple-50/60 border border-gray-100 hover:border-purple-200 rounded-xl transition flex items-center gap-3 group">
                                <div class="w-9 h-9 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-purple-600 uppercase tracking-wider">Questions</p>
                                    <p class="text-sm font-semibold text-gray-800 mt-0.5">Buat Bank Soal</p>
                                </div>
                            </a>

                            <a href="{{ route('lecturer.courses.index') }}"
                               class="p-3.5 bg-gray-50 hover:bg-emerald-50/60 border border-gray-100 hover:border-emerald-200 rounded-xl transition flex items-center gap-3 group">
                                <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">My Courses</p>
                                    <p class="text-sm font-semibold text-gray-800 mt-0.5">Daftar Kursus Saya</p>
                                </div>
                            </a>

                            <a href="{{ route('lecturer.dashboard', ['tab' => 'materials']) }}"
                               class="p-3.5 bg-gray-50 hover:bg-amber-50/60 border border-gray-100 hover:border-amber-200 rounded-xl transition flex items-center gap-3 group">
                                <div class="w-9 h-9 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Materials</p>
                                    <p class="text-sm font-semibold text-gray-800 mt-0.5">Learning Materials</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Recent Questions --}}
                    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-5 space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                                Recent Questions
                            </h3>
                            <a href="{{ route('lecturer.dashboard', ['tab' => 'questions']) }}" class="text-xs font-semibold text-purple-600 hover:text-purple-700 transition">
                                View all →
                            </a>
                        </div>

                        <div class="space-y-3">
                            @forelse($questions->take(5) as $question)
                                <div class="p-3 bg-gray-50/80 border border-gray-100 rounded-xl hover:bg-gray-50 transition space-y-1.5">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                            {{ $question->question_type === 'essay' ? 'Essay' : 'Multiple Choice' }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                            {{ ucfirst($question->difficulty) }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-700 line-clamp-2 leading-relaxed font-medium">
                                        {{ $question->question }}
                                    </p>
                                </div>
                            @empty
                                <div class="p-8 text-center border-2 border-dashed border-gray-200 rounded-xl bg-gray-50/50">
                                    <p class="text-xs text-gray-400">Belum ada soal yang dibuat.</p>
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
                                Kelola materi yang digunakan untuk pembelajaran kursus.
                            </p>
                        </div>

                        <a href="{{ route('lecturer.courses.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-sm transition self-start sm:self-auto">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14"/>
                                <path d="M5 12h14"/>
                            </svg>
                            + Add Material via Course
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($materials as $material)
                            <div class="p-4 bg-gray-50/70 border border-gray-100 rounded-xl hover:border-gray-200 hover:bg-white hover:shadow-sm transition flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <polyline points="14,2 14,8 20,8"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-gray-900">{{ $material->title ?? 'Untitled Material' }}</h4>
                                        @if(!empty($material->description))
                                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">{{ $material->description }}</p>
                                        @endif
                                        @if($material->course)
                                            <span class="inline-flex items-center mt-2 px-2 py-0.5 rounded-md bg-gray-200/70 text-gray-700 text-[11px] font-medium">
                                                Course: {{ $material->course->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 self-end sm:self-center">
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

            {{-- ==================== QUESTIONS TAB ==================== --}}
            @if($tab === 'questions')
                @php
                    $groupedQuestions = $questions->groupBy('quiz_id');
                @endphp

                @if(isset($retakeRequests) && $retakeRequests->isNotEmpty())
                    <div class="bg-amber-50/70 border border-amber-200/80 shadow-sm rounded-2xl p-5 mb-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                                <span>📩 Permintaan Retake Final Quiz</span>
                                <span class="px-2.5 py-0.5 rounded-full bg-amber-500 text-white text-xs font-bold">{{ $retakeRequests->where('status', 'pending')->count() }} Pending</span>
                            </h3>
                            <span class="text-xs text-gray-500">Passing Score Minimal: 70</span>
                        </div>

                        <div class="space-y-3">
                            @foreach($retakeRequests as $req)
                                <div class="p-4 bg-white border border-gray-200 rounded-xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-bold text-sm text-gray-900">{{ $req->user->name ?? 'Mahasiswa' }}</h4>
                                            <span class="px-2 py-0.5 text-[11px] font-bold rounded-full {{ $req->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($req->status === 'rejected' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                                                {{ ucfirst($req->status) }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Course: <strong>{{ $req->course->name ?? 'Course' }}</strong> • Quiz: <strong class="text-gray-800">{{ $req->quiz->title ?? 'Quiz' }}</strong> • Diajukan: {{ $req->created_at->format('d M Y H:i') }}
                                        </p>
                                    </div>

                                    @if($req->status === 'pending')
                                        <div class="flex items-center gap-2 self-end sm:self-auto">
                                            <form method="POST" action="{{ route('lecturer.quizzes.retake.approve', $req->id) }}">
                                                @csrf
                                                <button type="submit" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                                                    ✅ Setujui Retake (+1 Attempt)
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('lecturer.quizzes.retake.reject', $req->id) }}">
                                                @csrf
                                                <button type="submit" class="px-3 py-2 bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-100 text-xs font-bold rounded-xl transition">
                                                    ❌ Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <p class="text-xs font-semibold text-gray-400">
                                            Ditinjau pada {{ $req->reviewed_at ? $req->reviewed_at->format('d M Y H:i') : '-' }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Create Questions Builder Card --}}
                <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-5 sm:p-6 space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-gray-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center flex-shrink-0">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-800">Create Questions for One Quiz</h3>
                                <p class="text-xs text-gray-500">Buat banyak nomor soal dalam 1 kuis, lalu simpan sekaligus.</p>
                            </div>
                        </div>

                        <button type="button" id="add-question"
                                class="inline-flex items-center gap-2 px-3.5 py-2 bg-purple-50 border border-purple-200 hover:bg-purple-100 text-purple-700 text-xs font-semibold rounded-xl transition self-start sm:self-auto">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                            + Tambah Nomor Soal
                        </button>
                    </div>

                    <form method="POST" action="{{ route('lecturer.questions.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">Quiz Target</label>
                            <select name="quiz_id" id="quiz_id_select" onchange="syncQuizFilter(this.value)" class="w-full rounded-xl border border-gray-200 bg-gray-50/70 p-3 text-sm font-medium text-gray-900 focus:border-purple-500 focus:bg-white focus:outline-none transition" required>
                                <option value="">-- Pilih Quiz Target --</option>
                                @forelse($quizzes as $quiz)
                                    <option value="{{ $quiz->id }}" data-question-count="{{ $quiz->questions_count ?? 0 }}" {{ (string) old('quiz_id', request('quiz_id')) === (string) $quiz->id ? 'selected' : '' }}>
                                        {{ $quiz->title }} — {{ $quiz->course->name ?? 'Course' }} ({{ $quiz->questions_count ?? 0 }} Soal Ada)
                                        @if($quiz->quiz_type === 'final')
                                            (Final Quiz)
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
                                        @if($quizRef->quiz_type === 'final')
                                            • Final Quiz
                                        @endif
                                    </p>
                                    @if($quizRef->start_date || $quizRef->end_date)
                                        <p class="text-[11px] text-gray-400 mt-0.5">
                                            Jadwal: {{ $quizRef->start_date ? \Carbon\Carbon::parse($quizRef->start_date)->format('d M Y H:i') : 'Mulai Sekarang' }} — {{ $quizRef->end_date ? \Carbon\Carbon::parse($quizRef->end_date)->format('d M Y H:i') : 'Tanpa Tenggat' }}
                                        </p>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2 self-start sm:self-auto">
                                    @if($quizRef && $quizRef->course)
                                        <button type="button" onclick="document.getElementById('edit-dashboard-quiz-form-{{ $quizId }}').classList.toggle('hidden')" class="px-3 py-1.5 bg-amber-50 border border-amber-200 text-amber-700 hover:bg-amber-100 text-xs font-semibold rounded-xl transition">
                                            ⚙️ Edit Waktu & Durasi
                                        </button>
                                    @endif
                                    <span class="px-2.5 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">
                                        {{ $mcCountPerQuiz }} Multiple Choice
                                    </span>
                                </div>
                            </div>

                            @if($quizRef && $quizRef->course)
                                <div id="edit-dashboard-quiz-form-{{ $quizId }}" class="hidden p-4 bg-amber-50/50 border border-amber-200/80 rounded-xl space-y-3">
                                    <h5 class="text-xs font-bold text-amber-900 flex items-center gap-1.5">
                                        <span>⚙️ Edit Waktu & Pengaturan Quiz</span>
                                        <span class="text-amber-700">({{ $quizRef->title }})</span>
                                    </h5>
                                    <form method="POST" action="{{ route('lecturer.courses.quizzes.update', [$quizRef->course_id, $quizRef->id]) }}" class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                        @csrf
                                        @method('PUT')

                                        <div>
                                            <label class="block font-bold text-gray-700 mb-1">Judul Quiz</label>
                                            <input type="text" name="title" value="{{ old('title', $quizRef->title) }}" class="w-full rounded-xl border-gray-300 p-2 text-xs" required>
                                        </div>

                                        <div>
                                            <label class="block font-bold text-gray-700 mb-1">Durasi (Menit)</label>
                                            <input type="number" name="time_limit" value="{{ old('time_limit', $quizRef->time_limit) }}" min="1" placeholder="Bebas / Tanpa Limit" class="w-full rounded-xl border-gray-300 p-2 text-xs">
                                        </div>

                                        <div>
                                            <label class="block font-bold text-gray-700 mb-1">Max Attempts</label>
                                            <input type="number" name="max_attempts" value="{{ old('max_attempts', $quizRef->max_attempts) }}" min="1" max="100" class="w-full rounded-xl border-gray-300 p-2 text-xs" required>
                                        </div>

                                        <div>
                                            <label class="block font-bold text-gray-700 mb-1">Start Date</label>
                                            <input type="datetime-local" name="start_date" value="{{ $quizRef->start_date ? \Carbon\Carbon::parse($quizRef->start_date)->format('Y-m-d\TH:i') : '' }}" class="w-full rounded-xl border-gray-300 p-2 text-xs">
                                        </div>

                                        <div class="md:col-span-2">
                                            <label class="block font-bold text-gray-700 mb-1">End Date / Deadline Baru</label>
                                            <input type="datetime-local" name="end_date" value="{{ $quizRef->end_date ? \Carbon\Carbon::parse($quizRef->end_date)->format('Y-m-d\TH:i') : '' }}" class="w-full rounded-xl border-gray-300 p-2 text-xs">
                                        </div>

                                        <div class="md:col-span-2 flex justify-end gap-2 pt-2">
                                            <button type="button" onclick="document.getElementById('edit-dashboard-quiz-form-{{ $quizId }}').classList.add('hidden')" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg">Batal</button>
                                            <button type="submit" class="px-4 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg shadow-sm">Simpan Waktu & Tenggat Baru</button>
                                        </div>
                                    </form>
                                </div>
                            @endif

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
                                                        {{ $question->question_type === 'essay' ? 'Essay' : 'Multiple Choice' }}
                                                    </span>
                                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800">
                                                        {{ ucfirst($question->difficulty) }}
                                                    </span>
                                                </div>
                                                <p class="text-xs font-medium text-gray-800 leading-relaxed">{{ $question->question }}</p>
                                            </div>

                                            <div class="flex items-center gap-2 self-end sm:self-auto flex-shrink-0">
                                                <a href="{{ route('lecturer.questions.edit', $question->id) }}"
                                                   class="px-3 py-1 bg-white border border-gray-200 hover:bg-gray-50 text-blue-600 text-xs font-semibold rounded-lg transition">
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('lecturer.questions.destroy', $question->id) }}" onsubmit="return confirm('Yakin ingin menghapus soal ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1 bg-rose-50 border border-rose-200 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-lg transition">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        @if($question->question_type === 'multiple_choice')
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1">
                                                @foreach(['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'] as $key => $label)
                                                    @php $isCorrect = $question->correct_answer === $label; @endphp
                                                    <div class="flex items-center gap-2 p-2 rounded-lg border text-xs {{ $isCorrect ? 'bg-emerald-50 border-emerald-200 text-emerald-900 font-medium' : 'bg-white border-gray-200 text-gray-600' }}">
                                                        <span class="w-5 h-5 rounded-md flex items-center justify-center font-bold text-[11px] flex-shrink-0 {{ $isCorrect ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                                            {{ $label }}
                                                        </span>
                                                        <span class="truncate">{{ $question->{'option_' . $key} }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="p-2.5 bg-gray-100 border border-gray-200 rounded-lg text-xs text-gray-500">
                                                Essay question — penilaian dilakukan secara manual.
                                            </div>
                                        @endif
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