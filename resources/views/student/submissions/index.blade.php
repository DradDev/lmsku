<x-app-layout>
    <div class="min-h-screen bg-[#050C1B]">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white">My Submissions</h1>
                <p class="text-slate-400 mt-2">
                    Track all assignment submissions from your enrolled courses.
                </p>
            </div>

            @if(session('success'))
                <div class="mb-4 rounded-xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 rounded-xl border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-rose-300">
                    {{ session('error') }}
                </div>
            @endif

            @if($submissions->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($submissions as $submission)
                        @php
                            $grade = $submission->grade;
                            $status = is_null($grade) ? 'Pending Review' : 'Graded';
                            $statusClass = is_null($grade)
                                ? 'bg-amber-400/10 text-amber-300 border border-amber-400/20'
                                : 'bg-emerald-400/10 text-emerald-300 border border-emerald-400/20';
                        @endphp

                        <div class="rounded-2xl border border-indigo-400/15 bg-[#0B1328] p-6 shadow-sm transition hover:border-violet-400/30 hover:bg-[#0E1730]">
                            <div class="flex items-start justify-between gap-4 mb-4">
                                <div>
                                    <p class="text-xs font-semibold text-violet-400 uppercase tracking-wide mb-2">
                                        {{ $submission->assignment->course->name ?? $submission->assignment->course->title ?? 'Course' }}
                                    </p>

                                    <h2 class="text-xl font-bold text-white">
                                        {{ $submission->assignment->title ?? 'Assignment' }}
                                    </h2>

                                    <p class="text-sm text-slate-400 mt-2">
                                        Submitted by {{ $submission->user->name ?? auth()->user()->name }}
                                    </p>
                                </div>

                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ $status }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-5 text-sm">
                                <div class="rounded-xl border border-white/5 bg-[#111B34] p-4">
                                    <p class="text-slate-400 mb-1">Submitted At</p>
                                    <p class="font-semibold text-slate-100">
                                        {{ optional($submission->created_at)->format('d M Y, H:i') ?? '-' }}
                                    </p>
                                </div>

                                <div class="rounded-xl border border-white/5 bg-[#111B34] p-4">
                                    <p class="text-slate-400 mb-1">Grade</p>
                                    <p class="font-semibold text-slate-100">
                                        {{ is_null($grade) ? 'Not graded yet' : $grade }}
                                    </p>
                                </div>
                            </div>

                            <a href="{{ route('student.submissions.show', $submission->id) }}"
                               class="block text-center rounded-xl bg-gradient-to-r from-violet-500 to-fuchsia-500 px-4 py-2.5 text-sm font-medium text-white transition hover:from-violet-400 hover:to-fuchsia-400">
                                View Submission
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-amber-400/20 bg-amber-500/10 p-6 text-amber-300">
                    Kamu belum memiliki submission assignment.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
