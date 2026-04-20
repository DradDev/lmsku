<x-app-layout>
    <div class="min-h-screen bg-[#050C1B]">
        <div class="max-w-5xl mx-auto px-6 py-8">
            <div class="mb-6">
                <a href="{{ route('student.submissions.index') }}"
                   class="inline-flex items-center text-sm text-slate-400 hover:text-violet-400 mb-4 transition">
                    ← Back to Submissions
                </a>

                <div class="rounded-2xl border border-indigo-400/15 bg-[#0B1328] p-6 shadow-sm">
                    <p class="text-sm font-semibold text-violet-400 uppercase tracking-wide mb-2">
                        {{ $submission->assignment->course->name ?? $submission->assignment->course->title ?? 'Course' }}
                    </p>

                    <h1 class="text-3xl font-bold text-white">
                        {{ $submission->assignment->title ?? 'Assignment Submission' }}
                    </h1>

                    <p class="text-slate-400 mt-2">
                        Submitted by {{ $submission->user->name ?? auth()->user()->name }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2">
                    <div class="rounded-2xl border border-indigo-400/15 bg-[#0B1328] p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-white mb-5">Submission Details</h2>

                        <div class="space-y-4 text-sm">
                            <div>
                                <p class="text-slate-400 mb-1">Assignment</p>
                                <p class="font-semibold text-slate-100">
                                    {{ $submission->assignment->title ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-slate-400 mb-1">Course</p>
                                <p class="font-semibold text-slate-100">
                                    {{ $submission->assignment->course->name ?? $submission->assignment->course->title ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-slate-400 mb-1">Submitted At</p>
                                <p class="font-semibold text-slate-100">
                                    {{ optional($submission->created_at)->format('d M Y, H:i') ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-slate-400 mb-1">Grade</p>
                                <p class="font-semibold text-slate-100">
                                    {{ is_null($submission->grade) ? 'Not graded yet' : $submission->grade }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="rounded-2xl border border-indigo-400/15 bg-[#0B1328] p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-white mb-4">Quick Actions</h2>

                        <div class="space-y-3">
                            @if(!empty($submission->file_path))
                                <a href="{{ asset('storage/' . $submission->file_path) }}"
                                   target="_blank"
                                   class="block w-full text-center rounded-xl bg-gradient-to-r from-violet-500 to-fuchsia-500 px-4 py-2.5 text-sm font-medium text-white transition hover:from-violet-400 hover:to-fuchsia-400">
                                    Open Submission File
                                </a>

                                <a href="{{ asset('storage/' . $submission->file_path) }}"
                                   download
                                   class="block w-full text-center rounded-xl bg-[#111B34] px-4 py-2.5 text-sm font-medium text-slate-100 border border-white/5 hover:bg-[#16213d] transition">
                                    Download File
                                </a>
                            @else
                                <div class="rounded-xl border border-amber-400/20 bg-amber-500/10 p-4 text-sm text-amber-300">
                                    File submission belum tersedia.
                                </div>
                            @endif

                            <a href="{{ route('student.submissions.index') }}"
                               class="block w-full text-center rounded-xl border border-violet-400/20 bg-transparent px-4 py-2.5 text-sm font-medium text-violet-300 hover:bg-violet-500/10 transition">
                                Back to Submission List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
