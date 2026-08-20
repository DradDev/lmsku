<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-6 space-y-6">

            {{-- HEADER NAVIGATION --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-2">
                <a href="{{ isset($course) ? route('lecturer.courses.show', $course->id) : route('lecturer.courses.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-700 transition">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali ke Kelas {{ $course->name ?? 'Course' }}</span>
                </a>

                <div class="flex items-center flex-wrap gap-2">
                    @if(isset($course->academicTerm))
                        <span class="px-3 py-1 bg-slate-100 text-slate-700 font-bold text-xs rounded-full border border-slate-200">
                            🏫 {{ $course->academicTerm->name }}
                        </span>
                    @endif
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 font-bold text-xs rounded-full border border-indigo-200">
                        ⏱️ Durasi: {{ $quiz->time_limit ? $quiz->time_limit . ' Menit' : 'Tanpa Batas' }}
                    </span>
                    <span class="px-3 py-1 {{ $quiz->quiz_type === 'final' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-purple-50 text-purple-700 border-purple-200' }} font-bold text-xs rounded-full border">
                        🏷️ {{ $quiz->quiz_type === 'final' ? 'Final Quiz' : ($quiz->quiz_type === 'weekly' ? 'Weekly Quiz' : 'Daily Quiz') }}
                    </span>
                    <span class="px-3 py-1 bg-violet-50 text-violet-700 font-bold text-xs rounded-full border border-violet-200">
                        📝 Total Soal: {{ $quiz->questions->count() }}
                    </span>
                </div>
            </div>

            {{-- ALERTS --}}
            @if (session('success'))
                <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-sm text-sm font-medium">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-emerald-900">Berhasil!</p>
                        <p class="mt-0.5 text-emerald-700 text-xs sm:text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-center gap-3 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl shadow-sm text-sm font-medium">
                    <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-rose-900">Pemberitahuan</p>
                        <p class="mt-0.5 text-rose-700 text-xs sm:text-sm">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl shadow-sm text-sm">
                    <div class="flex items-center gap-2 font-bold mb-1 text-rose-900">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        Mohon periksa kembali kesalahan berikut:
                    </div>
                    <ul class="list-disc pl-6 space-y-1 text-rose-700 text-xs">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(!($isTermActive ?? true))
                <div class="p-4 bg-amber-50 border border-amber-200 text-amber-900 rounded-2xl flex items-center justify-between gap-3 shadow-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold flex-shrink-0">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-sm text-amber-950">Semester Non-Aktif (Mode Read-Only)</p>
                            <p class="text-xs text-amber-800">Periode semester ini telah ditutup. Pengelolaan butir soal kuis dikunci untuk arsip dan tidak dapat dimodifikasi.</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-amber-200/80 text-amber-900 rounded-xl text-xs font-extrabold whitespace-nowrap">
                        Terkunci
                    </span>
                </div>
            @endif

            {{-- 2-COLUMN QUIZ & QUESTIONS MANAGEMENT INTERFACE --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                {{-- Left Column: Add Question Form (5 Cols) --}}
                <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
                    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                        <h3 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                            <span>➕ Tambah Soal Pilihan Ganda</span>
                        </h3>
                        <span class="text-[11px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100">
                            {{ $quiz->title }}
                        </span>
                    </div>

                    @if($isTermActive ?? true)
                        <form action="{{ route('lecturer.quizzes.questions.store', $quiz) }}" method="POST" class="space-y-4">
                            @csrf

                            <!-- DIFFICULTY & TYPE -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tingkat Kesulitan</label>
                                    <select name="difficulty" class="w-full rounded-xl border border-slate-300 bg-slate-50 p-2.5 text-xs font-semibold text-slate-900 focus:border-indigo-500 focus:outline-none" required>
                                        <option value="easy">Easy (Mudah)</option>
                                        <option value="medium" selected>Medium (Sedang)</option>
                                        <option value="hard">Hard (Sulit)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tipe Soal</label>
                                    <div class="w-full rounded-xl border border-slate-200 bg-slate-100 p-2.5 text-xs font-bold text-slate-600 cursor-not-allowed">
                                        Multiple Choice
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Teks Pertanyaan (Question)</label>
                                <textarea name="question" rows="4" required placeholder="Tuliskan pertanyaan evaluasi di sini..."
                                          class="w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl text-xs p-3.5 bg-slate-50/50"></textarea>
                            </div>

                            <div class="space-y-2.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Pilihan Jawaban (Opsi A - D)</label>

                                @foreach(['A' => 'option_a', 'B' => 'option_b', 'C' => 'option_c', 'D' => 'option_d'] as $letter => $field)
                                <div class="flex items-center gap-2">
                                    <span class="font-black text-xs text-indigo-700 w-5 text-center">{{ $letter }}.</span>
                                    <input type="text" name="{{ $field }}" required placeholder="Jawaban {{ $letter }}"
                                           class="flex-1 border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-2.5 bg-white">
                                    <label class="flex items-center gap-1.5 text-[11px] text-slate-600 font-bold cursor-pointer bg-slate-100 px-2.5 py-1.5 rounded-lg hover:bg-slate-200 transition">
                                        <input type="radio" name="correct_answer" value="{{ $letter }}" {{ $letter === 'A' ? 'checked' : '' }} class="text-indigo-600 border-slate-300 focus:ring-indigo-500">
                                        <span>Kunci</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>

                            <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                                💾 Simpan Soal Evaluasi
                            </button>
                        </form>
                    @else
                        <div class="p-6 text-center bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-500 space-y-2">
                            <p class="font-bold text-slate-700">Form Input Dinonaktifkan</p>
                            <p>Penambahan soal baru tidak diizinkan pada semester yang telah ditutup.</p>
                        </div>
                    @endif
                </div>

                {{-- Right Column: Question List (7 Cols) --}}
                <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
                    <h3 class="font-extrabold text-base text-slate-900 border-b border-slate-100 pb-3 flex items-center justify-between">
                        <span>📋 Bank Soal Kuis ({{ $quiz->title }})</span>
                        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-full border border-indigo-100">{{ $quiz->questions->count() }} Soal Tersimpan</span>
                    </h3>

                    <div class="space-y-3.5 max-h-[650px] overflow-y-auto pr-1">
                        @forelse($quiz->questions as $qIndex => $q)
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-3 hover:bg-slate-100/60 transition">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="space-y-1.5">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-200 text-slate-700">
                                                No. {{ $loop->iteration }}
                                            </span>
                                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-indigo-100 text-indigo-700">
                                                Multiple Choice
                                            </span>
                                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                {{ ucfirst($q->difficulty ?? 'medium') }}
                                            </span>
                                        </div>
                                        <h4 class="font-extrabold text-xs text-slate-900 leading-relaxed pt-1">
                                            {{ $q->question }}
                                        </h4>
                                    </div>

                                    @if($isTermActive ?? true)
                                        <form action="{{ route('lecturer.questions.destroy', $q) }}" method="POST" onsubmit="return confirm('Hapus butir soal ini?')" class="m-0 flex-shrink-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 bg-rose-50 text-rose-600 hover:bg-rose-100 font-bold text-[11px] rounded-lg transition border border-rose-200">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <div class="grid grid-cols-2 gap-2 text-xs pt-1">
                                    @foreach(['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d] as $letter => $val)
                                        <div class="p-2.5 rounded-xl border {{ strtoupper($q->correct_answer) === $letter ? 'bg-emerald-100/80 border-emerald-300 text-emerald-950 font-extrabold shadow-sm' : 'bg-white border-slate-200 text-slate-700 font-medium' }}">
                                            <span class="font-black mr-1">{{ $letter }}.</span> {{ $val }}
                                            @if(strtoupper($q->correct_answer) === $letter)
                                                <span class="text-[10px] text-emerald-800 font-black ml-1 bg-emerald-200 px-1.5 py-0.5 rounded">(Kunci)</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center bg-slate-50 border border-dashed border-slate-200 rounded-2xl text-xs text-slate-400">
                                Belum ada butir soal pada kuis ini. Gunakan formulir di sebelah kiri untuk menambahkan soal pilihan ganda.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
