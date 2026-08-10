<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                ➕ Publikasikan Project Industri Baru
            </h2>
            <a href="{{ route('vendor.projects.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold text-xs rounded-xl">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-6">

                <form action="{{ route('vendor.projects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Judul Project Real Client / Industri</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               placeholder="Contoh: Pengembangan Fitur Microservices Payment Gateway"
                               class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-3">
                        @error('title') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Level Kesulitan</label>
                            <select name="difficulty_level" required class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-3">
                                <option value="Beginner">Beginner</option>
                                <option value="Intermediate" selected>Intermediate</option>
                                <option value="Advanced">Advanced</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Durasi Pengerjaan (Hari)</label>
                            <input type="number" name="duration_days" value="{{ old('duration_days', 30) }}" required min="1"
                                   class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-3">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Maksimal Mahasiswa</label>
                            <input type="number" name="max_students" value="{{ old('max_students', 3) }}" required min="1"
                                   class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-3">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Deskripsi & Deliverables Project</label>
                        <textarea name="description" rows="4" required placeholder="Jelaskan kebutuhan teknis, arsitektur, dan luaran project..."
                                  class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-3">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Benefits & Insentif Mahasiswa</label>
                        <input type="text" name="benefits" value="{{ old('benefits') }}"
                               placeholder="Contoh: Honorarium Pengerjaan + Sertifikat Magang Industri"
                               class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-3">
                    </div>

                    <!-- TOR Brief Upload -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Unggah TOR / Brief File (PDF/DOCX/ZIP)</label>
                        <input type="file" name="brief_file" accept=".pdf,.doc,.docx,.zip"
                               class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    </div>

                    <!-- Primary Required Skill -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Persyaratan Skill Utama (Prasyarat Sertifikat)</label>
                        <select name="main_skill_id" required class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl text-xs p-3">
                            <option value="">-- Pilih Skill Utama --</option>
                            @foreach($mainSkills as $ms)
                                <option value="{{ $ms->id }}">⚡ {{ $ms->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                        <a href="{{ route('vendor.projects.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-bold text-xs rounded-xl">Batal</a>
                        <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-sm">
                            🚀 Publikasikan Project Industri
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
