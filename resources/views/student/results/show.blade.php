<x-app-layout>
<div class="min-h-screen bg-slate-950 text-white">
    <div class="mx-auto max-w-4xl px-4 py-8">

        <a href="{{ route('student.results.index') }}"
           class="text-sm text-slate-400 hover:text-white">
            ← Kembali ke Quiz Results
        </a>

        {{-- Header Card --}}
        <div class="mt-6 rounded-3xl border border-slate-800 bg-slate-900 p-6">
            <div class="mb-4 flex flex-wrap gap-2">
                @if($result->is_verified)
                    <span class="rounded-full bg-emerald-500/15 px-3 py-1 text-xs font-semibold text-emerald-300">
                        Sudah Dinilai
                    </span>
                @else
                    <span class="rounded-full bg-amber-500/15 px-3 py-1 text-xs font-semibold text-amber-300">
                        Menunggu Penilaian Essay
                    </span>
                @endif
                @if(!empty($result->blockchain_hash))
                    <span class="rounded-full bg-blue-500/15 px-3 py-1 text-xs font-semibold text-blue-300">
                        Blockchain Recorded
                    </span>
                @endif
            </div>

            <h1 class="text-2xl font-bold text-white">
                {{ $result->quiz->title ?? 'Quiz Result' }}
            </h1>
            <p class="mt-2 text-sm text-slate-400">
                Course: <span class="text-slate-200">{{ $result->quiz->course->name ?? '-' }}</span>
            </p>
            <p class="mt-1 text-sm text-slate-400">
                Date: <span class="text-slate-200">{{ $result->created_at?->format('d M Y H:i') ?? '-' }}</span>
            </p>

            <div class="mt-8 rounded-2xl bg-slate-950 p-6 text-center">
                <p class="text-sm uppercase tracking-widest text-slate-500">Final Score</p>
                <h2 class="mt-3 text-5xl font-bold text-purple-400">{{ $result->score }}</h2>
            </div>

            @if(!empty($result->blockchain_hash))
                <div class="mt-6 rounded-2xl border border-slate-800 bg-slate-950 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Blockchain Hash</p>
                    <p class="break-all text-sm text-slate-300">{{ $result->blockchain_hash }}</p>
                </div>
            @endif
        </div>

        {{-- Detail Jawaban --}}
        @php
            $answers = $result->answers()->with('question')->get();
            $mcAnswers = $answers->filter(fn($a) => $a->question?->question_type === 'multiple_choice');
            $essayAnswers = $answers->filter(fn($a) => $a->question?->question_type === 'essay');
        @endphp

        @if($mcAnswers->count() > 0)
            <div class="mt-6">
                <h2 class="text-lg font-semibold text-white mb-3">Detail Jawaban Pilihan Ganda</h2>
                <div class="space-y-3">
                    @foreach($mcAnswers as $i => $answer)
                        @php $correct = $answer->is_correct; @endphp
                        <div class="rounded-2xl border {{ $correct ? 'border-emerald-700 bg-emerald-950/40' : 'border-rose-800 bg-rose-950/30' }} p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <p class="text-xs font-semibold uppercase tracking-wider {{ $correct ? 'text-emerald-400' : 'text-rose-400' }} mb-1">
                                        Soal {{ $i + 1 }} — {{ $correct ? '✓ Benar' : '✗ Salah' }}
                                    </p>
                                    <p class="text-sm text-slate-200 leading-relaxed">
                                        {{ $answer->question->question ?? '-' }}
                                    </p>
                                </div>
                                <span class="shrink-0 rounded-full px-2 py-1 text-xs font-bold {{ $correct ? 'bg-emerald-500/20 text-emerald-300' : 'bg-rose-500/20 text-rose-300' }}">
                                    {{ $correct ? '+1' : '0' }}
                                </span>
                            </div>

                            <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                                <div class="rounded-lg bg-slate-800/60 px-3 py-2">
                                    <span class="text-slate-500">Jawaban kamu: </span>
                                    <span class="font-semibold {{ $correct ? 'text-emerald-300' : 'text-rose-300' }}">
                                        {{ $answer->selected_option ?? '-' }}
                                    </span>
                                </div>
                                @if(!$correct)
                                    <div class="rounded-lg bg-slate-800/60 px-3 py-2">
                                        <span class="text-slate-500">Jawaban benar: </span>
                                        <span class="font-semibold text-emerald-300">
                                            {{ $answer->question->correct_answer ?? '-' }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($essayAnswers->count() > 0)
            <div class="mt-6">
                <h2 class="text-lg font-semibold text-white mb-3">Detail Jawaban Essay</h2>
                <div class="space-y-3">
                    @foreach($essayAnswers as $i => $answer)
                        <div class="rounded-2xl border border-slate-700 bg-slate-900 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-violet-400 mb-1">
                                Essay {{ $i + 1 }}
                                @if(!is_null($answer->score))
                                    — Nilai: <span class="text-emerald-400">{{ $answer->score }} pts</span>
                                @else
                                    — <span class="text-amber-400">Belum dinilai</span>
                                @endif
                            </p>
                            <p class="text-sm text-slate-200 leading-relaxed mb-3">{{ $answer->question->question ?? '-' }}</p>

                            <div class="rounded-xl bg-slate-800 px-3 py-2 text-sm text-slate-300">
                                <span class="block text-xs text-slate-500 mb-1">Jawaban kamu:</span>
                                {{ $answer->answer_text ?? '-' }}
                            </div>

                            @if(!empty($answer->feedback))
                                <div class="mt-2 rounded-xl bg-violet-950/40 border border-violet-800 px-3 py-2 text-sm">
                                    <span class="block text-xs text-violet-400 mb-1">Feedback Lecturer:</span>
                                    <span class="text-slate-300">{{ $answer->feedback }}</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
</x-app-layout>