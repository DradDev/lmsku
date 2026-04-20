<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-5xl mx-auto px-6">

            <div class="mb-8">
                <a href="{{ route('admin.questions.index') }}"
                   class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 mb-4">
                    ← Back to Question Bank
                </a>

                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                    Admin Panel
                </p>
                <h1 class="text-3xl font-bold text-slate-900">Edit Question</h1>
                <p class="mt-2 text-slate-500">
                    Perbarui detail soal, tipe pertanyaan, jawaban benar, difficulty, dan status review.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <form action="{{ route('admin.questions.update', $question->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">Question</label>
                            <textarea
                                name="question"
                                rows="5"
                                class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none"
                                placeholder="Tulis pertanyaan..."
                                required>{{ old('question', $question->question) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700">Question Type</label>
                                <select
                                    id="question_type"
                                    name="question_type"
                                    class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none"
                                    required>
                                    <option value="essay" {{ old('question_type', $question->question_type) === 'essay' ? 'selected' : '' }}>Essay</option>
                                    <option value="multiple_choice" {{ old('question_type', $question->question_type) === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700">Difficulty</label>
                                <select
                                    name="difficulty"
                                    class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none"
                                    required>
                                    <option value="easy" {{ old('difficulty', $question->difficulty) === 'easy' ? 'selected' : '' }}>Easy</option>
                                    <option value="medium" {{ old('difficulty', $question->difficulty) === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="hard" {{ old('difficulty', $question->difficulty) === 'hard' ? 'selected' : '' }}>Hard</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700">Status</label>
                                <select
                                    name="status"
                                    class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none"
                                    required>
                                    <option value="pending" {{ old('status', $question->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ old('status', $question->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ old('status', $question->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700">Correct Answer</label>
                                <select
                                    id="correct_answer"
                                    name="correct_answer"
                                    class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none">
                                    <option value="">Select Answer</option>
                                    <option value="A" {{ old('correct_answer', $question->correct_answer) === 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('correct_answer', $question->correct_answer) === 'B' ? 'selected' : '' }}>B</option>
                                    <option value="C" {{ old('correct_answer', $question->correct_answer) === 'C' ? 'selected' : '' }}>C</option>
                                    <option value="D" {{ old('correct_answer', $question->correct_answer) === 'D' ? 'selected' : '' }}>D</option>
                                </select>
                            </div>
                        </div>

                        <div id="multiple-choice-fields" class="space-y-4">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <h2 class="text-sm font-semibold text-slate-800 mb-4">Multiple Choice Options</h2>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Option A</label>
                                        <input
                                            type="text"
                                            name="option_a"
                                            value="{{ old('option_a', $question->option_a) }}"
                                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none"
                                            placeholder="Masukkan opsi A">
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Option B</label>
                                        <input
                                            type="text"
                                            name="option_b"
                                            value="{{ old('option_b', $question->option_b) }}"
                                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none"
                                            placeholder="Masukkan opsi B">
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Option C</label>
                                        <input
                                            type="text"
                                            name="option_c"
                                            value="{{ old('option_c', $question->option_c) }}"
                                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none"
                                            placeholder="Masukkan opsi C">
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Option D</label>
                                        <input
                                            type="text"
                                            name="option_d"
                                            value="{{ old('option_d', $question->option_d) }}"
                                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none"
                                            placeholder="Masukkan opsi D">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <button
                                type="submit"
                                class="inline-flex items-center rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">
                                Update Question
                            </button>

                            <a href="{{ route('admin.questions.index') }}"
                               class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>

                <div class="space-y-6">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-slate-900 mb-5">Question Info</h2>

                        <div class="space-y-4">
                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="text-sm text-slate-400">Quiz</p>
                                <p class="mt-1 text-base font-semibold text-slate-900">
                                    {{ $question->quiz->title ?? '-' }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="text-sm text-slate-400">Course</p>
                                <p class="mt-1 text-base font-semibold text-slate-900">
                                    {{ $question->quiz->course->name ?? '-' }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="text-sm text-slate-400">Submitted By</p>
                                <p class="mt-1 text-base font-semibold text-slate-900">
                                    {{ $question->user->name ?? '-' }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="text-sm text-slate-400">Created At</p>
                                <p class="mt-1 text-sm font-medium text-slate-800">
                                    {{ optional($question->created_at)->format('d M Y, H:i') ?: '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-amber-200 bg-amber-50 p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-amber-900 mb-3">Catatan</h2>
                        <p class="text-sm leading-6 text-amber-700">
                            Jika kamu ubah tipe soal menjadi essay, semua opsi dan jawaban benar akan diabaikan saat disimpan.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('question_type');
            const mcFields = document.getElementById('multiple-choice-fields');
            const correctAnswer = document.getElementById('correct_answer');

            function toggleMultipleChoiceFields() {
                const isMultipleChoice = typeSelect.value === 'multiple_choice';

                mcFields.style.display = isMultipleChoice ? 'block' : 'none';
                correctAnswer.disabled = !isMultipleChoice;

                mcFields.querySelectorAll('input').forEach(input => {
                    input.required = isMultipleChoice;
                });
            }

            typeSelect.addEventListener('change', toggleMultipleChoiceFields);
            toggleMultipleChoiceFields();
        });
    </script>
</x-app-layout>
