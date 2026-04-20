<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        <div class="max-w-4xl mx-auto px-6 py-8">
            <div class="mb-6">
                <a href="{{ url()->previous() }}"
                   class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 mb-4">
                    ← Back
                </a>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h1 class="text-3xl font-bold text-slate-900">
                        {{ $quiz->title }}
                    </h1>
                    <p class="text-slate-500 mt-2">
                        Complete all questions and submit your answers.
                    </p>
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
                <form method="POST" action="{{ route('student.quiz.submit', $quiz->id) }}">
                    @csrf

                    <div class="space-y-6">
                        @foreach($quiz->questions as $index => $question)
                            @php
                                $type = $question->question_type ?? 'multiple_choice';
                            @endphp

                            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
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
                                                    <input
                                                        type="radio"
                                                        name="answers[{{ $question->id }}]"
                                                        value="{{ $key }}"
                                                        class="mt-1 text-indigo-600 focus:ring-indigo-500"
                                                        {{ old("answers.{$question->id}") === $key ? 'checked' : '' }}
                                                    >
                                                    <div>
                                                        <span class="block text-sm font-semibold text-slate-700">{{ $key }}.</span>
                                                        <span class="block text-slate-800">{{ $option }}</span>
                                                    </div>
                                                </label>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">
                                            Your Answer
                                        </label>
                                        <textarea
                                            name="answers[{{ $question->id }}]"
                                            rows="6"
                                            class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                            placeholder="Write your answer here...">{{ old("answers.{$question->id}") }}</textarea>

                                        @error("answers.{$question->id}")
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <button class="bg-purple-600 text-white px-8 py-3 rounded-xl hover:bg-purple-700 transition">
                            Submit Quiz
                        </button>
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
