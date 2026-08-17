<x-app-layout>
    @php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag();
    $progress = $enrollment?->progress_percent ?? 0;

    $completedMaterialCount = $enrollment?->completed_material_count ?? 0;
    $completedQuizCount = $enrollment?->completed_quiz_count ?? 0;

    $totalMaterialCount = $enrollment?->total_material_count ?? $course->materials->count();
    $totalQuizCount = $enrollment?->total_quiz_count ?? $course->quizzes->count();

    $status = $enrollment?->status ?? 'not_started';

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
    @endphp

    <div class="min-h-screen bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 py-8">
            
            @if(isset($isReadOnly) && $isReadOnly)
            <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-800 shadow-sm flex items-center gap-2">
                <span>ℹ️</span>
                <span>Course Ini Sudah Berakhir (Expired) — Anda mengakses dalam mode Read-Only untuk riwayat belajar & data AI.</span>
            </div>
            @endif

            <div class="mb-6">
                <a href="{{ route('student.courses.index') }}"
                    class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 mb-4">
                    ← Back to Courses
                </a>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-3">
                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClasses[$status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                    {{ $statusLabels[$status] ?? $status }}
                                </span>

                                @if($course instanceof \App\Models\CourseOffering)
                                    <span class="inline-flex rounded-full bg-emerald-100 border border-emerald-300 px-3.5 py-1 text-xs font-bold text-emerald-900 shadow-sm">
                                        📌 {{ $course->section_name }} (Dosen: {{ $course->lecturer->name ?? 'Dosen' }})
                                    </span>
                                @endif

                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                    {{ $course->level ?? 'No Level' }}
                                </span>

                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                    {{ $course->duration_weeks ?? 0 }} minggu
                                </span>
                            </div>

                            <h1 class="text-3xl font-bold text-slate-900">
                                {{ $course->name ?? $course->title }}
                            </h1>

                            <p class="text-slate-500 mt-2">
                                Instructor: {{ $course->user->name ?? 'Unknown Instructor' }}
                            </p>

                            @if (! empty($course->description))
                            <p class="text-slate-600 mt-4 max-w-3xl leading-relaxed">
                                {{ $course->description }}
                            </p>
                            @endif
                        </div>

                        @if(!$enrollment)
                            <div class="w-full lg:w-96 bg-purple-50 rounded-2xl p-5 border border-purple-200 shadow-sm">
                                <span class="inline-block text-[11px] font-bold text-purple-700 bg-purple-100 px-3 py-1 rounded-full uppercase tracking-wider">
                                    👀 Course Preview (Belum Terdaftar)
                                </span>
                                <h3 class="text-lg font-bold text-purple-950 mt-3">
                                    Tertarik Mengikuti Course Ini?
                                </h3>
                                <p class="text-xs text-purple-800 mt-1 leading-relaxed">
                                    Ambil course ini sekarang untuk membuka akses penuh ke seluruh modul materi, kuis evaluasi, dan klaim Sertifikat Digital Blockchain.
                                </p>

                                <form action="{{ route('student.courses.enroll', $course->id) }}" method="POST" class="mt-4">
                                    @csrf
                                    <button type="submit" class="w-full py-3 px-4 bg-purple-700 hover:bg-purple-800 text-white font-extrabold text-sm rounded-xl shadow-md transition flex items-center justify-center gap-2">
                                        <span>🎓 Ambil / Enroll Course Ini</span>
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="w-full lg:w-96 bg-slate-50 rounded-2xl p-5 border border-slate-200">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-sm text-slate-500">
                                        Course Progress
                                    </p>

                                    <span class="text-sm font-semibold text-indigo-700">
                                        {{ $progress }}%
                                    </span>
                                </div>

                                <h2 class="text-3xl font-bold text-slate-900 mb-4">
                                    {{ $progress }}%
                                </h2>

                                <div class="w-full bg-slate-200 rounded-full h-3 mb-5 overflow-hidden">
                                    <div class="h-3 rounded-full {{ $progress >= 100 ? 'bg-emerald-600' : 'bg-indigo-600' }}"
                                        style="width: {{ $progress }}%">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div class="rounded-xl bg-white border border-slate-200 p-3">
                                        <span class="block text-lg font-bold text-slate-900">
                                            {{ $completedMaterialCount }}/{{ $totalMaterialCount }}
                                        </span>
                                        <span class="text-slate-500">
                                            Materials
                                        </span>
                                    </div>

                                    <div class="rounded-xl bg-white border border-slate-200 p-3">
                                        <span class="block text-lg font-bold text-slate-900">
                                            {{ $completedQuizCount }}/{{ $totalQuizCount }}
                                        </span>
                                        <span class="text-slate-500">
                                            Quizzes
                                        </span>
                                    </div>
                                </div>

                                <p class="text-xs text-slate-500 mt-4 leading-relaxed">
                                    Progress dihitung otomatis dari material yang sudah dibuka dan quiz yang sudah dikerjakan.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if (session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2 space-y-6">

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
                            <div>
                                <h2 class="text-xl font-semibold text-slate-900">
                                    Learning Materials
                                </h2>

                                <p class="text-sm text-slate-500">
                                    Buka material untuk menaikkan progress course.
                                </p>
                            </div>

                            <span class="inline-flex w-fit rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                {{ $completedMaterialCount }}/{{ $totalMaterialCount }} selesai
                            </span>
                        </div>

                        @if ($course->materials->count() > 0)
                        <div class="space-y-4">
                            @foreach ($course->materials as $material)
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 rounded-2xl border border-slate-200 p-4">
                                <div>
                                    <h3 class="font-semibold text-slate-900">
                                        {{ $material->title }}
                                    </h3>

                                    <p class="text-sm text-slate-500 mt-1">
                                        Material for {{ $course->name ?? $course->title }}
                                    </p>
                                </div>

                                <div class="flex gap-3">
                                    @if($enrollment)
                                        <a href="{{ route('student.materials.show', $material) }}"
                                            class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
                                            View Material
                                        </a>
                                    @else
                                        <span class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-3.5 py-2 text-xs font-bold text-slate-500 border border-slate-200">
                                            🔒 Terkunci (Ambil Course)
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5 text-slate-500">
                            Belum ada materi untuk course ini.
                        </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
                            <div>
                                <h2 class="text-xl font-semibold text-slate-900">
                                    Quizzes
                                </h2>

                                <p class="text-sm text-slate-500">
                                    Kerjakan quiz untuk menguji pemahaman dan menaikkan progress.
                                </p>
                            </div>

                            <span class="inline-flex w-fit rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                {{ $completedQuizCount }}/{{ $totalQuizCount }} selesai
                            </span>
                        </div>

                        @if ($course->quizzes->count() > 0)
                        <div class="space-y-4">
                            @foreach ($course->quizzes as $quiz)
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 rounded-2xl border border-slate-200 p-4">
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="font-semibold text-slate-900">
                                            {{ $quiz->title }}
                                        </h3>

                                        @if ($quiz->quiz_type === 'final')
                                        <span class="inline-flex rounded-full bg-emerald-100 px-3 py-0.5 text-xs font-bold text-emerald-700">
                                            Final Quiz
                                        </span>
                                        @elseif ($quiz->quiz_type === 'weekly')
                                        <span class="inline-flex rounded-full bg-blue-100 px-3 py-0.5 text-xs font-bold text-blue-700">
                                            Weekly Quiz
                                        </span>
                                        @endif

                                        @if ($quiz->start_date && now()->lt($quiz->start_date))
                                            <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-800">
                                                Belum Dibuka
                                            </span>
                                        @elseif ($quiz->end_date && now()->gt($quiz->end_date))
                                            <span class="inline-flex rounded-full bg-slate-200 px-2.5 py-0.5 text-xs font-bold text-slate-700">
                                                Waktu Berakhir
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700 border border-emerald-200">
                                                Terbuka
                                            </span>
                                        @endif
                                    </div>

                                    <div class="text-xs text-slate-500 mt-1.5 space-y-0.5">
                                        <p>
                                            {{ $quiz->questions->count() ?? 0 }} Soal &bull;
                                            Durasi: {{ $quiz->time_limit ? $quiz->time_limit . ' Menit' : 'Tanpa Batas' }} &bull;
                                            Sisa Kesempatan: {{ $quiz->max_attempts === 0 ? 'Unlimited' : $quiz->remainingAttempts(Auth::id()) . 'x' }}
                                        </p>
                                        @if($quiz->start_date)
                                            <p>Jadwal Mulai: <span class="font-semibold text-slate-700">{{ $quiz->start_date->format('d M Y, H:i') }}</span></p>
                                        @endif
                                        @if($quiz->end_date)
                                            <p>Batas Deadline: <span class="font-semibold {{ now()->gt($quiz->end_date) ? 'text-rose-600 font-bold' : 'text-slate-700' }}">{{ $quiz->end_date->format('d M Y, H:i') }}</span></p>
                                        @endif
                                    </div>

                                    @if ($quiz->quiz_type === 'final')
                                    <p class="text-xs text-emerald-700 font-semibold mt-2">
                                        Kuis ini digunakan sebagai penentu penerbitan sertifikat kelulusan.
                                    </p>
                                    @endif
                                </div>

                                <div class="flex items-center gap-3">
                                    @if(!$enrollment)
                                        <span class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-3.5 py-2 text-xs font-bold text-slate-500 border border-slate-200">
                                            Terkunci (Ambil Course)
                                        </span>
                                    @elseif ($quiz->start_date && now()->lt($quiz->start_date))
                                        <button type="button" disabled class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-3.5 py-2 text-xs font-bold text-slate-400 border border-slate-200 cursor-not-allowed">
                                            Belum Dibuka ({{ $quiz->start_date->format('d M H:i') }})
                                        </button>
                                    @elseif ($quiz->end_date && now()->gt($quiz->end_date))
                                        <button type="button" disabled class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-3.5 py-2 text-xs font-bold text-slate-400 border border-slate-200 cursor-not-allowed">
                                            Waktu Berakhir
                                        </button>
                                    @elseif ($quiz->quiz_type === 'final' && $verifiedFinalAttempt && $verifiedFinalAttempt->score < 70 && !$quiz->canAttempt(Auth::id()))
                                        @if ($retakeRequest && $retakeRequest->status === 'pending')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold rounded-xl">
                                                Request Retake Pending
                                            </span>
                                        @else
                                            <form method="POST" action="{{ route('student.quiz.request-retake', $quiz) }}">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Kirim permintaan retake Final Quiz ke Author?')" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                                    Request Retake Final Quiz
                                                </button>
                                            </form>
                                        @endif
                                    @elseif (!$quiz->canAttempt(Auth::id()))
                                        <button type="button" disabled class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-3.5 py-2 text-xs font-bold text-slate-400 border border-slate-200 cursor-not-allowed">
                                            Kesempatan Habis
                                        </button>
                                    @else
                                        <a href="{{ route('student.quiz.show', $quiz) }}"
                                            class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700 shadow-sm transition">
                                            Mulai Kuis
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5 text-slate-500">
                            Belum ada quiz untuk course ini.
                        </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-900 mb-4">
                            Course Summary
                        </h2>

                        <div class="space-y-4">
                            <div class="rounded-xl bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">
                                    Instructor
                                </p>

                                <p class="font-semibold text-slate-900 mt-1">
                                    {{ $course->user->name ?? 'Unknown Instructor' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">
                                    Level
                                </p>

                                <p class="font-semibold text-slate-900 mt-1">
                                    {{ $course->level ?? '-' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">
                                    Duration
                                </p>

                                <p class="font-semibold text-slate-900 mt-1">
                                    {{ $course->duration_weeks ?? '-' }} minggu
                                </p>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">
                                    Total Materials
                                </p>

                                <p class="font-semibold text-slate-900 mt-1">
                                    {{ $totalMaterialCount }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">
                                    Total Quizzes
                                </p>

                                <p class="font-semibold text-slate-900 mt-1">
                                    {{ $totalQuizCount }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">
                                    Final Quiz
                                </p>

                                <p class="font-semibold text-slate-900 mt-1">
                                    {{ $finalQuiz?->title ?? 'Belum ditentukan' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-900 mb-4">
                            Certificate Status
                        </h2>

                        @if ($canDownloadCertificate)
                        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-700">
                            {{ $certificateStatusText }}
                        </div>

                        @if ($verifiedFinalAttempt)
                        <div class="mt-4 rounded-xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-sm text-slate-500">
                                Verified Final Score
                            </p>

                            <p class="mt-1 text-2xl font-bold text-slate-900">
                                {{ $verifiedFinalAttempt->score }}
                            </p>
                        </div>
                        @endif
                        @else
                        <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-sm text-amber-700">
                            {{ $certificateStatusText }}
                            @if ($verifiedFinalAttempt && $verifiedFinalAttempt->score < 70 && $finalQuiz && !$finalQuiz->canAttempt(Auth::id()))
                                <div class="mt-3 pt-3 border-t border-amber-200">
                                    <p class="text-xs font-bold text-amber-900 mb-2">Nilai Terakhir Anda: {{ $verifiedFinalAttempt->score }} (Dibawah passing threshold 70)</p>
                                    @if ($retakeRequest && $retakeRequest->status === 'pending')
                                        <p class="text-xs font-semibold text-blue-700">⏳ Permintaan retake Anda sudah terkirim & menunggu persetujuan Author.</p>
                                    @else
                                        <form method="POST" action="{{ route('student.quiz.request-retake', $finalQuiz) }}">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Kirim permintaan retake Final Quiz ke Author?')" class="w-full text-center px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                                                📩 Request Retake Final Quiz
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-900 mb-4">
                            Quick Actions
                        </h2>

                        <div class="space-y-3">
                            <a href="{{ route('student.courses.index') }}"
                                class="block w-full text-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-slate-800">
                                Browse More Courses
                            </a>

                            <a href="{{ route('student.results.index') }}"
                                class="block w-full text-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">
                                View My Results
                            </a>

                            @if ($canDownloadCertificate)
                            <a href="{{ route('student.certificate.show', $course->id) }}"
                                class="block w-full text-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700">
                                View Certificate
                            </a>

                            <a href="{{ route('student.certificate.download', $course->id) }}"
                                class="block w-full text-center rounded-xl bg-violet-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-violet-700">
                                Download Certificate
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>