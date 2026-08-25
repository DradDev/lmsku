<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.material-form-wrap {
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
    $isMasterCourse = ($targetScope === 'all') || ($course instanceof \App\Models\MasterCourse) || empty($course->section_name);
    $courseName = $course->name ?? ($course->masterCourse->name ?? 'Mata Kuliah');
    $sectionName = $course->section_name ?? null;
    $returnRoute = ($course instanceof \App\Models\CourseOffering)
        ? route('lecturer.courses.show', $course->id)
        : route('lecturer.materials.index');
@endphp

<div class="material-form-wrap max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

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
                Portal Dosen • Unggah Materi Pembelajaran
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                {{ $isMasterCourse ? 'Upload Materi Induk Kurikulum' : 'Upload Materi Khusus ' . ($sectionName ?: 'Kelas') }}
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

        <form action="{{ route('lecturer.materials.store', $course->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-5">
            @csrf

            {{-- SCOPE HIDDEN/EXPLICIT --}}
            <input type="hidden" name="target_scope" value="{{ $isMasterCourse ? 'all' : 'class' }}">

            {{-- JUDUL MATERI --}}
            <div>
                <label class="block mb-1.5 text-xs font-extrabold text-slate-800">Judul Materi Pembelajaran <span class="text-rose-500">*</span></label>
                <input type="text"
                       name="title"
                       value="{{ old('title') }}"
                       placeholder="Contoh: Modul 1 - Pengenalan Arsitektur Cloud"
                       class="w-full rounded-xl border border-slate-300 p-2.5 text-xs font-semibold text-slate-900 placeholder:text-slate-400 focus:border-blue-600 focus:ring-blue-600"
                       required>
            </div>

            {{-- BERKAS FILE --}}
            <div>
                <label class="block mb-1.5 text-xs font-extrabold text-slate-800">Berkas Dokumen / Slide (PDF, PPT, DOC, ZIP) <span class="text-rose-500">*</span></label>
                <input type="file"
                       name="file"
                       class="w-full rounded-xl border border-slate-300 p-2 text-xs bg-slate-50 text-slate-900 focus:border-blue-600 focus:ring-blue-600"
                       accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar"
                       required>
                <p class="mt-1.5 text-[11px] text-slate-400">
                    Format didukung: PDF, PPTX, DOCX, ZIP (Maksimal 20 MB).
                </p>
            </div>

            {{-- INFO BANNER SCOPE DISTRIBUSI --}}
            @if($isMasterCourse)
                <div class="p-4 bg-blue-50/70 border border-blue-200 rounded-xl flex items-start gap-3">
                    <div class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        🌐
                    </div>
                    <div class="text-xs">
                        <div class="font-extrabold text-blue-950">Materi Induk Kurikulum (Master)</div>
                        <div class="text-blue-700 text-[11px] mt-0.5">
                            Materi ini otomatis tersedia dan dapat dipelajari oleh seluruh mahasiswa di <strong>semua rombel kelas</strong> mata kuliah ini.
                        </div>
                    </div>
                </div>
            @else
                <div class="p-4 bg-indigo-50/70 border border-indigo-200 rounded-xl flex items-start gap-3">
                    <div class="w-7 h-7 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        🎯
                    </div>
                    <div class="text-xs">
                        <div class="font-extrabold text-indigo-950">Materi Khusus {{ $sectionName ?: 'Rombel Ini' }}</div>
                        <div class="text-indigo-700 text-[11px] mt-0.5">
                            Materi ini bersifat pengayaan khusus dan <strong>hanya dapat diakses oleh mahasiswa yang terdaftar di {{ $sectionName ?: 'kelas ini' }}</strong>.
                        </div>
                    </div>
                </div>
            @endif

            {{-- ACTION BUTTONS --}}
            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                <a href="{{ $returnRoute }}"
                   class="px-4 py-2 bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
                    Batal
                </a>

                <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                    Upload Materi
                </button>
            </div>
        </form>
    </div>

</div>
</x-app-layout>