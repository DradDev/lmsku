<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Mata Kuliah
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Perbarui informasi course, skill, dan tag pembelajaran.
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-5">
                <a href="{{ route('lecturer.courses.show', $course->id) }}"
                    class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                    ← Kembali ke Detail Course
                </a>
            </div>

            @if ($errors->any())
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                <p class="font-medium mb-2">Terjadi kesalahan:</p>

                <ul class="list-disc pl-5 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Informasi Course
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Pastikan data course tetap relevan dan mudah dipahami mahasiswa.
                    </p>
                </div>

                <form action="{{ route('lecturer.courses.update', $course->id) }}" method="POST" class="p-5 md:p-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Mata Kuliah
                        </label>

                        <input type="text"
                            name="name"
                            value="{{ old('name', $course->name) }}"
                            placeholder="Contoh: Pemrograman Web"
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                            required>

                        @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi
                        </label>

                        <textarea name="description"
                            rows="5"
                            placeholder="Tulis deskripsi singkat course..."
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('description', $course->description) }}</textarea>

                        @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @php
                    $selectedSkillIds = array_map('intval', (array) old('skill_ids', $course->skills->pluck('id')->toArray()));
                    $selectedTagIds = array_map('intval', (array) old('tag_ids', $course->tags->pluck('id')->toArray()));
                    $mainSkillId = old('main_skill_id', optional($course->skills->firstWhere('pivot.is_main', true))->id);
                    @endphp

                    <div class="border-t border-gray-100 pt-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Bidang / Skill Utama
                        </label>

                        <select id="main_skill_select"
                            name="main_skill_id"
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">Pilih bidang utama</option>

                            @foreach ($mainSkills as $mainSkill)
                            <option value="{{ $mainSkill->id }}"
                                @selected((int) $mainSkillId===(int) $mainSkill->id)>
                                {{ $mainSkill->name }}
                            </option>
                            @endforeach
                        </select>

                        <p class="text-xs text-gray-500 mt-1">
                            Pilih bidang utama untuk menampilkan detail skill.
                        </p>

                        @error('main_skill_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Detail Skill
                        </label>

                        @foreach ($mainSkills as $mainSkill)
                        <div class="skill-detail-group hidden" data-parent-id="{{ $mainSkill->id }}">
                            <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                                <div class="mb-3">
                                    <p class="font-medium text-gray-800">
                                        Detail {{ $mainSkill->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Sesuaikan detail skill yang berhubungan dengan course ini.
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @forelse ($mainSkill->children as $childSkill)
                                    <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition">
                                        <input type="checkbox"
                                            name="skill_ids[]"
                                            value="{{ $childSkill->id }}"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                            @checked(in_array((int) $childSkill->id, $selectedSkillIds))>

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

                        <div id="empty-skill-message"
                            class="p-4 bg-gray-50 border border-dashed border-gray-300 rounded-xl text-center">
                            <p class="text-sm text-gray-500">
                                Pilih bidang utama terlebih dahulu untuk menampilkan detail skill.
                            </p>
                        </div>

                        @error('skill_ids')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tags Course
                        </label>

                        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @forelse ($tags as $tag)
                                <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition">
                                    <input type="checkbox"
                                        name="tag_ids[]"
                                        value="{{ $tag->id }}"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                        @checked(in_array((int) $tag->id, $selectedTagIds))>

                                    <span class="text-sm font-medium text-gray-700">
                                        {{ $tag->name }}
                                    </span>
                                </label>
                                @empty
                                <div class="md:col-span-3 p-4 bg-white border border-dashed border-gray-300 rounded-xl text-center">
                                    <p class="text-sm text-gray-500">
                                        Belum ada tag tersedia.
                                    </p>
                                </div>
                                @endforelse
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 mt-1">
                            Tag membantu sistem mengelompokkan course berdasarkan topik pembelajaran.
                        </p>

                        @error('tag_ids')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-4">
                        <p class="text-sm font-medium text-yellow-800 mb-1">
                            Perhatian
                        </p>
                        <p class="text-sm text-yellow-700 leading-relaxed">
                            Perubahan course akan langsung terlihat oleh mahasiswa yang sudah terdaftar.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 pt-5 border-t border-gray-100">
                        <a href="{{ route('lecturer.courses.show', $course->id) }}"
                            class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">
                            Batal
                        </a>

                        <button type="submit"
                            class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl shadow-sm transition">
                            Update Course
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainSkillSelect = document.getElementById('main_skill_select');
            const detailGroups = document.querySelectorAll('.skill-detail-group');
            const emptySkillMessage = document.getElementById('empty-skill-message');

            function showSelectedSkillDetails() {
                const selectedMainSkillId = mainSkillSelect.value;
                let hasSelectedGroup = false;

                detailGroups.forEach(function(group) {
                    if (group.dataset.parentId === selectedMainSkillId) {
                        group.classList.remove('hidden');
                        hasSelectedGroup = true;
                    } else {
                        group.classList.add('hidden');

                        group.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
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
</x-app-layout>