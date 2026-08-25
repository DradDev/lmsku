<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                </svg>
            </div>

            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    Edit Project
                </h2>
                <p class="text-sm text-gray-500">
                    Update project information, skill, tags, and publication status.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">
                        Project Information
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Complete project details so it can be accurately recommended to students.
                    </p>
                </div>

                <form action="{{ route('lecturer.projects.update', $project) }}" method="POST" class="p-5 md:p-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Project Title
                        </label>

                        <input type="text"
                               name="title"
                               value="{{ old('title', $project->title) }}"
                               class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                               placeholder="Example: Smart Contract Evaluation System"
                               required>

                        @error('title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="5"
                                  class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                  placeholder="Write a brief description about this project...">{{ old('description', $project->description) }}</textarea>

                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Difficulty Level
                            </label>

                            <div class="relative">
                                <select name="difficulty_level"
                                        class="w-full appearance-none rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm pr-10"
                                        required>
                                    <option value="Beginner" @selected(old('difficulty_level', $project->difficulty_level) === 'Beginner')>
                                        Beginner
                                    </option>
                                    <option value="Intermediate" @selected(old('difficulty_level', $project->difficulty_level) === 'Intermediate')>
                                        Intermediate
                                    </option>
                                    <option value="Advanced" @selected(old('difficulty_level', $project->difficulty_level) === 'Advanced')>
                                        Advanced
                                    </option>
                                </select>

                                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </div>
                            </div>

                            @error('difficulty_level')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Project Duration
                            </label>

                            <div class="relative">
                                <input type="number"
                                       name="duration_days"
                                       value="{{ old('duration_days', $project->duration_days) }}"
                                       min="1"
                                       class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm pr-16"
                                       required>

                                <span class="absolute inset-y-0 right-4 flex items-center text-sm text-gray-400">
                                    days
                                </span>
                            </div>

                            @error('duration_days')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @php
                        $selectedSkillIds = old('skill_ids', $projectSkillIds ?? []);
                        $selectedTagIds = old('tag_ids', $project->tags->pluck('id')->toArray());
                    @endphp

                    <!-- MULTI-SELECTION MAIN SKILLS (PILIH 2 ATAU LEBIH) -->
                    <div class="border border-blue-100 bg-gradient-to-br from-blue-50/60 to-indigo-50/30 rounded-2xl p-5 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-blue-200/60 pb-3">
                            <label class="block text-sm font-extrabold text-gray-900 flex items-center gap-2">
                                <span>Target Main Skills Utama (Pilih 1, 2, atau Lebih) <span class="text-rose-500">*</span></span>
                            </label>
                            <span id="selected_skills_count" class="text-xs font-bold text-blue-700 bg-blue-100 px-3 py-1 rounded-full border border-blue-200">
                                {{ count($selectedSkillIds) }} Main Skill Terpilih
                            </span>
                        </div>

                        <p class="text-xs text-gray-600 font-medium leading-relaxed">
                            Klik untuk memilih 1, 2, atau lebih Main Skill yang diuji pada project ini. Specialty Tags di bawah akan tersaring otomatis sesuai kombinasi Main Skill yang Anda pilih:
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
                                        class="skill-chip inline-flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-extrabold border transition-all text-left cursor-pointer {{ $isSkillSelected ? 'bg-blue-600 text-white border-blue-600 shadow-md ring-2 ring-blue-200' : 'bg-white text-slate-800 border-slate-200 hover:border-blue-400 hover:bg-blue-50' }}">
                                    <span>{{ $sk->name }}</span>
                                    <span class="chip-status text-xs font-black ml-1.5">{{ $isSkillSelected ? '✓' : '+' }}</span>
                                </button>
                            @endforeach
                        </div>
                        @error('skill_ids') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror

                        <div id="no_course_warning" class="hidden p-4 bg-amber-50 border border-amber-300 rounded-xl text-xs text-amber-900 space-y-1 shadow-sm">
                            <div class="flex items-center gap-2 font-bold text-amber-950">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-amber-600"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y3="13"/><line x1="12" y1="17" x2="12.01" y3="17"/></svg>
                                <span>Peringatan: Belum Ada Course Aktif untuk Main Skill yang Dipilih</span>
                            </div>
                            <p id="no_course_warning_text" class="leading-relaxed">
                                Terdapat Main Skill yang dipilih belum memiliki Course aktif di sistem.
                            </p>
                        </div>
                    </div>

                    <!-- DYNAMIC FILTERED SPECIALTY TAGS BASED ON SELECTED MAIN SKILLS -->
                    <div class="border border-gray-200 bg-white rounded-2xl p-5 shadow-sm space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3">
                            <div>
                                <label class="block text-sm font-bold text-gray-800">
                                    Tag Spesialisasi Project (Project Specialty Tags)
                                </label>
                                <p class="text-xs text-gray-500 mt-0.5">Tag spesialisasi yang relevan dengan Main Skill terpilih di atas.</p>
                            </div>

                            <span id="selected_tags_count" class="shrink-0 text-xs font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-200">
                                {{ count($selectedTagIds) }} Tag Terpilih
                            </span>
                        </div>

                        <!-- LIVE SELECTED TAGS PREVIEW PILLS -->
                        <div id="selected_tags_pills_bar" class="flex flex-wrap gap-1.5 p-3 bg-gray-50 rounded-xl border border-gray-200 min-h-[44px] items-center">
                            <span id="no_tags_placeholder" class="text-xs text-gray-400 italic {{ count($selectedTagIds) > 0 ? 'hidden' : '' }}">
                                Belum ada tag spesialisasi yang dipilih. Klik chip tag di bawah untuk memilih.
                            </span>
                        </div>

                        <!-- Search Filter Input Box -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"/>
                                    <path d="m21 21-4.3-4.3"/>
                                </svg>
                            </div>

                            <input type="text"
                                   id="custom_tag_search"
                                   autocomplete="off"
                                   placeholder="Ketik untuk mencari tag (misal: Flutter, Laravel, Figma, IoT, AI, Docker)..."
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-300 focus:border-blue-600 focus:ring-blue-600 text-sm shadow-sm transition">
                        </div>

                        <!-- Hidden Native Inputs for Form Submission -->
                        <div id="hidden_tags_container">
                            @foreach($selectedTagIds as $tagId)
                                <input type="hidden" name="tag_ids[]" value="{{ $tagId }}" id="hidden_tag_{{ $tagId }}">
                            @endforeach
                        </div>

                        <!-- Tags Chips Grid -->
                        <div id="tags_chips_grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 max-h-56 overflow-y-auto p-1">
                            @foreach($tags as $tg)
                                @php $isTagSelected = in_array($tg->id, $selectedTagIds); @endphp
                                <button type="button" 
                                        data-tag-id="{{ $tg->id }}"
                                        data-tag-skill-id="{{ $tg->skill_id ?? '' }}"
                                        data-tag-name="{{ strtolower($tg->name) }}"
                                        data-display-name="{{ $tg->name }}"
                                        onclick="toggleTagChip(this)"
                                        class="tag-chip inline-flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold border transition-all text-left cursor-pointer {{ $isTagSelected ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm ring-2 ring-indigo-200' : 'bg-white text-slate-700 border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/50' }}">
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
                            if (!container) return [];
                            const inputs = container.querySelectorAll('input[name="skill_ids[]"]');
                            return Array.from(inputs).map(inp => inp.value);
                        }

                        function filterSpecialtyTagsByMainSkills() {
                            const selectedSkillIds = getSelectedSkillIds();
                            const tagChips = document.querySelectorAll('.tag-chip');
                            const notice = document.getElementById('no_skills_selected_notice');
                            const searchInput = document.getElementById('custom_tag_search');
                            const query = searchInput ? searchInput.value.toLowerCase().trim() : '';

                            if (selectedSkillIds.length === 0) {
                                tagChips.forEach(chip => chip.classList.add('hidden'));
                                if (notice) notice.classList.remove('hidden');
                                return;
                            }

                            if (notice) notice.classList.add('hidden');
                            let visibleCount = 0;

                            tagChips.forEach(chip => {
                                const tagSkillId = chip.getAttribute('data-tag-skill-id');
                                const tagName = chip.getAttribute('data-tag-name') || '';
                                const matchesSkill = !tagSkillId || selectedSkillIds.includes(tagSkillId);
                                const matchesQuery = !query || tagName.includes(query);

                                if (matchesSkill && matchesQuery) {
                                    chip.classList.remove('hidden');
                                    visibleCount++;
                                } else {
                                    chip.classList.add('hidden');
                                }
                            });

                            if (visibleCount === 0 && !query) {
                                tagChips.forEach(chip => chip.classList.remove('hidden'));
                            }

                            checkMainSkillCourseWarning();
                        }

                        function toggleSkillChip(btn) {
                            const sId = btn.getAttribute('data-skill-id');
                            const container = document.getElementById('hidden_skills_container');
                            const existing = document.getElementById('hidden_skill_' + sId);

                            if (existing) {
                                existing.remove();
                                btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600', 'shadow-md', 'ring-2', 'ring-blue-200');
                                btn.classList.add('bg-white', 'text-slate-800', 'border-slate-200', 'hover:border-blue-400', 'hover:bg-blue-50');
                                btn.querySelector('.chip-status').textContent = '+';
                            } else {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'skill_ids[]';
                                input.value = sId;
                                input.id = 'hidden_skill_' + sId;
                                container.appendChild(input);

                                btn.classList.remove('bg-white', 'text-slate-800', 'border-slate-200', 'hover:border-blue-400', 'hover:bg-blue-50');
                                btn.classList.add('bg-blue-600', 'text-white', 'border-blue-600', 'shadow-md', 'ring-2', 'ring-blue-200');
                                btn.querySelector('.chip-status').textContent = '✓';
                            }

                            const count = container.querySelectorAll('input').length;
                            document.getElementById('selected_skills_count').textContent = count + ' Main Skill Terpilih';

                            // Dynamically filter specialty tags based on updated selected skills
                            filterSpecialtyTagsByMainSkills();
                        }

                        function toggleTagChip(btn) {
                            const tagId = btn.getAttribute('data-tag-id');
                            const container = document.getElementById('hidden_tags_container');
                            const existing = document.getElementById('hidden_tag_' + tagId);

                            if (existing) {
                                existing.remove();
                                btn.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-sm', 'ring-2', 'ring-indigo-200');
                                btn.classList.add('bg-white', 'text-slate-700', 'border-slate-200', 'hover:border-indigo-300', 'hover:bg-indigo-50/50');
                                btn.querySelector('.chip-status').textContent = '+';
                            } else {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'tag_ids[]';
                                input.value = tagId;
                                input.id = 'hidden_tag_' + tagId;
                                container.appendChild(input);

                                btn.classList.remove('bg-white', 'text-slate-700', 'border-slate-200', 'hover:border-indigo-300', 'hover:bg-indigo-50/50');
                                btn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-sm', 'ring-2', 'ring-indigo-200');
                                btn.querySelector('.chip-status').textContent = '✓';
                            }

                            updateTagCountAndPills();
                        }

                        function updateTagCountAndPills() {
                            const container = document.getElementById('hidden_tags_container');
                            const count = container ? container.querySelectorAll('input').length : 0;
                            const countSpan = document.getElementById('selected_tags_count');
                            const pillsBar = document.getElementById('selected_tags_pills_bar');
                            const placeholder = document.getElementById('no_tags_placeholder');

                            if (countSpan) {
                                countSpan.textContent = count + ' Tag Terpilih';
                            }

                            if (pillsBar) {
                                pillsBar.querySelectorAll('.active-tag-pill').forEach(el => el.remove());

                                const selectedInputs = container ? container.querySelectorAll('input') : [];
                                if (selectedInputs.length === 0) {
                                    if (placeholder) placeholder.classList.remove('hidden');
                                } else {
                                    if (placeholder) placeholder.classList.add('hidden');

                                    selectedInputs.forEach(input => {
                                        const tagId = input.value;
                                        const btn = document.querySelector(`.tag-chip[data-tag-id="${tagId}"]`);
                                        const tagName = btn ? btn.getAttribute('data-display-name') : ('Tag #' + tagId);

                                        const pill = document.createElement('span');
                                        pill.className = 'active-tag-pill inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-600 text-white shadow-sm cursor-pointer hover:bg-indigo-700 transition';
                                        pill.innerHTML = `#${tagName} <span class="hover:text-red-200 font-bold ml-1">✕</span>`;
                                        pill.onclick = function() {
                                            if (btn) toggleTagChip(btn);
                                        };
                                        pillsBar.appendChild(pill);
                                    });
                                }
                            }
                        }

                        const skillsWithoutCourses = @json($skillsWithoutCourses ?? []);
                        const warningBox = document.getElementById('no_course_warning');
                        const warningText = document.getElementById('no_course_warning_text');

                        function checkMainSkillCourseWarning() {
                            if (!warningBox) return;
                            const selectedIds = getSelectedSkillIds().map(x => parseInt(x));
                            const missing = selectedIds.filter(id => skillsWithoutCourses.includes(id));

                            if (missing.length > 0) {
                                warningBox.classList.remove('hidden');
                                if (warningText) {
                                    warningText.textContent = `Terdapat ${missing.length} Main Skill terpilih yang belum memiliki Course aktif di sistem. Mahasiswa belum bisa membangun prasyarat pendaftaran sebelum Course pembina skill dibuat.`;
                                }
                            } else {
                                warningBox.classList.add('hidden');
                            }
                        }

                        document.addEventListener('DOMContentLoaded', function () {
                            const searchInput = document.getElementById('custom_tag_search');
                            if (searchInput) {
                                searchInput.addEventListener('input', filterSpecialtyTagsByMainSkills);
                            }

                            filterSpecialtyTagsByMainSkills();
                            updateTagCountAndPills();
                        });
                    </script>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Maximum Student Quota
                            </label>

                            <input type="number"
                                   name="max_students"
                                   value="{{ old('max_students', $project->max_students ?? 1) }}"
                                   min="1"
                                   class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                   required>

                            @error('max_students')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror

                            <p class="text-xs text-gray-400 mt-1">
                                Ensure quota is not smaller than current enrolled students.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Publication Status
                            </label>

                            <label class="flex items-center justify-between gap-4 p-4 rounded-xl border border-gray-200 bg-gray-50 cursor-pointer hover:bg-blue-50 hover:border-blue-200 transition">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">
                                        Publish project to students
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        If active, the project will be visible to students.
                                    </p>
                                </div>

                                <input type="checkbox"
                                       name="is_published"
                                       value="1"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                       @checked(old('is_published', $project->is_published))>
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 pt-5 border-t border-gray-100">
                        <a href="{{ route('lecturer.projects.index') }}"
                           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 12H5" />
                                <path d="M12 19l-7-7 7-7" />
                            </svg>
                            Cancel
                        </a>

                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                            Update Project
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>