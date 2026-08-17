<x-app-layout>
    <div class="py-6" x-data="{ showCreateTermModal: false, showEditTermModal: false, editTermData: {} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- PAGE HERO HEADER CARD WITH PROMINENT ADD SEMESTER BUTTON -->
            <div class="bg-white border border-slate-200 shadow-xs rounded-2xl p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-bold shadow-xs flex-shrink-0">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                        </div>

                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-widest rounded-md bg-blue-50 text-blue-700 border border-blue-100">
                                    Portal Administrasi Semester
                                </span>
                            </div>
                            <h2 class="font-bold text-xl text-slate-900 leading-tight mt-0.5">
                                Pengelolaan Periode Semester Akademik
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Atur rentang tanggal perkuliahan semester, penawaran matkul, pembuatan rombel, dan penugasan dosen pengampu.
                            </p>
                        </div>
                    </div>

                    <!-- PROMINENT ADD NEW SEMESTER BUTTON -->
                    <div class="flex items-center gap-3">
                        <button type="button" 
                                onclick="document.getElementById('createTermModal').style.display='flex'"
                                class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs font-bold rounded-xl shadow-xs transition whitespace-nowrap">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                            <span>+ Periode Semester Baru</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- SUCCESS / ERROR ALERTS -->
            @if (session('success'))
                <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-xs text-xs font-semibold">
                    <svg class="mt-0.5 flex-shrink-0 text-emerald-600" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                    <div>
                        <p class="font-bold text-sm">Berhasil</p>
                        <p class="text-xs mt-0.5 text-emerald-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-start gap-3 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl shadow-xs text-xs font-semibold">
                    <svg class="mt-0.5 flex-shrink-0 text-rose-600" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <div>
                        <p class="font-bold text-sm">Gagal</p>
                        <p class="text-xs mt-0.5 text-rose-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- SEMESTER CARDS GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($terms as $term)
                    <div class="bg-white border {{ $term->is_active ? 'border-blue-300 ring-2 ring-blue-500/10' : 'border-slate-200' }} rounded-2xl p-6 shadow-xs hover:border-slate-300 transition flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                @if($term->is_active)
                                    <span class="px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        Semester Berjalan (Aktif)
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                        Non-Aktif
                                    </span>
                                @endif

                                <span class="text-xs font-mono font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                                    {{ $term->academic_year ?? 'Akademik' }}
                                </span>
                            </div>

                            <h3 class="text-lg font-bold text-slate-900 leading-snug">
                                <a href="{{ route('admin.academic-terms.show', $term) }}" class="hover:text-blue-600 transition">
                                    {{ $term->name }}
                                </a>
                            </h3>

                            <!-- START DATE & END DATE DISPLAY -->
                            <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-xl">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-blue-600"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                <span>
                                    @if($term->start_date && $term->end_date)
                                        {{ $term->start_date->format('d M Y') }} – {{ $term->end_date->format('d M Y') }}
                                    @elseif($term->start_date)
                                        Mulai: {{ $term->start_date->format('d M Y') }}
                                    @else
                                        Periode Belum Diatur
                                    @endif
                                </span>
                            </div>

                            <!-- Stats Counters -->
                            <div class="pt-2 grid grid-cols-3 gap-2 text-center text-xs border-t border-slate-100">
                                <div class="bg-slate-50 border border-slate-200 rounded-xl p-2">
                                    <span class="block font-bold text-slate-900 text-sm">{{ $term->master_courses_count ?? 0 }}</span>
                                    <span class="text-[10px] text-slate-500 font-semibold">Total Matkul</span>
                                </div>

                                <div class="bg-slate-50 border border-slate-200 rounded-xl p-2">
                                    <span class="block font-bold text-blue-600 text-sm">{{ $term->offerings_count ?? 0 }}</span>
                                    <span class="text-[10px] text-slate-500 font-semibold">Rombel</span>
                                </div>

                                <div class="bg-slate-50 border border-slate-200 rounded-xl p-2">
                                    <span class="block font-bold text-slate-800 text-sm">
                                        {{ $term->lecturers_count ?? 0 }}
                                    </span>
                                    <span class="text-[10px] text-slate-500 font-semibold">Dosen</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Action Buttons -->
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                            <a href="{{ route('admin.academic-terms.show', $term) }}" 
                               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                <span>Buka Pengaturan Semester</span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>

                            <div class="flex items-center gap-1">
                                <!-- Edit Year & Dates Button -->
                                <button type="button" 
                                        @click="showEditTermModal = true; editTermData = { id: {{ $term->id }}, name: '{{ addslashes($term->name) }}', academic_year: '{{ addslashes($term->academic_year ?? '') }}', term_type: '{{ $term->term_type ?? 'ganjil' }}', start_date: '{{ $term->start_date ? $term->start_date->format('Y-m-d') : '' }}', end_date: '{{ $term->end_date ? $term->end_date->format('Y-m-d') : '' }}', is_active: {{ $term->is_active ? 'true' : 'false' }}, update_url: '{{ route('admin.academic-terms.update', $term) }}' }"
                                        title="Edit Semester & Tanggal Perkuliahan"
                                        class="p-2 text-slate-500 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-xl transition">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                </button>

                                <!-- Toggle Active Status Button -->
                                <form action="{{ route('admin.academic-terms.toggle-active', $term) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            title="{{ $term->is_active ? 'Non-aktifkan Semester' : 'Aktifkan Semester Berjalan' }}"
                                            class="p-2 {{ $term->is_active ? 'text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200' : 'text-slate-600 bg-slate-100 hover:bg-slate-200 border border-slate-200' }} rounded-xl transition">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        <!-- MODAL: EDIT PERIODE SEMESTER -->
        <div x-show="showEditTermModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" 
             style="display: none;">
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-md overflow-hidden" @click.away="showEditTermModal = false">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Edit Periode Semester & Tanggal</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Perbarui nama, tahun akademik, dan rentang tanggal semester.</p>
                    </div>
                    <button type="button" @click="showEditTermModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
                </div>

                <form :action="editTermData.update_url" method="POST" class="p-5 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Semester <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" x-model="editTermData.name" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tahun Akademik <span class="text-rose-500">*</span></label>
                            <input type="text" name="academic_year" x-model="editTermData.academic_year" placeholder="Contoh: 2026/2027" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Semester <span class="text-rose-500">*</span></label>
                            <select name="term_type" x-model="editTermData.term_type" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                                <option value="ganjil">Ganjil</option>
                                <option value="genap">Genap</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Mulai Perkuliahan</label>
                            <input type="date" name="start_date" x-model="editTermData.start_date" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Selesai Perkuliahan</label>
                            <input type="date" name="end_date" x-model="editTermData.end_date" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold">
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" x-model="editTermData.is_active" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-xs font-bold text-slate-800">Tetapkan Sebagai Semester Berjalan (Aktif)</span>
                        </label>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button type="button" @click="showEditTermModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition border border-slate-200">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: TAMBAH PERIODE SEMESTER BARU -->
        <div id="createTermModal" class="fixed inset-0 z-50 items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" style="display: none;">
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-md overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">+ Tambah Periode Semester Baru</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Buat periode semester baru lengkap dengan jadwal perkuliahan.</p>
                    </div>
                    <button type="button" onclick="document.getElementById('createTermModal').style.display='none'" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
                </div>

                <form action="{{ route('admin.academic-terms.store') }}" method="POST" class="p-5 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Semester <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" placeholder="Contoh: Semester Ganjil 2027/2028" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tahun Akademik <span class="text-rose-500">*</span></label>
                            <input type="text" name="academic_year" placeholder="Contoh: 2027/2028" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Semester <span class="text-rose-500">*</span></label>
                            <select name="term_type" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                                <option value="ganjil" selected>Ganjil</option>
                                <option value="genap">Genap</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Mulai Perkuliahan</label>
                            <input type="date" name="start_date" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Selesai Perkuliahan</label>
                            <input type="date" name="end_date" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold">
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-xs font-bold text-slate-800">Langsung Aktifkan Semester Ini</span>
                        </label>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button type="button" onclick="document.getElementById('createTermModal').style.display='none'" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition border border-slate-200">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition">Buat Semester</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
