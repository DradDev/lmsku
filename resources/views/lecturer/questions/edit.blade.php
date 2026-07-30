<x-app-layout>
    @php
        $selectedSkillIds = $question->skills->pluck('id')->toArray();
        $mainSkillId = optional($question->skills->firstWhere('parent_id', null))->id;
    @endphp

    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-4xl mx-auto px-6">

            <div class="mb-6">
                <a href="{{ route('lecturer.dashboard', ['tab' => 'questions', 'quiz_id' => $question->quiz_id]) }}"
                    class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-700">
                    ← Kembali ke Daftar Soal
                </a>

                <h1 class="text-3xl font-bold tracking-tight text-slate-900 mt-2">
                    Edit Soal Quiz
                </h1>
                <p class="text-sm text-slate-500">
                    Perbarui informasi soal, opsi jawaban, dan skill yang diuji.
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
                <form method="POST" action="{{ route('lecturer.questions.update', $question->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="question_type" value="multiple_choice">

                    <!-- QUIZ SELECT -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Quiz Target
                        </label>
                        <select name="quiz_id" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none" required>
                            @foreach ($quizzes as $quiz)
                                <option value="{{ $quiz->id }}" @selected((int) old('quiz_id', $question->quiz_id) === (int) $quiz->id)>
                                    {{ $quiz->title }} — {{ $quiz->course->name ?? '-' }}
                                    @if ($quiz->quiz_type === 'final')
                                        (Final Quiz)
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
                            <select name="difficulty" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none" required>
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
                            class="w-full rounded-2xl border border-slate-300 bg-slate-50 p-4 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none"
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
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Opsi B</label>
                                <input type="text" name="option_b" value="{{ old('option_b', $question->option_b) }}" required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Opsi C</label>
                                <input type="text" name="option_c" value="{{ old('option_c', $question->option_c) }}" required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Opsi D</label>
                                <input type="text" name="option_d" value="{{ old('option_d', $question->option_d) }}" required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Kunci Jawaban Benar (Correct Answer)
                            </label>
                            <select name="correct_answer" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-900 focus:border-indigo-500 focus:outline-none" required>
                                <option value="">-- Pilih Jawaban Benar --</option>
                                <option value="A" @selected(old('correct_answer', $question->correct_answer) === 'A')>A</option>
                                <option value="B" @selected(old('correct_answer', $question->correct_answer) === 'B')>B</option>
                                <option value="C" @selected(old('correct_answer', $question->correct_answer) === 'C')>C</option>
                                <option value="D" @selected(old('correct_answer', $question->correct_answer) === 'D')>D</option>
                            </select>
                        </div>
                    </div>

                    <!-- SKILL & SUB SKILLS -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Bidang / Skill Utama
                            </label>
                            <select id="main_skill_select" name="main_skill_id" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none">
                                <option value="">-- Pilih Bidang Utama --</option>
                                @foreach ($mainSkills as $mainSkill)
                                    <option value="{{ $mainSkill->id }}" @selected((int) old('main_skill_id', $mainSkillId) === (int) $mainSkill->id)>
                                        {{ $mainSkill->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Detail Skill yang Diuji
                            </label>
                            @foreach ($mainSkills as $mainSkill)
                                <div class="skill-detail-group hidden" data-parent-id="{{ $mainSkill->id }}">
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 space-y-2">
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">
                                            Detail {{ $mainSkill->name }}
                                        </p>

                                        @forelse ($mainSkill->children as $childSkill)
                                            <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                                                <input type="checkbox" name="skill_ids[]" value="{{ $childSkill->id }}"
                                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                                    @checked(in_array($childSkill->id, old('skill_ids', $selectedSkillIds)))>
                                                <span>{{ $childSkill->name }}</span>
                                            </label>
                                        @empty
                                            <p class="text-xs text-slate-400">Belum ada detail skill untuk bidang ini.</p>
                                        @endforelse
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('lecturer.dashboard', ['tab' => 'questions', 'quiz_id' => $question->quiz_id]) }}"
                            class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mainSkillSelect = document.getElementById('main_skill_select');
            const detailGroups = document.querySelectorAll('.skill-detail-group');

            function showSelectedSkillDetails() {
                const selectedMainSkillId = mainSkillSelect.value;

                detailGroups.forEach(function (group) {
                    if (group.dataset.parentId === selectedMainSkillId) {
                        group.classList.remove('hidden');
                    } else {
                        group.classList.add('hidden');
                    }
                });
            }

            if (mainSkillSelect) {
                mainSkillSelect.addEventListener('change', showSelectedSkillDetails);
                showSelectedSkillDetails();
            }
        });
    </script>
</x-app-layout>