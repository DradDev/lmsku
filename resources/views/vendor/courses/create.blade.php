<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                ➕ Buat Course Sertifikasi Industri Baru
            </h2>
            <a href="{{ route('vendor.courses.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold text-xs rounded-xl">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-6">

                <!-- Info Banner Master Course Mandiri -->
                <div class="p-4 bg-indigo-50 border border-indigo-200 text-indigo-900 rounded-2xl flex items-center gap-3">
                    <span class="text-xl">💡</span>
                    <div class="text-xs">
                        <span class="font-extrabold block">Pembuatan Master Course Sertifikasi Mandiri:</span>
                        <span class="text-indigo-700">Sebagai Author Mitra Vendor, Anda tidak terikat Semester Kampus. Silabus yang Anda buat akan langsung menjadi Master Course Sertifikasi milik organisasi Anda.</span>
                    </div>
                </div>

                <form action="{{ route('vendor.courses.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nama Course Sertifikasi</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               placeholder="Contoh: Digital Bootcamp Cloud Computing Fundamentals"
                               class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-3">
                        @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Level Kesulitan</label>
                            <select name="level" required class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-3">
                                <option value="Beginner">Beginner</option>
                                <option value="Intermediate">Intermediate</option>
                                <option value="Advanced">Advanced</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Kategori</label>
                            <select name="category_id" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-3">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Passing Grade Kuis (0-100)</label>
                            <input type="number" name="certificate_threshold" value="{{ old('certificate_threshold', 75) }}" required min="0" max="100"
                                   class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-3">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Deskripsi & Pokok Bahasan</label>
                        <textarea name="description" rows="4" required placeholder="Jelaskan silabus pelatihan dan kualifikasi yang akan diuji..."
                                  class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-3">{{ old('description') }}</textarea>
                    </div>

                    <!-- Target Skills -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Target Skill Kompetensi Utama</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-44 overflow-y-auto border border-gray-200 rounded-xl p-3 bg-gray-50">
                            @foreach($skills as $sk)
                            <label class="flex items-center gap-2 text-xs text-gray-700 font-semibold cursor-pointer">
                                <input type="checkbox" name="skill_ids[]" value="{{ $sk->id }}" class="rounded text-indigo-600 border-gray-300">
                                <span>⚡ {{ $sk->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                        <a href="{{ route('vendor.courses.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-bold text-xs rounded-xl">Batal</a>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-sm">
                            🚀 Publikasikan Course Sertifikasi
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
