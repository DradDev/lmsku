<x-app-layout>
    <main class="py-6 min-h-screen bg-slate-50" role="main" aria-label="Rincian Hasil Kuis Mahasiswa">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <a href="{{ route('student.results.index') }}"
               aria-label="Kembali ke daftar hasil kuis"
               class="inline-flex items-center gap-1.5 text-sm font-bold text-indigo-700 hover:text-indigo-900 transition">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Back to Quiz Results
            </a>

            {{-- Header Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <div class="mb-4 flex flex-wrap gap-2">
                    @if($result->is_verified)
                        <span class="rounded-full bg-emerald-100 border border-emerald-300 px-3 py-1 text-xs font-extrabold text-emerald-900 flex items-center gap-1">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                            Graded
                        </span>
                    @else
                        <span class="rounded-full bg-amber-100 border border-amber-300 px-3 py-1 text-xs font-extrabold text-amber-900 flex items-center gap-1">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Pending Essay Evaluation
                        </span>
                    @endif

                    @if(!empty($result->blockchain_hash))
                        <span class="rounded-full bg-indigo-100 border border-indigo-300 px-3 py-1 text-xs font-extrabold text-indigo-900 flex items-center gap-1">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            Blockchain Recorded
                        </span>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div>
                        <span class="text-xs font-extrabold text-indigo-700 uppercase tracking-wider">Quiz Evaluation Result</span>
                        <h1 class="text-2xl font-black text-slate-900 mt-1">
                            {{ $result->quiz->title ?? 'Quiz Result' }}
                        </h1>
                        <p class="mt-2 text-sm text-slate-700 font-medium">
                            Course: <span class="font-bold text-slate-900">{{ $result->quiz->course->name ?? '-' }}</span>
                        </p>
                        <p class="mt-1 text-xs text-slate-600 font-medium">
                            Completion Date: <span class="font-semibold text-slate-800">{{ $result->created_at?->format('d M Y H:i') ?? '-' }}</span>
                        </p>
                    </div>

                    <div class="bg-gradient-to-tr from-indigo-50 to-purple-50 border border-indigo-200 rounded-2xl p-5 text-center min-w-[150px] shadow-sm">
                        <p class="text-xs uppercase font-extrabold tracking-widest text-indigo-700">Final Score</p>
                        <h2 class="mt-1 text-4xl font-black text-indigo-800">{{ $result->score }}</h2>
                        <span class="text-[11px] font-bold text-slate-700">Competency Score</span>
                    </div>
                </div>

                @if(!empty($result->blockchain_hash))
                    <div class="mt-6 rounded-xl border border-indigo-100 bg-indigo-50/50 p-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-indigo-700 mb-1">Blockchain Hash Integrity</p>
                        <p class="break-all text-xs font-mono text-indigo-900">{{ $result->blockchain_hash }}</p>
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
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span>Multiple Choice Answer Review</span>
                    </h2>
                    <div class="space-y-4">
                        @foreach($mcAnswers as $i => $answer)
                            @php $correct = $answer->is_correct; @endphp
                            <div class="rounded-xl border {{ $correct ? 'border-emerald-300 bg-emerald-50/60' : 'border-rose-300 bg-rose-50/60' }} p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <p class="text-xs font-extrabold uppercase tracking-wider {{ $correct ? 'text-emerald-900' : 'text-rose-900' }} mb-1 flex items-center gap-1">
                                            <span>Question {{ $i + 1 }}</span>
                                            <span>— {{ $correct ? '✓ Correct' : '✗ Incorrect' }}</span>
                                        </p>
                                        <p class="text-sm font-bold text-slate-900 leading-relaxed">
                                            {{ $answer->question->question ?? '-' }}
                                        </p>
                                    </div>
                                    <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-black {{ $correct ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : 'bg-rose-100 text-rose-900 border border-rose-300' }}">
                                        {{ $correct ? '+1 Point' : '0 Point' }}
                                    </span>
                                </div>

                                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                    <div class="rounded-lg bg-white border border-slate-200 px-3 py-2">
                                        <span class="text-slate-700 font-medium">Your Answer: </span>
                                        <span class="font-extrabold {{ $correct ? 'text-emerald-900' : 'text-rose-900' }}">
                                            {{ $answer->selected_option ?? '-' }}
                                        </span>
                                    </div>
                                    @if(!$correct)
                                        <div class="rounded-lg bg-emerald-50 border border-emerald-300 px-3 py-2">
                                            <span class="text-slate-700 font-medium">Correct Answer: </span>
                                            <span class="font-extrabold text-emerald-900">
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
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span>Essay Answer Review</span>
                    </h2>
                    <div class="space-y-4">
                        @foreach($essayAnswers as $i => $answer)
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-xs font-extrabold uppercase tracking-wider text-indigo-800 mb-1 flex items-center gap-2">
                                    <span>Essay {{ $i + 1 }}</span>
                                    @if(!is_null($answer->score))
                                        <span class="text-emerald-900 font-extrabold bg-emerald-100 border border-emerald-300 px-2 py-0.5 rounded-full">Score: {{ $answer->score }} pts</span>
                                    @else
                                        <span class="text-amber-900 font-extrabold bg-amber-100 border border-amber-300 px-2 py-0.5 rounded-full">Pending Evaluation</span>
                                    @endif
                                </p>
                                <p class="text-sm font-bold text-slate-900 leading-relaxed mb-3">{{ $answer->question->question ?? '-' }}</p>

                                <div class="rounded-lg bg-white border border-slate-200 px-3.5 py-2.5 text-sm text-slate-900">
                                    <span class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wider">Your Answer:</span>
                                    {{ $answer->answer_text ?? '-' }}
                                </div>

                                @if(!empty($answer->feedback))
                                    <div class="mt-3 rounded-lg bg-indigo-50 border border-indigo-200 px-3.5 py-2.5 text-sm">
                                        <span class="block text-xs font-extrabold text-indigo-900 mb-1 uppercase tracking-wider">Author / Vendor Feedback:</span>
                                        <span class="text-slate-900 font-medium">{{ $answer->feedback }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </main>
</x-app-layout>