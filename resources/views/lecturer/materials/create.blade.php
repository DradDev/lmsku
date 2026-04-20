<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-6xl mx-auto px-6">

            <div class="mb-8">
                <a href="{{ route('lecturer.materials.index') }}"
                   class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 mb-4">
                    ← Kembali ke Materials
                </a>

                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                    Lecturer Portal
                </p>
                <h1 class="text-3xl font-bold text-slate-900">Upload Learning Material</h1>
                <p class="text-slate-500 mt-2">
                    Tambahkan materi pembelajaran ke course yang Anda kelola.
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
                    <form action="{{ route('lecturer.materials.store') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          class="space-y-6">
                        @csrf

                        <div>
                            <label class="block mb-2 text-sm font-medium text-slate-700">Course</label>
                            <select name="course_id"
                                    class="w-full rounded-2xl border border-slate-300 bg-slate-50 p-3 text-slate-900 focus:border-violet-500 focus:outline-none"
                                    required>
                                <option value="">Pilih course</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-slate-700">Judul Materi</label>
                            <input type="text"
                                   name="title"
                                   value="{{ old('title') }}"
                                   placeholder="Contoh: Modul HTML Dasar"
                                   class="w-full rounded-2xl border border-slate-300 bg-slate-50 p-3 text-slate-900 placeholder:text-slate-400 focus:border-violet-500 focus:outline-none"
                                   required>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-slate-700">File Materi</label>
                            <input type="file"
                                   name="file"
                                   class="w-full rounded-2xl border border-slate-300 bg-white p-3 text-slate-900 file:mr-4 file:rounded-xl file:border-0 file:bg-violet-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-violet-700"
                                   accept=".pdf,.doc,.docx,.ppt,.pptx"
                                   required>

                            <p class="mt-2 text-xs text-slate-500">
                                Format yang didukung: PDF, DOC, DOCX, PPT, PPTX. Maksimal 20MB.
                            </p>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <a href="{{ route('lecturer.materials.index') }}"
                               class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700">
                                Upload Material
                            </button>
                        </div>
                    </form>
                </div>

                <div class="space-y-6">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-slate-900 mb-4">Upload Tips</h2>
                        <div class="space-y-4 text-sm text-slate-600">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                Gunakan judul materi yang singkat dan jelas agar mudah dipahami mahasiswa.
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                Format file yang paling nyaman untuk preview biasanya PDF.
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                Pastikan Anda memilih course yang benar sebelum upload materi.
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-indigo-200 bg-indigo-50 p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-indigo-900 mb-3">Info</h2>
                        <p class="text-sm leading-6 text-indigo-700">
                            Setelah materi berhasil diupload, Anda bisa langsung membuka detail materi, mengunduh file, atau memperbarui file kapan saja.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
