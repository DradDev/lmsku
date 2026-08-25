<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 20h9"/>
                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                </svg>
            </div>

            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    Edit Project (Mitra Vendor Industri) — {{ $project->title }}
                </h2>
                <p class="text-sm text-gray-500">
                    Perbarui rincian project, target skill utama, tag spesialisasi, level kesulitan, durasi, dan status publikasi.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">
                                Project Information
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Kelola target kompetensi utama dan syarat kelayakan pendaftaran mahasiswa.
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('vendor.projects.update', $project) }}" method="POST" enctype="multipart/form-data" class="p-5 md:p-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Project Title / Judul Project Real Client
                            </label>

                            <input type="text"
                                   name="title"
                                   value="{{ old('title', $project->title) }}"
                                   class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500 text-sm"
                                   required>

                            @error('title')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Asal Provider Project
                            </label>

                            <div class="relative">
                                <select name="provider_type" class="w-full appearance-none rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500 text-sm pr-10 bg-slate-50">
                                    <option value="external" selected>External Mitra Vendor Industri</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Description / Deskripsi & Deliverables Project
                        </label>

                        <textarea name="description"
                                  rows="4"
                                  class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500 text-sm"
                                  required>{{ old('description', $project->description) }}</textarea>

                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Benefit & Output Untuk Mahasiswa (Opsional)
                            </label>

                            <textarea name="benefits"
                                      rows="3"
                                      class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500 text-sm"
                                      placeholder="Contoh: Insentif bulanan, Sertifikat Pendamping Portofolio Resmi, Surat Rekomendasi Industri...">{{ old('benefits', $project->benefits) }}</textarea>

                            @error('benefits')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                TOR / Project Brief File (Opsional)
                            </label>

                            @if($project->brief_file_url)
                                <div class="mb-2 p-2.5 bg-slate-50 rounded-xl border border-slate-200 text-xs flex items-center justify-between">
                                    <span class="font-bold text-slate-700">File TOR Saat Ini Tersimpan</span>
                                    <a href="{{ asset($project->brief_file_url) }}" target="_blank" class="text-purple-600 font-extrabold hover:underline">Unduh Brief ↗</a>
                                </div>
                            @endif

                            <input type="file"
                                   name="brief_file"
                                   accept=".pdf,.doc,.docx,.zip"
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 border border-gray-300 rounded-xl cursor-pointer">

                            <p class="text-xs text-gray-400 mt-1">Pilih file baru jika ingin mengganti TOR (PDF/ZIP, Max 10MB).</p>

                            @error('brief_file')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Level Kesulitan Project
                            </label>

                            <select name="difficulty_level" class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500 text-sm">
                                <option value="Beginner" @selected(old('difficulty_level', $project->difficulty_level) === 'Beginner')>Beginner</option>
                                <option value="Intermediate" @selected(old('difficulty_level', $project->difficulty_level) === 'Intermediate')>Intermediate</option>
                                <option value="Advanced" @selected(old('difficulty_level', $project->difficulty_level) === 'Advanced')>Advanced</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Estimasi Pengerjaan (Hari)
                            </label>

                            <input type="number"
                                   name="duration_days"
                                   value="{{ old('duration_days', $project->duration_days) }}"
                                   min="1"
                                   class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500 text-sm"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Kuota Maksimal Mahasiswa
                            </label>

                            <input type="number"
                                   name="max_students"
                                   value="{{ old('max_students', $project->max_students) }}"
                                   min="1"
                                   class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500 text-sm"
                                   required>
                        </div>
                    </div>

                    <!-- MULTI-SELECTION MAIN SKILLS -->
                    @php
                        $selectedSkillIds = old('skill_ids', $projectSkillIds ?? []);
                    @endphp
                    <div class="border border-purple-200 bg-gradient-to-br from-purple-50/60 to-indigo-50/30 rounded-2xl p-5 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-purple-200/60 pb-3">
                            <label class="block text-sm font-extrabold text-gray-900 flex items-center gap-2">
                                <span>Target Main Skills Utama (Pilih 2 atau Lebih) <span class="text-rose-500">*</span></span>
                            </label>
                            <span id="selected_skills_count" class="text-xs font-bold text-purple-700 bg-purple-100 px-3 py-1 rounded-full border border-purple-200">
                                {{ count($selectedSkillIds) }} Main Skill Terpilih
                            </span>
                        </div>

                        <p class="text-xs text-gray-600 font-medium leading-relaxed">
                            Klik untuk memilih 1, 2, atau lebih Main Skill. Specialty Tags di bawah akan tersaring otomatis sesuai kombinasi Main Skill yang Anda pilih:
                        </p>

                        <!-- Hidden Native Inputs Container for Skills -->
                        <div id="hidden_skills_container">
                            @foreach($selectedSkillIds as $sId)
                                <input type="hidden" name="skill_ids[]" value="{{ $sId }}" id="hidden_skill_{{ $sId }}">
                            @endforeach
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5 max-h-56 overflow-y-auto p-1">
                            @foreach($mainSkills as $sk)
                                @php $isSkillSelected = in_array($sk->id, $selectedSkillIds); @endphp
                                <button type="button" 
                                        data-skill-id="{{ $sk->id }}"
                                        data-skill-name="{{ $sk->name }}"
                                        onclick="toggleSkillChip(this)"
                                        class="skill-chip inline-flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-extrabold border transition-all text-left cursor-pointer {{ $isSkillSelected ? 'bg-purple-700 text-white border-purple-700 shadow-md ring-2 ring-purple-300' : 'bg-white text-slate-800 border-slate-200 hover:border-purple-400 hover:bg-purple-50' }}">
                                    <span>{{ $sk->name }}</span>
                                    <span class="chip-status text-xs font-black ml-1.5">{{ $isSkillSelected ? '✓' : '+' }}</span>
                                </button>
                            @endforeach
                        </div>
                        @error('skill_ids') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- DYNAMIC FILTERED SPECIALTY TAGS BASED ON SELECTED MAIN SKILLS -->
                    @php
                        $selectedTagIds = old('tag_ids', $project->tags->pluck('id')->toArray());
                    @endphp
                    <div class="border border-gray-200 bg-white rounded-2xl p-5 shadow-sm space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3">
                            <div>
                                <label class="block text-sm font-bold text-gray-800">
                                    Specialty Tags (Tersaring Otomatis dari Main Skill Terpilih)
                                </label>
                                <p class="text-xs text-gray-500 mt-0.5">Tag spesialisasi yang relevan dengan Main Skill terpilih di atas.</p>
                            </div>

                            <span id="selected_tags_count" class="shrink-0 text-xs font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-200">
                                {{ count($selectedTagIds) }} Tag Terpilih
                            </span>
                        </div>

                        <!-- Hidden Native Inputs Container for Tags -->
                        <div id="hidden_tags_container">
                            @foreach($selectedTagIds as $tId)
                                <input type="hidden" name="tag_ids[]" value="{{ $tId }}" id="hidden_tag_{{ $tId }}">
                            @endforeach
                        </div>

                        <div id="tags_chips_grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 max-h-56 overflow-y-auto p-1">
                            @foreach($tags as $tg)
                                @php $isTagSelected = in_array($tg->id, $selectedTagIds); @endphp
                                <button type="button" 
                                        data-tag-id="{{ $tg->id }}"
                                        data-tag-skill-id="{{ $tg->skill_id ?? '' }}"
                                        data-tag-name="{{ $tg->name }}"
                                        onclick="toggleTagChip(this)"
                                        class="tag-chip inline-flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold border transition-all text-left cursor-pointer {{ $isTagSelected ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm' : 'bg-white text-slate-700 border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/50' }}">
                                    <span>#{{ $tg->name }}</span>
                                    <span class="chip-status text-[11px] font-extrabold ml-1">{{ $isTagSelected ? '✓' : '+' }}</span>
                                </button>
                            @endforeach
                        </div>

                        <p id="no_skills_selected_notice" class="hidden text-xs text-gray-400 italic text-center py-3">
                            Pilih setidaknya 1 Target Main Skill pada bagian atas untuk menampilkan Specialty Tags yang relevan.
                        </p>
                    </div>

                    <script>
                        function getSelectedSkillIds() {
                            const container = document.getElementById('hidden_skills_container');
                            const inputs = container.querySelectorAll('input[name="skill_ids[]"]');
                            return Array.from(inputs).map(inp => inp.value);
                        }

                        function filterSpecialtyTagsByMainSkills() {
                            const selectedSkillIds = getSelectedSkillIds();
                            const tagChips = document.querySelectorAll('.tag-chip');
                            const notice = document.getElementById('no_skills_selected_notice');

                            if (selectedSkillIds.length === 0) {
                                tagChips.forEach(chip => chip.classList.add('hidden'));
                                notice.classList.remove('hidden');
                                return;
                            }

                            notice.classList.add('hidden');
                            let visibleCount = 0;

                            tagChips.forEach(chip => {
                                const tagSkillId = chip.getAttribute('data-tag-skill-id');
                                if (!tagSkillId || selectedSkillIds.includes(tagSkillId)) {
                                    chip.classList.remove('hidden');
                                    visibleCount++;
                                } else {
                                    chip.classList.add('hidden');
                                }
                            });

                            if (visibleCount === 0) {
                                tagChips.forEach(chip => chip.classList.remove('hidden'));
                            }
                        }

                        function toggleSkillChip(btn) {
                            const sId = btn.getAttribute('data-skill-id');
                            const container = document.getElementById('hidden_skills_container');
                            const existing = document.getElementById('hidden_skill_' + sId);

                            if (existing) {
                                existing.remove();
                                btn.classList.remove('bg-purple-700', 'text-white', 'border-purple-700', 'shadow-md', 'ring-2', 'ring-purple-300');
                                btn.classList.add('bg-white', 'text-slate-800', 'border-slate-200', 'hover:border-purple-400', 'hover:bg-purple-50');
                                btn.querySelector('.chip-status').textContent = '+';
                            } else {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'skill_ids[]';
                                input.value = sId;
                                input.id = 'hidden_skill_' + sId;
                                container.appendChild(input);

                                btn.classList.remove('bg-white', 'text-slate-800', 'border-slate-200', 'hover:border-purple-400', 'hover:bg-purple-50');
                                btn.classList.add('bg-purple-700', 'text-white', 'border-purple-700', 'shadow-md', 'ring-2', 'ring-purple-300');
                                btn.querySelector('.chip-status').textContent = '✓';
                            }

                            const count = container.querySelectorAll('input').length;
                            document.getElementById('selected_skills_count').textContent = count + ' Main Skill Terpilih';

                            // Dynamically filter specialty tags based on updated selected skills!
                            filterSpecialtyTagsByMainSkills();
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

                        // Run dynamic filter on page load
                        document.addEventListener('DOMContentLoaded', filterSpecialtyTagsByMainSkills);
                    </script>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Status Publikasi Project
                            </label>

                            <div class="relative">
                                <select name="is_published" class="w-full appearance-none rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500 text-sm pr-10 font-bold text-gray-800">
                                    <option value="1" @selected(old('is_published', $project->is_published) == 1)>Published (Terbuka untuk Talent Matching)</option>
                                    <option value="0" @selected(old('is_published', $project->is_published) == 0)>Project Bank (Draft Internal)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('vendor.projects.show', $project) }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition">
                            Batal
                        </a>

                        <button type="submit" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-sm rounded-xl shadow-sm transition">
                            Simpan Perubahan Project
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
