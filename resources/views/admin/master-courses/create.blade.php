<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- HERO HEADER CARD -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 5v14"/><path d="M5 12h14"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-extrabold text-xl text-slate-900 leading-tight">
                                Tambah Master Course Baru
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Buat templat mata kuliah kurikulum induk baru untuk perpustakaan LMS kampus.
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('admin.master-courses.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                        ← Kembali ke Katalog
                    </a>
                </div>
            </div>

            <!-- FORM CARD -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6 md:p-8">
                <form action="{{ route('admin.master-courses.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- ROW 1: BASIC METADATA -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Kode Mata Kuliah <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <input type="text"
                                   name="code"
                                   value="{{ old('code') }}"
                                   class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs font-bold"
                                   placeholder="Otomatis: TK-[SKILL]-[LEVEL]-001">
                            @error('code')
                                <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Level Kesulitan <span class="text-rose-500">*</span>
                            </label>
                            <select name="level" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs font-bold bg-white" required>
                                <option value="">-- Pilih Level --</option>
                                <option value="Beginner" {{ old('level') == 'Beginner' ? 'selected' : '' }}>Beginner (Dasar)</option>
                                <option value="Intermediate" {{ old('level') == 'Intermediate' ? 'selected' : '' }}>Intermediate (Menengah)</option>
                                <option value="Advanced" {{ old('level') == 'Advanced' ? 'selected' : '' }}>Advanced (Lanjut)</option>
                            </select>
                            @error('level')
                                <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Passing Score Sertifikat (%) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number"
                                   name="certificate_threshold"
                                   value="{{ old('certificate_threshold', 75) }}"
                                   min="1"
                                   max="100"
                                   class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs font-bold"
                                   placeholder="75"
                                   required>
                            @error('certificate_threshold')
                                <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- ROW 2: COURSE NAME & CATEGORY -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nama Mata Kuliah Induk <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs font-bold"
                                   placeholder="Contoh: Pemrograman Web Enterprise dengan Laravel"
                                   required>
                            @error('name')
                                <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kategori Kurikulum</label>
                            <select name="category_id" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs font-bold bg-white">
                                <option value="">-- Pilih Kategori (Opsional) --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- ROW 3: DYNAMIC MULTI-SKILL TO SUB-TAG COMPETENCY SECTION -->
                    <div class="p-5 bg-slate-50 border border-slate-200 rounded-2xl space-y-4">
                        <div class="border-b border-slate-200 pb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <h3 class="text-sm font-extrabold text-slate-900">
                                    Target Skill & Sub-Tag Kompetensi Course
                                </h3>
                                <p class="text-[11px] text-slate-500 mt-0.5">
                                    Centang satu atau beberapa Skill Utama di sebelah kiri. Kelompok Tag Sub-Topik yang relevan akan otomatis muncul di sebelah kanan.
                                </p>
                            </div>
                        </div>

                        <!-- MASTER-DETAIL GRID -->
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                            
                            <!-- LEFT COLUMN: SKILLS (5 COLS) -->
                            <div class="md:col-span-5 space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="text-xs font-extrabold text-slate-800">1. Pilih Skill Utama (Bisa Multiple):</label>
                                    <span id="create-skill-count-badge" class="px-2.5 py-0.5 bg-blue-100 text-blue-800 text-[11px] font-extrabold rounded-full">0 Terpilih</span>
                                </div>

                                <div class="max-h-80 overflow-y-auto border border-slate-300 rounded-xl p-2.5 bg-white space-y-2">
                                    @foreach($skills as $sk)
                                        @php
                                            $subTagCount = $tags->where('skill_id', $sk->id)->count();
                                            $isChecked = is_array(old('skill_ids')) && in_array($sk->id, old('skill_ids'));
                                        @endphp
                                        <label class="create-skill-checkbox-card flex items-center justify-between gap-2 p-2.5 bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-blue-300 transition text-xs font-semibold text-slate-800"
                                               data-create-skill-id="{{ $sk->id }}"
                                               style="{{ $isChecked ? 'background-color: #EFF6FF; border-color: #3B82F6;' : '' }}">
                                            <div class="flex items-center gap-2.5">
                                                <input type="checkbox"
                                                       name="skill_ids[]"
                                                       value="{{ $sk->id }}"
                                                       class="create-skill-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer"
                                                       data-skill-id="{{ $sk->id }}"
                                                       {{ $isChecked ? 'checked' : '' }}>
                                                <span class="font-bold text-slate-900">{{ $sk->name }}</span>
                                            </div>
                                            <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-md flex-shrink-0">
                                                {{ $subTagCount }} Tag
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- RIGHT COLUMN: TAG GROUPS (7 COLS) -->
                            <div class="md:col-span-7 space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="text-xs font-extrabold text-slate-800">2. Tag Sub-Topik (Otomatis Per Skill):</label>
                                    <span id="create-tag-count-badge" class="px-2.5 py-0.5 bg-indigo-100 text-indigo-800 text-[11px] font-extrabold rounded-full">0 Terpilih</span>
                                </div>

                                <!-- PESAN KOSONG -->
                                <div id="create-no-skill-notice" class="p-8 text-center bg-white border-2 border-dashed border-slate-300 rounded-xl text-xs text-slate-500 space-y-1">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2" class="mx-auto"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                                    <div class="font-bold text-slate-700">Pilih Skill Utama Terlebih Dahulu</div>
                                    <span>Centang minimal 1 Skill Utama di sebelah kiri untuk menampilkan daftar kelompok Tag Sub-Topik.</span>
                                </div>

                                <!-- TAG GROUPS WRAPPER -->
                                <div id="create-tags-wrapper" class="max-h-80 overflow-y-auto space-y-3" style="display: none;">
                                    @foreach($skills as $sk)
                                        @php
                                            $skillTags = $tags->where('skill_id', $sk->id);
                                        @endphp
                                        <div class="create-skill-tag-group-card bg-white border border-slate-200 rounded-xl overflow-hidden shadow-2xs"
                                             data-parent-skill-id="{{ $sk->id }}"
                                             style="display: none;">
                                            
                                            <!-- GROUP HEADER WITH QUICK ACTIONS -->
                                            <div class="bg-slate-100 px-3.5 py-2 border-b border-slate-200 text-xs font-extrabold text-slate-800 flex items-center justify-between">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="w-2 h-2 rounded-full bg-blue-600 inline-block"></span>
                                                    <span>TAG SUB-TOPIK: {{ strtoupper($sk->name) }}</span>
                                                </div>

                                                <div class="flex items-center gap-1.5">
                                                    <button type="button"
                                                            onclick="toggleCreateTagsInGroup({{ $sk->id }}, true)"
                                                            class="px-2 py-0.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-[10px] font-bold rounded transition">
                                                        Pilih Semua
                                                    </button>
                                                    <button type="button"
                                                            onclick="toggleCreateTagsInGroup({{ $sk->id }}, false)"
                                                            class="px-2 py-0.5 bg-slate-50 hover:bg-slate-200 text-slate-600 border border-slate-300 text-[10px] font-bold rounded transition">
                                                        Batal Semua
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- TAG CHECKBOXES GRID -->
                                            <div class="p-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                @forelse($skillTags as $tg)
                                                    @php
                                                        $isTagChecked = is_array(old('tag_ids')) && in_array($tg->id, old('tag_ids'));
                                                    @endphp
                                                    <label class="flex items-center gap-2 p-1.5 bg-slate-50 border border-slate-100 rounded-lg text-xs font-medium text-slate-700 cursor-pointer hover:bg-slate-100 transition">
                                                        <input type="checkbox"
                                                               name="tag_ids[]"
                                                               value="{{ $tg->id }}"
                                                               class="create-tag-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 cursor-pointer"
                                                               data-parent-skill-id="{{ $sk->id }}"
                                                               {{ $isTagChecked ? 'checked' : '' }}>
                                                        <span class="font-semibold text-slate-800">#{{ $tg->name }}</span>
                                                    </label>
                                                @empty
                                                    <span class="text-xs text-slate-400 italic col-span-2">Belum ada tag terdaftar di bawah skill ini.</span>
                                                @endforelse
                                            </div>

                                        </div>
                                    @endforeach
                                </div>

                            </div>

                        </div>
                    </div>

                    <!-- ROW 4: DESCRIPTION -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi Kurikulum Induk (Opsional)</label>
                        <textarea name="description"
                                  rows="3"
                                  class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs font-medium"
                                  placeholder="Jelaskan gambaran umum kurikulum, pokok bahasan, dan prasyarat mata kuliah ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SUBMIT BUTTONS -->
                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.master-courses.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                            Batal
                        </a>

                        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-xl shadow-md shadow-blue-500/20 transition">
                            Simpan Master Course
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- SCRIPT FILTERING & GROUPING DINAMIS MULTI-SKILL TO TAGS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const skillCbs = document.querySelectorAll('.create-skill-checkbox');
            const groupCards = document.querySelectorAll('.create-skill-tag-group-card');
            const tagsWrapper = document.getElementById('create-tags-wrapper');
            const noNotice = document.getElementById('create-no-skill-notice');
            const skillBadge = document.getElementById('create-skill-count-badge');
            const tagBadge = document.getElementById('create-tag-count-badge');

            function updateCreateDynamicForm() {
                const selectedSkillIds = Array.from(skillCbs)
                    .filter(cb => cb.checked)
                    .map(cb => cb.getAttribute('data-skill-id'));

                skillBadge.textContent = selectedSkillIds.length + ' Skill Terpilih';

                // Highlight active skill card UI
                document.querySelectorAll('.create-skill-checkbox-card').forEach(card => {
                    const skillId = card.getAttribute('data-create-skill-id');
                    if (selectedSkillIds.includes(skillId)) {
                        card.style.backgroundColor = '#EFF6FF';
                        card.style.borderColor = '#3B82F6';
                    } else {
                        card.style.backgroundColor = '#FFFFFF';
                        card.style.borderColor = '#E2E8F0';
                    }
                });

                if (selectedSkillIds.length === 0) {
                    noNotice.style.display = 'block';
                    tagsWrapper.style.display = 'none';
                    tagBadge.textContent = '0 Tag Terpilih';
                } else {
                    noNotice.style.display = 'none';
                    tagsWrapper.style.display = 'block';

                    let selectedTagCount = 0;

                    groupCards.forEach(card => {
                        const parentSkillId = card.getAttribute('data-parent-skill-id');
                        const tagCheckboxes = card.querySelectorAll('.create-tag-checkbox');

                        if (selectedSkillIds.includes(parentSkillId)) {
                            card.style.display = 'block';
                            tagCheckboxes.forEach(cb => {
                                if (cb.checked) selectedTagCount++;
                            });
                        } else {
                            card.style.display = 'none';
                            // Uncheck hidden tags automatically
                            tagCheckboxes.forEach(cb => {
                                cb.checked = false;
                            });
                        }
                    });

                    tagBadge.textContent = selectedTagCount + ' Tag Terpilih';
                }
            }

            window.toggleCreateTagsInGroup = function(skillId, selectAll) {
                const card = document.querySelector(`.create-skill-tag-group-card[data-parent-skill-id="${skillId}"]`);
                if (card) {
                    const tagCheckboxes = card.querySelectorAll('.create-tag-checkbox');
                    tagCheckboxes.forEach(cb => cb.checked = selectAll);
                    updateCreateDynamicForm();
                }
            };

            skillCbs.forEach(cb => {
                cb.addEventListener('change', updateCreateDynamicForm);
            });

            document.querySelectorAll('.create-tag-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    const checkedTags = document.querySelectorAll('.create-tag-checkbox:checked');
                    tagBadge.textContent = checkedTags.length + ' Tag Terpilih';
                });
            });

            // Run initial sync on page load
            updateCreateDynamicForm();
        });
    </script>
</x-app-layout>
