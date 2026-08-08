<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Master Course
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">
                <form action="{{ route('admin.master-courses.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Kode Mata Kuliah</label>
                        <input type="text"
                               name="code"
                               value="{{ old('code') }}"
                               class="border rounded w-full p-2"
                               placeholder="Contoh: TKG101"
                               required>

                        @error('code')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <p class="text-sm text-gray-500 mt-1">
                            Kode unik untuk identifikasi mata kuliah (contoh: TKG101, INF202).
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Nama Mata Kuliah</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               class="border rounded w-full p-2"
                               placeholder="Contoh: Pemrograman Web Laravel"
                               required>

                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Level</label>
                        <select name="level" class="border rounded w-full p-2" required>
                            <option value="">-- Pilih Level --</option>
                            <option value="Beginner" {{ old('level') == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="Intermediate" {{ old('level') == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="Advanced" {{ old('level') == 'Advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>

                        @error('level')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Kategori</label>
                        <select name="category_id" class="border rounded w-full p-2">
                            <option value="">-- Pilih Kategori (Opsional) --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('category_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Deskripsi (opsional)</label>
                        <textarea name="description"
                                  rows="4"
                                  class="border rounded w-full p-2"
                                  placeholder="Deskripsi kurikulum mata kuliah...">{{ old('description') }}</textarea>

                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                            Simpan
                        </button>

                        <a href="{{ route('admin.master-courses.index') }}" class="px-4 py-2 bg-gray-200 rounded">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
