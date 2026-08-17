<x-app-layout>
    <div class="py-6" x-data="{ 
        showCreateOfferingModal: false, 
        showEditOfferingModal: false,
        selectedMasterCourseId: '',
        selectedMasterCourseName: '',
        editOfferingData: {}
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- HERO HEADER CARD FOR SEMESTER ADMINISTRATION -->
            <div class="bg-white border border-slate-200 shadow-xs rounded-2xl p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <a href="{{ route('admin.academic-terms.index') }}" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 border border-slate-200 flex items-center justify-center font-bold transition flex-shrink-0">
                            ←
                        </a>

                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="font-bold text-xl text-slate-900 leading-tight">
                                    Administrasi {{ $academicTerm->name }}
                                </h2>
                                @if($academicTerm->is_active)
                                    <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-widest rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        Semester Berjalan (Aktif)
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-widest rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                        Non-Aktif
                                    </span>
                                @endif
                            </div>
                            
                            <div class="flex flex-wrap items-center gap-3 mt-1 text-xs text-slate-500">
                                <span>Tahun Akademik: <strong class="text-slate-800">{{ $academicTerm->academic_year ?? '2026/2027' }}</strong></span>
                                <span>•</span>
                                <span class="flex items-center gap-1 font-semibold text-slate-700 bg-slate-50 border border-slate-200 px-2.5 py-0.5 rounded-lg">
                                    Rentang Perkuliahan: {{ $academicTerm->start_date ? $academicTerm->start_date->format('d M Y') : 'Mulai Belum Diatur' }} – {{ $academicTerm->end_date ? $academicTerm->end_date->format('d M Y') : 'Selesai Belum Diatur' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- PROMINENT ADD OFFERING BUTTON -->
                    <div>
                        <button type="button" 
                                @click="selectedMasterCourseId = ''; showCreateOfferingModal = true"
                                class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs font-bold rounded-xl shadow-xs transition whitespace-nowrap">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                            <span>+ Buka Matkul Baru di Semester Ini</span>
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

            <!-- EXECUTIVE METRICS CARDS FOR THIS SEMESTER -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600">Mata Kuliah Dibuka</span>
                    <div class="flex items-baseline justify-between pt-1">
                        <h3 class="text-3xl font-bold text-slate-900">{{ $offeredMasterCoursesCount }}</h3>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-100">dari {{ $allMasterCourses->count() }} Matkul</span>
                    </div>
                    <p class="text-[11px] text-slate-500">Pada {{ $academicTerm->name }}</p>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-600">Total Rombel Kelas</span>
                    <div class="flex items-baseline justify-between pt-1">
                        <h3 class="text-3xl font-bold text-blue-600">{{ $totalOfferingsCount }}</h3>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 border border-slate-200">Rombel</span>
                    </div>
                    <p class="text-[11px] text-slate-500">Kelas A, B, C, Paralel</p>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-600">Dosen Pengampu Bertugas</span>
                    <div class="flex items-baseline justify-between pt-1">
                        <h3 class="text-3xl font-bold text-slate-900">{{ $totalLecturersCount }}</h3>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100">Dosen</span>
                    </div>
                    <p class="text-[11px] text-slate-500">Pengampu kelas semester ini</p>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Mahasiswa Terdaftar</span>
                    <div class="flex items-baseline justify-between pt-1">
                        <h3 class="text-3xl font-bold text-emerald-700">{{ $totalEnrollmentsCount }}</h3>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200">Enrollments</span>
                    </div>
                    <p class="text-[11px] text-slate-500">Total partisipan siswa</p>
                </div>
            </div>

            <!-- SEMESTER COURSE OFFERINGS ADMINISTRATION LIST -->
            <div class="space-y-6">
                <div class="border-b border-slate-200 pb-3">
                    <h3 class="text-lg font-bold text-slate-900">Penawaran Rombel Kelas Semester Ini</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar kelas paralel dan penugasan Dosen Pengampu yang sedang dibuka pada {{ $academicTerm->name }}.</p>
                </div>

                @if($groupedOfferings->isEmpty())
                    <div class="bg-white border-2 border-dashed border-slate-200 rounded-2xl p-12 text-center space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto text-2xl font-bold border border-blue-100">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                        </div>
                        <h4 class="text-base font-bold text-slate-800">Belum Ada Mata Kuliah / Rombel Dibuka</h4>
                        <p class="text-xs text-slate-500 max-w-md mx-auto">
                            Klik tombol di bawah ini untuk memilih Master Course dari pustaka kurikulum dan membuka rombel kelas baru pada semester ini.
                        </p>
                        <button type="button" 
                                @click="selectedMasterCourseId = ''; showCreateOfferingModal = true"
                                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                            + Buka Matkul Baru di Semester Ini
                        </button>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($groupedOfferings as $masterId => $offeringsGroup)
                            @php $masterCourse = $offeringsGroup->first()->masterCourse; @endphp
                            <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
                                <div class="p-5 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <span class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-xs shadow-xs flex-shrink-0">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                                        </span>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-0.5 text-[10px] font-mono font-bold bg-slate-200 text-slate-700 rounded-md">
                                                    {{ $masterCourse->code ?? 'MC-'.$masterCourse->id }}
                                                </span>
                                                <h4 class="text-base font-bold text-slate-900">
                                                    {{ $masterCourse->name }}
                                                </h4>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-0.5">
                                                Level: <strong>{{ $masterCourse->level }}</strong> • Kategori: <strong>{{ optional($masterCourse->category)->name ?? 'Umum' }}</strong>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- DIRECT BUTTON TO ADD A NEW ROMBEL CLASS TO THIS SPECIFIC MASTER COURSE -->
                                    <div class="flex items-center gap-2">
                                        <span class="px-3 py-1.5 bg-blue-50 text-blue-700 border border-blue-100 text-xs font-bold rounded-xl">
                                            {{ $offeringsGroup->count() }} Rombel Kelas
                                        </span>

                                        <button type="button" 
                                                @click="selectedMasterCourseId = '{{ $masterCourse->id }}'; showCreateOfferingModal = true"
                                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold text-xs rounded-xl shadow-xs transition">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                                            <span>+ Tambah Rombel Matkul Ini</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="w-full text-left text-xs">
                                        <thead class="bg-slate-100/70 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                                            <tr>
                                                <th class="px-5 py-3">Nama Rombel / Kelas</th>
                                                <th class="px-5 py-3">Dosen Pengampu Utama</th>
                                                <th class="px-5 py-3">Terisi / Kuota</th>
                                                <th class="px-5 py-3">Threshold</th>
                                                <th class="px-5 py-3">Status Rombel</th>
                                                <th class="px-5 py-3 text-right">Aksi Moderasi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @foreach($offeringsGroup as $off)
                                                @php 
                                                    $enrolledCount = $off->enrollments ? $off->enrollments->count() : 0;
                                                    $cap = $off->capacity ?? 40;
                                                    $isFull = $enrolledCount >= $cap;
                                                    $pct = min(100, round(($enrolledCount / max(1, $cap)) * 100));
                                                @endphp
                                                <tr class="hover:bg-slate-50/80 transition">
                                                    <td class="px-5 py-3.5 font-bold text-slate-900">
                                                        <div class="flex items-center gap-2">
                                                            <span class="w-2 h-2 rounded-full {{ $academicTerm->is_active && $off->status === 'published' ? 'bg-blue-600' : 'bg-slate-400' }}"></span>
                                                            <span>{{ $off->section_name }}</span>
                                                        </div>
                                                    </td>

                                                    <td class="px-5 py-3.5">
                                                        @if($off->lecturer)
                                                            <div class="flex items-center gap-2">
                                                                <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-700 border border-slate-200 flex items-center justify-center font-bold text-[10px]">
                                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                                                </span>
                                                                <div>
                                                                    <p class="font-bold text-slate-900">{{ $off->lecturer->name }}</p>
                                                                    <p class="text-[10px] text-slate-400">{{ $off->lecturer->email }}</p>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <span class="text-rose-600 italic font-semibold">Belum Ditugaskan</span>
                                                        @endif
                                                    </td>

                                                    <td class="px-5 py-3.5">
                                                        <div class="flex items-center justify-between gap-2 mb-1">
                                                            <span class="font-bold text-slate-900">{{ $enrolledCount }} / {{ $cap }} Mhs</span>
                                                            <span class="text-[10px] font-bold {{ $isFull ? 'text-rose-600' : 'text-slate-500' }}">{{ $pct }}%</span>
                                                        </div>
                                                        <div class="w-28 bg-slate-100 h-1.5 rounded-full overflow-hidden border border-slate-200">
                                                            <div class="h-1.5 rounded-full {{ $isFull ? 'bg-rose-500' : 'bg-blue-600' }}" style="width: {{ $pct }}%"></div>
                                                        </div>
                                                    </td>

                                                    <td class="px-5 py-3.5">
                                                        <span class="font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">
                                                            {{ $off->certificate_threshold ?? 70 }}%
                                                        </span>
                                                    </td>

                                                    <td class="px-5 py-3.5">
                                                        @if($academicTerm->is_active && $off->status === 'published')
                                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                                Published
                                                            </span>
                                                        @elseif(!$academicTerm->is_active && $off->status === 'published')
                                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200" title="Semester sedang non-aktif sehingga kelas otomatis tidak dapat diakses">
                                                                Non-Aktif (Semester Ditutup)
                                                            </span>
                                                        @elseif($off->status === 'draft')
                                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                                                Draft
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-800 border border-rose-200">
                                                                Cancelled
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
                                                                        status: '{{ $off->status }}',
                                                                        start_date: '{{ $off->start_date ? $off->start_date->format('Y-m-d') : ($academicTerm->start_date ? $academicTerm->start_date->format('Y-m-d') : '') }}',
                                                                        end_date: '{{ $off->end_date ? $off->end_date->format('Y-m-d') : ($academicTerm->end_date ? $academicTerm->end_date->format('Y-m-d') : '') }}'
                                                                    }; showEditOfferingModal = true"
                                                                    class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] rounded-lg transition border border-slate-200">
                                                                Edit Rombel
                                                            </button>

                                                            <form action="{{ route('admin.academic-terms.offerings.destroy', $off->id) }}" method="POST" onsubmit="return confirm('Tutup / Batalkan rombel kelas ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="px-2 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-[11px] font-bold rounded-lg transition">
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- CARD FOOTER ACTION BAR TO ADD A PARALLEL CLASS -->
                                <div class="px-5 py-3 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                    <span class="text-[11px] font-semibold text-slate-500">Ingin menambah rombel paralel baru untuk <strong>{{ $masterCourse->name }}</strong> (misal: Kelas B / Kelas C)?</span>
                                    <button type="button" 
                                            @click="selectedMasterCourseId = '{{ $masterCourse->id }}'; showCreateOfferingModal = true"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 font-bold text-xs rounded-xl transition whitespace-nowrap">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                                        <span>+ Buka Rombel Paralel (Kelas B/C)</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        <!-- MODAL: BUKA MATKUL & CREATION OF CLASS SECTION IN THIS SEMESTER -->
        <div x-show="showCreateOfferingModal"
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4"
             style="display: none;">
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-lg overflow-hidden" @click.away="showCreateOfferingModal = false">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Buka Matkul & Rombel Kelas Baru</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Pilih Master Course dari pustaka kurikulum dan tentukan Dosen Pengampu pada {{ $academicTerm->name }}.</p>
                    </div>
                    <button type="button" @click="showCreateOfferingModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
                </div>

                <form action="{{ route('admin.academic-terms.offerings.store') }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    <input type="hidden" name="academic_term_id" value="{{ $academicTerm->id }}">

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Master Course Induk <span class="text-rose-500">*</span></label>
                        <select name="master_course_id" x-model="selectedMasterCourseId" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                            <option value="">-- Pilih Master Course dari Pustaka --</option>
                            @foreach($allMasterCourses as $mc)
                                <option value="{{ $mc->id }}">{{ $mc->code ?? 'MC-'.$mc->id }} — {{ $mc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Rombel / Kelas <span class="text-rose-500">*</span></label>
                            <input type="text" name="section_name" placeholder="Contoh: Kelas A, TIF-3A" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kuota Mahasiswa <span class="text-rose-500">*</span></label>
                            <input type="number" name="capacity" value="40" min="1" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Dosen Pengampu Utama <span class="text-rose-500">*</span></label>
                        <select name="lecturer_id" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                            <option value="">-- Pilih Dosen Pengampu --</option>
                            @foreach($lecturers as $lec)
                                <option value="{{ $lec->id }}">
                                    {{ $lec->name }} ({{ $lec->assigned_classes_count ?? 0 }} Kelas Diampu)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Mulai Rombel</label>
                            <input type="date" name="start_date" value="{{ $academicTerm->start_date ? $academicTerm->start_date->format('Y-m-d') : '' }}" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Selesai Rombel</label>
                            <input type="date" name="end_date" value="{{ $academicTerm->end_date ? $academicTerm->end_date->format('Y-m-d') : '' }}" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Threshold Kelulusan (%) <span class="text-rose-500">*</span></label>
                            <input type="number" name="certificate_threshold" value="70" min="1" max="100" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Rombel <span class="text-rose-500">*</span></label>
                            <select name="status" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                                <option value="published" selected>Published (Terbuka)</option>
                                <option value="draft">Draft (Tertutup)</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button type="button" @click="showCreateOfferingModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition border border-slate-200">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition">Simpan & Buka Rombel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: EDIT ROMBEL KELAS -->
        <div x-show="showEditOfferingModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4"
             style="display: none;">
            <div @click.away="showEditOfferingModal = false" class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-lg overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Edit Rombel / Ganti Dosen Pengampu</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Perbarui nama rombel, dosen pengampu, kuota, atau status publikasi.</p>
                    </div>
                    <button type="button" @click="showEditOfferingModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
                </div>

                <form :action="'/admin/academic-terms/offerings/' + editOfferingData.id" method="POST" class="p-5 space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Rombel / Kelas <span class="text-rose-500">*</span></label>
                            <input type="text" name="section_name" x-model="editOfferingData.section_name" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kuota Mahasiswa <span class="text-rose-500">*</span></label>
                            <input type="number" name="capacity" x-model="editOfferingData.capacity" min="1" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Dosen Pengampu Utama <span class="text-rose-500">*</span></label>
                        <select name="lecturer_id" x-model="editOfferingData.lecturer_id" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                            @foreach($lecturers as $lec)
                                <option value="{{ $lec->id }}">
                                    {{ $lec->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Mulai Rombel</label>
                            <input type="date" name="start_date" x-model="editOfferingData.start_date" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Selesai Rombel</label>
                            <input type="date" name="end_date" x-model="editOfferingData.end_date" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Threshold Kelulusan (%) <span class="text-rose-500">*</span></label>
                            <input type="number" name="certificate_threshold" x-model="editOfferingData.certificate_threshold" min="1" max="100" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Rombel <span class="text-rose-500">*</span></label>
                            <select name="status" x-model="editOfferingData.status" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-xs font-semibold" required>
                                <option value="published">Published (Terbuka)</option>
                                <option value="draft">Draft (Tertutup)</option>
                                <option value="cancelled">Cancelled (Batal)</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button type="button" @click="showEditOfferingModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition border border-slate-200">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
