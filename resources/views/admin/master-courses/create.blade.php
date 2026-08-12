<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- HERO HEADER CARD -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 5v14"/><path d="M5 12h14"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-extrabold text-xl text-slate-900 leading-tight">
                                Tambah Master Course Baru
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Buat templat mata kuliah kurikulum induk baru untuk perpustakaan LMS kampus.
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('admin.master-courses.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                        ← Kembali ke Katalog
                    </a>
                </div>
            </div>

            <!-- FORM CARD -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
                <form action="{{ route('admin.master-courses.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kode Mata Kuliah <span class="text-slate-400 font-normal">(Opsional - Otomatis)</span></label>
                            <input type="text"
                                   name="code"
                                   value="{{ old('code') }}"
                                   class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs font-bold"
                                   placeholder="Kosongkan untuk Kode Otomatis: TK-EMB-INT-001">
                            @error('code')
                                <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Level Kesulitan <span class="text-rose-500">*</span></label>
                            <select name="level" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs font-bold bg-white" required>
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
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Passing Score Sertifikat (%) <span class="text-rose-500">*</span></label>
                            <input type="number"
                                   name="certificate_threshold"
                                   value="{{ old('certificate_threshold', 75) }}"
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
                               value="{{ old('name') }}"
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
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- TARGET SKILL COMPETENCY SELECTION -->
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                        <div>
                            <label class="block text-xs font-extrabold text-slate-800">
                                Target Skill Utama (Competency Skills)
                            </label>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                Skill pertama yang Anda centang akan digunakan sebagai rujukan Singkatan Kode Course (contoh: <strong>Embedded Systems → TK-EMB-INT-001</strong>).
                            </p>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 pt-1 max-h-48 overflow-y-auto">
                            @foreach($skills as $skill)
                                <label class="flex items-center gap-2 p-2 bg-white border border-slate-200 rounded-lg cursor-pointer hover:border-blue-300 transition text-xs font-medium text-slate-700">
                                    <input type="checkbox"
                                           name="skill_ids[]"
                                           value="{{ $skill->id }}"
                                           {{ is_array(old('skill_ids')) && in_array($skill->id, old('skill_ids')) ? 'checked' : '' }}
                                           class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span>{{ $skill->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- SPECIALTY TAGS SELECTION -->
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                        <div>
                            <label class="block text-xs font-extrabold text-slate-800">
                                Tag Spesialisasi (Specialty Tags)
                            </label>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                Centang tag spesialisasi yang relevan dengan kurikulum mata kuliah ini.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2 pt-1">
                            @foreach($tags as $tag)
                                <label class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-purple-300 transition text-xs font-semibold text-purple-700">
                                    <input type="checkbox"
                                           name="tag_ids[]"
                                           value="{{ $tag->id }}"
                                           {{ is_array(old('tag_ids')) && in_array($tag->id, old('tag_ids')) ? 'checked' : '' }}
                                           class="rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                                    <span>#{{ $tag->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi Kurikulum Induk (Opsional)</label>
                        <textarea name="description"
                                  rows="4"
                                  class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs font-medium"
                                  placeholder="Jelaskan gambaran umum kurikulum, pokok bahasan, dan prasyarat mata kuliah ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.master-courses.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                            Batal
                        </a>

                        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-xl shadow-md shadow-blue-500/20 transition">
                            Simpan Master Course
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
