<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.material-edit-wrap {
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
    $isMaster = ($material->materialable_type === \App\Models\MasterCourse::class);
    $parent = $material->materialable;
    $courseName = $isMaster ? ($parent->name ?? 'Mata Kuliah') : ($parent->masterCourse->name ?? ($parent->name ?? 'Mata Kuliah'));
    $offeringId = !$isMaster ? $material->materialable_id : null;
    $returnUrl = $offeringId ? route('lecturer.courses.show', $offeringId) : route('lecturer.materials.index');
@endphp

<div class="material-edit-wrap max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    {{-- TOP NAVIGATION --}}
    <div class="flex items-center justify-between">
        <a href="{{ $returnUrl }}"
           class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Detail Mata Kuliah</span>
        </a>
    </div>

    {{-- FORM CARD --}}
    <div class="compro-card p-6 sm:p-8 space-y-6">
        <div>
            <div class="text-[11px] font-extrabold uppercase tracking-wider text-blue-600 mb-1">
                Portal Dosen • Edit Materi Pembelajaran
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                Perbarui Modul Materi
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Mata Kuliah: <strong class="text-slate-800">{{ $courseName }}</strong>
                @if($isMaster)
                    <span class="ml-2 px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 text-[10px] font-extrabold">🌐 Materi Induk (Semua Rombel)</span>
                @else
                    <span class="ml-2 px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-[10px] font-extrabold">🎯 Materi Khusus Rombel</span>
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

        <form action="{{ route('lecturer.materials.update', $material->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-5">
            @csrf
            @method('PUT')

            {{-- JUDUL MATERI --}}
            <div>
                <label class="block mb-1.5 text-xs font-extrabold text-slate-800">Judul Materi Pembelajaran <span class="text-rose-500">*</span></label>
                <input type="text"
                       name="title"
                       value="{{ old('title', $material->title) }}"
                       placeholder="Contoh: Modul 1 - Pengenalan Arsitektur Cloud"
                       class="w-full rounded-xl border border-slate-300 p-2.5 text-xs font-semibold text-slate-900 placeholder:text-slate-400 focus:border-blue-600 focus:ring-blue-600"
                       required>
            </div>

            {{-- GANTI BERKAS FILE --}}
            <div>
                <label class="block mb-1.5 text-xs font-extrabold text-slate-800">Ganti Berkas Dokumen (Opsional)</label>
                @if($material->file_path)
                    <div class="mb-2 p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2 text-slate-700 font-semibold truncate">
                            <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="truncate">{{ basename($material->file_path) }}</span>
                        </div>
                        <a href="{{ Storage::url($material->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-bold shrink-0">
                            Unduh File Saat Ini →
                        </a>
                    </div>
                @endif

                <input type="file"
                       name="file"
                       class="w-full rounded-xl border border-slate-300 p-2 text-xs bg-slate-50 text-slate-900 focus:border-blue-600 focus:ring-blue-600"
                       accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar">
                <p class="mt-1.5 text-[11px] text-slate-400">
                    Kosongkan jika tidak ingin mengubah file dokumen saat ini. Format didukung: PDF, PPTX, DOCX, ZIP (Maks 20MB).
                </p>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                <a href="{{ $returnUrl }}"
                   class="px-4 py-2 bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
                    Batal
                </a>

                <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
</x-app-layout>