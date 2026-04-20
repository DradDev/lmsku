<x-app-layout>
<div class="min-h-screen bg-slate-950 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 py-8">

        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Daftar Assignment</h1>
                <p class="mt-1 text-sm text-slate-400">
                    Kelola tugas untuk course yang Anda ajar.
                </p>
            </div>

            <a href="{{ route('lecturer.assignments.create') }}"
               class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">
                + Buat Assignment
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse($assignments as $assignment)
                <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5 hover:border-slate-600 transition">
                    <div class="mb-4">
                        <span class="rounded-full bg-blue-500/10 px-3 py-1 text-xs font-semibold text-blue-300">
                            Assignment
                        </span>
                    </div>

                    <h2 class="text-lg font-bold text-white">
                        {{ $assignment->title }}
                    </h2>

                    <p class="mt-2 text-sm text-slate-400">
                        Deadline:
                        <span class="text-slate-200">
                            {{ $assignment->deadline ?? 'Tidak ada deadline' }}
                        </span>
                    </p>

                    @if($assignment->course ?? false)
                        <p class="mt-1 text-sm text-slate-500">
                            Course: {{ $assignment->course->name }}
                        </p>
                    @endif

                    <div class="mt-5 flex gap-2">
                        <a href="{{ route('lecturer.assignments.show', $assignment->id) }}"
                           class="rounded-xl bg-slate-800 px-3 py-2 text-sm font-semibold text-slate-200 hover:bg-slate-700">
                            View
                        </a>

                        <a href="{{ route('lecturer.assignments.edit', $assignment->id) }}"
                           class="rounded-xl bg-amber-500 px-3 py-2 text-sm font-semibold text-white hover:bg-amber-400">
                            Edit
                        </a>

                        <form action="{{ route('lecturer.assignments.destroy', $assignment->id) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus assignment ini?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="rounded-xl bg-rose-600 px-3 py-2 text-sm font-semibold text-white hover:bg-rose-500">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-800 bg-slate-900/50 p-10 text-center md:col-span-2 xl:col-span-3">
                    <p class="text-sm text-slate-400">Belum ada assignment.</p>
                    <a href="{{ route('lecturer.assignments.create') }}"
                       class="mt-4 inline-block rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">
                        Buat assignment pertama
                    </a>
                </div>
            @endforelse
        </div>

    </div>
</div>
</x-app-layout>
