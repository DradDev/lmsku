<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                </div>

                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">
                        Edit Course
                    </h2>
                    <p class="text-sm text-gray-500">
                        Update course details, main skill requirement, and specialty tags.
                    </p>
                </div>
            </div>

            <a href="{{ route('lecturer.courses.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-semibold rounded-xl shadow-sm transition">
                ← Back to Courses
            </a>
        </div>
    </x-slot>

    @php
    $selectedTagIds = array_map('intval', (array) old('tag_ids', $course->tags->pluck('id')->toArray()));
    $mainSkillId = old('main_skill_id', optional($course->skills->firstWhere('pivot.is_main', true))->id);
    @endphp

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-5 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-sm text-sm font-medium">
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

            @if ($errors->any())
            <div class="mb-5 p-4 bg-red-100 text-red-700 rounded-2xl border border-red-200">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">
                        Course Information
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Update course information to keep learning materials and competency standards up to date.
                    </p>
                </div>

                <form action="{{ route('lecturer.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data" class="p-5 md:p-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Course Name
                        </label>

                        <input type="text"
                            name="name"
                            value="{{ old('name', $course->name) }}"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="Example: Web Development with Laravel & Vue"
                            required>

                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Description
                        </label>

                        <textarea name="description"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            rows="4"
                            placeholder="Explain the objectives and content of this course...">{{ old('description', $course->description) }}</textarea>

                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Learning Materials
                        </label>

                        @if ($course->materials->count() > 0)
                            <div class="mb-2 flex flex-col gap-1">
                                <span class="text-xs font-semibold text-indigo-600">Current Materials ({{ $course->materials->count() }} uploaded):</span>
                                @foreach($course->materials as $mat)
                                    <span class="text-xs text-gray-600 truncate">• {{ $mat->title }}</span>
                                @endforeach
                            </div>
                        @endif

                        <input type="file"
                            name="material_file"
                            accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">

                        <p class="text-xs text-gray-400 mt-1">
                            Upload additional material. PDF, PPT, DOCX, ZIP up to 20MB.
                        </p>

                        @error('material_file')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Difficulty Level
                            </label>

                            <select name="level" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                <option value="Beginner" @selected(old('level', $course->level) === 'Beginner')>Beginner</option>
                                <option value="Intermediate" @selected(old('level', $course->level) === 'Intermediate')>Intermediate</option>
                                <option value="Advanced" @selected(old('level', $course->level) === 'Advanced')>Advanced</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Duration (Weeks)
                            </label>

                            <input type="number"
                                name="duration_weeks"
                                value="{{ old('duration_weeks', $course->duration_weeks) }}"
                                min="1"
                                class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Certificate Passing Score (%)
                            </label>

                            <input type="number"
                                name="certificate_threshold"
                                value="{{ old('certificate_threshold', $course->certificate_threshold ?? 60) }}"
                                min="0"
                                max="100"
                                class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                required>
                        </div>
                    </div>

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
                                    <option value="{{ $mainSkill->id }}" @selected((int) $mainSkillId === (int) $mainSkill->id)>
                                        {{ $mainSkill->name }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </div>
                        </div>

                        <p class="text-xs text-gray-400 mt-1">
                            Select the primary skill of this course. Students who pass the final quiz in this skill earn competency certification.
                        </p>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Course Specialty Tags
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

                            if (mainSkillSelect) {
                                mainSkillSelect.addEventListener('change', filterCustomTags);
                            }
                            if (searchInput) {
                                searchInput.addEventListener('input', filterCustomTags);
                            }

                            filterCustomTags();
                        });
                    </script>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 pt-5 border-t border-gray-100">
                        <a href="{{ route('lecturer.courses.show', $course->id) }}"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 12H5"/>
                                <path d="M12 19l-7-7 7-7"/>
                            </svg>
                            Cancel
                        </a>

                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            Update Course
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>