<x-app-layout>
    <div class="min-h-screen bg-slate-950 text-white py-8">
        <div class="mx-auto max-w-5xl px-6">
            <div class="mb-8">
                <a href="{{ route('lecturer.courses.quizzes.results.index', [$course->id, $quiz->id]) }}"
                   class="text-sm text-blue-400 hover:text-blue-300">
                    ← Kembali ke Hasil Quiz
                </a>

                <div class="mt-3 flex flex-wrap items-center gap-3">
                    <h1 class="text-3xl font-bold">{{ $result->user->name ?? 'Mahasiswa' }}</h1>
                    @if($result->is_verified)
                        <span class="inline-flex rounded-full border border-emerald-500/30 bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-300">
                            Selesai
                        </span>
                    @else
                        <span class="inline-flex rounded-full border border-amber-500/30 bg-amber-500/10 px-3 py-1 text-xs font-semibold text-amber-300">
                            Menunggu Penilaian Essay
                        </span>
                    @endif
                </div>

                <p class="mt-2 text-sm text-slate-400">
                    Quiz: {{ $quiz->title }} — Course: {{ $course->name }}
                </p>
            </div>

            <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="rounded-2xl bg-slate-900 border border-slate-800 p-4">
                    <p class="text-xs uppercase tracking-wider text-slate-500">Score</p>
                    <p class="mt-1 text-3xl font-bold text-indigo-400">{{ $result->score }}</p>
                </div>
                <div class="rounded-2xl bg-slate-900 border border-slate-800 p-4">
                    <p class="text-xs uppercase tracking-wider text-slate-500">Email</p>
                    <p class="mt-1 text-sm font-semibold text-slate-200">{{ $result->user->email ?? '-' }}</p>
                </div>
                <div class="rounded-2xl bg-slate-900 border border-slate-800 p-4">
                    <p class="text-xs uppercase tracking-wider text-slate-500">Submitted At</p>
                    <p class="mt-1 text-sm font-semibold text-slate-200">
                        {{ optional($result->created_at)->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-800 bg-slate-900 shadow-2xl shadow-black/20 overflow-hidden">
                <div class="border-b border-slate-800 px-6 py-5">
                    <h2 class="text-xl font-semibold text-white">Detail Jawaban</h2>
                    <p class="mt-1 text-sm text-slate-500">Rincian jawaban mahasiswa per soal.</p>
                </div>

                @if($result->answers && $result->answers->count())
                    <div class="divide-y divide-slate-800">
                        @foreach($result->answers as $answer)
                            <div class="px-6 py-5">
                                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 flex-wrap mb-2">
                                            @if($answer->question)
                                                <span class="inline-flex rounded-full bg-slate-800 px-3 py-1 text-xs font-semibold text-slate-300">
                                                    {{ $answer->question->question_type === 'essay' ? 'Essay' : 'Multiple Choice' }}
                                                </span>
                                            @endif
                                            @if(!is_null($answer->is_correct))
                                                @if($answer->is_correct)
                                                    <span class="inline-flex rounded-full border border-emerald-500/30 bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-300">Correct</span>
                                                @else
                                                    <span class="inline-flex rounded-full border border-rose-500/30 bg-rose-500/10 px-3 py-1 text-xs font-semibold text-rose-300">Incorrect</span>
                                                @endif
                                            @endif
                                        </div>

                                        <h3 class="text-base font-semibold text-white">
                                            {{ $answer->question->question ?? 'Question not found' }}
                                        </h3>

                                        @if($answer->question && $answer->question->question_type === 'multiple_choice')
                                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                                <div class="rounded-xl bg-slate-800 border border-slate-700 p-3">
                                                    <p class="text-slate-500">Selected Option</p>
                                                    <p class="mt-1 font-semibold text-slate-100">{{ $answer->selected_option ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-xl bg-slate-800 border border-slate-700 p-3">
                                                    <p class="text-slate-500">Correct Answer</p>
                                                    <p class="mt-1 font-semibold text-slate-100">{{ $answer->question->correct_answer ?? '-' }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="mt-4 rounded-xl bg-slate-800 border border-slate-700 p-4">
                                                <p class="text-sm text-slate-500 mb-2">Student Answer</p>
                                                <p class="text-slate-200 whitespace-pre-line">{{ $answer->answer_text ?? '-' }}</p>
                                            </div>
                                        @endif

                                        @if(!empty($answer->feedback))
                                            <div class="mt-4 rounded-xl bg-amber-500/10 border border-amber-500/30 p-4">
                                                <p class="text-sm font-semibold text-amber-300 mb-2">Feedback Kamu</p>
                                                <p class="text-sm text-amber-200">{{ $answer->feedback }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="w-full lg:w-40">
                                        <div class="rounded-2xl bg-slate-800 border border-slate-700 p-4 text-center">
                                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Answer Score</p>
                                            <p class="mt-2 text-2xl font-bold text-indigo-400">
                                                {{ is_null($answer->score) ? '-' : $answer->score }}
                                            </p>
                                        </div>

                                        @if($answer->question && $answer->question->question_type === 'essay')
                                            <a href="{{ route('lecturer.courses.quizzes.answers.index', [$course->id, $quiz->id]) }}"
                                               class="mt-3 block text-center rounded-xl bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-700">
                                                Nilai / Edit Feedback
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <h3 class="text-lg font-semibold text-white">Belum ada detail jawaban</h3>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>