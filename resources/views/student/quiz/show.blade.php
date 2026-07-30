<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="mb-6">
                <a href="{{ url()->previous() }}"
                   class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 mb-4">
                    ← Back
                </a>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <h1 class="text-3xl font-bold text-slate-900">
                                {{ $quiz->title }}
                            </h1>
                            <p class="text-slate-500 mt-2">
                                Complete all questions and submit your answers.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-4 w-full lg:w-auto mt-4 lg:mt-0 justify-end">
                            <div class="rounded-xl bg-slate-50 border border-slate-200 px-4 py-3 text-center min-w-[120px]">
                                <p class="text-sm text-slate-500">Quiz Type</p>
                                <div class="mt-1">
                                    <span class="inline-flex items-center rounded-full border {{ $quiz->quiz_type_badge_class }} px-2 py-0.5 text-xs font-semibold">
                                        {{ $quiz->quiz_type_label }}
                                    </span>
                                </div>
                            </div>
                            <div class="rounded-xl bg-slate-50 border border-slate-200 px-4 py-3 text-center min-w-[120px]">
                                <p class="text-sm text-slate-500">Total Questions</p>
                                <p class="text-xl font-bold text-slate-900 mt-1">{{ $quiz->questions->count() }}</p>
                            </div>
                            <div class="rounded-xl bg-slate-50 border border-slate-200 px-4 py-3 text-center min-w-[120px]">
                                <p class="text-sm text-slate-500">Attempts Left</p>
                                <p class="text-xl font-bold text-indigo-600 mt-1">{{ $remainingAttempts }}</p>
                            </div>
                            @if($quiz->end_date)
                            <div class="rounded-xl bg-slate-50 border border-slate-200 px-4 py-3 text-center min-w-[120px]">
                                <p class="text-sm text-slate-500">Deadline</p>
                                <p class="text-sm font-bold text-rose-600 mt-1">{{ \Carbon\Carbon::parse($quiz->end_date)->format('d M, H:i') }}</p>
                            </div>
                            @endif
                            <div class="rounded-xl bg-slate-50 border border-slate-200 px-4 py-3 text-center min-w-[120px]">
                                <p class="text-sm text-slate-500">Status</p>
                                <p class="text-lg font-bold text-indigo-600 mt-1">In Progress</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('error'))
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($quiz->questions->count() > 0)
                <form action="{{ route('student.quiz.submit', $quiz->id) }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
                        <div class="xl:col-span-3">
                            <div class="space-y-6">
                                @foreach($quiz->questions as $index => $question)
                                    <div class="question-card bg-white rounded-2xl shadow-sm border border-slate-200 p-6"
                                         id="question-{{ $index + 1 }}">
                                        <div class="flex items-start justify-between gap-4 mb-5">
                                            <div>
                                                <p class="text-sm font-medium text-indigo-600 mb-2">
                                                    Question {{ $index + 1 }}
                                                </p>
                                                <h2 class="text-lg font-semibold text-slate-900 leading-relaxed">
                                                    {{ $question->question_text ?? $question->question ?? 'Question text not found' }}
                                                </h2>
                                            </div>

                                            @if(!empty($question->difficulty))
                                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                                    {{ ucfirst($question->difficulty) }}
                                                </span>
                                            @endif
                                        </div>

                                        @php
                                            $type = $question->question_type ?? 'multiple_choice';
                                        @endphp

                                        @if($type === 'multiple_choice')
                                            <div class="space-y-3">
                                                @foreach([
                                                    'A' => $question->option_a ?? null,
                                                    'B' => $question->option_b ?? null,
                                                    'C' => $question->option_c ?? null,
                                                    'D' => $question->option_d ?? null,
                                                    'E' => $question->option_e ?? null,
                                                ] as $key => $option)
                                                    @if(!is_null($option))
                                                        <label class="flex items-start gap-3 rounded-xl border border-slate-200 p-4 hover:border-indigo-300 hover:bg-indigo-50 transition cursor-pointer">
                                                            <input type="radio"
                                                                   name="answers[{{ $question->id }}]"
                                                                   value="{{ $key }}"
                                                                   class="mt-1 text-indigo-600 focus:ring-indigo-500"
                                                                   {{ old("answers.{$question->id}") === $key ? 'checked' : '' }}>
                                                            <div>
                                                                <span class="block text-sm font-semibold text-slate-700">{{ $key }}.</span>
                                                                <span class="block text-slate-800">{{ $option }}</span>
                                                            </div>
                                                        </label>
                                                    @endif
                                                @endforeach

                                                @error("answers.{$question->id}")
                                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @else
                                            <div class="text-sm text-slate-500 italic">
                                                Essay questions are no longer supported.
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="xl:col-span-1">
                            <div class="sticky top-6 space-y-6">
                                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                                    <h2 class="text-lg font-semibold text-slate-900 mb-4">Question Navigator</h2>

                                    <div class="grid grid-cols-4 gap-3">
                                        @foreach($quiz->questions as $index => $question)
                                            <a href="#question-{{ $index + 1 }}"
                                               class="flex items-center justify-center rounded-xl border border-slate-200 bg-slate-50 h-11 text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:border-indigo-300 transition">
                                                {{ $index + 1 }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                                    <h2 class="text-lg font-semibold text-slate-900 mb-4">Submission</h2>

                                    <p class="text-sm text-slate-500 mb-5">
                                        Make sure you have answered all required questions before submitting.
                                    </p>

                                    <button type="submit"
                                            class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-medium text-white hover:bg-indigo-700 transition">
                                        Submit Quiz
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            @else
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <p class="text-slate-500">Quiz ini belum memiliki soal.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
