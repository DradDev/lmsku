<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- HERO HEADER CARD -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                        </div>
                        <div>
                            <h2 class="font-extrabold text-xl text-slate-900 leading-tight">
                                Edit Master Course: {{ $masterCourse->name }}
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Perbarui metadata, level, passing score sertifikat, dan kategori kurikulum induk.
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('admin.master-courses.show', $masterCourse) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                        ← Batal / Detail
                    </a>
                </div>
            </div>

            <!-- FORM CARD -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
                <form action="{{ route('admin.master-courses.update', $masterCourse) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kode Mata Kuliah <span class="text-rose-500">*</span></label>
                            <input type="text"
                                   name="code"
                                   value="{{ old('code', $masterCourse->code) }}"
                                   class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs font-bold"
                                   placeholder="Contoh: TKG101, INF202"
                                   required>
                            @error('code')
                                <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Level Kesulitan <span class="text-rose-500">*</span></label>
                            <select name="level" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs font-bold bg-white" required>
                                <option value="">-- Pilih Level --</option>
                                <option value="Beginner" {{ old('level', $masterCourse->level) == 'Beginner' ? 'selected' : '' }}>Beginner (Dasar)</option>
                                <option value="Intermediate" {{ old('level', $masterCourse->level) == 'Intermediate' ? 'selected' : '' }}>Intermediate (Menengah)</option>
                                <option value="Advanced" {{ old('level', $masterCourse->level) == 'Advanced' ? 'selected' : '' }}>Advanced (Lanjut)</option>
                            </select>
                            @error('level')
                                <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Passing Score Sertifikat (%) <span class="text-rose-500">*</span></label>
                            <input type="number"
                                   name="certificate_threshold"
                                   value="{{ old('certificate_threshold', $masterCourse->certificate_threshold ?? 75) }}"
                                   min="1"
                                   max="100"
                                   class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs font-bold"
                                   placeholder="75"
                                   required>
                            @error('certificate_threshold')
                                <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Mata Kuliah Induk <span class="text-rose-500">*</span></label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $masterCourse->name) }}"
                               class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs font-bold"
                               placeholder="Contoh: Pemrograman Web Enterprise dengan Laravel"
                               required>
                        @error('name')
                            <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Kategori Kurikulum</label>
                        <select name="category_id" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs font-bold bg-white">
                            <option value="">-- Pilih Kategori (Opsional) --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $masterCourse->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi Kurikulum Induk (Opsional)</label>
                        <textarea name="description"
                                  rows="4"
                                  class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs font-medium"
                                  placeholder="Jelaskan gambaran umum kurikulum, pokok bahasan, dan prasyarat mata kuliah ini...">{{ old('description', $masterCourse->description) }}</textarea>
                        @error('description')
                            <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.master-courses.show', $masterCourse) }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                            Batal
                        </a>

                        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-xl shadow-md shadow-blue-500/20 transition">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
