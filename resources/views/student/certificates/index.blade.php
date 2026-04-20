<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-6">

            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                    Student Portal
                </p>
                <h1 class="text-3xl font-bold text-slate-900">Certificates</h1>
                <p class="mt-2 text-slate-500">
                    Lihat certificate yang sudah tersedia dan pantau certificate yang masih terkunci.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                @forelse($courses as $course)
                    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <h2 class="text-lg font-semibold text-slate-900">{{ $course->name }}</h2>

                            @if($course->can_get_certificate)
                                <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                    Ready
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                    Locked
                                </span>
                            @endif
                        </div>

                        <div class="space-y-3 mb-5">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Instructor</p>
                                <p class="mt-1 text-sm font-medium text-slate-800">{{ $course->user->name ?? 'Lecturer' }}</p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Final Quiz</p>
                                <p class="mt-1 text-sm font-medium text-slate-800">{{ $course->final_quiz?->title ?? 'Belum ada final quiz' }}</p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Status</p>
                                <p class="mt-1 text-sm font-medium text-slate-800">{{ $course->certificate_status_text }}</p>
                            </div>

                            @if($course->verified_final_attempt)
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Verified Score</p>
                                    <p class="mt-1 text-2xl font-bold text-indigo-600">{{ $course->verified_final_attempt->score }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @if($course->can_get_certificate)
                                <a href="{{ route('student.certificate.show', $course->id) }}"
                                   class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                                    View Certificate
                                </a>

                                <a href="{{ route('student.certificate.download', $course->id) }}"
                                   class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                                    Download
                                </a>
                            @else
                                <a href="{{ route('student.courses.show', $course->id) }}"
                                   class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                    Open Course
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-slate-500 shadow-sm">
                        Belum ada course yang terdaftar.
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
