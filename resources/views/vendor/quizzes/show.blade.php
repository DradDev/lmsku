<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-6 space-y-6">

            {{-- HEADER NAVIGATION --}}
            <div class="flex items-center justify-between mb-4">
                <a href="{{ isset($course) ? route('vendor.courses.show', $course->id) : route('vendor.courses.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-700 transition">
                    ← Kembali ke Course {{ $course->name ?? 'Sertifikasi' }}
                </a>

                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 font-bold text-xs rounded-full border border-indigo-200">
                        ⏱️ Durasi: {{ $quiz->time_limit }} Menit
                    </span>
                    <span class="px-3 py-1 bg-purple-50 text-purple-700 font-bold text-xs rounded-full border border-purple-200">
                        📝 Total Soal: {{ $quiz->questions->count() }}
                    </span>
                </div>
            </div>

            {{-- ALERTS --}}
            @if (session('success'))
                <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-sm text-sm font-medium">
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

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                {{-- Left Column: Add Question Form (5 Cols) --}}
                <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
                    <h3 class="font-extrabold text-base text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                        <span>➕ Tambah Soal Pilihan Ganda Baru</span>
                    </h3>

                    <form action="{{ route('vendor.quizzes.questions.store', $quiz) }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- DIFFICULTY & TYPE -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Difficulty</label>
                                <select name="difficulty" class="w-full rounded-xl border border-slate-300 bg-slate-50 p-2.5 text-xs font-semibold text-slate-900 focus:border-purple-500 focus:outline-none" required>
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
                            <textarea name="question" rows="4" required placeholder="Tulis pertanyaan evaluasi di sini..."
                                      class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-2xl text-xs p-3.5 bg-slate-50/50"></textarea>
                        </div>

                        <div class="space-y-2.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Pilihan Jawaban (Opsi A - D)</label>

                            @foreach(['A' => 'option_a', 'B' => 'option_b', 'C' => 'option_c', 'D' => 'option_d'] as $letter => $field)
                            <div class="flex items-center gap-2">
                                <span class="font-black text-xs text-purple-700 w-5 text-center">{{ $letter }}.</span>
                                <input type="text" name="{{ $field }}" required placeholder="Jawaban {{ $letter }}"
                                       class="flex-1 border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-2.5 bg-white">
                                <label class="flex items-center gap-1.5 text-[11px] text-slate-600 font-bold cursor-pointer bg-slate-100 px-2.5 py-1.5 rounded-lg hover:bg-slate-200 transition">
                                    <input type="radio" name="correct_answer" value="{{ $letter }}" {{ $letter === 'A' ? 'checked' : '' }} class="text-purple-600 border-slate-300">
                                    <span>Kunci</span>
                                </label>
                            </div>
                            @endforeach
                        </div>

                        <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                            💾 Simpan Soal Evaluasi
                        </button>
                    </form>
                </div>

                {{-- Right Column: Question List (7 Cols) --}}
                <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
                    <h3 class="font-extrabold text-base text-slate-900 border-b border-slate-100 pb-3 flex items-center justify-between">
                        <span>📋 Bank Soal Kuis ({{ $quiz->title }})</span>
                        <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2.5 py-0.5 rounded-full">{{ $quiz->questions->count() }} Soal Tersimpan</span>
                    </h3>

                    <div class="space-y-3.5 max-h-[650px] overflow-y-auto pr-1">
                        @forelse($quiz->questions as $qIndex => $q)
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-3 hover:bg-slate-100/60 transition">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="space-y-1.5">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-200 text-slate-700">
                                                No. {{ $qIndex + 1 }}
                                            </span>
                                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-purple-100 text-purple-700">
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

                                    <form action="{{ route('vendor.questions.destroy', $q) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')" class="m-0 flex-shrink-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 bg-rose-50 text-rose-600 hover:bg-rose-100 font-bold text-[11px] rounded-lg transition">🗑️ Hapus</button>
                                    </form>
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
                                Belum ada soal evaluasi ditambahkan. Gunakan formulir di sebelah kiri untuk membuat soal kuis.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
