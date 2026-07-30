<x-app-layout>
    @php
    $courseEnrollments = $enrollments ?? collect();

    $statusLabels = [
    'not_started' => 'Not Started',
    'in_progress' => 'In Progress',
    'completed' => 'Completed',
    ];

    $statusClasses = [
    'not_started' => 'bg-slate-100 text-slate-700 border-slate-200',
    'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200',
    'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    ];

    $averageProgress = $courseEnrollments->count() > 0
    ? round($courseEnrollments->avg('progress_percent'))
    : 0;

    $completedStudentCount = $courseEnrollments
    ->where('status', 'completed')
    ->count();
    @endphp

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

                        <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                            {{ $course->name }}
                        </h1>

                        <p class="mt-3 max-w-3xl text-slate-500 leading-7">
                            {{ $course->description ?: 'No description available for this course.' }}
                        </p>

                        <div class="mt-4 flex flex-wrap gap-2 text-sm">
                            <span class="rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-700">
                                Level: {{ $course->level ?? '-' }}
                            </span>

                            <span class="rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-700">
                                Durasi: {{ $course->duration_weeks ?? '-' }} minggu
                            </span>

                            <span class="rounded-full bg-indigo-50 px-3 py-1 font-semibold text-indigo-700">
                                Avg Progress: {{ $averageProgress }}%
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 min-w-full xl:min-w-[520px]">
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Materials
                            </p>
                            <p class="mt-2 text-2xl font-bold text-slate-900">
                                {{ $materials->count() }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Quizzes
                            </p>
                            <p class="mt-2 text-2xl font-bold text-slate-900">
                                {{ $quizzes->count() }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Students
                            </p>
                            <p class="mt-2 text-2xl font-bold text-slate-900">
                                {{ $courseEnrollments->count() }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Completed
                            </p>
                            <p class="mt-2 text-2xl font-bold text-slate-900">
                                {{ $completedStudentCount }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ session('error') }}
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

            @if (auth()->check() && auth()->user()->role === 'lecturer' && $course->user_id === auth()->id())
            <div class="mb-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5">
                    <h2 class="text-xl font-semibold text-slate-900">
                        Quiz Management
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Lecturer bisa langsung membuat quiz tanpa harus upload material terlebih dahulu.
                        Tandai satu quiz sebagai <span class="font-semibold text-violet-700">Final Quiz</span>
                        untuk certificate.
                    </p>
                </div>

                <form method="POST"
                    action="{{ route('lecturer.courses.quizzes.store', $course->id) }}"
                    class="grid gap-4 md:grid-cols-3">
                    @csrf

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Quiz Title
                        </label>

                        <input type="text"
                            name="title"
                            value="{{ old('title') }}"
                            class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-violet-500 focus:outline-none"
                            placeholder="Contoh: Week 1 Quiz"
                            required>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Time Limit (minutes)
                        </label>

                        <input type="number"
                            name="time_limit"
                            value="{{ old('time_limit') }}"
                            min="1"
                            class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-violet-500 focus:outline-none"
                            placeholder="Optional">
                    </div>

                    <div class="md:col-span-3 rounded-2xl border border-violet-200 bg-violet-50 px-4 py-3">
                        <label class="flex items-center gap-3 text-sm font-medium text-slate-700">
                            <input type="checkbox"
                                name="is_final"
                                value="1"
                                {{ old('is_final') ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500">

                            <span>
                                Jadikan quiz ini sebagai
                                <span class="font-semibold text-violet-700">Final Quiz</span>
                                untuk certificate
                            </span>
                        </label>
                    </div>

                    <div class="md:col-span-3 flex justify-end">
                        <button type="submit"
                            class="rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700">
                            + Create Quiz
                        </button>
                    </div>
                </form>
            </div>
            @endif

            <div class="mb-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-slate-900">
                            Student Progress
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Pantau progress mahasiswa berdasarkan material yang dibuka dan quiz yang dikerjakan.
                        </p>
                    </div>

                    <span class="inline-flex w-fit rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                        {{ $courseEnrollments->count() }} Student Terdaftar
                    </span>
                </div>

                @if ($courseEnrollments->isEmpty())
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 text-sm text-slate-500">
                    Belum ada mahasiswa yang mengambil course ini.
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-slate-200 text-sm">
                        <thead>
                            <tr class="bg-slate-100 text-left text-slate-700">
                                <th class="border border-slate-200 p-3">Student</th>
                                <th class="border border-slate-200 p-3">Status</th>
                                <th class="border border-slate-200 p-3">Progress</th>
                                <th class="border border-slate-200 p-3">Material</th>
                                <th class="border border-slate-200 p-3">Quiz</th>
                                <th class="border border-slate-200 p-3">Started</th>
                                <th class="border border-slate-200 p-3">Last Activity</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($courseEnrollments as $enrollment)
                            @php
                            $progress = $enrollment->progress_percent ?? 0;
                            $status = $enrollment->status ?? 'not_started';
                            @endphp

                            <tr class="bg-white">
                                <td class="border border-slate-200 p-3 align-top">
                                    <div class="font-semibold text-slate-900">
                                        {{ $enrollment->user->name ?? 'Unknown User' }}
                                    </div>

                                    <div class="text-xs text-slate-500">
                                        {{ $enrollment->user->email ?? '-' }}
                                    </div>
                                </td>

                                <td class="border border-slate-200 p-3 align-top">
                                    <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClasses[$status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                        {{ $statusLabels[$status] ?? $status }}
                                    </span>
                                </td>

                                <td class="border border-slate-200 p-3 align-top min-w-[190px]">
                                    <div class="mb-2 flex justify-between text-xs text-slate-500">
                                        <span>Progress</span>
                                        <span class="font-semibold text-slate-700">{{ $progress }}%</span>
                                    </div>

                                    <div class="h-3 w-full overflow-hidden rounded-full bg-slate-200">
                                        <div class="h-3 rounded-full {{ $progress >= 100 ? 'bg-emerald-600' : 'bg-indigo-600' }}"
                                            style="width: {{ $progress }}%">
                                        </div>
                                    </div>
                                </td>

                                <td class="border border-slate-200 p-3 align-top">
                                    {{ $enrollment->completed_material_count ?? 0 }}
                                    /
                                    {{ $enrollment->total_material_count ?? 0 }}
                                </td>

                                <td class="border border-slate-200 p-3 align-top">
                                    {{ $enrollment->completed_quiz_count ?? 0 }}
                                    /
                                    {{ $enrollment->total_quiz_count ?? 0 }}
                                </td>

                                <td class="border border-slate-200 p-3 align-top text-slate-600">
                                    {{ optional($enrollment->started_at)->format('d M Y H:i') ?? '-' }}
                                </td>

                                <td class="border border-slate-200 p-3 align-top text-slate-600">
                                    {{ optional($enrollment->last_activity_at)->format('d M Y H:i') ?? '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2 space-y-6">

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-6">
                            <h2 class="text-2xl font-semibold text-slate-900">
                                Course Quizzes
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Semua quiz yang tersedia untuk course ini.
                            </p>
                        </div>

                        <div class="space-y-4">
                            @forelse ($quizzes as $quiz)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="text-lg font-semibold text-slate-900">
                                                {{ $quiz->title }}
                                            </h3>

                                            @if ($quiz->is_final)
                                            <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                Final Quiz
                                            </span>
                                            @endif
                                        </div>

                                        <p class="mt-2 text-sm text-slate-500">
                                            Time Limit:
                                            <span class="text-slate-700">
                                                {{ $quiz->time_limit ? $quiz->time_limit . ' minutes' : 'No limit' }}
                                            </span>
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500">
                                            Total Questions:
                                            <span class="text-slate-700">
                                                {{ $quiz->questions->count() }}
                                            </span>
                                        </p>

                                        @if ($quiz->is_final)
                                        <p class="mt-2 text-xs font-medium text-emerald-700">
                                            Quiz ini dipakai untuk penentuan certificate student.
                                        </p>
                                        @endif
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('lecturer.courses.quizzes.results.index', [$course->id, $quiz->id]) }}"
                                            class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
                                            Lihat Hasil
                                        </a>

                                        @if ($quiz->questions->where('question_type', 'essay')->count() > 0)
                                        <a href="{{ route('lecturer.courses.quizzes.answers.index', [$course->id, $quiz->id]) }}"
                                            class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-amber-600">
                                            Nilai Essay
                                        </a>
                                        @endif

                                        @if (! $quiz->is_final)
                                        <form method="POST"
                                            action="{{ route('lecturer.courses.quizzes.make-final', [$course->id, $quiz->id]) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
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

                                            <button type="submit"
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
                        <div class="mb-6 flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-2xl font-semibold text-slate-900">
                                    Learning Materials
                                </h2>

                                <p class="mt-1 text-sm text-slate-500">
                                    Materi pembelajaran yang tersedia di course ini.
                                </p>
                            </div>

                            <a href="{{ route('lecturer.materials.create', $course->id) }}"
                                class="inline-flex items-center rounded-xl bg-violet-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-violet-700 whitespace-nowrap">
                                + Add Material
                            </a>
                        </div>

                        @if ($materials->count())
                        <div class="grid gap-4 md:grid-cols-2">
                            @foreach ($materials as $material)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <h3 class="font-semibold text-slate-900">
                                    {{ $material->title }}
                                </h3>

                                <p class="mt-2 text-sm text-slate-500">
                                    Uploaded {{ optional($material->created_at)->format('d M Y') ?: '-' }}
                                </p>

                                <div class="mt-4 flex flex-wrap gap-2">
                                    <a href="{{ route('lecturer.materials.show', $material->id) }}"
                                        class="inline-flex rounded-xl bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700">
                                        View
                                    </a>

                                    <a href="{{ route('lecturer.materials.edit', $material->id) }}"
                                        class="inline-flex rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                                        Edit
                                    </a>

                                    <form action="{{ route('lecturer.materials.destroy', $material->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin hapus materi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-8 text-center text-slate-500">
                            Belum ada materi untuk course ini. Klik "+ Add Material" untuk mulai upload.
                        </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-slate-900 mb-5">
                            Students
                        </h2>

                        @if ($courseEnrollments->count())
                        <div class="space-y-3">
                            @foreach ($courseEnrollments as $enrollment)
                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            {{ $enrollment->user->name ?? 'Unknown User' }}
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ $enrollment->user->email ?? 'No email' }}
                                        </p>
                                    </div>

                                    <span class="text-sm font-semibold text-indigo-700">
                                        {{ $enrollment->progress_percent ?? 0 }}%
                                    </span>
                                </div>
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
                        <h2 class="text-xl font-semibold text-slate-900 mb-5">
                            Assignments
                        </h2>

                        @if ($assignments->count())
                        <div class="space-y-3">
                            @foreach ($assignments as $assignment)
                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="font-semibold text-slate-900">
                                    {{ $assignment->title }}
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $assignment->description ?? 'No description.' }}
                                </p>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-500">
                            Belum ada assignment untuk course ini.
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>