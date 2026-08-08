<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Buka Kelas Baru
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">
                <form action="{{ route('admin.course-offerings.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Mata Kuliah Induk</label>
                        <select name="master_course_id" class="border rounded w-full p-2" required>
                            <option value="">-- Pilih Mata Kuliah --</option>
                            @foreach($masterCourses as $mc)
                                <option value="{{ $mc->id }}" {{ old('master_course_id') == $mc->id ? 'selected' : '' }}>
                                    {{ $mc->name }} ({{ $mc->code }})
                                </option>
                            @endforeach
                        </select>

                        @error('master_course_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Semester</label>
                        <select name="academic_term_id" class="border rounded w-full p-2" required>
                            <option value="">-- Pilih Semester --</option>
                            @foreach($terms as $t)
                                <option value="{{ $t->id }}" {{ old('academic_term_id') == $t->id ? 'selected' : '' }}>
                                    {{ $t->name }} {{ $t->is_active ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>

                        @error('academic_term_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Nama Kelas</label>
                        <input type="text"
                               name="section_name"
                               value="{{ old('section_name') }}"
                               class="border rounded w-full p-2"
                               placeholder="Contoh: Kelas A"
                               required>

                        @error('section_name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Dosen Pengampu</label>
                        <select name="lecturer_id" class="border rounded w-full p-2" required>
                            <option value="">-- Pilih Dosen --</option>
                            @foreach($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}" {{ old('lecturer_id') == $lecturer->id ? 'selected' : '' }}>
                                    {{ $lecturer->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('lecturer_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-semibold mb-2">Kuota Mahasiswa</label>
                            <input type="number"
                                   name="capacity"
                                   value="{{ old('capacity') }}"
                                   class="border rounded w-full p-2"
                                   placeholder="30"
                                   min="1">

                            @error('capacity')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror

                            <p class="text-sm text-gray-500 mt-1">
                                Kosongkan jika tidak ada batasan kuota.
                            </p>
                        </div>

                        <div>
                            <label class="block font-semibold mb-2">Threshold Sertifikat</label>
                            <input type="number"
                                   name="certificate_threshold"
                                   value="{{ old('certificate_threshold', 60) }}"
                                   class="border rounded w-full p-2"
                                   min="0"
                                   max="100"
                                   required>

                            @error('certificate_threshold')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror

                            <p class="text-sm text-gray-500 mt-1">
                                Nilai minimum untuk kelulusan sertifikat (0-100).
                            </p>
                        </div>
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
                        <label class="block font-semibold mb-2">Status</label>
                        <select name="status" class="border rounded w-full p-2" required>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                        </select>

                        @error('status')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                            Simpan & Buka Kelas
                        </button>

                        <a href="{{ route('admin.course-offerings.index') }}" class="px-4 py-2 bg-gray-200 rounded">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
