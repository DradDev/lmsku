<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-6">

            <div class="mb-8">
                <a href="{{ route('lecturer.courses.index') }}"
                   class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 mb-4">
                    ← Kembali ke Courses
                </a>

                <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-6">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                            Lecturer Portal
                        </p>
                        <h1 class="text-3xl font-bold tracking-tight text-slate-900">{{ $course->name }}</h1>
                        <p class="mt-3 max-w-3xl text-slate-500 leading-7">
                            {{ $course->description ?: 'No description available for this course.' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 min-w-full xl:min-w-[460px]">
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Materials</p>
                            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $materials->count() }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Quizzes</p>
                            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $quizzes->count() }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Assignments</p>
                            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $assignments->count() }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Students</p>
                            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $students->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(auth()->check() && auth()->user()->role === 'lecturer' && $course->user_id === auth()->id())
                <div class="mb-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-5">
                        <h2 class="text-xl font-semibold text-slate-900">Quiz Management</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Lecturer bisa langsung membuat quiz tanpa harus upload material terlebih dahulu.
                            Tandai satu quiz sebagai <span class="font-semibold text-violet-700">Final Quiz</span> untuk certificate.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('lecturer.courses.quizzes.store', $course->id) }}" class="grid gap-4 md:grid-cols-3">
                        @csrf

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-slate-700">Quiz Title</label>
                            <input
                                type="text"
                                name="title"
                                value="{{ old('title') }}"
                                class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-violet-500 focus:outline-none"
                                placeholder="Contoh: Week 1 Quiz"
                                required
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">Time Limit (minutes)</label>
                            <input
                                type="number"
                                name="time_limit"
                                value="{{ old('time_limit') }}"
                                min="1"
                                class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-violet-500 focus:outline-none"
                                placeholder="Optional"
                            >
                        </div>

                        <div class="md:col-span-3 rounded-2xl border border-violet-200 bg-violet-50 px-4 py-3">
                            <label class="flex items-center gap-3 text-sm font-medium text-slate-700">
                                <input
                                    type="checkbox"
                                    name="is_final"
                                    value="1"
                                    {{ old('is_final') ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500"
                                >
                                Jadikan quiz ini sebagai <span class="font-semibold text-violet-700">Final Quiz</span> untuk certificate
                            </label>
                        </div>

                        <div class="md:col-span-3 flex justify-end">
                            <button
                                type="submit"
                                class="rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700">
                                + Create Quiz
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2 space-y-6">

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-6">
                            <h2 class="text-2xl font-semibold text-slate-900">Course Quizzes</h2>
                            <p class="mt-1 text-sm text-slate-500">Semua quiz yang tersedia untuk course ini.</p>
                        </div>

                        <div class="space-y-4">
                            @forelse($quizzes as $quiz)
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                        <div>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <h3 class="text-lg font-semibold text-slate-900">{{ $quiz->title }}</h3>

                                                @if($quiz->is_final)
                                                    <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                        Final Quiz
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="mt-2 text-sm text-slate-500">
                                                Time Limit:
                                                <span class="text-slate-700">{{ $quiz->time_limit ? $quiz->time_limit . ' minutes' : 'No limit' }}</span>
                                            </p>

                                            <p class="mt-1 text-sm text-slate-500">
                                                Total Questions:
                                                <span class="text-slate-700">{{ $quiz->questions->count() }}</span>
                                            </p>

                                            @if($quiz->is_final)
                                                <p class="mt-2 text-xs font-medium text-emerald-700">
                                                    Quiz ini dipakai untuk penentuan certificate student.
                                                </p>
                                            @endif
                                        </div>

                                        <div class="flex flex-wrap gap-2">
                                            @if($quiz->questions->where('question_type', 'essay')->count() > 0)
                                                <a href="{{ route('lecturer.courses.quizzes.answers.index', [$course->id, $quiz->id]) }}"
                                                   class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-amber-600">
                                                    Nilai Essay
                                                </a>
                                            @endif

                                            @if(!$quiz->is_final)
                                                <form method="POST" action="{{ route('lecturer.courses.quizzes.make-final', [$course->id, $quiz->id]) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button
                                                        type="submit"
                                                        class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
                                                        Set Final
                                                    </button>
                                                </form>
                                            @endif

                                            <form method="POST"
                                                  action="{{ route('lecturer.courses.quizzes.destroy', [$course->id, $quiz->id]) }}"
                                                  onsubmit="return confirm('Yakin ingin menghapus quiz ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-8 text-center text-slate-500">
                                    Belum ada quiz untuk course ini.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-6">
                            <h2 class="text-2xl font-semibold text-slate-900">Learning Materials</h2>
                            <p class="mt-1 text-sm text-slate-500">Materi pembelajaran yang tersedia di course ini.</p>
                        </div>

                        @if($materials->count())
                            <div class="grid gap-4 md:grid-cols-2">
                                @foreach($materials as $material)
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                        <h3 class="font-semibold text-slate-900">{{ $material->title }}</h3>
                                        <p class="mt-2 text-sm text-slate-500">
                                            Uploaded {{ optional($material->created_at)->format('d M Y') ?: '-' }}
                                        </p>
                                        <a href="{{ route('lecturer.materials.show', $material->id) }}"
                                           class="mt-4 inline-flex rounded-xl bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700">
                                            View Material
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-8 text-center text-slate-500">
                                Belum ada materi untuk course ini.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-slate-900 mb-5">Students</h2>

                        @if($students->count())
                            <div class="space-y-3">
                                @foreach($students as $student)
                                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                        <p class="font-semibold text-slate-900">{{ $student->name }}</p>
                                        <p class="mt-1 text-sm text-slate-500">{{ $student->email ?? 'No email' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-500">
                                Belum ada mahasiswa yang terdaftar.
                            </div>
                        @endif
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-slate-900 mb-5">Assignments</h2>

                        @if($assignments->count())
                            <div class="space-y-3">
                                @foreach($assignments as $assignment)
                                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                        <p class="font-semibold text-slate-900">{{ $assignment->title }}</p>
                                        <p class="mt-1 text-sm text-slate-500">
                                            Deadline: {{ $assignment->deadline ? \Carbon\Carbon::parse($assignment->deadline)->format('d M Y') : 'No deadline' }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-500">
                                Belum ada assignment di course ini.
                            </div>
                        @endif
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-slate-900 mb-5">Quick Actions</h2>

                        <div class="space-y-3">
                            <a href="{{ route('lecturer.courses.edit', $course->id) }}"
                               class="block w-full text-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Edit Course
                            </a>

                            <a href="{{ route('lecturer.materials.create') }}"
                               class="block w-full text-center rounded-xl bg-violet-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-violet-700">
                                Upload Material
                            </a>

                            <a href="{{ route('lecturer.dashboard', ['tab' => 'questions']) }}"
                               class="block w-full text-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                                Manage Questions
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
