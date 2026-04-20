<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-3xl mx-auto px-6">
            <div class="mb-8">
                <a href="{{ route('lecturer.courses.show', $course->id) }}"
                   class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 mb-4">
                    ← Kembali ke Detail Course
                </a>

                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                    Lecturer Portal
                </p>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">Edit Mata Kuliah</h1>
                <p class="mt-2 text-slate-500">
                    Perbarui informasi course agar tetap rapi, relevan, dan mudah dipahami mahasiswa.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <form action="{{ route('lecturer.courses.update', $course->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Nama Mata Kuliah</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $course->name) }}"
                            class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-violet-500 focus:outline-none"
                            placeholder="Contoh: Pemrograman Web"
                            required
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Deskripsi</label>
                        <textarea
                            name="description"
                            rows="6"
                            class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-violet-500 focus:outline-none"
                            placeholder="Tulis deskripsi singkat course..."
                        >{{ old('description', $course->description) }}</textarea>
                    </div>

                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                        <p class="text-sm font-semibold text-amber-700 mb-2">Perhatian</p>
                        <p class="text-sm text-amber-700 leading-6">
                            Perubahan nama dan deskripsi course akan langsung terlihat oleh mahasiswa yang sudah terdaftar di course ini.
                        </p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('lecturer.courses.show', $course->id) }}"
                           class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
