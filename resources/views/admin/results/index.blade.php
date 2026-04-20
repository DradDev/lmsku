<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                    Admin Panel
                </p>
                <h1 class="text-3xl font-bold text-slate-900">Quiz Results</h1>
                <p class="mt-2 text-slate-500">
                    Pantau hasil quiz mahasiswa, status verifikasi, dan detail attempt.
                </p>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Results</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $totalResults }}</p>
                </div>

                <div class="rounded-2xl border border-emerald-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Verified</p>
                    <p class="mt-3 text-3xl font-bold text-emerald-600">{{ $verifiedCount }}</p>
                </div>

                <div class="rounded-2xl border border-amber-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Pending</p>
                    <p class="mt-3 text-3xl font-bold text-amber-500">{{ $pendingCount }}</p>
                </div>

                <div class="rounded-2xl border border-indigo-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Average Score</p>
                    <p class="mt-3 text-3xl font-bold text-indigo-600">{{ $averageScore }}</p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-xl font-semibold text-slate-900">All Quiz Attempts</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Daftar seluruh hasil quiz mahasiswa.
                    </p>
                </div>

                @if($results->count())
                    <div class="divide-y divide-slate-200">
                        @foreach ($results as $result)
                            <div class="px-6 py-5 hover:bg-slate-50 transition">
                                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap mb-2">
                                            <h3 class="text-lg font-semibold text-slate-900">
                                                {{ $result->quiz->title ?? 'Quiz' }}
                                            </h3>

                                            @if($result->is_verified)
                                                <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                    Verified
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                                    Pending
                                                </span>
                                            @endif
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                                            <div>
                                                <p class="text-slate-400">Student</p>
                                                <p class="font-medium text-slate-800">{{ $result->user->name ?? 'Unknown Student' }}</p>
                                            </div>

                                            <div>
                                                <p class="text-slate-400">Course</p>
                                                <p class="font-medium text-slate-800">{{ $result->quiz->course->name ?? '-' }}</p>
                                            </div>

                                            <div>
                                                <p class="text-slate-400">Submitted</p>
                                                <p class="font-medium text-slate-800">
                                                    {{ optional($result->created_at)->format('d M Y, H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 xl:justify-end">
                                        <div class="rounded-2xl bg-indigo-50 border border-indigo-100 px-5 py-3 min-w-[120px] text-center">
                                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Score</p>
                                            <p class="mt-1 text-2xl font-bold text-indigo-600">{{ $result->score }}</p>
                                        </div>

                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('admin.results.show', $result->id) }}"
                                               class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                                                View Details
                                            </a>

                                            @if(!$result->is_verified)
                                                <form method="POST" action="{{ route('admin.results.verify', $result->id) }}">
                                                    @csrf
                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                                                        Verify
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <div class="mx-auto max-w-md">
                            <h3 class="text-lg font-semibold text-slate-900">Belum ada hasil quiz</h3>
                            <p class="mt-2 text-sm text-slate-500">
                                Result quiz mahasiswa akan muncul di sini setelah ada submission.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
