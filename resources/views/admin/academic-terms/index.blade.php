<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center font-bold">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                </div>

                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">
                        Pengelolaan Periode Semester Akademik
                    </h2>
                    <p class="text-sm text-gray-500">
                        Pilih semester untuk membuka pengaturan penawaran matkul, pembuatan kelas rombel, dan penugasan dosen.
                    </p>
                </div>
            </div>

            <button type="button" 
                    onclick="document.getElementById('createTermModal').style.display='flex'"
                    class="px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5 whitespace-nowrap">
                <span>+ Periode Semester Baru</span>
            </button>
        </div>
    </x-slot>

    <div class="py-6" x-data="{ showCreateTermModal: false, showEditTermModal: false, editTermData: {} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- SUCCESS / ERROR ALERTS -->
            @if (session('success'))
                <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl shadow-sm">
                    <svg class="mt-0.5 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                    <div>
                        <p class="font-semibold text-sm">Berhasil</p>
                        <p class="text-xs mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl shadow-sm">
                    <svg class="mt-0.5 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <div>
                        <p class="font-semibold text-sm">Gagal</p>
                        <p class="text-xs mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- SEMESTER CARDS GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($terms as $term)
                    <div class="bg-white border {{ $term->is_active ? 'border-teal-300 ring-2 ring-teal-500/10' : 'border-gray-200' }} rounded-2xl p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                @if($term->is_active)
                                    <span class="px-3 py-1 text-xs font-extrabold uppercase tracking-wider rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        🟢 Semester Berjalan (Aktif)
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                        ⚪ Non-Aktif
                                    </span>
                                @endif

                                <span class="text-xs font-mono font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">
                                    {{ $term->academic_year ?? 'Akademik' }}
                                </span>
                            </div>

                            <h3 class="text-lg font-extrabold text-slate-900 leading-snug">
                                <a href="{{ route('admin.academic-terms.show', $term) }}" class="hover:text-teal-600 transition">
                                    {{ $term->name }}
                                </a>
                            </h3>

                            <!-- METRICS SUMMARY IN CARD -->
                            <div class="grid grid-cols-3 gap-2 pt-2 border-t border-gray-100">
                                <div class="bg-slate-50 p-2.5 rounded-xl text-center">
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Matkul</span>
                                    <span class="text-base font-extrabold text-slate-800">{{ $term->master_courses_count ?? 0 }}</span>
                                </div>

                                <div class="bg-slate-50 p-2.5 rounded-xl text-center">
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Rombel</span>
                                    <span class="text-base font-extrabold text-blue-600">{{ $term->offerings_count ?? 0 }}</span>
                                </div>

                                <div class="bg-slate-50 p-2.5 rounded-xl text-center">
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Dosen</span>
                                    <span class="text-base font-extrabold text-purple-600">{{ $term->lecturers_count ?? 0 }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- CARD ACTION BUTTONS -->
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between gap-2">
                            <a href="{{ route('admin.academic-terms.show', $term) }}" 
                               class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition">
                                <span>📂 Buka Pengaturan Semester</span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>

                            @if(!$term->is_active)
                                <form action="{{ route('admin.academic-terms.toggle-active', $term->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            title="Aktifkan Semester Ini"
                                            class="px-3 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 font-bold text-xs rounded-xl transition">
                                        🟢
                                    </button>
                                </form>
                            @endif

                            <button type="button" 
                                    @click="editTermData = { id: {{ $term->id }}, name: '{{ addslashes($term->name) }}', academic_year: '{{ $term->academic_year }}', term_type: '{{ $term->term_type }}', is_active: {{ $term->is_active ? 'true' : 'false' }} }; showEditTermModal = true"
                                    class="px-3 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">
                                ✏️
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        <!-- MODAL: EDIT TAHUN AKADEMIK SEMESTER -->
        <div x-show="showEditTermModal" 
             x-transition.opacity
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4"
             style="display: none;">
            <div @click.away="showEditTermModal = false" class="bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-md overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-gray-900">✏️ Edit Tahun Akademik Semester</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Perbarui nama, tahun akademik, atau status aktif semester ini.</p>
                    </div>
                    <button type="button" @click="showEditTermModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>
                </div>

                <form :action="'/admin/academic-terms/' + editTermData.id" method="POST" class="p-5 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Semester <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" x-model="editTermData.name" class="w-full rounded-xl border-gray-300 focus:border-teal-600 focus:ring-teal-600 text-xs font-bold" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Tahun Akademik <span class="text-rose-500">*</span></label>
                            <input type="text" name="academic_year" x-model="editTermData.academic_year" placeholder="Contoh: 2026/2027" class="w-full rounded-xl border-gray-300 focus:border-teal-600 focus:ring-teal-600 text-xs font-bold" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Jenis Semester <span class="text-rose-500">*</span></label>
                            <select name="term_type" x-model="editTermData.term_type" class="w-full rounded-xl border-gray-300 focus:border-teal-600 focus:ring-teal-600 text-xs font-bold" required>
                                <option value="ganjil">Ganjil</option>
                                <option value="genap">Genap</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" x-model="editTermData.is_active" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span class="text-xs font-bold text-slate-800">🟢 Tetapkan Sebagai Semester Berjalan (Aktif)</span>
                        </label>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2">
                        <button type="button" @click="showEditTermModal = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition">💾 Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: TAMBAH PERIODE SEMESTER BARU -->
        <div id="createTermModal" class="fixed inset-0 z-50 items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" style="display: none;">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-md overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-gray-900">+ Tambah Periode Semester Baru</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Buat periode semester baru (Semester Ganjil / Genap).</p>
                    </div>
                    <button type="button" onclick="document.getElementById('createTermModal').style.display='none'" class="text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>
                </div>

                <form action="{{ route('admin.academic-terms.store') }}" method="POST" class="p-5 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Semester <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" placeholder="Contoh: Semester Ganjil 2027/2028" class="w-full rounded-xl border-gray-300 focus:border-teal-600 focus:ring-teal-600 text-xs font-bold" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Tahun Akademik <span class="text-rose-500">*</span></label>
                            <input type="text" name="academic_year" placeholder="Contoh: 2027/2028" class="w-full rounded-xl border-gray-300 focus:border-teal-600 focus:ring-teal-600 text-xs font-bold" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Jenis Semester <span class="text-rose-500">*</span></label>
                            <select name="term_type" class="w-full rounded-xl border-gray-300 focus:border-teal-600 focus:ring-teal-600 text-xs font-bold" required>
                                <option value="ganjil" selected>Ganjil</option>
                                <option value="genap">Genap</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span class="text-xs font-bold text-slate-800">🟢 Langsung Aktifkan Semester Ini</span>
                        </label>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2">
                        <button type="button" onclick="document.getElementById('createTermModal').style.display='none'" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition">🚀 Buat Semester</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
