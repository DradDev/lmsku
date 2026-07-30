<x-app-layout>
    @php
        $totalResults = $results->count();
        $averageScore = $totalResults > 0 ? round($results->avg('score'), 2) : 0;
        $highestScore = $totalResults > 0 ? $results->max('score') : 0;
    @endphp

    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-6">

            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                    Student Portal
                </p>
                <h1 class="text-3xl font-bold text-slate-900">Quiz Results</h1>
                <p class="mt-2 text-slate-500">
                    Semua hasil quiz kamu, dikelompokkan per course.
                </p>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Results</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $totalResults }}</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Average Score</p>
                    <p class="mt-3 text-3xl font-bold text-indigo-600">{{ $averageScore }}</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Highest Score</p>
                    <p class="mt-3 text-3xl font-bold text-emerald-600">{{ $highestScore }}</p>
                </div>
            </div>

            @if($resultsByCourse->count())
                <div class="space-y-8">
                    @foreach($resultsByCourse as $courseName => $courseResults)
                        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                            <div class="border-b border-slate-200 px-6 py-5">
                                <h2 class="text-xl font-semibold text-slate-900">{{ $courseName }}</h2>
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $courseResults->count() }} quiz attempt
                                </p>
                            </div>

                            <div class="divide-y divide-slate-200">
                                @foreach($courseResults as $result)
                                    <div class="px-6 py-5 hover:bg-slate-50 transition">
                                        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                                            <div>
                                                <div class="flex items-center gap-2 flex-wrap mb-2">
                                                    <h3 class="text-lg font-semibold text-slate-900">
                                                        {{ $result->quiz->title ?? '-' }}
                                                    </h3>

                                                    @if($result->is_verified)
                                                        <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                            Selesai
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                                            Menunggu Penilaian Essay
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                                    <div>
                                                        <p class="text-slate-400">Date</p>
                                                        <p class="font-medium text-slate-800">
                                                            {{ $result->created_at ? $result->created_at->format('d M Y H:i') : '-' }}
                                                        </p>
                                                    </div>

                                                    <div>
                                                        <p class="text-slate-400">Status</p>
                                                        <p class="font-medium {{ $result->is_verified ? 'text-emerald-600' : 'text-amber-600' }}">
                                                            {{ $result->is_verified ? 'Sudah dinilai' : 'Menunggu lecturer' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                                <div class="rounded-2xl bg-indigo-50 border border-indigo-100 px-5 py-3 min-w-[120px] text-center">
                                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Score</p>
                                                    <p class="mt-1 text-2xl font-bold text-indigo-600">{{ $result->score }}</p>
                                                </div>

                                                <a href="{{ route('student.results.show', $result->id) }}"
                                                   class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-3xl border border-slate-200 bg-white p-12 text-center text-slate-500 shadow-sm">
                    Belum ada hasil quiz.
                </div>
            @endif

        </div>
    </div>
</x-app-layout>