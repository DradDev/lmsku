<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                🎓 {{ $course->name }}
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('vendor.courses.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold text-xs rounded-xl">
                    ← Kembali
                </a>
                <a href="{{ route('vendor.courses.edit', $course) }}" class="px-4 py-2 bg-indigo-600 text-white font-bold text-xs rounded-xl">
                    ✏️ Edit Course
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
            <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-xl shadow-sm font-semibold text-xs">
                {{ session('success') }}
            </div>
            @endif

            <!-- Hero Detail Card -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 pb-3">
                    <span class="px-2.5 py-0.5 bg-purple-100 text-purple-800 font-extrabold text-xs rounded-md">
                        🏢 Vendor Certified: {{ Auth::user()->name }}
                    </span>
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 font-bold text-xs rounded-full">
                        Passing Grade Kuis: {{ $course->certificate_threshold }}
                    </span>
                </div>

                <p class="text-xs text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ $course->description }}
                </p>
            </div>

            <!-- Management 2 Columns: Materials & Enrolled Students -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Materials & Quizzes Section -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <h3 class="font-extrabold text-base text-gray-900">📚 Materials & Quizzes</h3>
                    </div>

                    <div class="space-y-3">
                        <h4 class="font-bold text-xs text-gray-700 uppercase">Materi Pembelajaran:</h4>
                        @forelse($course->materials as $mat)
                            <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 flex items-center justify-between">
                                <span>📄 {{ $mat->title }}</span>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 italic">Belum ada materi diunggah.</p>
                        @endforelse

                        <h4 class="font-bold text-xs text-gray-700 uppercase pt-2">Kuis Kelulusan:</h4>
                        @forelse($course->quizzes as $qz)
                            <div class="p-3 bg-indigo-50 border border-indigo-100 rounded-xl text-xs font-bold text-indigo-900 flex items-center justify-between">
                                <span>📝 {{ $qz->title }}</span>
                                <span class="text-[10px] bg-indigo-200 px-2 py-0.5 rounded">{{ $qz->questions_count }} Soal</span>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 italic">Belum ada kuis kelulusan dibuat.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Enrolled Students Section -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <h3 class="font-extrabold text-base text-gray-900">👨‍🎓 Enrolled Students ({{ $course->students->count() }})</h3>
                    </div>

                    <div class="space-y-2">
                        @forelse($course->students as $std)
                            <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-between text-xs">
                                <span class="font-bold text-gray-900">{{ $std->name }}</span>
                                <span class="text-gray-500">{{ $std->email }}</span>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 italic">Belum ada mahasiswa terdaftar pada course sertifikasi ini.</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
