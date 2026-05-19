<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Skill
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded p-6">
                <form action="{{ route('admin.skills.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Nama Skill</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               class="border rounded w-full p-2"
                               required>

                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Deskripsi</label>
                        <textarea name="description"
                                  class="border rounded w-full p-2"
                                  rows="4">{{ old('description') }}</textarea>

                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Parent / Skill Utama</label>

                        <select name="parent_id" class="border rounded w-full p-2">
                            <option value="">-- Jadikan Skill Utama --</option>

                            @foreach ($parentSkills as $parentSkill)
                                <option value="{{ $parentSkill->id }}"
                                    @selected((int) old('parent_id') === (int) $parentSkill->id)>
                                    {{ $parentSkill->name }}
                                </option>
                            @endforeach
                        </select>

                        <p class="text-sm text-gray-500 mt-1">
                            Kosongkan jika ini adalah skill utama, misalnya Software atau ML / AI.
                            Pilih parent jika ini detail skill, misalnya Laravel di bawah Software.
                        </p>

                        @error('parent_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded">
                            Simpan
                        </button>

                        <a href="{{ route('admin.skills.index') }}"
                           class="px-4 py-2 bg-gray-200 rounded">
                            Batal
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>