<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.academic-terms.index') }}" class="w-10 h-10 rounded-xl bg-gray-100 text-gray-600 hover:bg-gray-200 flex items-center justify-center font-bold transition">
                    ←
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="font-bold text-xl text-gray-800 leading-tight">
                            Administrasi {{ $academicTerm->name }}
                        </h2>
                        @if($academicTerm->is_active)
                            <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-widest rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                🟢 Semester Berjalan (Aktif)
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-widest rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                ⚪ Non-Aktif
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500">
                        Pengaturan penawaran matkul, pembukaan rombel kelas baru, dan penugasan Dosen Pengampu pada semester {{ $academicTerm->academic_year ?? '' }}.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" 
                        onclick="document.getElementById('createOfferingModal').style.display='flex'"
                        class="px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5 whitespace-nowrap">
                    <span>+ Tambah / Buka Matkul di Semester Ini</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6" x-data="{ 
        showCreateOfferingModal: false, 
        showEditOfferingModal: false,
        selectedMasterCourseId: '',
        selectedMasterCourseName: '',
        editOfferingData: {}
    }">
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

            <!-- EXECUTIVE METRICS CARDS FOR THIS SEMESTER -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-teal-600">Mata Kuliah Dibuka</span>
                    <div class="flex items-baseline justify-between pt-1">
                        <h3 class="text-3xl font-extrabold text-teal-700">{{ $offeredMasterCoursesCount }}</h3>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-teal-50 text-teal-700">dari {{ $allMasterCourses->count() }} Matkul</span>
                    </div>
                    <p class="text-[11px] text-teal-600">Pada {{ $academicTerm->name }}</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600">Total Rombel Kelas</span>
                    <div class="flex items-baseline justify-between pt-1">
                        <h3 class="text-3xl font-extrabold text-blue-600">{{ $totalOfferingsCount }}</h3>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-blue-50 text-blue-700">Rombel</span>
                    </div>
                    <p class="text-[11px] text-blue-500">Kelas A, B, C, Paralel</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-purple-600">Dosen Pengampu Bertugas</span>
                    <div class="flex items-baseline justify-between pt-1">
                        <h3 class="text-3xl font-extrabold text-purple-600">{{ $totalLecturersCount }}</h3>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-purple-50 text-purple-700">Dosen</span>
                    </div>
                    <p class="text-[11px] text-purple-500">Pengampu kelas semester ini</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Mahasiswa Terdaftar</span>
                    <div class="flex items-baseline justify-between pt-1">
                        <h3 class="text-3xl font-extrabold text-emerald-600">{{ $totalEnrollmentsCount }}</h3>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700">Enrollments</span>
                    </div>
                    <p class="text-[11px] text-emerald-600">Total partisipan siswa</p>
                </div>
            </div>

            <!-- SEMESTER COURSE OFFERINGS ADMINISTRATION LIST -->
            <div class="space-y-6">
                <div class="flex items-center justify-between border-b border-gray-200 pb-3">
                    <h3 class="text-base font-extrabold text-gray-900 flex items-center gap-2">
                        <span>📋 Daftar Pengaturan Matkul & Rombel Kelas Semester Ini</span>
                    </h3>

                    <button type="button" 
                            onclick="document.getElementById('createOfferingModal').style.display='flex'"
                            class="px-3.5 py-2 bg-teal-50 hover:bg-teal-100 text-teal-700 border border-teal-200 font-bold text-xs rounded-xl transition flex items-center gap-1.5">
                        <span>+ Buka Rombel Baru</span>
                    </button>
                </div>

                @if($groupedOfferings->isEmpty())
                    <div class="bg-white border border-dashed border-gray-300 rounded-2xl p-12 text-center space-y-3">
                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                        </div>
                        <h4 class="font-bold text-base text-slate-800">Belum Ada Mata Kuliah Yang Dibuka</h4>
                        <p class="text-xs text-slate-500 max-w-md mx-auto">
                            Klik tombol di bawah untuk memilih Master Course dari pustaka kurikulum dan membuat rombel kelas pertama di semester ini.
                        </p>
                        <button type="button" 
                                onclick="document.getElementById('createOfferingModal').style.display='flex'"
                                class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition">
                            + Tambah / Buka Matkul Pertama
                        </button>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($groupedOfferings as $masterCourseId => $offerings)
                            @php
                                $mc = $offerings->first()->masterCourse;
                            @endphp
                            <div class="bg-white border border-teal-200 shadow-sm rounded-2xl overflow-hidden">
                                <!-- Master Course Header -->
                                <div class="p-4 bg-teal-50/60 border-b border-gray-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="font-mono text-[11px] font-extrabold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-300">
                                                {{ $mc->code ?? 'MC-' . $mc->id }}
                                            </span>

                                            <span class="text-xs font-bold text-indigo-700 bg-indigo-50 px-2.5 py-0.5 rounded-md border border-indigo-100">
                                                📁 {{ $mc->category->name ?? 'Umum' }}
                                            </span>

                                            <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2.5 py-0.5 rounded-md border border-slate-200">
                                                Level: {{ $mc->level }}
                                            </span>

                                            <span class="text-xs font-extrabold text-emerald-800 bg-emerald-100 px-3 py-0.5 rounded-full border border-emerald-200">
                                                🟢 Dibuka ({{ $offerings->count() }} Rombel)
                                            </span>
                                        </div>

                                        <h4 class="font-extrabold text-base text-slate-900">
                                            {{ $mc->name }}
                                        </h4>
                                    </div>

                                    <div class="flex items-center gap-2 whitespace-nowrap">
                                        <button type="button" 
                                                @click="selectedMasterCourseId = '{{ $mc->id }}'; selectedMasterCourseName = '{{ addslashes($mc->name) }}'; showCreateOfferingModal = true"
                                                class="px-3.5 py-2 bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition flex items-center gap-1">
                                            <span>+ Tambah Rombel Kelas</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Opened Rombel Classes Table -->
                                <div class="overflow-x-auto">
                                    <table class="w-full text-xs text-left">
                                        <thead>
                                            <tr class="bg-white border-b border-gray-100 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                                <th class="px-5 py-3">Nama Rombel Kelas</th>
                                                <th class="px-5 py-3">Dosen Pengampu Utama</th>
                                                <th class="px-5 py-3">Kuota Mahasiswa</th>
                                                <th class="px-5 py-3">Threshold Kelulusan (%)</th>
                                                <th class="px-5 py-3">Status Kelas</th>
                                                <th class="px-5 py-3 text-right">Aksi Administrasi</th>
                                            </tr>
                                        </thead>

                                        <tbody class="divide-y divide-gray-100">
                                            @foreach($offerings as $off)
                                                @php
                                                    $enrolledCount = $off->enrollments->count();
                                                    $capacity = $off->capacity ?? 40;
                                                    $pct = min(100, round(($enrolledCount / $capacity) * 100));
                                                    $isFull = $enrolledCount >= $capacity;
                                                @endphp
                                                <tr class="hover:bg-slate-50/50 transition">
                                                    <td class="px-5 py-3.5 font-extrabold text-slate-800">
                                                        <span class="inline-flex items-center gap-1 bg-sky-50 text-sky-800 border border-sky-200 px-2.5 py-1 rounded-lg">
                                                            📌 {{ $off->section_name }}
                                                        </span>
                                                    </td>

                                                    <td class="px-5 py-3.5">
                                                        <div class="font-bold text-slate-900">👨‍🏫 {{ $off->lecturer->name ?? 'Belum Ditugaskan' }}</div>
                                                        <div class="text-[10px] text-slate-400">{{ $off->lecturer->email ?? '-' }}</div>
                                                    </td>

                                                    <td class="px-5 py-3.5">
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-extrabold {{ $isFull ? 'text-rose-600' : 'text-slate-800' }}">
                                                                {{ $enrolledCount }} / {{ $capacity }} Mhs
                                                            </span>
                                                            @if($isFull)
                                                                <span class="text-[10px] font-bold text-rose-700 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200">FULL 🔒</span>
                                                            @endif
                                                        </div>

                                                        <div class="w-28 bg-gray-100 rounded-full h-1.5 mt-1 overflow-hidden">
                                                            <div class="h-1.5 rounded-full {{ $isFull ? 'bg-rose-500' : 'bg-teal-500' }}" style="width: {{ $pct }}%"></div>
                                                        </div>
                                                    </td>

                                                    <td class="px-5 py-3.5">
                                                        <span class="font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">
                                                            {{ $off->certificate_threshold ?? 70 }}%
                                                        </span>
                                                    </td>

                                                    <td class="px-5 py-3.5">
                                                        @if($off->status === 'published')
                                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                                🟢 Published
                                                            </span>
                                                        @elseif($off->status === 'draft')
                                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                                🟡 Draft
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                                🔴 Cancelled
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td class="px-5 py-3.5 text-right">
                                                        <div class="flex items-center justify-end gap-1.5">
                                                            <button type="button" 
                                                                    @click="editOfferingData = {
                                                                        id: {{ $off->id }},
                                                                        section_name: '{{ addslashes($off->section_name) }}',
                                                                        lecturer_id: '{{ $off->lecturer_id }}',
                                                                        capacity: {{ $off->capacity ?? 40 }},
                                                                        certificate_threshold: {{ $off->certificate_threshold ?? 70 }},
                                                                        status: '{{ $off->status }}'
                                                                    }; showEditOfferingModal = true"
                                                                    class="px-2.5 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-[11px] rounded-lg transition">
                                                                ✏️ Edit Rombel
                                                            </button>

                                                            <form action="{{ route('admin.academic-terms.offerings.destroy', $off->id) }}" method="POST" onsubmit="return confirm('Tutup / Batalkan rombel kelas ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="px-2 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-[11px] font-bold rounded-lg transition">
                                                                    🔴 Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        <!-- MODAL: BUKA MATKUL & CREATION OF CLASS SECTION IN THIS SEMESTER -->
        <div id="createOfferingModal" 
             class="fixed inset-0 z-50 items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4"
             style="display: none;">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-lg overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-gray-900">🚀 Tambah Matkul & Penugasan Dosen</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Pilih Master Course dari pustaka kurikulum dan tentukan Dosen Pengampu pada {{ $academicTerm->name }}.</p>
                    </div>
                    <button type="button" onclick="document.getElementById('createOfferingModal').style.display='none'" class="text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>
                </div>

                <form action="{{ route('admin.academic-terms.offerings.store') }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    <input type="hidden" name="academic_term_id" value="{{ $academicTerm->id }}">

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Pilih Master Course Induk <span class="text-rose-500">*</span></label>
                        <select name="master_course_id" class="w-full rounded-xl border-gray-300 focus:border-teal-600 focus:ring-teal-600 text-xs font-bold" required>
                            <option value="">-- Pilih Master Course dari Pustaka --</option>
                            @foreach($allMasterCourses as $mc)
                                <option value="{{ $mc->id }}">📚 {{ $mc->code ?? 'MC-'.$mc->id }} — {{ $mc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Rombel / Kelas <span class="text-rose-500">*</span></label>
                            <input type="text" name="section_name" placeholder="Contoh: Kelas A, TIF-3A" class="w-full rounded-xl border-gray-300 focus:border-teal-600 focus:ring-teal-600 text-xs font-bold" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Kuota Mahasiswa <span class="text-rose-500">*</span></label>
                            <input type="number" name="capacity" value="40" min="1" class="w-full rounded-xl border-gray-300 focus:border-teal-600 focus:ring-teal-600 text-xs font-bold" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Dosen Pengampu Utama <span class="text-rose-500">*</span></label>
                        <select name="lecturer_id" class="w-full rounded-xl border-gray-300 focus:border-teal-600 focus:ring-teal-600 text-xs font-bold" required>
                            <option value="">-- Pilih Dosen Pengampu --</option>
                            @foreach($lecturers as $lec)
                                <option value="{{ $lec->id }}">
                                    👨‍🏫 {{ $lec->name }} ({{ $lec->assigned_classes_count ?? 0 }} Kelas Diampu)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Threshold Kelulusan (%) <span class="text-rose-500">*</span></label>
                            <input type="number" name="certificate_threshold" value="70" min="1" max="100" class="w-full rounded-xl border-gray-300 focus:border-teal-600 focus:ring-teal-600 text-xs font-bold" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Status Rombel <span class="text-rose-500">*</span></label>
                            <select name="status" class="w-full rounded-xl border-gray-300 focus:border-teal-600 focus:ring-teal-600 text-xs font-bold" required>
                                <option value="published" selected>🟢 Published (Terbuka)</option>
                                <option value="draft">🟡 Draft (Tertutup)</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2">
                        <button type="button" onclick="document.getElementById('createOfferingModal').style.display='none'" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition">🚀 Simpan & Buka Rombel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: EDIT ROMBEL KELAS -->
        <div x-show="showEditOfferingModal" 
             x-transition.opacity
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4"
             style="display: none;">
            <div @click.away="showEditOfferingModal = false" class="bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-lg overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-gray-900">✏️ Edit Rombel / Ganti Dosen Pengampu</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Perbarui nama rombel, dosen pengampu, kuota, atau status publikasi.</p>
                    </div>
                    <button type="button" @click="showEditOfferingModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>
                </div>

                <form :action="'/admin/academic-terms/offerings/' + editOfferingData.id" method="POST" class="p-5 space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Rombel / Kelas <span class="text-rose-500">*</span></label>
                            <input type="text" name="section_name" x-model="editOfferingData.section_name" class="w-full rounded-xl border-gray-300 focus:border-teal-600 focus:ring-teal-600 text-xs font-bold" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Kuota Mahasiswa <span class="text-rose-500">*</span></label>
                            <input type="number" name="capacity" x-model="editOfferingData.capacity" min="1" class="w-full rounded-xl border-gray-300 focus:border-teal-600 focus:ring-teal-600 text-xs font-bold" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Dosen Pengampu Utama <span class="text-rose-500">*</span></label>
                        <select name="lecturer_id" x-model="editOfferingData.lecturer_id" class="w-full rounded-xl border-gray-300 focus:border-teal-600 focus:ring-teal-600 text-xs font-bold" required>
                            @foreach($lecturers as $lec)
                                <option value="{{ $lec->id }}">
                                    👨‍🏫 {{ $lec->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Threshold Kelulusan (%) <span class="text-rose-500">*</span></label>
                            <input type="number" name="certificate_threshold" x-model="editOfferingData.certificate_threshold" min="1" max="100" class="w-full rounded-xl border-gray-300 focus:border-teal-600 focus:ring-teal-600 text-xs font-bold" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Status Rombel <span class="text-rose-500">*</span></label>
                            <select name="status" x-model="editOfferingData.status" class="w-full rounded-xl border-gray-300 focus:border-teal-600 focus:ring-teal-600 text-xs font-bold" required>
                                <option value="published">🟢 Published (Terbuka)</option>
                                <option value="draft">🟡 Draft (Tertutup)</option>
                                <option value="cancelled">🔴 Cancelled (Batal)</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2">
                        <button type="button" @click="showEditOfferingModal = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition">💾 Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
