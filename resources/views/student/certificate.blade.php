<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-5xl mx-auto px-6">
            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <a href="{{ route('student.certificate.index') }}"
                   class="text-sm text-slate-500 hover:text-slate-700">
                    ← Back to Certificates
                </a>

                <a href="{{ route('student.certificate.download', $course->id) }}"
                   class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">
                    Download Certificate
                </a>
            </div>

            <div class="bg-white border border-slate-200 rounded-3xl shadow-sm p-12 text-center">
                <p class="text-sm uppercase tracking-[0.3em] text-slate-400 mb-4">
                    Certificate of Completion
                </p>

                <h1 class="text-5xl font-bold text-slate-900 mb-4">
                    {{ $student->name }}
                </h1>

                <p class="text-lg text-slate-600 mb-8">
                    has successfully completed the final multiple choice quiz for
                </p>

                <h2 class="text-3xl font-semibold text-indigo-600 mb-3">
                    {{ $course->name }}
                </h2>

                <p class="text-slate-500 mb-10">
                    Final Quiz: {{ $finalQuiz->title }}
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-wider text-slate-400">Final Score</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ $attempt->score }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-wider text-slate-400">Blockchain Status</p>
                        @if(!empty($attempt->blockchain_hash))
                            <p class="mt-2 text-xl font-bold text-emerald-600">Verified on Blockchain</p>
                        @else
                            <p class="mt-2 text-xl font-bold text-amber-600">Pending Verification</p>
                        @endif
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-wider text-slate-400">Issued Date</p>
                        <p class="mt-2 text-xl font-bold text-slate-900">{{ now()->format('d M Y') }}</p>
                    </div>
                </div>

                @if(!empty($attempt->blockchain_hash))
                    <div class="mt-8 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-left">
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700 mb-1">Blockchain Record</p>
                        <p class="text-xs font-mono text-emerald-800 break-all">TX: {{ $attempt->tx_id }}</p>
                        <p class="mt-1 text-xs font-mono text-emerald-800 break-all">Hash: {{ $attempt->blockchain_hash }}</p>
                    </div>
                @else
                    <div class="mt-8 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-left">
                        <p class="text-sm text-amber-700">
                            Sertifikat ini masih menunggu approval admin untuk dicatat ke blockchain.
                            Status akan otomatis berubah menjadi "Verified on Blockchain" setelah diproses.
                        </p>
                    </div>
                @endif

                <div class="mt-12 pt-8 border-t border-slate-200">
                    <p class="text-sm text-slate-500">
                        Instructor: {{ $course->user->name ?? 'Lecturer' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
