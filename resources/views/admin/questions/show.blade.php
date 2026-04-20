<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-6">

            <div class="mb-8 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                <div>
                    <a href="{{ route('admin.questions.index') }}"
                       class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 mb-3">
                        ← Back to Question Bank
                    </a>

                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                        Question Details
                    </p>
                    <h1 class="text-3xl font-bold text-slate-900">
                        {{ \Illuminate\Support\Str::limit($question->question, 80) }}
                    </h1>
                    <p class="mt-2 text-slate-500">
                        Detail soal, status review, quiz terkait, dan action admin.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.questions.edit', $question->id) }}"
                       class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Edit Question
                    </a>

                    @if($question->status !== 'approved')
                        <form method="POST" action="{{ route('admin.questions.approve', $question->id) }}">
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex items-center rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700">
                                Approve
                            </button>
                        </form>
                    @endif

                    @if($question->status !== 'rejected')
                        <form method="POST" action="{{ route('admin.questions.reject', $question->id) }}">
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex items-center rounded-xl bg-amber-500 px-5 py-3 text-sm font-semibold text-white hover:bg-amber-600">
                                Reject
                            </button>
                        </form>
                    @endif

                    <form method="POST"
                          action="{{ route('admin.questions.destroy', $question->id) }}"
                          onsubmit="return confirm('Yakin ingin menghapus soal ini?')">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white hover:bg-rose-700">
                            Delete
                        </button>
                    </form>
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
                            <h2 class="text-xl font-semibold text-slate-900">Question Overview</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Informasi utama dari soal yang dipilih.
                            </p>
                        </div>

                        @if($question->status === 'approved')
                            <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                Approved
                            </span>
                        @elseif($question->status === 'rejected')
                            <span class="inline-flex items-center rounded-full border border-rose-200 bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">
                                Rejected
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                Pending
                            </span>
                        @endif
                    </div>

                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5 mb-5">
                        <p class="text-sm text-slate-400 mb-2">Question</p>
                        <p class="text-lg font-semibold text-slate-900 leading-8">
                            {{ $question->question }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-sm text-slate-400">Question Type</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">
                                {{ $question->question_type === 'essay' ? 'Essay' : 'Multiple Choice' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-indigo-50 border border-indigo-100 p-4">
                            <p class="text-sm text-slate-400">Difficulty</p>
                            <p class="mt-1 text-2xl font-bold text-indigo-600">
                                {{ ucfirst($question->difficulty) }}
                            </p>
                        </div>

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
                            <p class="text-sm text-slate-400">Updated At</p>
                            <p class="mt-1 text-base font-semibold text-slate-900">
                                {{ optional($question->updated_at)->format('d M Y, H:i') ?: '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-slate-900 mb-5">Review Actions</h2>

                    <div class="space-y-4">
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-sm text-slate-400">Current Status</p>
                            <p class="mt-1 text-lg font-semibold
                                {{ $question->status === 'approved' ? 'text-emerald-600' : ($question->status === 'rejected' ? 'text-rose-600' : 'text-amber-600') }}">
                                {{ ucfirst($question->status) }}
                            </p>
                        </div>

                        @if($question->status !== 'approved')
                            <form method="POST" action="{{ route('admin.questions.approve', $question->id) }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full inline-flex justify-center items-center rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-700">
                                    Approve This Question
                                </button>
                            </form>
                        @endif

                        @if($question->status !== 'rejected')
                            <form method="POST" action="{{ route('admin.questions.reject', $question->id) }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full inline-flex justify-center items-center rounded-xl bg-amber-500 px-4 py-3 text-sm font-semibold text-white hover:bg-amber-600">
                                    Reject This Question
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('admin.questions.edit', $question->id) }}"
                           class="block w-full text-center rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Edit Question
                        </a>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-xl font-semibold text-slate-900">Answer Structure</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Detail opsi jawaban untuk soal multiple choice atau catatan penilaian essay.
                    </p>
                </div>

                @if($question->question_type === 'multiple_choice')
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach(['A' => $question->option_a, 'B' => $question->option_b, 'C' => $question->option_c, 'D' => $question->option_d] as $label => $option)
                                @php $isCorrect = $question->correct_answer === $label; @endphp

                                <div class="rounded-2xl border {{ $isCorrect ? 'border-emerald-200 bg-emerald-50' : 'border-slate-200 bg-slate-50' }} p-4">
                                    <div class="flex items-center justify-between gap-3 mb-2">
                                        <p class="text-sm font-semibold {{ $isCorrect ? 'text-emerald-700' : 'text-slate-700' }}">
                                            Option {{ $label }}
                                        </p>

                                        @if($isCorrect)
                                            <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                Correct Answer
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-sm text-slate-800 leading-6">
                                        {{ $option ?: '-' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="px-6 py-10 text-center">
                        <h3 class="text-lg font-semibold text-slate-900">Essay Question</h3>
                        <p class="mt-2 text-sm text-slate-500">
                            Soal essay tidak memiliki opsi jawaban dan akan dinilai secara manual.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
