<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- HEADER CARD -->
            <div class="bg-white border border-slate-200 shadow-xs rounded-2xl p-6">
                <div class="flex items-center gap-3.5">
                    <a href="{{ route('admin.course-offerings.index') }}" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 border border-slate-200 flex items-center justify-center font-bold transition flex-shrink-0">
                        ←
                    </a>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-widest rounded-md bg-blue-50 text-blue-700 border border-blue-100">
                                Penawaran Perkuliahan
                            </span>
                        </div>
                        <h2 class="font-bold text-xl text-slate-900 leading-tight mt-0.5">
                            Buka Rombel Kelas Baru
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Pilih mata kuliah dari kurikulum dan tugaskan dosen pengampu pada semester yang dipilih.
                        </p>
                    </div>
                </div>
            </div>

            <!-- FORM CARD -->
            <div class="bg-white border border-slate-200 shadow-xs rounded-2xl p-6">
                <form action="{{ route('admin.course-offerings.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Mata Kuliah Induk <span class="text-rose-500">*</span></label>
                        <select name="master_course_id" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                            <option value="">-- Pilih Mata Kuliah --</option>
                            @foreach($masterCourses as $mc)
                                <option value="{{ $mc->id }}" {{ old('master_course_id', $selectedMasterCourseId ?? '') == $mc->id ? 'selected' : '' }}>
                                    {{ $mc->name }} ({{ $mc->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('master_course_id')
                            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Semester Akademik <span class="text-rose-500">*</span></label>
                        <select name="academic_term_id" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                            <option value="">-- Pilih Semester --</option>
                            @foreach($terms as $t)
                                <option value="{{ $t->id }}" {{ old('academic_term_id', $selectedAcademicTermId ?? '') == $t->id ? 'selected' : '' }}>
                                    {{ $t->name }} {{ $t->is_active ? '(Aktif)' : '(Non-Aktif)' }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_term_id')
                            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Rombel / Kelas <span class="text-rose-500">*</span></label>
                            <input type="text"
                                   name="section_name"
                                   value="{{ old('section_name') }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold"
                                   placeholder="Contoh: Kelas A"
                                   required>
                            @error('section_name')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Dosen Pengampu <span class="text-rose-500">*</span></label>
                            <select name="lecturer_id" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($lecturers as $lecturer)
                                    <option value="{{ $lecturer->id }}" {{ old('lecturer_id') == $lecturer->id ? 'selected' : '' }}>
                                        {{ $lecturer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lecturer_id')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kuota Mahasiswa</label>
                            <input type="number"
                                   name="capacity"
                                   value="{{ old('capacity') }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold"
                                   placeholder="40"
                                   min="1">
                            @error('capacity')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Threshold Sertifikat (%) <span class="text-rose-500">*</span></label>
                            <input type="number"
                                   name="certificate_threshold"
                                   value="{{ old('certificate_threshold', 70) }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold"
                                   min="0"
                                   max="100"
                                   required>
                            @error('certificate_threshold')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Mulai</label>
                            <input type="date"
                                   name="start_date"
                                   value="{{ old('start_date') }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold">
                            @error('start_date')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Selesai</label>
                            <input type="date"
                                   name="end_date"
                                   value="{{ old('end_date') }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold">
                            @error('end_date')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Rombel <span class="text-rose-500">*</span></label>
                        <select name="status" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Tertutup)</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published (Terbuka)</option>
                        </select>
                        @error('status')
                            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                        <a href="{{ route('admin.course-offerings.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition border border-slate-200">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                            Simpan & Buka Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
