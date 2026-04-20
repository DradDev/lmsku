<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-8 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                <div>
                    <a href="{{ route('admin.results.index') }}"
                       class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 mb-3">
                        ← Back to Results
                    </a>

                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                        Result Details
                    </p>
                    <h1 class="text-3xl font-bold text-slate-900">{{ $result->quiz->title ?? 'Quiz Result' }}</h1>
                    <p class="mt-2 text-slate-500">
                        Detail hasil quiz mahasiswa, status verifikasi, dan jawaban attempt.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    @if(!$result->is_verified)
                        <form method="POST" action="{{ route('admin.results.verify', $result->id) }}">
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex items-center rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700">
                                Verify Result
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
                <div class="xl:col-span-2 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div>
                            <h2 class="text-xl font-semibold text-slate-900">Attempt Overview</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Informasi utama dari hasil quiz yang dipilih.
                            </p>
                        </div>

                        @if($result->is_verified)
                            <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                Verified
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                Pending Verification
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-sm text-slate-400">Student</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">{{ $result->user->name ?? '-' }}</p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-sm text-slate-400">Course</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">{{ $result->quiz->course->name ?? '-' }}</p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-sm text-slate-400">Quiz</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">{{ $result->quiz->title ?? '-' }}</p>
                        </div>

                        <div class="rounded-2xl bg-indigo-50 border border-indigo-100 p-4">
                            <p class="text-sm text-slate-400">Score</p>
                            <p class="mt-1 text-3xl font-bold text-indigo-600">{{ $result->score }}</p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-sm text-slate-400">Submitted At</p>
                            <p class="mt-1 text-base font-semibold text-slate-900">
                                {{ optional($result->created_at)->format('d M Y, H:i') }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-sm text-slate-400">Updated At</p>
                            <p class="mt-1 text-base font-semibold text-slate-900">
                                {{ optional($result->updated_at)->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-slate-900 mb-5">Verification</h2>

                    <div class="space-y-4">
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-sm text-slate-400">Status</p>
                            <p class="mt-1 text-lg font-semibold {{ $result->is_verified ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $result->is_verified ? 'Verified' : 'Pending' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-sm text-slate-400">Blockchain Hash</p>
                            <p class="mt-2 break-all text-sm text-slate-700">
                                {{ $result->blockchain_hash ?: 'Belum tersedia karena result belum diverifikasi.' }}
                            </p>
                        </div>

                        @if(!$result->is_verified)
                            <form method="POST" action="{{ route('admin.results.verify', $result->id) }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full inline-flex justify-center items-center rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-700">
                                    Verify This Result
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-xl font-semibold text-slate-900">Answer Details</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Rincian jawaban mahasiswa untuk attempt ini.
                    </p>
                </div>

                @if($result->answers && $result->answers->count())
                    <div class="divide-y divide-slate-200">
                        @foreach($result->answers as $answer)
                            <div class="px-6 py-5">
                                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 flex-wrap mb-2">
                                            @if($answer->question)
                                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                                    {{ $answer->question->question_type === 'essay' ? 'Essay' : 'Multiple Choice' }}
                                                </span>
                                            @endif

                                            @if(!is_null($answer->is_correct))
                                                @if($answer->is_correct)
                                                    <span class="inline-flex rounded-full border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                        Correct
                                                    </span>
                                                @else
                                                    <span class="inline-flex rounded-full border border-rose-200 bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">
                                                        Incorrect
                                                    </span>
                                                @endif
                                            @endif
                                        </div>

                                        <h3 class="text-base font-semibold text-slate-900">
                                            {{ $answer->question->question ?? 'Question not found' }}
                                        </h3>

                                        @if($answer->question && $answer->question->question_type === 'multiple_choice')
                                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                                <div class="rounded-xl bg-slate-50 border border-slate-200 p-3">
                                                    <p class="text-slate-400">Selected Option</p>
                                                    <p class="mt-1 font-semibold text-slate-900">
                                                        {{ $answer->selected_option ?? '-' }}
                                                    </p>
                                                </div>

                                                <div class="rounded-xl bg-slate-50 border border-slate-200 p-3">
                                                    <p class="text-slate-400">Correct Answer</p>
                                                    <p class="mt-1 font-semibold text-slate-900">
                                                        {{ $answer->question->correct_answer ?? '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="mt-4 rounded-xl bg-slate-50 border border-slate-200 p-4">
                                                <p class="text-sm text-slate-400 mb-2">Student Answer</p>
                                                <p class="text-slate-800 whitespace-pre-line">
                                                    {{ $answer->answer_text ?? '-' }}
                                                </p>
                                            </div>
                                        @endif

                                        @if(!empty($answer->feedback))
                                            <div class="mt-4 rounded-xl bg-amber-50 border border-amber-200 p-4">
                                                <p class="text-sm font-semibold text-amber-700 mb-2">Feedback</p>
                                                <p class="text-sm text-amber-800">
                                                    {{ $answer->feedback }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="w-full lg:w-40">
                                        <div class="rounded-2xl bg-indigo-50 border border-indigo-100 p-4 text-center">
                                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Answer Score</p>
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
                    <div class="px-6 py-12 text-center">
                        <h3 class="text-lg font-semibold text-slate-900">Belum ada detail jawaban</h3>
                        <p class="mt-2 text-sm text-slate-500">
                            Attempt ini belum memiliki data jawaban yang dapat ditampilkan.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
