<x-app-layout>
    <div class="min-h-screen bg-slate-950 text-white py-8">
        <div class="mx-auto max-w-6xl px-6">
            <div class="mb-8">
                <a href="{{ route('lecturer.courses.show', $course->id) }}"
                   class="text-sm text-blue-400 hover:text-blue-300">
                    ← Back to Course
                </a>

                <h1 class="mt-3 text-3xl font-bold">Grade Essay Answers</h1>
                <p class="mt-2 text-sm text-slate-400">
                    Quiz: {{ $quiz->title }} — Course: {{ $course->name }}
                </p>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-sm text-rose-300">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-6">
                @forelse($essayAnswers as $answer)
                    <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-2xl shadow-black/20">
                        <div class="mb-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-white">
                                    {{ $answer->user->name ?? 'Mahasiswa' }}
                                </h2>
                                <p class="text-sm text-slate-400">
                                    Submitted:
                                    {{ $answer->created_at ? $answer->created_at->format('d M Y H:i') : '-' }}
                                </p>
                            </div>

                            <div class="text-sm text-slate-300">
                                Attempt Score:
                                <span class="font-semibold text-purple-400">
                                    {{ $answer->attempt->score ?? 0 }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-4 rounded-2xl border border-slate-800 bg-slate-950 p-4">
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Soal Essay
                            </p>
                            <p class="text-slate-200">
                                {{ $answer->question->question ?? '-' }}
                            </p>
                        </div>

                        <div class="mb-5 rounded-2xl border border-slate-800 bg-slate-950 p-4">
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Jawaban Mahasiswa
                            </p>
                            <p class="whitespace-pre-line text-slate-200">
                                {{ $answer->answer_text ?: 'Belum ada jawaban.' }}
                            </p>
                        </div>

                        <form method="POST"
                              action="{{ route('lecturer.courses.quizzes.answers.grade', [$course->id, $quiz->id, $answer->id]) }}"
                              class="grid gap-4 md:grid-cols-[180px_1fr_auto]">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-300">
                                    Nilai (0 - 100)
                                </label>
                                <input
                                    type="number"
                                    name="score"
                                    min="0"
                                    max="100"
                                    value="{{ old('score', $answer->score) }}"
                                    class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-sm text-white focus:border-blue-500 focus:outline-none"
                                    required
                                >
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-300">
                                    Feedback
                                </label>
                                <textarea
                                    name="feedback"
                                    rows="3"
                                    class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-sm text-white focus:border-blue-500 focus:outline-none"
                                    placeholder="Tulis feedback untuk mahasiswa...">{{ old('feedback', $answer->feedback) }}</textarea>
                            </div>

                            <div class="flex items-end">
                                <button
                                    type="submit"
                                    class="rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-500">
                                    Simpan Nilai
                                </button>
                            </div>
                        </form>
                    </div>
                @empty
                    <div class="rounded-3xl border border-slate-800 bg-slate-900 p-8 text-center text-slate-400">
                        Belum ada jawaban essay dari mahasiswa untuk quiz ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
