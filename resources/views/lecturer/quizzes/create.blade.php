<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.quiz-form-wrap {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #0f172a;
    padding-bottom: 3.5rem;
}

.compro-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}
</style>

@php
    $targetScope = request()->query('scope', 'all');
    $isMasterCourse = ($targetScope === 'all') || ($courseObj instanceof \App\Models\MasterCourse) || empty($courseObj->section_name);
    $courseName = $courseObj->name ?? ($courseObj->masterCourse->name ?? 'Mata Kuliah');
    $sectionName = $courseObj->section_name ?? null;
    $returnRoute = ($courseObj instanceof \App\Models\CourseOffering)
        ? route('lecturer.courses.show', $courseObj->id)
        : route('lecturer.courses.index');
@endphp

<div class="quiz-form-wrap max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    {{-- TOP NAVIGATION --}}
    <div class="flex items-center justify-between">
        <a href="{{ $returnRoute }}"
           class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    {{-- FORM CARD --}}
    <div class="compro-card p-6 sm:p-8 space-y-6">
        <div>
            <div class="text-[11px] font-extrabold uppercase tracking-wider text-blue-600 mb-1">
                Portal Dosen • Buat Kuis Baru
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                {{ $isMasterCourse ? 'Buat Kuis Induk Kurikulum' : 'Buat Kuis Khusus ' . ($sectionName ?: 'Kelas') }}
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Mata Kuliah: <strong class="text-slate-800">{{ $courseName }}</strong>
                @if($isMasterCourse)
                    <span class="ml-2 px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 text-[10px] font-extrabold">🌐 Berlaku untuk SEMUA Rombel</span>
                @else
                    <span class="ml-2 px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-[10px] font-extrabold">🎯 Khusus {{ $sectionName ?: 'Rombel Ini' }}</span>
                @endif
            </p>
        </div>

        @if (isset($errors) && $errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs space-y-1">
                <div class="font-bold">Mohon periksa kembali:</div>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('lecturer.courses.quizzes.store', $courseObj->id) }}"
              method="POST"
              class="space-y-5">
            @csrf

            {{-- SCOPE HIDDEN/EXPLICIT --}}
            <input type="hidden" name="target_scope" value="{{ $isMasterCourse ? 'all' : 'class' }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- JUDUL KUIS --}}
                <div class="md:col-span-2">
                    <label class="block mb-1.5 text-xs font-extrabold text-slate-800">Judul Kuis <span class="text-rose-500">*</span></label>
                    <input type="text"
                           name="title"
                           value="{{ old('title') }}"
                           placeholder="Contoh: Kuis Akhir - Final Exam"
                           class="w-full rounded-xl border border-slate-300 p-2.5 text-xs font-semibold text-slate-900 placeholder:text-slate-400 focus:border-blue-600 focus:ring-blue-600"
                           required>
                </div>

                {{-- TIPE KUIS --}}
                <div>
                    <label class="block mb-1.5 text-xs font-extrabold text-slate-800">Tipe Kuis <span class="text-rose-500">*</span></label>
                    <select name="quiz_type" class="w-full rounded-xl border-slate-300 p-2.5 text-xs font-bold text-blue-900 bg-white focus:border-blue-600 focus:ring-blue-600" required>
                        <option value="daily" {{ old('quiz_type') === 'daily' ? 'selected' : '' }}>Kuis Harian / Section Quiz</option>
                        <option value="weekly" {{ old('quiz_type') === 'weekly' ? 'selected' : '' }}>Kuis Mingguan / Evaluasi Bab</option>
                        <option value="final" {{ old('quiz_type') === 'final' ? 'selected' : '' }}>Kuis Akhir (Final Quiz / Kelulusan)</option>
                    </select>
                </div>

                {{-- DURASI PENGERJAAN --}}
                <div>
                    <label class="block mb-1.5 text-xs font-extrabold text-slate-800">Durasi Pengerjaan (Menit)</label>
                    <input type="number" 
                           name="time_limit" 
                           value="{{ old('time_limit') }}"
                           min="1" 
                           placeholder="Contoh: 60 (kosongkan jika tanpa batas)" 
                           class="w-full rounded-xl border border-slate-300 p-2.5 text-xs font-semibold text-slate-900 placeholder:text-slate-400 focus:border-blue-600 focus:ring-blue-600">
                </div>

                {{-- MAKSIMAL PERCOBAAN --}}
                <div class="md:col-span-2">
                    <label class="block mb-1.5 text-xs font-extrabold text-slate-800">Maksimal Percobaan</label>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                        <input type="number" 
                               id="max_attempts_input" 
                               name="max_attempts" 
                               value="{{ old('max_attempts', 1) }}" 
                               min="0" max="100" 
                               class="w-full sm:w-1/2 rounded-xl border-slate-300 p-2.5 text-xs font-semibold focus:border-blue-600 focus:ring-blue-600">
                        <label class="inline-flex items-center gap-2 px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-100 transition">
                            <input type="checkbox" 
                                   name="is_unlimited" 
                                   value="1" 
                                   {{ old('is_unlimited') ? 'checked' : '' }}
                                   onchange="document.getElementById('max_attempts_input').disabled = this.checked; if(this.checked){ document.getElementById('max_attempts_input').value = 0; }" 
                                   class="rounded text-blue-600 focus:ring-blue-500">
                            <span class="text-xs font-bold text-slate-700">Tidak ada batas (Unlimited)</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="pt-4 mt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ $returnRoute }}"
                   class="px-5 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold rounded-xl text-xs transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-xs shadow-blue-200 transition">
                    Simpan Kuis
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const unlimitedCheckbox = document.querySelector('input[name="is_unlimited"]');
        const maxAttemptsInput = document.getElementById('max_attempts_input');
        if (unlimitedCheckbox && unlimitedCheckbox.checked) {
            maxAttemptsInput.disabled = true;
            maxAttemptsInput.value = 0;
        }
    });
</script>
</x-app-layout>