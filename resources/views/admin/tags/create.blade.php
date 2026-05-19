<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Tag
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded p-6">
                <form action="{{ route('admin.tags.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Nama Tag</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               class="border rounded w-full p-2"
                               required>

                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <p class="text-sm text-gray-500 mt-1">
                            Contoh: Software, ML / AI, Backend, Beginner Friendly, Project Based.
                        </p>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded">
                            Simpan
                        </button>

                        <a href="{{ route('admin.tags.index') }}"
                           class="px-4 py-2 bg-gray-200 rounded">
                            Batal
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>