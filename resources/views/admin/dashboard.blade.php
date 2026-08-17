<x-app-layout>
    <div class="min-h-screen bg-slate-50 text-slate-800">
        <div class="mx-auto max-w-7xl px-6 py-8">
            <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-slate-900">Admin Dashboard</h1>
                    <p class="mt-2 text-sm text-slate-500">
                        Monitor hasil Asesmen Mahasiswa.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-6">
                <input type="hidden" name="result_status" value="{{ $resultStatus }}">

                <div class="flex flex-col gap-3 md:flex-row">
                    <div class="flex-1">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari nama mahasiswa, email, course..."
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

                <div class="mb-6 grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Results</p>
                        <h3 class="mt-3 text-3xl font-bold text-slate-900">{{ $resultStats['total'] }}</h3>
                    </div>

                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Verified</p>
                        <h3 class="mt-3 text-3xl font-bold text-emerald-800">{{ $resultStats['verified'] }}</h3>
                    </div>

                    <div class="rounded-2xl border border-amber-200 bg-amber-50/50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">Unverified</p>
                        <h3 class="mt-3 text-3xl font-bold text-amber-800">{{ $resultStats['unverified'] }}</h3>
                    </div>
                </div>

                <div class="mb-6 flex flex-wrap gap-3">
                    <a href="{{ route('admin.dashboard', ['result_status' => 'all', 'search' => $search]) }}"
                        class="rounded-full px-4 py-2 text-sm font-medium transition
                        {{ $resultStatus === 'all' ? 'bg-slate-800 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                        All Results
                    </a>

                    <a href="{{ route('admin.dashboard', ['result_status' => 'verified', 'search' => $search]) }}"
                        class="rounded-full px-4 py-2 text-sm font-medium transition
                        {{ $resultStatus === 'verified' ? 'bg-emerald-500 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                        Verified
                    </a>

                    <a href="{{ route('admin.dashboard', ['result_status' => 'unverified', 'search' => $search]) }}"
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

                        </div>

                </div>

        </div>
    </div>
</x-app-layout>
