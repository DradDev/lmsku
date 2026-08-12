<x-app-layout>
    <div class="py-8 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- PAGE HEADER CARD -->
            <div class="bg-white border border-slate-200/80 shadow-xs rounded-2xl p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <a href="{{ route('admin.master-courses.index') }}" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 flex items-center justify-center font-bold transition flex-shrink-0">
                            ←
                        </a>

                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">
                                    {{ $masterCourse->code ?? 'MC-' . $masterCourse->id }}
                                </span>

                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-50 border border-emerald-100 px-2.5 py-0.5 rounded-md">
                                    {{ $masterCourse->level }}
                                </span>

                                @if($masterCourse->category)
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-700 bg-blue-50 border border-blue-100 px-2.5 py-0.5 rounded-md">
                                        {{ $masterCourse->category->name }}
                                    </span>
                                @endif
                            </div>

                            <h1 class="text-xl font-bold text-slate-900 leading-tight">
                                {{ $masterCourse->name }}
                            </h1>
                            <p class="text-xs text-slate-500 mt-0.5 max-w-2xl leading-relaxed">
                                {{ $masterCourse->description ?: 'Silabus induk mata kuliah. Kelola penawaran kelas paralel untuk semester aktif dan atur target kompetensi skill.' }}
                            </p>
                        </div>
                    </div>

                    <!-- METRICS & ACTIONS -->
                    <div class="flex items-center gap-4 border-t md:border-t-0 md:border-l border-slate-100 pt-4 md:pt-0 md:pl-6">
                        <div class="text-center px-2">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Rombel</span>
                            <span class="text-2xl font-extrabold text-slate-900 mt-0.5 block">{{ $totalOfferings }}</span>
                        </div>

                        <div class="text-center px-2">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Semester</span>
                            <span class="text-2xl font-extrabold text-slate-900 mt-0.5 block">{{ $totalSemesters }}</span>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <a href="#edit-master-course-section" 
                               onclick="document.getElementById('edit-master-course-section').scrollIntoView({behavior: 'smooth'}); return false;"
                               class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition text-center shadow-xs">
                                Edit Master Course
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SUCCESS & ERROR ALERTS -->
            @if (session('success'))
                <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold shadow-xs">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-emerald-600 flex-shrink-0"><path d="M20 6 9 17l-5-5"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-center gap-3 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-semibold shadow-xs">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-rose-600 flex-shrink-0"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- TARGET SKILLS & TAGS CARD -->
            <div class="bg-white border border-slate-200/80 shadow-xs rounded-2xl p-6 space-y-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Skill & Tag Target Kompetensi Matkul</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar skill utama dan tag sub-topik yang diujikan dalam mata kuliah ini.</p>
                </div>

                <div class="p-4 bg-slate-50/80 rounded-xl border border-slate-100 space-y-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Target Skill & Tag Aktif:</span>
                    <div class="flex flex-wrap gap-1.5">
                        @forelse($masterCourse->skills as $s)
                            <span class="px-2.5 py-1 bg-amber-50 text-amber-900 border border-amber-200 text-xs font-bold rounded-lg">
                                Main Skill: {{ $s->name }}
                            </span>
                        @empty
                        @endforelse

                        @forelse($masterCourse->tags as $t)
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-700 border border-slate-200 text-xs font-semibold rounded-lg">
                                Tag: {{ $t->name }}
                            </span>
                        @empty
                        @endforelse

                        @if($masterCourse->skills->isEmpty() && $masterCourse->tags->isEmpty())
                            <span class="text-xs text-slate-400 italic">Belum ada Skill atau Tag yang dihubungkan ke Master Course ini. Gunakan form di bawah untuk menentukan target kompetensi.</span>
                        @endif
                    </div>
                </div>

                <!-- UPDATE SKILLS & TAGS FORM -->
                <form action="{{ route('admin.master-courses.competencies.sync', $masterCourse) }}" method="POST" class="pt-4 border-t border-slate-100 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wider">Pilih Skill Induk Kurikulum:</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                            @foreach($allSkills as $skill)
                                <label class="flex items-center gap-2 p-2.5 bg-slate-50 hover:bg-slate-100/80 rounded-xl border border-slate-200/80 cursor-pointer text-xs font-semibold text-slate-800 transition">
                                    <input type="checkbox" name="skill_ids[]" value="{{ $skill->id }}" 
                                           {{ $masterCourse->skills->contains($skill->id) ? 'checked' : '' }} 
                                           class="rounded border-slate-300 text-blue-600 focus:ring-0">
                                    <span>{{ $skill->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                            Simpan Perubahan Skill & Tag
                        </button>
                    </div>
                </form>
            </div>

            <!-- OFFERINGS LIST BY SEMESTER -->
            <div class="bg-white border border-slate-200/80 shadow-xs rounded-2xl p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Daftar Rombel Kelas Paralel yang Dibuka</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Penawaran kelas perkuliahan aktif untuk mata kuliah ini di berbagai semester.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50/80 border-b border-slate-200/80 text-slate-400 font-bold uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3">Semester</th>
                                <th class="px-4 py-3">Nama Rombel / Kelas</th>
                                <th class="px-4 py-3">Dosen Pengampu</th>
                                <th class="px-4 py-3">Kuota</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($courseOfferings as $off)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-4 py-3.5 font-bold text-slate-800">
                                        {{ $off->academicTerm->name ?? 'Semester Non-Aktif' }}
                                    </td>
                                    <td class="px-4 py-3.5 font-extrabold text-slate-900">
                                        {{ $off->section_name }}
                                    </td>
                                    <td class="px-4 py-3.5 font-semibold text-slate-700">
                                        {{ $off->lecturer->name ?? 'Belum Ditugaskan' }}
                                    </td>
                                    <td class="px-4 py-3.5 font-bold text-slate-900">
                                        {{ $off->capacity }} Mhs
                                    </td>
                                    <td class="px-4 py-3.5">
                                        @if($off->status === 'published')
                                            <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Published
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> {{ ucfirst($off->status) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-xs text-slate-400 italic">
                                        Belum ada rombel kelas paralel yang dibuka untuk mata kuliah ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SECTION: EDIT MASTER COURSE ATTRIBUTES CARD -->
            <div id="edit-master-course-section" class="bg-white border border-slate-200/80 shadow-xs rounded-2xl p-6 space-y-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Edit Identitas & Syarat Kelulusan Master Course</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Perbarui nama mata kuliah, kode, deskripsi, level, serta threshold kelulusan sertifikat.</p>
                </div>

                <form action="{{ route('admin.master-courses.update', $masterCourse) }}" method="POST" class="space-y-4 pt-2">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kode Matkul <span class="text-rose-500">*</span></label>
                            <input type="text" name="code" value="{{ old('code', $masterCourse->code) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-0 text-xs font-bold" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Mata Kuliah <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $masterCourse->name) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-0 text-xs font-bold" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi Mata Kuliah</label>
                        <textarea name="description" rows="3" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-0 text-xs font-medium">{{ old('description', $masterCourse->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kategori Matkul <span class="text-rose-500">*</span></label>
                            <select name="category_id" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-0 text-xs font-bold" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $masterCourse->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Level Kesulitan <span class="text-rose-500">*</span></label>
                            <select name="level" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-0 text-xs font-bold" required>
                                <option value="Beginner" {{ $masterCourse->level === 'Beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="Intermediate" {{ $masterCourse->level === 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="Advanced" {{ $masterCourse->level === 'Advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Threshold Kelulusan (%) <span class="text-rose-500">*</span></label>
                            <input type="number" name="certificate_threshold" value="{{ old('certificate_threshold', $masterCourse->certificate_threshold ?? 75) }}" min="1" max="100" class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-0 text-xs font-bold" required>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                            Simpan Perubahan Identitas
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
