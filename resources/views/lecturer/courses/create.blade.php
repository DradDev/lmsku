<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-3xl mx-auto px-6">

            <div class="mb-8">
                <a href="{{ route('lecturer.courses.index') }}"
                   class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 mb-4">
                    ← Kembali ke Courses
                </a>

                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                    Lecturer Portal
                </p>
                <h1 class="text-3xl font-bold text-slate-900">Tambah Mata Kuliah</h1>
                <p class="text-slate-500 mt-2">
                    Buat course baru untuk materi, quiz, assignment, dan aktivitas pembelajaran.
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

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <form action="{{ route('lecturer.courses.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block mb-2 text-sm font-medium text-slate-700">Nama Mata Kuliah</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Contoh: Pemrograman Web"
                               class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-violet-500 focus:outline-none"
                               required>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-slate-700">Deskripsi</label>
                        <textarea name="description"
                                  rows="6"
                                  placeholder="Tulis deskripsi singkat course..."
                                  class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-violet-500 focus:outline-none">{{ old('description') }}</textarea>
                    </div>

                    <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-4">
                        <p class="text-sm font-semibold text-indigo-700 mb-2">Info</p>
                        <p class="text-sm text-indigo-600 leading-6">
                            Setelah course dibuat, Anda bisa langsung menambahkan learning material, quiz, assignment, dan menentukan final quiz untuk certificate.
                        </p>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('lecturer.courses.index') }}"
                           class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Cancel
                        </a>

                        <button type="submit"
                                class="rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700">
                            Create Course
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
