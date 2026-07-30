<x-app-layout>
    <div class="min-h-screen bg-slate-950 text-white py-8">
        <div class="mx-auto max-w-6xl px-6">
            <div class="mb-8">
                <a href="{{ route('lecturer.courses.show', $course->id) }}"
                   class="text-sm text-blue-400 hover:text-blue-300">
                    ← Kembali ke Course
                </a>

                <h1 class="mt-3 text-3xl font-bold">Hasil Quiz Mahasiswa</h1>
                <p class="mt-2 text-sm text-slate-400">
                    Quiz: {{ $quiz->title }} — Course: {{ $course->name }}
                </p>
            </div>

            <div class="rounded-3xl border border-slate-800 bg-slate-900 shadow-2xl shadow-black/20 overflow-hidden">
                @if($attempts->count())
                    <div class="divide-y divide-slate-800">
                        @foreach($attempts as $attempt)
                            <div class="px-6 py-5 hover:bg-slate-800/50 transition">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap mb-1">
                                            <h3 class="text-base font-semibold text-white">
                                                {{ $attempt->user->name ?? 'Mahasiswa' }}
                                            </h3>

                                            @if($attempt->is_verified)
                                                <span class="inline-flex rounded-full border border-emerald-500/30 bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-300">
                                                    Selesai
                                                </span>
                                            @else
                                                <span class="inline-flex rounded-full border border-amber-500/30 bg-amber-500/10 px-3 py-1 text-xs font-semibold text-amber-300">
                                                    Menunggu Penilaian Essay
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-slate-400">
                                            {{ $attempt->user->email ?? '-' }} ·
                                            {{ optional($attempt->created_at)->format('d M Y, H:i') }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <div class="rounded-2xl bg-slate-800 border border-slate-700 px-5 py-2 text-center">
                                            <p class="text-xs uppercase tracking-wider text-slate-500">Score</p>
                                            <p class="text-xl font-bold text-indigo-400">{{ $attempt->score }}</p>
                                        </div>

                                        <a href="{{ route('lecturer.courses.quizzes.results.show', [$course->id, $quiz->id, $attempt->id]) }}"
                                           class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                                            Lihat Jawaban
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-16 text-center text-slate-500">
                        Belum ada mahasiswa yang mengerjakan quiz ini.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>