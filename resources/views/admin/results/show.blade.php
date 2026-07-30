<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-6">

            {{-- Header --}}
            <div class="mb-8 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                <div>
                    <a href="{{ route('admin.results.index') }}"
                       class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 mb-3">
                        ← Back to Results
                    </a>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">Result Details</p>
                    <h1 class="text-3xl font-bold text-slate-900">{{ $result->quiz->title ?? 'Quiz Result' }}</h1>
                    <p class="mt-2 text-slate-500">
                        Status verifikasi blockchain untuk attempt ini. Detail jawaban per soal
                        bisa dilihat oleh lecturer course terkait.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    @if(!$result->is_verified)
                        <form method="POST" action="{{ route('admin.results.verify', $result->id) }}">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700">
                                ✓ Approve & Record to Blockchain
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.results.integrity', $result->id) }}">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">
                                ⬡ Check Integrity
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('info'))
                <div class="mb-6 rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                    {{ session('info') }}
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">

                {{-- Attempt Overview --}}
                <div class="xl:col-span-2 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div>
                            <h2 class="text-xl font-semibold text-slate-900">Attempt Overview</h2>
                            <p class="mt-1 text-sm text-slate-500">Informasi utama dari hasil quiz yang dipilih.</p>
                        </div>
                        @if($result->is_verified)
                            <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                ✓ Verified
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                Pending Verification
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-sm text-slate-400">Student</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">{{ $result->user->name ?? '-' }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-sm text-slate-400">Course</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">{{ $result->quiz->course->name ?? '-' }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-sm text-slate-400">Quiz</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">{{ $result->quiz->title ?? '-' }}</p>
                        </div>
                        <div class="rounded-2xl bg-indigo-50 border border-indigo-100 p-4">
                            <p class="text-sm text-slate-400">Score</p>
                            <p class="mt-1 text-3xl font-bold text-indigo-600">{{ $result->score }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-sm text-slate-400">Submitted At</p>
                            <p class="mt-1 text-base font-semibold text-slate-900">
                                {{ optional($result->created_at)->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-sm text-slate-400">Completed At (Blockchain)</p>
                            <p class="mt-1 text-base font-semibold text-slate-900">
                                {{ $result->completed_at ? $result->completed_at->format('d M Y, H:i') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Verification Panel --}}
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-slate-900 mb-5">Blockchain Verification</h2>

                    <div class="space-y-4">
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-sm text-slate-400">Status</p>
                            <p class="mt-1 text-lg font-semibold {{ $result->is_verified ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $result->is_verified ? '✓ Verified on Blockchain' : 'Pending' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-sm text-slate-400 mb-1">Blockchain ID</p>
                            <p class="text-sm font-mono text-slate-700">
                                {{ $result->blockchain_id ?: '-' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-sm text-slate-400 mb-1">Transaction ID</p>
                            <p class="break-all text-xs font-mono text-slate-600">
                                {{ $result->tx_id ?: 'Belum tersedia.' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-sm text-slate-400 mb-1">Hash (SHA-256)</p>
                            <p class="break-all text-xs font-mono text-slate-600">
                                {{ $result->blockchain_hash ?: 'Belum tersedia karena result belum diverifikasi.' }}
                            </p>
                        </div>

                        @if(!$result->is_verified)
                            <form method="POST" action="{{ route('admin.results.verify', $result->id) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-700">
                                    ✓ Approve & Record to Blockchain
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.results.integrity', $result->id) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-700">
                                    ⬡ Verify Integrity
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>