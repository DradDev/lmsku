<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <span>📝 Kelola Soal Evaluasi Kuis — {{ $quiz->title }}</span>
            </h2>
            <a href="{{ route('vendor.courses.show', $course) }}" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold text-xs rounded-xl hover:bg-gray-200 transition">
                ← Kembali ke Course
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
            <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-xl shadow-sm font-semibold text-xs flex items-center gap-2">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                <!-- Left Column: Add Question Form (5 Cols) -->
                <div class="lg:col-span-5 bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                    <h3 class="font-extrabold text-base text-gray-900 border-b border-gray-100 pb-3">
                        ➕ Tambah Soal Pilihan Ganda Baru
                    </h3>

                    <form action="{{ route('vendor.quizzes.questions.store', $quiz) }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Pertanyaan / Soal</label>
                            <textarea name="question" rows="3" required placeholder="Tuliskan pertanyaan evaluasi..."
                                      class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-3"></textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase">Pilihan Jawaban (Opsi A - D)</label>

                            @foreach(['A' => 'option_a', 'B' => 'option_b', 'C' => 'option_c', 'D' => 'option_d'] as $letter => $field)
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs text-indigo-700 w-5">{{ $letter }}.</span>
                                <input type="text" name="{{ $field }}" required placeholder="Jawaban {{ $letter }}"
                                       class="flex-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-2.5">
                                <label class="flex items-center gap-1 text-[11px] text-gray-600 font-semibold cursor-pointer">
                                    <input type="radio" name="correct_answer" value="{{ $letter }}" {{ $letter === 'A' ? 'checked' : '' }} class="text-indigo-600 border-gray-300">
                                    <span>Kunci</span>
                                </label>
                            </div>
                            @endforeach
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                            💾 Simpan Soal Evaluasi
                        </button>
                    </form>
                </div>

                <!-- Right Column: Question List (7 Cols) -->
                <div class="lg:col-span-7 bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                    <h3 class="font-extrabold text-base text-gray-900 border-b border-gray-100 pb-3 flex items-center justify-between">
                        <span>📋 Daftar Soal Evaluasi ({{ $quiz->questions->count() }} Soal)</span>
                        <span class="text-xs font-semibold text-gray-500">Waktu: {{ $quiz->time_limit }} Menit</span>
                    </h3>

                    @forelse($quiz->questions as $qIndex => $q)
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl space-y-2">
                            <div class="flex items-start justify-between gap-2">
                                <h4 class="font-bold text-xs text-gray-900">
                                    {{ $qIndex + 1 }}. {{ $q->question }}
                                </h4>

                                <form action="{{ route('vendor.questions.destroy', $q) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-bold">🗑️ Hapus</button>
                                </form>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-xs pt-1">
                                @foreach(['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d] as $letter => $val)
                                    <div class="p-2 rounded-lg border {{ strtoupper($q->correct_answer) === $letter ? 'bg-emerald-100 border-emerald-300 text-emerald-900 font-bold' : 'bg-white border-gray-200 text-gray-700' }}">
                                        {{ $letter }}. {{ $val }}
                                        @if(strtoupper($q->correct_answer) === $letter)
                                            <span class="text-[10px] text-emerald-700 font-black ml-1">(Kunci Jawaban)</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center bg-gray-50 rounded-xl text-xs text-gray-500">
                            Belum ada soal evaluasi ditambahkan. Gunakan formulir di sebelah kiri untuk menambah soal.
                        </div>
                    @endforelse
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
