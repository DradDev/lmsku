<x-app-layout>
    <div class="min-h-screen bg-slate-50 text-slate-900 py-8">
        <div class="mx-auto max-w-5xl px-6">
            <div class="mb-8">
                <a href="{{ route('lecturer.courses.quizzes.results.index', [$course->id, $quiz->id]) }}"
                   class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition mb-3">
                    ← Back to Quiz Results
                </a>

                <div class="mt-1 flex flex-wrap items-center gap-3">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $result->user->name ?? 'Student' }}</h1>
                    @if($result->is_verified)
                        <span class="inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                            Graded
                        </span>
                    @else
                        <span class="inline-flex rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                            Pending Grading
                        </span>
                    @endif
                </div>

                <p class="mt-2 text-sm text-slate-500">
                    Quiz: <span class="font-semibold text-slate-700">{{ $quiz->title }}</span> — Course: <span class="font-semibold text-slate-700">{{ $course->name }}</span>
                </p>
            </div>

            <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-4">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Score</p>
                    <p class="mt-1 text-3xl font-bold text-indigo-600">{{ $result->score }}</p>
                </div>
                <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-4">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Email</p>
                    <p class="mt-1 text-sm font-bold text-gray-800">{{ $result->user->email ?? '-' }}</p>
                </div>
                <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-4">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Submitted At</p>
                    <p class="mt-1 text-sm font-bold text-gray-800">
                        {{ optional($result->created_at)->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-5">
                    <h2 class="text-xl font-bold text-gray-900">Detail Jawaban</h2>
                    <p class="mt-1 text-xs text-slate-500">Rincian jawaban mahasiswa per soal.</p>
                </div>

                @if($result->answers && $result->answers->count())
                    <div class="divide-y divide-gray-100">
                        @foreach($result->answers as $answer)
                            <div class="px-6 py-5">
                                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 flex-wrap mb-2">
                                            @if($answer->question)
                                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                                    {{ $answer->question->question_type === 'essay' ? 'Essay' : 'Multiple Choice' }}
                                                </span>
                                            @endif
                                            @if(!is_null($answer->is_correct))
                                                @if($answer->is_correct)
                                                    <span class="inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Correct</span>
                                                @else
                                                    <span class="inline-flex rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700">Incorrect</span>
                                                @endif
                                            @endif
                                        </div>

                                        <h3 class="text-base font-bold text-gray-900">
                                            {{ $answer->question->question ?? 'Question not found' }}
                                        </h3>

                                        @if($answer->question && $answer->question->question_type === 'multiple_choice')
                                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                                <div class="rounded-xl bg-slate-50 border border-gray-200 p-3">
                                                    <p class="text-xs text-slate-500">Selected Option</p>
                                                    <p class="mt-1 font-bold text-slate-800">{{ $answer->selected_option ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-xl bg-slate-50 border border-gray-200 p-3">
                                                    <p class="text-xs text-slate-500">Correct Answer</p>
                                                    <p class="mt-1 font-bold text-slate-800">{{ $answer->question->correct_answer ?? '-' }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="mt-4 rounded-xl bg-slate-50 border border-gray-200 p-4">
                                                <p class="text-xs text-slate-500 mb-2">Student Answer</p>
                                                <p class="text-slate-800 whitespace-pre-line font-medium">{{ $answer->answer_text ?? '-' }}</p>
                                            </div>
                                        @endif

                                        @if(!empty($answer->feedback))
                                            <div class="mt-4 rounded-xl bg-amber-50 border border-amber-200 p-4">
                                                <p class="text-xs font-bold text-amber-800 mb-1">Feedback Kamu</p>
                                                <p class="text-sm text-amber-900">{{ $answer->feedback }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="w-full lg:w-40">
                                        <div class="rounded-2xl bg-slate-50 border border-gray-200 p-4 text-center">
                                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Answer Score</p>
                                            <p class="mt-2 text-2xl font-bold text-indigo-600">
                                                {{ is_null($answer->score) ? '-' : $answer->score }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-12 text-center text-slate-400 font-medium">
                        Belum ada detail jawaban
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>