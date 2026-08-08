<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Periode Semester
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">
                <form action="{{ route('admin.academic-terms.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Nama Semester</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               class="border rounded w-full p-2"
                               placeholder="Contoh: 2025/2026 Ganjil"
                               required>

                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Tahun Ajaran (opsional)</label>
                        <input type="text"
                               name="academic_year"
                               value="{{ old('academic_year') }}"
                               class="border rounded w-full p-2"
                               placeholder="Contoh: 2025/2026">

                        @error('academic_year')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Tipe Semester</label>
                        <select name="term_type" class="border rounded w-full p-2" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="ganjil" {{ old('term_type') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="genap" {{ old('term_type') == 'genap' ? 'selected' : '' }}>Genap</option>
                        </select>

                        @error('term_type')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-semibold mb-2">Tanggal Mulai</label>
                            <input type="date"
                                   name="start_date"
                                   value="{{ old('start_date') }}"
                                   class="border rounded w-full p-2">

                            @error('start_date')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-semibold mb-2">Tanggal Selesai</label>
                            <input type="date"
                                   name="end_date"
                                   value="{{ old('end_date') }}"
                                   class="border rounded w-full p-2">

                            @error('end_date')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center gap-2">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   class="rounded"
                                   {{ old('is_active') ? 'checked' : '' }}>
                            <span class="font-semibold">Jadikan semester aktif</span>
                        </label>
                        <p class="text-sm text-gray-500 mt-1">
                            Semester lain yang sedang aktif akan otomatis dinonaktifkan.
                        </p>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                            Simpan
                        </button>

                        <a href="{{ route('admin.academic-terms.index') }}" class="px-4 py-2 bg-gray-200 rounded">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
