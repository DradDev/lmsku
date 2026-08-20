<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-6xl mx-auto px-6">

            @php
                $backOffering = $material->courseOffering ?? ($material->masterCourse?->offerings?->first());
                $backOfferingId = $material->course_offering_id ?? ($backOffering?->id ?? 1);
            @endphp
            <div class="mb-8">
                <a href="{{ route('lecturer.courses.show', $backOfferingId) }}"
                   class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 mb-4">
                    ← Back to Course
                </a>

                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                    Lecturer Portal
                </p>
                <h1 class="text-3xl font-bold text-slate-900">Edit Learning Material</h1>
                <p class="text-slate-500 mt-2">
                    Course: <span class="font-semibold text-slate-700">{{ $material->masterCourse->name ?? ($material->courseOffering->full_name ?? ($material->course->name ?? '-')) }}</span>
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                    <p class="font-semibold">Please fix the following errors:</p>
                    <ul class="mt-2 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <form action="{{ route('lecturer.materials.update', $material->id) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="title" class="block text-sm font-semibold text-slate-700 mb-2">
                            Material Title
                        </label>
                        <input type="text"
                               id="title"
                               name="title"
                               value="{{ old('title', $material->title) }}"
                               class="w-full rounded-2xl border border-slate-300 bg-white p-3 text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="e.g. Pertemuan 1 - Pengenalan Laravel"
                               required>
                    </div>

                    @if(!empty($material->master_course_id))
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                        <label class="block text-sm font-bold text-slate-800 mb-1">Cakupan Distribusi Materi</label>
                        <p class="text-xs text-slate-500 mb-3">Tentukan apakah materi ini berlaku untuk seluruh kelas mata kuliah ini atau hanya kelas spesifik.</p>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2.5 text-xs text-slate-700 cursor-pointer">
                                <input type="radio" name="target_scope" value="all" {{ empty($material->course_offering_id) ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                <span><strong class="text-slate-900">Pustaka Induk (Semua Kelas)</strong> — Tersedia otomatis untuk seluruh kelas mata kuliah ini</span>
                            </label>
                            @if($material->courseOffering)
                            <label class="flex items-center gap-2.5 text-xs text-slate-700 cursor-pointer">
                                <input type="radio" name="target_scope" value="class" {{ !empty($material->course_offering_id) ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                <span><strong class="text-slate-900">Khusus {{ $material->courseOffering->section_name ?: 'Kelas Ini' }}</strong> — Hanya dapat diakses oleh mahasiswa di kelas ini</span>
                            </label>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>