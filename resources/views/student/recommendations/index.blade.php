<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Rekomendasi Untuk Kamu
        </h2>
    </x-slot>

    @php
    $userId = auth()->id();
    @endphp

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
            <div class="p-4 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="p-4 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
            @endif

            <div class="bg-white shadow rounded p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            Rekomendasi AI
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Rekomendasi ini dibuat berdasarkan aktivitas belajar, skill, minat, progress, dan model Machine Learning.
                        </p>
                    </div>

                    <span class="px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                        {{ $recommendations->count() }} Rekomendasi
                    </span>
                </div>
            </div>

            @if ($recommendations->isEmpty())
            <div class="bg-white shadow rounded p-8 text-center">
                <div class="mx-auto w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center mb-4">
                    <span class="text-2xl">🤖</span>
                </div>

                <h3 class="text-lg font-semibold text-gray-900">
                    Belum ada rekomendasi untuk kamu saat ini.
                </h3>

                <p class="text-sm text-gray-500 mt-2">
                    Rekomendasi akan muncul setelah sistem AI menjalankan batch prediksi.
                </p>

                <div class="mt-5 flex justify-center gap-2">
                    <a href="{{ route('student.courses.index') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded text-sm">
                        Lihat Course
                    </a>

                    <a href="{{ route('student.projects.index') }}"
                        class="px-4 py-2 bg-gray-700 text-white rounded text-sm">
                        Lihat Project
                    </a>
                </div>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($recommendations as $recommendation)
                @php
                $result = $recommendation['result'];
                $item = $recommendation['item'];

                if (! $item) {
                continue;
                }

                $isCourse = $result->item_type === 'course';
                $isProject = $result->item_type === 'project';

                $scorePercent = round(($result->prediction_score ?? 0) * 100, 2);

                $title = $isCourse
                ? ($item->name ?? 'Course tanpa nama')
                : ($item->title ?? 'Project tanpa judul');

                $description = $item->description ?? '-';

                $alreadyEnrolled = false;
                $alreadyJoined = false;

                $joinedCount = 0;
                $maxStudents = 1;
                $isFull = false;

                if ($isCourse) {
                $alreadyEnrolled = \Illuminate\Support\Facades\DB::table('enrollments')
                ->where('user_id', $userId)
                ->where('course_id', $item->id)
                ->exists();
                }

                if ($isProject) {
                $alreadyJoined = \Illuminate\Support\Facades\DB::table('project_participations')
                ->where('user_id', $userId)
                ->where('project_id', $item->id)
                ->exists();

                $joinedCount = \Illuminate\Support\Facades\DB::table('project_participations')
                ->where('project_id', $item->id)
                ->count();

                $maxStudents = $item->max_students ?? 1;
                $isFull = $joinedCount >= $maxStudents;
                }
                @endphp

                <div class="bg-white shadow rounded p-5 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start gap-3 mb-3">
                            <div>
                                <h3 class="font-bold text-lg text-gray-900">
                                    {{ $title }}
                                </h3>

                                <div class="flex flex-wrap gap-2 mt-2">
                                    <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700">
                                        Rank #{{ $result->rank }}
                                    </span>

                                    <span class="px-2 py-1 text-xs rounded {{ $isCourse ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                        {{ ucfirst($result->item_type) }}
                                    </span>

                                    @if ($isCourse && $alreadyEnrolled)
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">
                                        Sudah Diambil
                                    </span>
                                    @endif

                                    @if ($isProject)
                                    @if ($alreadyJoined)
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">
                                        Sudah Diambil
                                    </span>
                                    @elseif ($isFull)
                                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">
                                        Penuh
                                    </span>
                                    @else
                                    <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-700">
                                        Tersedia
                                    </span>
                                    @endif
                                    @endif
                                </div>
                            </div>

                            <div class="text-right shrink-0">
                                <p class="text-xs text-gray-500">
                                    Match
                                </p>

                                <p class="text-lg font-bold text-green-600">
                                    {{ $scorePercent }}%
                                </p>
                            </div>
                        </div>

                        <p class="text-sm text-gray-600 mb-4">
                            {{ \Illuminate\Support\Str::limit($description, 120) }}
                        </p>

                        <div class="mb-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>AI Score</span>
                                <span>{{ $scorePercent }}%</span>
                            </div>

                            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div class="h-3 rounded-full {{ $scorePercent >= 75 ? 'bg-green-600' : 'bg-blue-600' }}"
                                    style="width: {{ min(100, $scorePercent) }}%">
                                </div>
                            </div>
                        </div>

                        <div class="text-sm text-gray-700 space-y-1 mb-4">
                            @if ($isCourse)
                            <p>
                                <strong>Level:</strong>
                                {{ $item->level ?? '-' }}
                            </p>

                            <p>
                                <strong>Durasi:</strong>
                                {{ $item->duration_weeks ?? '-' }} minggu
                            </p>
                            @else
                            <p>
                                <strong>Level:</strong>
                                {{ $item->difficulty_level ?? '-' }}
                            </p>

                            <p>
                                <strong>Durasi:</strong>
                                {{ $item->duration_days ?? '-' }} hari
                            </p>

                            <p class="{{ $isFull ? 'text-red-600' : 'text-green-600' }}">
                                <strong>Kuota:</strong>
                                {{ $joinedCount }}/{{ $maxStudents }} student
                            </p>
                            @endif

                            <p>
                                <strong>Model:</strong>
                                {{ $result->model_name }} {{ $result->model_version }}
                            </p>
                        </div>
                    </div>

                    <div class="pt-4 border-t flex flex-wrap gap-2">
                        @if ($isCourse)
                        @if ($alreadyEnrolled)
                        <a href="{{ route('student.courses.show', $item) }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded text-sm">
                            Lanjut Belajar
                        </a>
                        @else
                        <form action="{{ route('student.courses.enroll', $item) }}" method="POST">
                            @csrf

                            <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded text-sm">
                                Ambil Course
                            </button>
                        </form>
                        @endif
                        @else
                        <a href="{{ route('student.projects.show', $item) }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded text-sm">
                            Detail Project
                        </a>

                        @if ($alreadyJoined)
                        <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded text-sm">
                            Sudah Diambil
                        </span>
                        @elseif ($isFull)
                        <button type="button"
                            class="px-4 py-2 bg-gray-400 text-white rounded text-sm cursor-not-allowed"
                            disabled>
                            Kuota Penuh
                        </button>
                        @else
                        <form action="{{ route('student.projects.join', $item) }}" method="POST">
                            @csrf

                            <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded text-sm">
                                Ambil Project
                            </button>
                        </form>
                        @endif
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</x-app-layout>