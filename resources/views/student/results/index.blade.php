<x-app-layout>
    @php
        $totalResults = $results->count();
        $averageScore = $totalResults > 0 ? round($results->avg('score'), 2) : 0;
        $highestScore = $totalResults > 0 ? $results->max('score') : 0;
    @endphp

    <main class="min-h-screen bg-slate-50 py-10" role="main" aria-label="Halaman Hasil Evaluasi Kuis dan Proyek">
        <div class="max-w-7xl mx-auto px-6">

            <div class="mb-8">
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-indigo-700 mb-2">
                    COMPRO TEKKOM · Student Portal
                </p>
                <h1 class="text-3xl font-extrabold text-slate-900">Quiz Results</h1>
                <p class="mt-2 text-slate-700 font-medium">
                    All your competency evaluation quiz results grouped by course.
                </p>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900 font-semibold" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-600">Total Results</p>
                    <p class="mt-3 text-3xl font-extrabold text-slate-900">{{ $totalResults }}</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-600">Average Score</p>
                    <p class="mt-3 text-3xl font-extrabold text-indigo-700">{{ $averageScore }}</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-600">Highest Score</p>
                    <p class="mt-3 text-3xl font-extrabold text-emerald-700">{{ $highestScore }}</p>
                </div>
            </div>

            @if($resultsByCourse->count())
                <div class="space-y-8">
                    @foreach($resultsByCourse as $courseName => $courseResults)
                        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                            <div class="border-b border-slate-200 px-6 py-5">
                                <h2 class="text-xl font-bold text-slate-900">{{ $courseName }}</h2>
                                <p class="mt-1 text-sm text-slate-600 font-medium">
                                    {{ $courseResults->count() }} quiz attempt(s)
                                </p>
                            </div>

                            <div class="divide-y divide-slate-200">
                                @foreach($courseResults as $result)
                                    <div class="px-6 py-5 hover:bg-slate-50 transition">
                                        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                                            <div>
                                                <div class="flex items-center gap-2 flex-wrap mb-2">
                                                    <h3 class="text-lg font-bold text-slate-900">
                                                        {{ $result->quiz->title ?? '-' }}
                                                    </h3>

                                                    @if($result->is_verified)
                                                        <span class="inline-flex items-center rounded-full border border-emerald-300 bg-emerald-100 px-3 py-1 text-xs font-extrabold text-emerald-900">
                                                            Graded
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center rounded-full border border-amber-300 bg-amber-100 px-3 py-1 text-xs font-extrabold text-amber-900">
                                                            Pending Grading
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                                    <div>
                                                        <p class="text-xs font-bold text-slate-600 uppercase">Date</p>
                                                        <p class="font-semibold text-slate-900">
                                                            {{ $result->created_at ? $result->created_at->format('d M Y H:i') : '-' }}
                                                        </p>
                                                    </div>

                                                    <div>
                                                        <p class="text-xs font-bold text-slate-600 uppercase">Status</p>
                                                        <p class="font-bold {{ $result->is_verified ? 'text-emerald-700' : 'text-amber-800' }}">
                                                            {{ $result->is_verified ? 'Graded' : 'Pending Author / Vendor' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                                <div class="rounded-2xl bg-indigo-50 border border-indigo-200 px-5 py-3 min-w-[120px] text-center">
                                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-600">Score</p>
                                                    <p class="mt-1 text-2xl font-extrabold text-indigo-700">{{ $result->score }}</p>
                                                </div>

                                                <a href="{{ route('student.results.show', $result->id) }}"
                                                   aria-label="Lihat rincian hasil kuis {{ $result->quiz->title ?? '' }}"
                                                   class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white hover:bg-slate-800 transition">
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
                <div class="rounded-3xl border border-slate-200 bg-white p-12 text-center text-slate-600 font-medium shadow-sm">
                    No quiz results available yet.
                </div>
            @endif

            @if($joinedProjects->count() > 0)
                <div class="mt-12 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                                <span>💼 Verified Project Results</span>
                            </h2>
                            <p class="text-sm text-slate-600 font-medium mt-1">Real-world industry projects accepted & completed by you.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($joinedProjects as $project)
                            <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition space-y-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-300 mb-2">
                                            ✓ Accepted & Joined
                                        </span>
                                        <h3 class="text-lg font-bold text-gray-900">{{ $project->title }}</h3>
                                        <p class="text-xs text-slate-600 font-medium mt-0.5">Author/Vendor: {{ $project->user->name ?? 'Vendor' }}</p>
                                    </div>
                                </div>

                                <p class="text-xs text-slate-700 line-clamp-2 leading-relaxed font-medium">{{ $project->description }}</p>

                                <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                                    <span class="font-bold text-indigo-700">Level: {{ ucfirst($project->difficulty_level) }}</span>
                                    <a href="{{ route('student.projects.show', $project->id) }}"
                                       aria-label="Lihat detail proyek {{ $project->title }}"
                                       class="text-indigo-700 hover:text-indigo-900 font-bold underline">View Project →</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </main>
</x-app-layout>