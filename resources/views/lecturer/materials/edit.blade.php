<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-6xl mx-auto px-6">

            <div class="mb-8">
                <a href="{{ route('lecturer.materials.show', $material->id) }}"
                   class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 mb-4">
                    ← Kembali ke Detail Material
                </a>

                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                    Lecturer Portal
                </p>
                <h1 class="text-3xl font-bold text-slate-900">Edit Learning Material</h1>
                <p class="text-slate-500 mt-2">
                    Perbarui judul, course, atau file materi pembelajaran.
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
                            <label class="block mb-2 text-sm font-medium text-slate-700">Course</label>
                            <select name="course_id"
                                    class="w-full rounded-2xl border border-slate-300 bg-slate-50 p-3 text-slate-900 focus:border-violet-500 focus:outline-none"
                                    required>
                                <option value="">Pilih course</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ old('course_id', $material->course_id) == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

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

                                    <a href="{{ asset('storage/' . $material->file_path) }}"
                                       download
                                       class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                                        Download
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
                            <a href="{{ route('lecturer.materials.index') }}"
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

                <div class="space-y-6">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-slate-900 mb-4">Edit Tips</h2>
                        <div class="space-y-4 text-sm text-slate-600">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                Ganti judul jika Anda ingin membuat nama materi lebih jelas untuk mahasiswa.
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                Gunakan file PDF bila Anda ingin preview langsung tampil lebih optimal.
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                File lama akan diganti otomatis jika Anda upload file baru.
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-amber-200 bg-amber-50 p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-amber-900 mb-3">Perhatian</h2>
                        <p class="text-sm leading-6 text-amber-700">
                            Jika Anda mengganti file materi, mahasiswa akan melihat versi file yang baru pada course terkait.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
