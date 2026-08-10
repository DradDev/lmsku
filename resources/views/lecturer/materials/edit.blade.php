<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-6xl mx-auto px-6">

            <div class="mb-8">
                <a href="{{ route('lecturer.courses.show', $material->course_offering_id ?? $material->course_id) }}"
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
                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-700">
                    <ul class="list-disc pl-5 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <form action="{{ route('lecturer.materials.update', $material->id) }}"
                          method="POST"
                          enctype="multipart/form-data"
                          class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block mb-2 text-sm font-medium text-slate-700">Judul Materi</label>
                            <input type="text"
                                   name="title"
                                   value="{{ old('title', $material->title) }}"
                                   placeholder="Contoh: Modul HTML Dasar"
                                   class="w-full rounded-2xl border border-slate-300 bg-slate-50 p-3 text-slate-900 placeholder:text-slate-400 focus:border-violet-500 focus:outline-none"
                                   required>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-sm font-medium text-slate-700">File Saat Ini</p>
                            <p class="mt-2 text-sm text-slate-500 break-all">
                                {{ $material->file_path ? basename($material->file_path) : 'Belum ada file' }}
                            </p>

                            @if(!empty($material->file_path))
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <a href="{{ asset('storage/' . $material->file_path) }}"
                                       target="_blank"
                                       class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                                        Open File
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-slate-700">Ganti File Materi</label>
                            <input type="file"
                                   name="file"
                                   class="w-full rounded-2xl border border-slate-300 bg-white p-3 text-slate-900 file:mr-4 file:rounded-xl file:border-0 file:bg-violet-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-violet-700"
                                   accept=".pdf,.doc,.docx,.ppt,.pptx">

                            <p class="mt-2 text-xs text-slate-500">
                                Kosongkan jika tidak ingin mengganti file. Format: PDF, DOC, DOCX, PPT, PPTX. Maksimal 20MB.
                            </p>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <a href="{{ route('lecturer.courses.show', $material->course_offering_id ?? $material->course_id) }}"
                               class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700">
                                Update Material
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>