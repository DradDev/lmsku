<x-app-layout>
<div class="min-h-screen bg-slate-950 text-white">
    <div class="mx-auto max-w-5xl px-4 py-8">

        <a href="{{ route('lecturer.assignments.index') }}"
           class="text-sm text-slate-400 hover:text-white">
            ← Kembali
        </a>

        <div class="mt-6 rounded-2xl border border-slate-800 bg-slate-900 p-6">
            <span class="rounded-full bg-blue-500/10 px-3 py-1 text-xs font-semibold text-blue-300">
                {{ $assignment->course->name ?? 'Course' }}
            </span>

            <h1 class="mt-4 text-2xl font-bold">{{ $assignment->title }}</h1>

            <p class="mt-3 text-sm text-slate-400">
                Deadline:
                <span class="text-slate-200">
                    {{ $assignment->deadline ?? 'Tidak ada deadline' }}
                </span>
            </p>

            <div class="mt-6 rounded-xl bg-slate-950 p-4 text-sm text-slate-300">
                {{ $assignment->description ?? 'Tidak ada deskripsi.' }}
            </div>
        </div>

        <div class="mt-8">
            <h2 class="mb-4 text-xl font-bold">Submission Mahasiswa</h2>

            @if(session('success'))
                <div class="mb-4 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-4">
                @forelse($assignment->submissions as $submission)
                    <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5">
                        <div class="mb-4 flex items-start justify-between gap-4">
                            <div>
                                <h3 class="font-bold text-white">
                                    {{ $submission->user->name ?? 'Student' }}
                                </h3>

                                <p class="mt-1 text-sm text-slate-400">
                                    Submitted:
                                    {{ $submission->created_at ? $submission->created_at->format('d M Y H:i') : '-' }}
                                </p>
                            </div>

                            <span class="rounded-full bg-purple-500/10 px-3 py-1 text-xs font-semibold text-purple-300">
                                {{ $submission->grade ?? $submission->score ?? 'Belum dinilai' }}
                            </span>
                        </div>

                        @if(!empty($submission->answer_text))
                            <div class="mb-4 rounded-xl bg-slate-950 p-4 text-sm text-slate-300">
                                {{ $submission->answer_text }}
                            </div>
                        @endif

                        @if(!empty($submission->file_path))
                            <a href="{{ asset('storage/' . $submission->file_path) }}"
                               target="_blank"
                               class="mb-4 inline-block rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">
                                Download File
                            </a>
                        @endif

                        <form method="POST"
                              action="{{ route('lecturer.assignments.submissions.grade', [$assignment->id, $submission->id]) }}"
                              class="mt-4 grid gap-3 md:grid-cols-[160px_1fr_auto]">
                            @csrf
                            @method('PATCH')

                            <input type="number"
                                   name="grade"
                                   min="0"
                                   max="100"
                                   value="{{ $submission->grade ?? $submission->score ?? '' }}"
                                   placeholder="Nilai"
                                   class="rounded-xl border border-slate-700 bg-slate-950 px-4 py-2 text-white">

                            <input type="text"
                                   name="feedback"
                                   value="{{ $submission->feedback ?? '' }}"
                                   placeholder="Feedback untuk mahasiswa"
                                   class="rounded-xl border border-slate-700 bg-slate-950 px-4 py-2 text-white">

                            <button type="submit"
                                    class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">
                                Simpan Nilai
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-800 bg-slate-900/50 p-10 text-center">
                        <p class="text-sm text-slate-400">Belum ada mahasiswa yang submit assignment ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
</x-app-layout>
