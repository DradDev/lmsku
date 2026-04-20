<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                    Admin Panel
                </p>
                <h1 class="text-3xl font-bold text-slate-900">Question Bank Review</h1>
                <p class="mt-2 text-slate-500">
                    Review, approve, reject, edit, dan hapus soal yang dikirim lecturer.
                </p>
            </div>

            @if(session('success'))
                <div
                    id="success-toast"
                    class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-sm">
                    {{ session('success') }}
                </div>

                <script>
                    window.addEventListener('DOMContentLoaded', function () {
                        const toast = document.getElementById('success-toast');
                        if (toast) {
                            setTimeout(() => {
                                toast.style.transition = 'all 0.4s ease';
                                toast.style.opacity = '0';
                                toast.style.transform = 'translateY(-6px)';
                            }, 2200);

                            setTimeout(() => {
                                toast.remove();
                            }, 2700);
                        }
                    });
                </script>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Questions</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $totalQuestions }}</p>
                </div>

                <div class="rounded-2xl border border-emerald-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Approved</p>
                    <p class="mt-3 text-3xl font-bold text-emerald-600">{{ $approvedCount }}</p>
                </div>

                <div class="rounded-2xl border border-amber-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Pending</p>
                    <p class="mt-3 text-3xl font-bold text-amber-500">{{ $pendingCount }}</p>
                </div>

                <div class="rounded-2xl border border-rose-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Rejected</p>
                    <p class="mt-3 text-3xl font-bold text-rose-500">{{ $rejectedCount }}</p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-xl font-semibold text-slate-900">All Submitted Questions</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Daftar seluruh soal dari lecturer beserta status review.
                    </p>
                </div>

                @if($questions->count())
                    <div class="divide-y divide-slate-200">
                        @foreach($questions as $question)
                            <div class="px-6 py-5 hover:bg-slate-50 transition">
                                <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-5">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2 flex-wrap mb-3">
                                            <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                                {{ $question->question_type === 'essay' ? 'Essay' : 'Multiple Choice' }}
                                            </span>

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

                                            <span class="inline-flex items-center rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                                {{ ucfirst($question->difficulty) }}
                                            </span>
                                        </div>

                                        <h3 class="text-lg font-semibold text-slate-900 break-words">
                                            {{ $question->question }}
                                        </h3>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm mt-4">
                                            <div>
                                                <p class="text-slate-400">Quiz</p>
                                                <p class="font-medium text-slate-800">
                                                    {{ $question->quiz->title ?? '-' }}
                                                </p>
                                            </div>

                                            <div>
                                                <p class="text-slate-400">Course</p>
                                                <p class="font-medium text-slate-800">
                                                    {{ $question->quiz->course->name ?? '-' }}
                                                </p>
                                            </div>

                                            <div>
                                                <p class="text-slate-400">Submitted By</p>
                                                <p class="font-medium text-slate-800">
                                                    {{ $question->user->name ?? '-' }}
                                                </p>
                                            </div>
                                        </div>

                                        @if($question->question_type === 'multiple_choice')
                                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-700">
                                                    <span class="font-semibold">A:</span> {{ $question->option_a }}
                                                </div>
                                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-700">
                                                    <span class="font-semibold">B:</span> {{ $question->option_b }}
                                                </div>
                                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-700">
                                                    <span class="font-semibold">C:</span> {{ $question->option_c }}
                                                </div>
                                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-700">
                                                    <span class="font-semibold">D:</span> {{ $question->option_d }}
                                                </div>
                                            </div>

                                            <div class="mt-3 rounded-xl border border-indigo-200 bg-indigo-50 p-3 text-sm">
                                                <span class="font-semibold text-indigo-700">Correct Answer:</span>
                                                <span class="text-slate-800">{{ $question->correct_answer ?? '-' }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="w-full xl:w-auto">
                                        <div class="flex flex-wrap gap-2 xl:justify-end">
                                            <a href="{{ route('admin.questions.show', $question->id) }}"
                                               class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                                                View
                                            </a>

                                            <a href="{{ route('admin.questions.edit', $question->id) }}"
                                               class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                                Edit
                                            </a>

                                            @if($question->status !== 'approved')
                                                <form method="POST" action="{{ route('admin.questions.approve', $question->id) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="inline-flex items-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                                                        Approve
                                                    </button>
                                                </form>
                                            @endif

                                            @if($question->status !== 'rejected')
                                                <form method="POST" action="{{ route('admin.questions.reject', $question->id) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="inline-flex items-center rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-600">
                                                        Reject
                                                    </button>
                                                </form>
                                            @endif

                                            <form method="POST"
                                                  action="{{ route('admin.questions.destroy', $question->id) }}"
                                                  onsubmit="return confirm('Yakin ingin menghapus soal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <div class="mx-auto max-w-md">
                            <h3 class="text-lg font-semibold text-slate-900">Belum ada soal</h3>
                            <p class="mt-2 text-sm text-slate-500">
                                Soal dari lecturer akan muncul di sini setelah dikirim untuk direview admin.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
