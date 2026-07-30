<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Course
        </h2>
    </x-slot>

    @php
    $skillGroups = $mainSkills ?? collect();
    $flatSkills = $skills ?? collect();
    @endphp

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white shadow rounded p-6">
                <form action="{{ route('lecturer.courses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-5">
                        <label class="block font-semibold mb-2">
                            Nama Course
                        </label>

                        <input type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="border rounded w-full p-2"
                            placeholder="Contoh: Web Programming with Laravel"
                            required>
                    </div>

                    <div class="mb-5">
                        <label class="block font-semibold mb-2">
                            Deskripsi
                        </label>

                        <textarea name="description"
                            class="border rounded w-full p-2"
                            rows="4"
                            placeholder="Jelaskan tujuan dan isi course...">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-5">
                        <label class="block font-semibold mb-2">
                            Kategori
                        </label>

                        <select name="category_id" class="border rounded w-full p-2">
                            <option value="">Pilih Kategori (opsional)</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('category_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block font-semibold mb-2">
                            Thumbnail Course
                        </label>

                        <input type="file"
                            name="thumbnail"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="border rounded w-full p-2">

                        <p class="text-sm text-gray-500 mt-1">
                            Opsional. Format JPG/PNG/WEBP, maksimal 2MB.
                        </p>

                        @error('thumbnail')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="block font-semibold mb-2">
                                Level
                            </label>

                            <select name="level"
                                class="border rounded w-full p-2"
                                required>
                                <option value="">Pilih Level</option>
                                <option value="Beginner" @selected(old('level')==='Beginner' )>
                                    Beginner
                                </option>
                                <option value="Intermediate" @selected(old('level')==='Intermediate' )>
                                    Intermediate
                                </option>
                                <option value="Advanced" @selected(old('level')==='Advanced' )>
                                    Advanced
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold mb-2">
                                Durasi Minggu
                            </label>

                            <input type="number"
                                name="duration_weeks"
                                value="{{ old('duration_weeks', 4) }}"
                                min="1"
                                class="border rounded w-full p-2"
                                required>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block font-semibold mb-2">
                            Skill Course
                        </label>

                        <p class="text-sm text-gray-500 mb-3">
                            Pilih skill yang berkaitan dengan course ini, lalu tentukan satu skill utama.
                        </p>

                        @if ($skillGroups->isNotEmpty())
                        <div class="space-y-4">
                            @foreach ($skillGroups as $parentSkill)
                            <div class="border rounded p-4 bg-gray-50">
                                <h4 class="font-semibold text-gray-800 mb-3">
                                    {{ $parentSkill->name }}
                                </h4>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @forelse ($parentSkill->children as $skill)
                                    <div class="flex items-center justify-between gap-3 bg-white border rounded p-2">
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox"
                                                name="skill_ids[]"
                                                value="{{ $skill->id }}"
                                                @checked(in_array($skill->id, old('skill_ids', [])))>

                                            <span>{{ $skill->name }}</span>
                                        </label>

                                        <label class="flex items-center gap-1 text-xs text-gray-600">
                                            <input type="radio"
                                                name="main_skill_id"
                                                value="{{ $skill->id }}"
                                                @checked((int) old('main_skill_id')===(int) $skill->id)>

                                            Main
                                        </label>
                                    </div>
                                    @empty
                                    <p class="text-sm text-gray-500">
                                        Belum ada detail skill pada kategori ini.
                                    </p>
                                    @endforelse
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @forelse ($flatSkills as $skill)
                            <div class="flex items-center justify-between gap-3 border rounded p-2">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox"
                                        name="skill_ids[]"
                                        value="{{ $skill->id }}"
                                        @checked(in_array($skill->id, old('skill_ids', [])))>

                                    <span>{{ $skill->name }}</span>
                                </label>

                                <label class="flex items-center gap-1 text-xs text-gray-600">
                                    <input type="radio"
                                        name="main_skill_id"
                                        value="{{ $skill->id }}"
                                        @checked((int) old('main_skill_id')===(int) $skill->id)>

                                    Main
                                </label>
                            </div>
                            @empty
                            <p class="text-sm text-gray-500">
                                Belum ada data skill.
                            </p>
                            @endforelse
                        </div>
                        @endif
                    </div>

                    <div class="mb-6">
                        <label class="block font-semibold mb-2">
                            Tags
                        </label>

                        <p class="text-sm text-gray-500 mb-3">
                            Pilih tag/minat yang sesuai dengan course.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            @forelse ($tags as $tag)
                            <label class="flex items-center gap-2 border rounded p-2">
                                <input type="checkbox"
                                    name="tag_ids[]"
                                    value="{{ $tag->id }}"
                                    @checked(in_array($tag->id, old('tag_ids', [])))>

                                <span>{{ $tag->name }}</span>
                            </label>
                            @empty
                            <p class="text-sm text-gray-500">
                                Belum ada data tag.
                            </p>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded">
                            Simpan Course
                        </button>

                        <a href="{{ route('lecturer.courses.index') }}"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded">
                            Batal
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>