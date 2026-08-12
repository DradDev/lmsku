<x-app-layout>
    <div class="py-8 bg-slate-50/50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- HERO HEADER CARD -->
            <div class="bg-white border border-slate-200/80 shadow-xs rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold border border-blue-100">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                        </div>
                        <div>
                            <h1 class="font-bold text-xl text-slate-900 leading-tight">
                                Tambah Master Course Baru
                            </h1>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Buat templat mata kuliah kurikulum induk baru untuk perpustakaan LMS kampus.
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('admin.master-courses.index') }}" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                        ← Kembali
                    </a>
                </div>
            </div>

            <!-- FORM CARD -->
            <div class="bg-white border border-slate-200/80 shadow-xs rounded-2xl p-6">
                <form action="{{ route('admin.master-courses.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kode Mata Kuliah <span class="text-rose-500">*</span></label>
                            <input type="text"
                                   name="code"
                                   value="{{ old('code') }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-0 text-xs font-bold"
                                   placeholder="Contoh: TKG101, INF202"
                                   required>
                            @error('code')
                                <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Level Kesulitan <span class="text-rose-500">*</span></label>
                            <select name="level" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-0 text-xs font-bold bg-white" required>
                                <option value="">-- Pilih Level --</option>
                                <option value="Beginner" {{ old('level') == 'Beginner' ? 'selected' : '' }}>Beginner (Dasar)</option>
                                <option value="Intermediate" {{ old('level') == 'Intermediate' ? 'selected' : '' }}>Intermediate (Menengah)</option>
                                <option value="Advanced" {{ old('level') == 'Advanced' ? 'selected' : '' }}>Advanced (Lanjut)</option>
                            </select>
                            @error('level')
                                <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Threshold Sertifikat (%) <span class="text-rose-500">*</span></label>
                            <input type="number"
                                   name="certificate_threshold"
                                   value="{{ old('certificate_threshold', 75) }}"
                                   min="1"
                                   max="100"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-0 text-xs font-bold"
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
                               value="{{ old('name') }}"
                               class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-0 text-xs font-bold"
                               placeholder="Contoh: Pemrograman Web Enterprise dengan Laravel"
                               required>
                        @error('name')
                            <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Kategori Kurikulum</label>
                        <select name="category_id" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-0 text-xs font-bold bg-white">
                            <option value="">-- Pilih Kategori (Opsional) --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                  class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-0 text-xs font-medium"
                                  placeholder="Jelaskan gambaran umum kurikulum, pokok bahasan, dan prasyarat mata kuliah ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                        <a href="{{ route('admin.master-courses.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                            Batal
                        </a>

                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                            Simpan Master Course
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
