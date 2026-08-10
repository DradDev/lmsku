<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                ✏️ Edit Course Sertifikasi — {{ $course->name }}
            </h2>
            <a href="{{ route('vendor.courses.show', $course) }}" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold text-xs rounded-xl">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
            <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-xl shadow-sm font-semibold text-xs flex items-center gap-2">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if ($errors->any())
            <div class="p-4 bg-red-100 border border-red-300 text-red-700 rounded-xl shadow-sm text-xs">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-6">

                <form action="{{ route('vendor.courses.update', $course) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nama Course Sertifikasi</label>
                        <input type="text" name="name" value="{{ old('name', $course->name) }}" required
                               class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-3">
                        @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Level Kesulitan</label>
                            <select name="level" required class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-3">
                                <option value="Beginner" {{ old('level', $course->level) === 'Beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="Intermediate" {{ old('level', $course->level) === 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="Advanced" {{ old('level', $course->level) === 'Advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Kategori</label>
                            <select name="category_id" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-3">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $course->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Passing Grade (0-100)</label>
                            <input type="number" name="certificate_threshold" value="{{ old('certificate_threshold', $course->certificate_threshold) }}" required min="0" max="100"
                                   class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-3">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Status Course</label>
                            <select name="is_archived" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-3 font-bold text-purple-900">
                                <option value="0" {{ old('is_archived', $course->is_archived) ? '' : 'selected' }}>🟢 Active Course (Terbuka)</option>
                                <option value="1" {{ old('is_archived', $course->is_archived) ? 'selected' : '' }}>🔴 Draft Bank (Internal)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Deskripsi & Pokok Bahasan</label>
                        <textarea name="description" rows="4" required class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-3">{{ old('description', $course->description) }}</textarea>
                    </div>

                    <!-- Target Skills -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Target Skill Kompetensi Utama</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-44 overflow-y-auto border border-gray-200 rounded-xl p-3 bg-gray-50">
                            @foreach($skills as $sk)
                            <label class="flex items-center gap-2 text-xs text-gray-700 font-semibold cursor-pointer">
                                <input type="checkbox" name="skill_ids[]" value="{{ $sk->id }}"
                                       {{ $course->skills->contains($sk->id) ? 'checked' : '' }}
                                       class="rounded text-indigo-600 border-gray-300">
                                <span>⚡ {{ $sk->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                        <a href="{{ route('vendor.courses.show', $course) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-bold text-xs rounded-xl">Batal</a>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-sm">
                            💾 Simpan Perubahan Course
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
