<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-4xl mx-auto px-6">

            @if (session('success'))
                <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-sm text-sm font-medium">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-emerald-900">Berhasil!</p>
                        <p class="mt-0.5 text-emerald-700 text-xs sm:text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl shadow-sm text-sm">
                    <div class="flex items-center gap-2 font-bold mb-1 text-rose-900">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        Mohon periksa kembali kesalahan berikut:
                    </div>
                    <ul class="list-disc pl-6 space-y-1 text-rose-700 text-xs">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- BREADCRUMB & HEADER -->
            <div class="mb-8">
                <a href="{{ route('vendor.courses.show', $course) }}"
                    class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-700 transition mb-3">
                    ← Kembali ke Detail Course
                </a>

                <p class="text-xs font-bold uppercase tracking-[0.2em] text-purple-600 mb-1">
                    Portal Author Mitra Vendor &bull; Certification Editor
                </p>

                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">
                    ✏️ Edit Course Sertifikasi — {{ $course->name }}
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    Perbarui informasi silabus, threshold sertifikat, durasi pelatihan, dan target kompetensi utama.
                </p>
            </div>

            <!-- FORM CARD CONTAINER -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 md:p-8">
                <form action="{{ route('vendor.courses.update', $course) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- GROUP 1: INFORMASI UTAMA -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">
                            📋 1. Informasi Utama Pelatihan
                        </h3>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Nama Course Sertifikasi <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $course->name) }}" required
                                   class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-3 font-semibold text-slate-900">
                            @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Level Kesulitan <span class="text-rose-500">*</span>
                                </label>
                                <select name="level" required class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-3 font-semibold text-slate-800">
                                    <option value="Beginner" @selected(old('level', $course->level) === 'Beginner')>Beginner</option>
                                    <option value="Intermediate" @selected(old('level', $course->level) === 'Intermediate')>Intermediate</option>
                                    <option value="Advanced" @selected(old('level', $course->level) === 'Advanced')>Advanced</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Kategori Keterampilan
                                </label>
                                <select name="category_id" class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-3 font-semibold text-slate-800">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" @selected(old('category_id', $course->category_id) == $cat->id)>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Estimasi Durasi (Minggu)
                                </label>
                                <div class="relative">
                                    <input type="number" name="duration_weeks" value="{{ old('duration_weeks', $course->duration_weeks ?? 4) }}" min="1" required
                                           class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-3 font-semibold text-slate-900 pr-16">
                                    <span class="absolute right-3 top-3 text-xs font-bold text-slate-400">minggu</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Passing Grade Kuis Penentu Sertifikat (%) <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="certificate_threshold" value="{{ old('certificate_threshold', $course->certificate_threshold ?? 75) }}" required min="0" max="100"
                                           class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-3 font-extrabold text-purple-900 pr-10">
                                    <span class="absolute right-3 top-3 text-xs font-bold text-slate-400">%</span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1">Batas nilai minimal Kuis Akhir untuk klaim Sertifikat Digital.</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Status Publikasi Course
                                </label>
                                <select name="is_archived" class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-3 font-bold text-purple-900">
                                    <option value="0" @selected(old('is_archived', $course->is_archived) == 0)>🟢 Active Course (Terbuka Dipublikasikan)</option>
                                    <option value="1" @selected(old('is_archived', $course->is_archived) == 1)>🔴 Project Bank (Draft Internal)</option>
                                </select>
                                <p class="text-[11px] text-slate-500 mt-1">Dapat diubah kapan saja via halaman detail course.</p>
                            </div>
                        </div>
                    </div>

                    <!-- GROUP 2: DESKRIPSI & SILABUS -->
                    <div class="space-y-4 pt-2">
                        <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">
                            📖 2. Deskripsi & Silabus Pelatihan
                        </h3>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Deskripsi Ringkas & Pokok Bahasan Materi <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="description" rows="4" required 
                                      class="w-full border-slate-300 focus:border-purple-500 focus:ring-purple-500 rounded-xl text-xs p-3 font-medium text-slate-800 leading-relaxed">{{ old('description', $course->description) }}</textarea>
                            @error('description') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- GROUP 3: TARGET SKILLS KOMPETENSI UTAMA -->
                    @php
                        $selectedSkillIds = old('skill_ids', $course->skills->pluck('id')->toArray());
                    @endphp
                    <div class="space-y-4 pt-2">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                                <span>⚡ 3. Target Skill Utama Kompetensi</span>
                            </h3>
                            <span id="selected_skills_count" class="text-xs font-bold text-purple-700 bg-purple-50 px-3 py-1 rounded-full border border-purple-200">
                                {{ count($selectedSkillIds) }} Skill Terpilih
                            </span>
                        </div>

                        <div class="border border-slate-200 bg-slate-50/50 rounded-2xl p-4 space-y-3">
                            <p class="text-xs text-slate-500">Pilih skill kompetensi utama yang diuji oleh course sertifikasi ini:</p>

                            <!-- Hidden Native Inputs Container -->
                            <div id="hidden_skills_container">
                                @foreach($selectedSkillIds as $sId)
                                    <input type="hidden" name="skill_ids[]" value="{{ $sId }}" id="hidden_skill_{{ $sId }}">
                                @endforeach
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-48 overflow-y-auto p-1">
                                @foreach($skills as $sk)
                                    @php $isSkillSelected = in_array($sk->id, $selectedSkillIds); @endphp
                                    <button type="button" 
                                            data-skill-id="{{ $sk->id }}"
                                            data-skill-name="{{ $sk->name }}"
                                            onclick="toggleSkillChip(this)"
                                            class="skill-chip inline-flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold border transition-all text-left cursor-pointer {{ $isSkillSelected ? 'bg-purple-600 text-white border-purple-600 shadow-sm' : 'bg-white text-slate-700 border-slate-200 hover:border-purple-300 hover:bg-purple-50/50' }}">
                                        <span>⚡ {{ $sk->name }}</span>
                                        <span class="chip-status text-[11px] font-extrabold ml-1">{{ $isSkillSelected ? '✓' : '+' }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- GROUP 4: SPECIALTY TAGS -->
                    @php
                        $selectedTagIds = old('tag_ids', $course->tags->pluck('id')->toArray());
                    @endphp
                    <div class="space-y-4 pt-2">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">
                                🏷️ 4. Tag Spesialisasi Pendukung (Opsional)
                            </h3>
                            <span id="selected_tags_count" class="text-xs font-bold text-purple-700 bg-purple-50 px-3 py-1 rounded-full border border-purple-200">
                                {{ count($selectedTagIds) }} Tag Terpilih
                            </span>
                        </div>

                        <div class="border border-slate-200 bg-slate-50/50 rounded-2xl p-4 space-y-3">
                            <!-- Hidden Native Inputs Container -->
                            <div id="hidden_tags_container">
                                @foreach($selectedTagIds as $tId)
                                    <input type="hidden" name="tag_ids[]" value="{{ $tId }}" id="hidden_tag_{{ $tId }}">
                                @endforeach
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 max-h-48 overflow-y-auto p-1">
                                @foreach($tags as $tg)
                                    @php $isTagSelected = in_array($tg->id, $selectedTagIds); @endphp
                                    <button type="button" 
                                            data-tag-id="{{ $tg->id }}"
                                            data-tag-name="{{ $tg->name }}"
                                            onclick="toggleTagChip(this)"
                                            class="tag-chip inline-flex items-center justify-between px-3 py-1.5 rounded-xl text-xs font-bold border transition-all text-left cursor-pointer {{ $isTagSelected ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm' : 'bg-white text-slate-700 border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/50' }}">
                                        <span>#{{ $tg->name }}</span>
                                        <span class="chip-status text-[11px] font-extrabold ml-1">{{ $isTagSelected ? '✓' : '+' }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <script>
                        function toggleSkillChip(btn) {
                            const sId = btn.getAttribute('data-skill-id');
                            const container = document.getElementById('hidden_skills_container');
                            const existing = document.getElementById('hidden_skill_' + sId);

                            if (existing) {
                                existing.remove();
                                btn.classList.remove('bg-purple-600', 'text-white', 'border-purple-600', 'shadow-sm');
                                btn.classList.add('bg-white', 'text-slate-700', 'border-slate-200', 'hover:border-purple-300', 'hover:bg-purple-50/50');
                                btn.querySelector('.chip-status').textContent = '+';
                            } else {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'skill_ids[]';
                                input.value = sId;
                                input.id = 'hidden_skill_' + sId;
                                container.appendChild(input);

                                btn.classList.remove('bg-white', 'text-slate-700', 'border-slate-200', 'hover:border-purple-300', 'hover:bg-purple-50/50');
                                btn.classList.add('bg-purple-600', 'text-white', 'border-purple-600', 'shadow-sm');
                                btn.querySelector('.chip-status').textContent = '✓';
                            }

                            const count = container.querySelectorAll('input').length;
                            document.getElementById('selected_skills_count').textContent = count + ' Skill Terpilih';
                        }

                        function toggleTagChip(btn) {
                            const tId = btn.getAttribute('data-tag-id');
                            const container = document.getElementById('hidden_tags_container');
                            const existing = document.getElementById('hidden_tag_' + tId);

                            if (existing) {
                                existing.remove();
                                btn.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-sm');
                                btn.classList.add('bg-white', 'text-slate-700', 'border-slate-200', 'hover:border-indigo-300', 'hover:bg-indigo-50/50');
                                btn.querySelector('.chip-status').textContent = '+';
                            } else {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'tag_ids[]';
                                input.value = tId;
                                input.id = 'hidden_tag_' + tId;
                                container.appendChild(input);

                                btn.classList.remove('bg-white', 'text-slate-700', 'border-slate-200', 'hover:border-indigo-300', 'hover:bg-indigo-50/50');
                                btn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-sm');
                                btn.querySelector('.chip-status').textContent = '✓';
                            }

                            const count = container.querySelectorAll('input').length;
                            document.getElementById('selected_tags_count').textContent = count + ' Tag Terpilih';
                        }
                    </script>

                    <!-- ACTION BUTTONS -->
                    <div class="pt-6 border-t border-slate-200 flex items-center justify-end gap-3">
                        <a href="{{ route('vendor.courses.show', $course) }}" 
                           class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                            💾 Simpan Perubahan Course
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
