<x-app-layout>
    <div class="min-h-screen bg-slate-50 text-slate-900 py-8">
        <div class="mx-auto max-w-6xl px-6">
            <div class="mb-8">
                <a href="{{ route('lecturer.courses.show', $course->id) }}"
                   class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition mb-3">
                    ← Back to Course
                </a>

                <h1 class="text-3xl font-bold text-gray-900">Student Quiz Results</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Quiz: <span class="font-semibold text-slate-700">{{ $quiz->title }}</span> — Course: <span class="font-semibold text-slate-700">{{ $course->name }}</span>
                </p>
            </div>

            <div class="rounded-3xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                @if($attempts->count())
                    <div class="divide-y divide-gray-100">
                        @foreach($attempts as $attempt)
                            <div class="px-6 py-5 hover:bg-gray-50/80 transition">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap mb-1">
                                            <h3 class="text-base font-bold text-gray-900">
                                                {{ $attempt->user->name ?? 'Mahasiswa' }}
                                            </h3>

                                            @if($attempt->is_verified)
                                                <span class="inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                    Selesai
                                                </span>
                                            @else
                                                <span class="inline-flex rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                                    Menunggu Penilaian
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-slate-500">
                                            {{ $attempt->user->email ?? '-' }} ·
                                            {{ optional($attempt->created_at)->format('d M Y, H:i') }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <div class="rounded-2xl bg-slate-50 border border-gray-200 px-5 py-2 text-center">
                                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Score</p>
                                            <p class="text-xl font-bold text-indigo-600">{{ $attempt->score }}</p>
                                        </div>

                                        <a href="{{ route('lecturer.courses.quizzes.results.show', [$course->id, $quiz->id, $attempt->id]) }}"
                                           class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition">
                                            Lihat Jawaban
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-16 text-center text-slate-400 font-medium">
                        Belum ada mahasiswa yang mengerjakan quiz ini.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>