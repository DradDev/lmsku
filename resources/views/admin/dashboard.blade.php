<x-app-layout>
    <div class="min-h-screen bg-slate-50 text-slate-800">
        <div class="mx-auto max-w-7xl px-6 py-8">
            <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-slate-900">Admin Dashboard</h1>
                    <p class="mt-2 text-sm text-slate-500">
                        Monitor hasil mahasiswa dan kelola bank soal dari lecturer.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.dashboard', ['tab' => 'results']) }}"
                        class="rounded-xl px-4 py-2 text-sm font-medium transition
                        {{ $tab === 'results' ? 'bg-blue-700 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                        Student Results
                    </a>

                    <a href="{{ route('admin.dashboard', ['tab' => 'bank']) }}"
                        class="rounded-xl px-4 py-2 text-sm font-medium transition
                        {{ $tab === 'bank' ? 'bg-blue-700 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                        Question Bank
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-6">
                <input type="hidden" name="tab" value="{{ $tab }}">

                @if ($tab === 'results')
                    <input type="hidden" name="result_status" value="{{ $resultStatus }}">
                @else
                    <input type="hidden" name="question_status" value="{{ $questionStatus }}">
                @endif

                <div class="flex flex-col gap-3 md:flex-row">
                    <div class="flex-1">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="{{ $tab === 'results' ? 'Cari nama mahasiswa, email, course...' : 'Cari soal, course, quiz, author...' }}"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none"
                        >
                    </div>

                    <button
                        type="submit"
                        class="rounded-2xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-800"
                    >
                        Search
                    </button>
                </div>
            </form>

            @if ($tab === 'results')
                <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-slate-500">Total Results</p>
                        <h3 class="mt-3 text-3xl font-bold text-slate-900">{{ $resultStats['total'] }}</h3>
                    </div>

                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                        <p class="text-sm text-emerald-700">Verified</p>
                        <h3 class="mt-3 text-3xl font-bold text-emerald-900">{{ $resultStats['verified'] }}</h3>
                    </div>

                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                        <p class="text-sm text-amber-700">Unverified</p>
                        <h3 class="mt-3 text-3xl font-bold text-amber-900">{{ $resultStats['unverified'] }}</h3>
                    </div>

                    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
                        <p class="text-sm text-blue-700">Average Score</p>
                        <h3 class="mt-3 text-3xl font-bold text-blue-900">{{ $resultStats['average_score'] }}</h3>
                    </div>
                </div>

                <div class="mb-6 flex flex-wrap gap-3">
                    <a href="{{ route('admin.dashboard', ['tab' => 'results', 'result_status' => 'all', 'search' => $search]) }}"
                        class="rounded-full px-4 py-2 text-sm font-medium transition
                        {{ $resultStatus === 'all' ? 'bg-slate-800 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                        All Results
                    </a>

                    <a href="{{ route('admin.dashboard', ['tab' => 'results', 'result_status' => 'verified', 'search' => $search]) }}"
                        class="rounded-full px-4 py-2 text-sm font-medium transition
                        {{ $resultStatus === 'verified' ? 'bg-emerald-500 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                        Verified
                    </a>

                    <a href="{{ route('admin.dashboard', ['tab' => 'results', 'result_status' => 'unverified', 'search' => $search]) }}"
                        class="rounded-full px-4 py-2 text-sm font-medium transition
                        {{ $resultStatus === 'unverified' ? 'bg-amber-500 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                        Unverified
                    </a>
                </div>

                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead class="bg-slate-100 text-slate-600">
                                <tr>
                                    <th class="px-6 py-4 font-semibold">Student</th>
                                    <th class="px-6 py-4 font-semibold">Course</th>
                                    <th class="px-6 py-4 font-semibold">Score</th>
                                    <th class="px-6 py-4 font-semibold">Date</th>
                                    <th class="px-6 py-4 font-semibold">Blockchain Hash</th>
                                    <th class="px-6 py-4 font-semibold">Status</th>
                                    <th class="px-6 py-4 font-semibold">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($results as $result)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-4 align-top">
                                            <div class="font-semibold text-slate-900">{{ $result->user->name ?? '-' }}</div>
                                            <div class="text-xs text-slate-400">{{ $result->user->email ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">
                                            {{ $result->quiz->course->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                                {{ $result->score }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">
                                            {{ $result->created_at ? $result->created_at->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-xs text-slate-400">
                                            {{ $result->blockchain_hash ?? 'Belum tercatat' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($result->is_verified)
                                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                    Verified
                                                </span>
                                            @else
                                                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                                    Unverified
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if (!$result->is_verified)
                                                <form method="POST" action="{{ route('admin.results.verify', $result->id) }}">
                                                    @csrf
                                                    <button
                                                        type="submit"
                                                        class="rounded-xl bg-blue-700 px-4 py-2 text-xs font-semibold text-white transition hover:bg-blue-800"
                                                    >
                                                        Verify
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-slate-400">Verified</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                                            Belum ada hasil mahasiswa.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if ($tab === 'bank')
                <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-slate-500">Total Questions</p>
                        <h3 class="mt-3 text-3xl font-bold text-slate-900">{{ $questionStats['total'] }}</h3>
                    </div>

                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                        <p class="text-sm text-emerald-700">Approved</p>
                        <h3 class="mt-3 text-3xl font-bold text-emerald-900">{{ $questionStats['approved'] }}</h3>
                    </div>

                    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
                        <p class="text-sm text-blue-700">Pending Review</p>
                        <h3 class="mt-3 text-3xl font-bold text-blue-900">{{ $questionStats['pending'] }}</h3>
                    </div>

                    <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5">
                        <p class="text-sm text-rose-700">Rejected</p>
                        <h3 class="mt-3 text-3xl font-bold text-rose-900">{{ $questionStats['rejected'] }}</h3>
                    </div>
                </div>

                <div class="mb-6 flex flex-wrap gap-3">
                    <a href="{{ route('admin.dashboard', ['tab' => 'bank', 'question_status' => 'all', 'search' => $search]) }}"
                        class="rounded-full px-4 py-2 text-sm font-medium transition
                        {{ $questionStatus === 'all' ? 'bg-slate-800 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                        All
                    </a>

                    <a href="{{ route('admin.dashboard', ['tab' => 'bank', 'question_status' => 'approved', 'search' => $search]) }}"
                        class="rounded-full px-4 py-2 text-sm font-medium transition
                        {{ $questionStatus === 'approved' ? 'bg-emerald-500 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                        Approved
                    </a>

                    <a href="{{ route('admin.dashboard', ['tab' => 'bank', 'question_status' => 'pending', 'search' => $search]) }}"
                        class="rounded-full px-4 py-2 text-sm font-medium transition
                        {{ $questionStatus === 'pending' ? 'bg-blue-700 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                        Pending
                    </a>

                    <a href="{{ route('admin.dashboard', ['tab' => 'bank', 'question_status' => 'rejected', 'search' => $search]) }}"
                        class="rounded-full px-4 py-2 text-sm font-medium transition
                        {{ $questionStatus === 'rejected' ? 'bg-rose-500 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                        Rejected
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse ($questions as $question)
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="mb-4 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                <div class="max-w-3xl">
                                    <div class="mb-3 flex flex-wrap gap-2">
                                        @if ($question->status === 'approved')
                                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Approved</span>
                                        @elseif ($question->status === 'rejected')
                                            <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">Rejected</span>
                                        @else
                                            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">Pending Review</span>
                                        @endif

                                        @if ($question->difficulty === 'easy')
                                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Easy</span>
                                        @elseif ($question->difficulty === 'hard')
                                            <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">Hard</span>
                                        @else
                                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Medium</span>
                                        @endif
                                    </div>

                                    <h3 class="text-lg font-semibold leading-relaxed text-slate-900">
                                        {{ $question->question }}
                                    </h3>

                                    <div class="mt-3 flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-500">
                                        <span><span class="text-slate-400">Course:</span> {{ $question->quiz->course->name ?? '-' }}</span>
                                        <span><span class="text-slate-400">Quiz:</span> {{ $question->quiz->title ?? '-' }}</span>
                                        <span><span class="text-slate-400">Author:</span> {{ $question->user->name ?? 'Unknown' }}</span>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <form method="POST" action="{{ route('admin.questions.approve', $question->id) }}">
                                        @csrf
                                        <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                            Approve
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.questions.reject', $question->id) }}">
                                        @csrf
                                        <button type="submit" class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-amber-600">
                                            Reject
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.questions.edit', $question->id) }}"
                                        class="rounded-xl bg-blue-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-800">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('admin.questions.destroy', $question->id) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus soal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="grid gap-3 md:grid-cols-2">
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-3 text-sm text-slate-600">
                                    <span class="font-semibold text-slate-800">A.</span> {{ $question->option_a }}
                                </div>
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-3 text-sm text-slate-600">
                                    <span class="font-semibold text-slate-800">B.</span> {{ $question->option_b }}
                                </div>
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-3 text-sm text-slate-600">
                                    <span class="font-semibold text-slate-800">C.</span> {{ $question->option_c }}
                                </div>
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-3 text-sm text-slate-600">
                                    <span class="font-semibold text-slate-800">D.</span> {{ $question->option_d }}
                                </div>
                            </div>

                            <div class="mt-4 text-sm text-slate-500">
                                Jawaban benar:
                                <span class="font-semibold text-emerald-600">{{ $question->correct_answer }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-400">
                            Belum ada soal yang masuk ke bank soal.
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
