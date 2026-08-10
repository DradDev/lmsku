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
                    Add New Project
                </h2>
                <p class="text-sm text-gray-500">
                    Create a new project to match with qualified students.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">
                                Project Information
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Complete project details, select main skill, tags, difficulty level, duration, and publication status.
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-xs space-y-1">
                        <div class="font-bold flex items-center gap-1.5 text-sm text-blue-950">
                            <span>🛡️ Syarat Kelayakan Pendaftaran Mahasiswa (Automated System)</span>
                        </div>
                        <p class="leading-relaxed">
                            Project ini nantinya akan dibuka untuk mahasiswa yang telah <strong>Lulus Final Quiz & Memiliki Sertifikat Terverifikasi</strong> pada Mata Kuliah pembina <strong>Main Skill</strong> yang Anda tentukan di bawah ini.
                        </p>
                    </div>
                </div>

                <form action="{{ route('lecturer.projects.store') }}" method="POST" class="p-5 md:p-6 space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Project Title
                        </label>

                        <input type="text"
                               name="title"
                               value="{{ old('title') }}"
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
                                  placeholder="Write a brief description about this project...">{{ old('description') }}</textarea>

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
                                    <option value="Beginner" @selected(old('difficulty_level') === 'Beginner')>
                                        Beginner
                                    </option>
                                    <option value="Intermediate" @selected(old('difficulty_level') === 'Intermediate')>
                                        Intermediate
                                    </option>
                                    <option value="Advanced" @selected(old('difficulty_level') === 'Advanced')>
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
                                       value="{{ old('duration_days', 7) }}"
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
                    </div>

                    @php
                        $selectedSkillIds = old('skill_ids', []);
                        $selectedTagIds = old('tag_ids', []);
                        $mainSkillId = old('main_skill_id');
                    @endphp

                    <div class="border-t border-gray-100 pt-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Primary Skill Requirement (Main Skill)
                        </label>

                        <div class="relative">
                            <select id="main_skill_select"
                                    name="main_skill_id"
                                    class="w-full appearance-none rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm pr-10"
                                    required>
                                <option value="">-- Select Primary Skill --</option>

                                @foreach ($mainSkills as $mainSkill)
                                    <option value="{{ $mainSkill->id }}"
                                            @selected((int) $mainSkillId === (int) $mainSkill->id)>
                                        {{ $mainSkill->name }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </div>
                        </div>

                        <p class="text-xs text-gray-400 mt-1">
                            Select the required primary skill. Students who pass the final quiz in this skill can apply for the project.
                        </p>

                        <div id="no_course_warning" class="hidden mt-3 p-4 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 space-y-1">
                            <div class="flex items-center gap-2 font-bold text-amber-900">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-amber-600"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y3="13"/><line x1="12" y1="17" x2="12.01" y3="17"/></svg>
                                <span>⚠️ Peringatan: Belum Ada Course Aktif untuk Main Skill Ini</span>
                            </div>
                            <p>
                                Belum terdapat Course aktif di sistem yang menguji kompetensi Main Skill ini. Project ini tetap bisa Anda simpan/upload, namun mahasiswa tidak akan bisa memenuhi prasyarat kompetensi project ini sebelum Course terkait dibuat.
                            </p>
                            <p class="font-semibold text-amber-900 pt-1">
                                💡 Disarankan untuk membuat Course baru dengan kuis kelulusan (passing score) untuk Main Skill ini terlebih dahulu!
                            </p>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Project Specialty Tags
                            </label>

                            <span id="selected_tags_count" class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full border border-indigo-100">
                                {{ count($selectedTagIds) }} tags selected
                            </span>
                        </div>

                        <!-- Search Filter Input Box -->
                        <div class="relative mb-3">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"/>
                                    <path d="m21 21-4.3-4.3"/>
                                </svg>
                            </div>

                            <input type="text"
                                   id="custom_tag_search"
                                   autocomplete="off"
                                   placeholder="Search tags (e.g. Computer Vision, Cybersecurity, Web Development, Microcontrollers, Animation)..."
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm transition">
                        </div>

                        <!-- Hidden Native Inputs for Form Submission -->
                        <div id="hidden_tags_container">
                            @foreach($selectedTagIds as $tagId)
                                <input type="hidden" name="tag_ids[]" value="{{ $tagId }}" id="hidden_tag_{{ $tagId }}">
                            @endforeach
                        </div>

                        <!-- Custom Tag Picker Container -->
                        <div class="border border-gray-200 rounded-2xl bg-gray-50/50 p-4 max-h-[260px] overflow-y-auto space-y-4 shadow-inner">
                            @foreach ($tags->groupBy(fn($t) => $t->skill->name ?? 'General') as $skillName => $skillTags)
                                <div class="tag-group-wrapper" data-skill-id="{{ $skillTags->first()->skill_id ?? '' }}">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-md border border-indigo-100/60">
                                            Skill: {{ $skillName }}
                                        </span>
                                        <span class="h-px bg-gray-200 flex-1"></span>
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($skillTags as $tag)
                                            @php $isSelected = in_array($tag->id, $selectedTagIds); @endphp
                                            <button type="button"
                                                    data-tag-id="{{ $tag->id }}"
                                                    data-tag-name="{{ strtolower($tag->name) }}"
                                                    data-skill-id="{{ $tag->skill_id }}"
                                                    onclick="toggleTagChip(this)"
                                                    class="tag-chip inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold border transition cursor-pointer select-none {{ $isSelected ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm' : 'bg-white text-gray-700 border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/50' }}">
                                                <span class="chip-icon">{{ $isSelected ? '✓' : '+' }}</span>
                                                <span>{{ $tag->name }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <p class="text-xs text-gray-400 mt-2">
                            Click tag badges to select or deselect them. Type in the search box to filter tags.
                        </p>
                    </div>

                    <script>
                        function toggleTagChip(btn) {
                            const tagId = btn.getAttribute('data-tag-id');
                            const container = document.getElementById('hidden_tags_container');
                            const existingHidden = document.getElementById('hidden_tag_' + tagId);

                            if (existingHidden) {
                                existingHidden.remove();
                                btn.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-sm');
                                btn.classList.add('bg-white', 'text-gray-700', 'border-gray-200', 'hover:border-indigo-300', 'hover:bg-indigo-50/50');
                                btn.querySelector('.chip-icon').textContent = '+';
                            } else {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'tag_ids[]';
                                input.value = tagId;
                                input.id = 'hidden_tag_' + tagId;
                                container.appendChild(input);

                                btn.classList.remove('bg-white', 'text-gray-700', 'border-gray-200', 'hover:border-indigo-300', 'hover:bg-indigo-50/50');
                                btn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-sm');
                                btn.querySelector('.chip-icon').textContent = '✓';
                            }

                            updateTagCount();
                        }

                        function updateTagCount() {
                            const container = document.getElementById('hidden_tags_container');
                            const count = container ? container.querySelectorAll('input').length : 0;
                            const countSpan = document.getElementById('selected_tags_count');
                            if (countSpan) {
                                countSpan.textContent = count + ' tags selected';
                            }
                        }

                        document.addEventListener('DOMContentLoaded', function () {
                            const mainSkillSelect = document.getElementById('main_skill_select');
                            const searchInput = document.getElementById('custom_tag_search');

                            function filterCustomTags() {
                                const selectedSkillId = mainSkillSelect ? mainSkillSelect.value : '';
                                const query = searchInput ? searchInput.value.toLowerCase().trim() : '';

                                const groupWrappers = document.querySelectorAll('.tag-group-wrapper');

                                groupWrappers.forEach(group => {
                                    const groupSkillId = group.getAttribute('data-skill-id');
                                    const chips = group.querySelectorAll('.tag-chip');
                                    let visibleChipCount = 0;

                                    const matchesMainSkill = query ? true : (!selectedSkillId || groupSkillId === selectedSkillId);

                                    chips.forEach(chip => {
                                        const tagName = chip.getAttribute('data-tag-name');
                                        const matchesQuery = !query || tagName.includes(query);

                                        if (matchesMainSkill && matchesQuery) {
                                            chip.style.display = 'inline-flex';
                                            visibleChipCount++;
                                        } else {
                                            chip.style.display = 'none';
                                        }
                                    });

                                    group.style.display = visibleChipCount > 0 ? 'block' : 'none';
                                });
                            }

                            const skillsWithoutCourses = @json($skillsWithoutCourses ?? []);
                            const warningBox = document.getElementById('no_course_warning');

                            function checkMainSkillCourseWarning() {
                                if (!mainSkillSelect || !warningBox) return;
                                const val = parseInt(mainSkillSelect.value);
                                if (val && skillsWithoutCourses.includes(val)) {
                                    warningBox.classList.remove('hidden');
                                } else {
                                    warningBox.classList.add('hidden');
                                }
                            }

                            if (mainSkillSelect) {
                                mainSkillSelect.addEventListener('change', function() {
                                    filterCustomTags();
                                    checkMainSkillCourseWarning();
                                });
                            }
                            if (searchInput) {
                                searchInput.addEventListener('input', filterCustomTags);
                            }

                            filterCustomTags();
                            checkMainSkillCourseWarning();
                        });
                    </script>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Maximum Student Quota
                            </label>

                            <input type="number"
                                   name="max_students"
                                   value="{{ old('max_students', 1) }}"
                                   min="1"
                                   class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                   required>

                            @error('max_students')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror

                            <p class="text-xs text-gray-400 mt-1">
                                Example: enter 1 if the project can only be taken by 1 student.
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
                                       @checked(old('is_published'))>
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
                                <path d="M12 5v14" />
                                <path d="M5 12h14" />
                            </svg>
                            Save Project
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>