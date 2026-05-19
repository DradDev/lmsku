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
                    Perbarui informasi project, skill, tag, dan status publikasi.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">
                        Informasi Project
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Lengkapi data project dengan benar agar mudah direkomendasikan ke student.
                    </p>
                </div>

                <form action="{{ route('lecturer.projects.update', $project) }}" method="POST" class="p-5 md:p-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Judul Project
                        </label>

                        <input type="text"
                               name="title"
                               value="{{ old('title', $project->title) }}"
                               class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                               placeholder="Contoh: Sistem Rekomendasi Course"
                               required>

                        @error('title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Deskripsi
                        </label>

                        <textarea name="description"
                                  rows="5"
                                  class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                  placeholder="Tulis deskripsi singkat mengenai project ini...">{{ old('description', $project->description) }}</textarea>

                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Level Kesulitan
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
                                Durasi Project
                            </label>

                            <div class="relative">
                                <input type="number"
                                       name="duration_days"
                                       value="{{ old('duration_days', $project->duration_days) }}"
                                       min="1"
                                       class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm pr-16"
                                       required>

                                <span class="absolute inset-y-0 right-4 flex items-center text-sm text-gray-400">
                                    hari
                                </span>
                            </div>

                            @error('duration_days')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @php
                        $selectedSkillIds = old('skill_ids', $project->skills->pluck('id')->toArray());
                        $selectedTagIds = old('tag_ids', $project->tags->pluck('id')->toArray());
                        $mainSkillId = old('main_skill_id', optional($project->skills->firstWhere('pivot.is_main', true))->id);
                    @endphp

                    <div class="border-t border-gray-100 pt-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Bidang / Skill Utama
                        </label>

                        <div class="relative">
                            <select id="main_skill_select"
                                    name="main_skill_id"
                                    class="w-full appearance-none rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm pr-10">
                                <option value="">Pilih bidang utama</option>

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
                            Pilih satu bidang utama, lalu pilih detail skill yang sesuai.
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Detail Skill
                        </label>

                        @foreach ($mainSkills as $mainSkill)
                            <div class="skill-detail-group hidden" data-parent-id="{{ $mainSkill->id }}">
                                <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center">
                                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                 stroke-linejoin="round">
                                                <path d="M20 7h-9" />
                                                <path d="M14 17H5" />
                                                <circle cx="17" cy="17" r="3" />
                                                <circle cx="7" cy="7" r="3" />
                                            </svg>
                                        </div>

                                        <div>
                                            <p class="font-semibold text-gray-800">
                                                Detail {{ $mainSkill->name }}
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                Pilih satu atau beberapa detail skill.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @forelse ($mainSkill->children as $childSkill)
                                            <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition">
                                                <input type="checkbox"
                                                       name="skill_ids[]"
                                                       value="{{ $childSkill->id }}"
                                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                       @checked(in_array($childSkill->id, $selectedSkillIds))>

                                                <span class="text-sm font-medium text-gray-700">
                                                    {{ $childSkill->name }}
                                                </span>
                                            </label>
                                        @empty
                                            <div class="sm:col-span-2 p-4 bg-white border border-dashed border-gray-300 rounded-xl text-center">
                                                <p class="text-sm text-gray-500">
                                                    Belum ada detail skill untuk bidang ini.
                                                </p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div id="empty-skill-message" class="p-4 bg-gray-50 border border-dashed border-gray-300 rounded-xl text-center">
                            <p class="text-sm text-gray-500">
                                Pilih bidang utama terlebih dahulu untuk menampilkan detail skill.
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tags Project
                        </label>

                        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach ($tags as $tag)
                                    <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition">
                                        <input type="checkbox"
                                               name="tag_ids[]"
                                               value="{{ $tag->id }}"
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                               @checked(in_array($tag->id, $selectedTagIds))>

                                        <span class="text-sm font-medium text-gray-700">
                                            {{ $tag->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <p class="text-xs text-gray-400 mt-1">
                            Tag membantu sistem mengelompokkan dan merekomendasikan project.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Kuota Maksimal Student
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
                                Pastikan kuota tidak lebih kecil dari jumlah student yang sudah bergabung.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Status Publikasi
                            </label>

                            <label class="flex items-center justify-between gap-4 p-4 rounded-xl border border-gray-200 bg-gray-50 cursor-pointer hover:bg-blue-50 hover:border-blue-200 transition">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">
                                        Publish project ke student
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        Jika aktif, project dapat dilihat oleh student.
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

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const mainSkillSelect = document.getElementById('main_skill_select');
                            const detailGroups = document.querySelectorAll('.skill-detail-group');
                            const emptySkillMessage = document.getElementById('empty-skill-message');

                            function showSelectedSkillDetails() {
                                const selectedMainSkillId = mainSkillSelect.value;
                                let hasSelectedGroup = false;

                                detailGroups.forEach(function (group) {
                                    if (group.dataset.parentId === selectedMainSkillId) {
                                        group.classList.remove('hidden');
                                        hasSelectedGroup = true;
                                    } else {
                                        group.classList.add('hidden');

                                        group.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
                                            checkbox.checked = false;
                                        });
                                    }
                                });

                                if (emptySkillMessage) {
                                    emptySkillMessage.classList.toggle('hidden', hasSelectedGroup);
                                }
                            }

                            mainSkillSelect.addEventListener('change', showSelectedSkillDetails);
                            showSelectedSkillDetails();
                        });
                    </script>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 pt-5 border-t border-gray-100">
                        <a href="{{ route('lecturer.projects.index') }}"
                           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 12H5" />
                                <path d="M12 19l-7-7 7-7" />
                            </svg>
                            Batal
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