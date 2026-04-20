<x-app-layout>
<div class="min-h-screen bg-slate-950 text-white">
    <div class="mx-auto max-w-4xl px-4 py-8">

        <a href="{{ route('student.results.index') }}"
           class="text-sm text-slate-400 hover:text-white">
            ← Kembali ke Quiz Results
        </a>

        <div class="mt-6 rounded-3xl border border-slate-800 bg-slate-900 p-6">
            <div class="mb-4 flex flex-wrap gap-2">
                <span class="rounded-full bg-emerald-500/15 px-3 py-1 text-xs font-semibold text-emerald-300">
                    Verified
                </span>

                @if(!empty($result->blockchain_hash))
                    <span class="rounded-full bg-blue-500/15 px-3 py-1 text-xs font-semibold text-blue-300">
                        Blockchain Recorded
                    </span>
                @endif
            </div>

            <h1 class="text-2xl font-bold text-white">
                {{ $result->quiz->title ?? 'Quiz Result' }}
            </h1>

            <p class="mt-2 text-sm text-slate-400">
                Course:
                <span class="text-slate-200">{{ $result->quiz->course->name ?? '-' }}</span>
            </p>

            <p class="mt-1 text-sm text-slate-400">
                Date:
                <span class="text-slate-200">
                    {{ $result->created_at ? $result->created_at->format('d M Y H:i') : '-' }}
                </span>
            </p>

            <div class="mt-8 rounded-2xl bg-slate-950 p-6 text-center">
                <p class="text-sm uppercase tracking-widest text-slate-500">Final Score</p>
                <h2 class="mt-3 text-5xl font-bold text-purple-400">
                    {{ $result->score }}
                </h2>
            </div>

            @if(!empty($result->blockchain_hash))
                <div class="mt-6 rounded-2xl border border-slate-800 bg-slate-950 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">
                        Blockchain Hash
                    </p>
                    <p class="break-all text-sm text-slate-300">
                        {{ $result->blockchain_hash }}
                    </p>
                </div>
            @endif
        </div>

    </div>
</div>
</x-app-layout>
