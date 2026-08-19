<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- HERO HEADER CARD -->
            <div class="bg-white border border-slate-200 shadow-xs rounded-2xl p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-bold shadow-xs flex-shrink-0">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" />
                                <path d="M3 9h18" />
                                <path d="M9 21V9" />
                            </svg>
                        </div>

                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-widest rounded-md bg-blue-50 text-blue-700 border border-blue-100">
                                    Administrasi Perkuliahan
                                </span>
                            </div>
                            <h2 class="font-bold text-xl text-slate-900 leading-tight mt-0.5">
                                Daftar Rombel Kelas Penawaran
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Kelola rombel kelas paralel, kuota mahasiswa, dosen pengampu, dan status per semester.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.course-offerings.create') }}"
                           class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition whitespace-nowrap">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 5v14" />
                                <path d="M5 12h14" />
                            </svg>
                            <span>+ Buka Kelas Baru</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- SUCCESS / ERROR ALERTS -->
            @if (session('success'))
                <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-xs text-xs font-semibold">
                    <svg class="mt-0.5 flex-shrink-0 text-emerald-600" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5" />
                    </svg>
                    <div>
                        <p class="font-bold text-sm">Berhasil</p>
                        <p class="text-xs mt-0.5 text-emerald-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-start gap-3 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl shadow-xs text-xs font-semibold">
                    <svg class="mt-0.5 flex-shrink-0 text-rose-600" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="15" y1="9" x2="9" y2="15" />
                        <line x1="9" y1="9" x2="15" y2="15" />
                    </svg>
                    <div>
                        <p class="font-bold text-sm">Gagal</p>
                        <p class="text-xs mt-0.5 text-rose-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- FILTER BAR CARD -->
            <div class="bg-white border border-slate-200 shadow-xs rounded-2xl p-5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Filter Rombel Berdasarkan Semester</h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Pilih semester untuk menyaring rombel kelas yang aktif atau terdaftar.
                        </p>
                    </div>

                    {{-- Filter Semester --}}
                    <form method="GET" action="{{ route('admin.course-offerings.index') }}" class="flex items-center gap-2">
                        <select name="academic_term_id" class="rounded-xl border-slate-300 text-xs font-semibold focus:border-blue-600 focus:ring-blue-600 p-2.5" onchange="this.form.submit()">
                            <option value="">Semua Semester</option>
                            @foreach($terms as $t)
                                <option value="{{ $t->id }}" {{ request('academic_term_id') == $t->id ? 'selected' : '' }}>
                                    {{ $t->name }} {{ $t->is_active ? '(Aktif)' : '(Non-Aktif)' }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            <!-- TABS: ALL / ACADEMIC / VENDOR -->
            <div class="flex items-center gap-2 pb-1 overflow-x-auto">
                <a href="{{ route('admin.course-offerings.index', array_merge(request()->query(), ['type' => 'all'])) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-xl transition whitespace-nowrap {{ ($type ?? 'all') === 'all' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                    <span>Semua Penawaran</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] {{ ($type ?? 'all') === 'all' ? 'bg-blue-700 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $totalCount ?? 0 }}</span>
                </a>
                <a href="{{ route('admin.course-offerings.index', array_merge(request()->query(), ['type' => 'academic'])) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-xl transition whitespace-nowrap {{ ($type ?? 'all') === 'academic' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                    <span>🏛️ Kelas Kampus (Akademik)</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] {{ ($type ?? 'all') === 'academic' ? 'bg-blue-700 text-white' : 'bg-blue-50 text-blue-700' }}">{{ $totalAcademicCount ?? 0 }}</span>
                </a>
                <a href="{{ route('admin.course-offerings.index', array_merge(request()->query(), ['type' => 'vendor'])) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-xl transition whitespace-nowrap {{ ($type ?? 'all') === 'vendor' ? 'bg-purple-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                    <span>🏢 Batch Pelatihan Vendor</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] {{ ($type ?? 'all') === 'vendor' ? 'bg-purple-700 text-white' : 'bg-purple-50 text-purple-700 border border-purple-100' }}">{{ $totalVendorCount ?? 0 }}</span>
                </a>
            </div>

            <!-- TABLE CARD -->
            <div class="bg-white border border-slate-200 shadow-xs rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-slate-100/70 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                                <th class="px-5 py-3.5 text-left">Mata Kuliah Induk</th>
                                <th class="px-5 py-3.5 text-left">Rombel Kelas</th>
                                <th class="px-5 py-3.5 text-left">Semester Akademik</th>
                                <th class="px-5 py-3.5 text-left">Dosen Pengampu</th>
                                <th class="px-5 py-3.5 text-left">Terisi / Kuota</th>
                                <th class="px-5 py-3.5 text-left">Threshold</th>
                                <th class="px-5 py-3.5 text-left">Status Rombel</th>
                                <th class="px-5 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($offerings as $offering)
                                @php
                                    $isTermActive = $offering->academicTerm ? $offering->academicTerm->is_active : true;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-5 py-3.5">
                                        <p class="font-bold text-slate-900">{{ $offering->masterCourse->name ?? '-' }}</p>
                                        <span class="inline-flex items-center px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-mono font-bold rounded mt-1 border border-slate-200">
                                            {{ $offering->masterCourse->code ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-3.5">
                                        @if($offering->type === 'vendor')
                                            <span class="inline-flex items-center px-2.5 py-1 bg-purple-50 text-purple-700 text-xs font-bold rounded-lg border border-purple-100">
                                                🏢 {{ $offering->section_name ?? '-' }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg border border-blue-100">
                                                🏛️ {{ $offering->section_name ?? '-' }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-3.5">
                                        @if($offering->type === 'vendor')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold rounded bg-purple-50 text-purple-700 border border-purple-100">
                                                Mitra Industri
                                            </span>
                                        @elseif($offering->academicTerm)
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-semibold text-slate-800">{{ $offering->academicTerm->name }}</span>
                                                @if($isTermActive)
                                                    <span class="px-1.5 py-0.5 text-[9px] font-bold rounded bg-emerald-50 text-emerald-800 border border-emerald-200">Aktif</span>
                                                @else
                                                    <span class="px-1.5 py-0.5 text-[9px] font-bold rounded bg-slate-100 text-slate-600 border border-slate-200">Non-Aktif</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-3.5">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-slate-800">{{ $offering->lecturer->institution->name ?? ($offering->lecturer->name ?? '-') }}</span>
                                            @if($offering->type === 'vendor')
                                                <span class="text-[10px] text-purple-600 font-semibold">{{ $offering->lecturer->name ?? 'Vendor Mitra' }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-5 py-3.5">
                                        @if($offering->capacity)
                                            <span class="font-bold {{ $offering->enrollments_count >= $offering->capacity ? 'text-rose-600' : 'text-slate-800' }}">
                                                {{ $offering->enrollments_count }}/{{ $offering->capacity }} Mhs
                                            </span>
                                        @else
                                            <span class="font-semibold text-slate-400">Unlimited</span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-3.5">
                                        <span class="font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">
                                            {{ $offering->certificate_threshold }}%
                                        </span>
                                    </td>

                                    <td class="px-5 py-3.5">
                                        @if($isTermActive && $offering->status === 'published')
                                            <span class="inline-flex items-center px-2.5 py-0.5 text-[11px] font-bold rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                Published
                                            </span>
                                        @elseif(!$isTermActive && $offering->status === 'published')
                                            <span class="inline-flex items-center px-2.5 py-0.5 text-[11px] font-bold rounded-full bg-slate-100 text-slate-700 border border-slate-200" title="Semester non-aktif sehingga kelas tidak dapat diakses">
                                                Non-Aktif (Semester Ditutup)
                                            </span>
                                        @elseif($offering->status === 'draft')
                                            <span class="inline-flex items-center px-2.5 py-0.5 text-[11px] font-bold rounded-full bg-amber-50 text-amber-800 border border-amber-200">
                                                Draft
                                            </span>
                                        @elseif($offering->status === 'ongoing')
                                            <span class="inline-flex items-center px-2.5 py-0.5 text-[11px] font-bold rounded-full bg-blue-50 text-blue-800 border border-blue-200">
                                                Ongoing
                                            </span>
                                        @elseif($offering->status === 'cancelled')
                                            <span class="inline-flex items-center px-2.5 py-0.5 text-[11px] font-bold rounded-full bg-rose-50 text-rose-800 border border-rose-200">
                                                Cancelled
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 text-[11px] font-bold rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                                {{ ucfirst($offering->status) }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-3.5 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('admin.course-offerings.show', $offering) }}"
                                               class="inline-flex items-center px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold rounded-lg transition border border-blue-200"
                                               title="Lihat riwayat mahasiswa terdaftar di rombel ini">
                                                Peserta ({{ $offering->enrollments_count }})
                                            </a>

                                            <a href="{{ route('admin.course-offerings.edit', $offering) }}"
                                               class="inline-flex items-center px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition border border-slate-200">
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.course-offerings.destroy', $offering) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin hapus kelas penawaran ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold rounded-lg transition border border-rose-200">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-12 text-center">
                                        <h3 class="text-sm font-bold text-slate-800">Belum ada kelas penawaran</h3>
                                        <p class="text-xs text-slate-500 mt-1 mb-4">
                                            Buka kelas baru untuk mulai menawarkan mata kuliah kepada mahasiswa.
                                        </p>
                                        <a href="{{ route('admin.course-offerings.create') }}"
                                           class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                            Buka Kelas Baru
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
