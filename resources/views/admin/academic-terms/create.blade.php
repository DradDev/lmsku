<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- HEADER CARD -->
            <div class="bg-white border border-slate-200 shadow-xs rounded-2xl p-6">
                <div class="flex items-center gap-3.5">
                    <a href="{{ route('admin.academic-terms.index') }}" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 border border-slate-200 flex items-center justify-center font-bold transition flex-shrink-0">
                        ←
                    </a>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-widest rounded-md bg-blue-50 text-blue-700 border border-blue-100">
                                Periode Akademik
                            </span>
                        </div>
                        <h2 class="font-bold text-xl text-slate-900 leading-tight mt-0.5">
                            Tambah Periode Semester Baru
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Daftarkan semester akademik baru dan tentukan jadwal perkuliahan.
                        </p>
                    </div>
                </div>
            </div>

            <!-- FORM CARD -->
            <div class="bg-white border border-slate-200 shadow-xs rounded-2xl p-6">
                <form action="{{ route('admin.academic-terms.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Semester <span class="text-rose-500">*</span></label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold"
                               placeholder="Contoh: Semester Ganjil 2026/2027"
                               required>
                        @error('name')
                            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tahun Ajaran <span class="text-rose-500">*</span></label>
                            <input type="text"
                                   name="academic_year"
                                   value="{{ old('academic_year') }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold"
                                   placeholder="Contoh: 2026/2027"
                                   required>
                            @error('academic_year')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Semester <span class="text-rose-500">*</span></label>
                            <select name="term_type" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                                <option value="">-- Pilih Jenis Semester --</option>
                                <option value="ganjil" {{ old('term_type') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                                <option value="genap" {{ old('term_type') == 'genap' ? 'selected' : '' }}>Genap</option>
                            </select>
                            @error('term_type')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Mulai Perkuliahan</label>
                            <input type="date"
                                   name="start_date"
                                   value="{{ old('start_date') }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold">
                            @error('start_date')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Selesai Perkuliahan</label>
                            <input type="date"
                                   name="end_date"
                                   value="{{ old('end_date') }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold">
                            @error('end_date')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                   {{ old('is_active') ? 'checked' : '' }}>
                            <span class="text-xs font-bold text-slate-800">Jadikan sebagai Semester Berjalan (Aktif)</span>
                        </label>
                        <p class="text-[11px] text-slate-500 mt-1 pl-6">
                            Semester lain yang sedang aktif akan otomatis dialihkan menjadi non-aktif.
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                        <a href="{{ route('admin.academic-terms.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition border border-slate-200">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                            Simpan Semester
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
