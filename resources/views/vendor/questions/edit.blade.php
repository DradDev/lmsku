<x-app-layout>
    @php
        $selectedSkillIds = $question->skills->pluck('id')->toArray();
        $mainSkillId = optional($question->skills->firstWhere('parent_id', null))->id;
    @endphp

    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-4xl mx-auto px-6">

            <div class="mb-6">
                <a href="{{ route('vendor.dashboard', ['tab' => 'quizzes']) }}"
                    class="inline-flex items-center text-sm font-semibold text-gray-500 hover:text-gray-700 transition">
                    ← Kembali ke Dashboard Kuis
                </a>

                <h1 class="text-3xl font-bold tracking-tight text-slate-900 mt-2">
                    Edit Soal Quiz Evaluasi Vendor
                </h1>
                <p class="text-sm text-slate-500">
                    Perbarui informasi soal, opsi jawaban, dan skill kompetensi yang diuji.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <form method="POST" action="{{ route('vendor.questions.update', $question->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="question_type" value="multiple_choice">

                    <!-- QUIZ SELECT -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Quiz Target
                        </label>
                        <select name="quiz_id" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-purple-500 focus:outline-none" required>
                            @foreach ($quizzes as $quiz)
                                <option value="{{ $quiz->id }}" @selected((int) old('quiz_id', $question->quiz_id) === (int) $quiz->id)>
                                    {{ $quiz->title }} — {{ $quiz->course->name ?? '-' }}
                                    @if ($quiz->quiz_type === 'final')
                                        (Final Quiz Penentu Sertifikat)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- DIFFICULTY & TYPE -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Difficulty (Tingkat Kesulitan)
                            </label>
                            <select name="difficulty" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-purple-500 focus:outline-none" required>
                                <option value="easy" @selected(old('difficulty', $question->difficulty) === 'easy')>Easy (Mudah)</option>
                                <option value="medium" @selected(old('difficulty', $question->difficulty) === 'medium')>Medium (Sedang)</option>
                                <option value="hard" @selected(old('difficulty', $question->difficulty) === 'hard')>Hard (Sulit)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Tipe Soal
                            </label>
                            <div class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-medium text-slate-600 cursor-not-allowed">
                                Multiple Choice (Pilihan Ganda)
                            </div>
                        </div>
                    </div>

                    <!-- QUESTION TEXT -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Teks Pertanyaan (Question)
                        </label>
                        <textarea name="question" rows="4" required
                            class="w-full rounded-2xl border border-slate-300 bg-slate-50 p-4 text-sm text-slate-900 placeholder:text-slate-400 focus:border-purple-500 focus:outline-none"
                            placeholder="Tulis pertanyaan di sini...">{{ old('question', $question->question) }}</textarea>
                    </div>

                    <!-- MULTIPLE CHOICE OPTIONS -->
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 space-y-4">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500">
                            Opsi Jawaban
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Opsi A</label>
                                <input type="text" name="option_a" value="{{ old('option_a', $question->option_a) }}" required
                                    class="w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-900 focus:border-purple-500 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Opsi B</label>
                                <input type="text" name="option_b" value="{{ old('option_b', $question->option_b) }}" required
                                    class="w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-900 focus:border-purple-500 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Opsi C</label>
                                <input type="text" name="option_c" value="{{ old('option_c', $question->option_c) }}" required
                                    class="w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-900 focus:border-purple-500 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Opsi D</label>
                                <input type="text" name="option_d" value="{{ old('option_d', $question->option_d) }}" required
                                    class="w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-900 focus:border-purple-500 focus:outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Kunci Jawaban Benar</label>
                            <select name="correct_answer" class="w-full rounded-xl border border-slate-300 bg-white p-3 text-sm font-bold text-emerald-800 focus:border-purple-500 focus:outline-none" required>
                                <option value="A" @selected(old('correct_answer', strtoupper($question->correct_answer)) === 'A')>Option A</option>
                                <option value="B" @selected(old('correct_answer', strtoupper($question->correct_answer)) === 'B')>Option B</option>
                                <option value="C" @selected(old('correct_answer', strtoupper($question->correct_answer)) === 'C')>Option C</option>
                                <option value="D" @selected(old('correct_answer', strtoupper($question->correct_answer)) === 'D')>Option D</option>
                            </select>
                        </div>
                    </div>

                    <!-- SUBMIT BUTTONS -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                        <a href="{{ route('vendor.dashboard', ['tab' => 'quizzes']) }}"
                            class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                            Batal
                        </a>

                        <button type="submit"
                            class="rounded-xl bg-purple-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-purple-700 shadow-sm transition">
                            Simpan Perubahan Soal
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
