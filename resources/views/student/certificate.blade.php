<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-5xl mx-auto px-6">
            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <a href="{{ route('student.certificate.index') }}"
                   class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                    ← Back to Certificates
                </a>

                <a href="{{ route('student.certificate.download', $course->id) }}"
                   class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition shadow-sm">
                    Download PDF Certificate
                </a>
            </div>

            <div class="bg-white border border-slate-200 rounded-3xl shadow-sm p-12 text-center relative overflow-hidden">
                <div class="mb-6">
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-xs font-mono font-bold text-indigo-700">
                        🔑 Credential ID: {{ $credentialCode }}
                    </span>
                </div>

                <p class="text-sm uppercase tracking-[0.3em] font-bold text-slate-400 mb-4">
                    Certificate of Course Completion
                </p>

                <h1 class="text-5xl font-bold text-slate-900 mb-4">
                    {{ $student->name }}
                </h1>

                <p class="text-lg text-slate-600 mb-8">
                    has successfully completed the competency evaluation and final quiz for
                </p>

                <h2 class="text-3xl font-bold text-indigo-600 mb-3">
                    {{ $course->name }}
                </h2>

                <p class="text-slate-500 mb-10 text-sm">
                    Final Quiz: {{ $finalQuiz->title }}
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Final Score</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ $attempt->score ?? 100 }} / 100</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Status</p>
                        <p class="mt-1 text-xl font-bold text-emerald-600">Verified</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Issued Date</p>
                        <p class="mt-1 text-xl font-bold text-slate-900">{{ now()->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="mt-12 pt-8 border-t border-slate-200 flex justify-between items-center text-xs text-slate-500">
                    <div>
                        <p class="font-bold text-slate-700">Instructor / Author</p>
                        <p>{{ $course->user->name ?? $course->lecturer->name ?? 'Lecturer' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-slate-700">Credential ID</p>
                        <p class="font-mono text-indigo-600 font-bold">{{ $credentialCode }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
