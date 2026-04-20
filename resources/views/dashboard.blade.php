<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">Welcome back, {{ Auth::user()->name }} 👋</h2>
                <p class="text-sm text-gray-400">Your learning journey continues</p>
            </div>
            <div class="px-3 py-1 text-xs font-semibold text-cyan-400 border border-cyan-500 rounded-full">
                AI Ready
            </div>
        </div>
    </x-slot>

    <style>
        body {
            background-color: #060b14;
        }
    </style>

    <div class="min-h-screen bg-[#060b14] text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
                <div class="bg-[#0d1522] border border-cyan-500/20 rounded-2xl p-5 shadow-lg">
                    <p class="text-sm text-gray-400 mb-2">Mata Kuliah</p>
                    <h3 class="text-3xl font-bold">5</h3>
                    <p class="text-xs text-gray-500 mt-2">Semester ini</p>
                </div>

                <div class="bg-[#0d1522] border border-purple-500/20 rounded-2xl p-5 shadow-lg">
                    <p class="text-sm text-gray-400 mb-2">Tugas Aktif</p>
                    <h3 class="text-3xl font-bold">3</h3>
                    <p class="text-xs text-gray-500 mt-2">1 mendekati deadline</p>
                </div>

                <div class="bg-[#0d1522] border border-green-500/20 rounded-2xl p-5 shadow-lg">
                    <p class="text-sm text-gray-400 mb-2">Materi Tersedia</p>
                    <h3 class="text-3xl font-bold">12</h3>
                    <p class="text-xs text-gray-500 mt-2">Sudah diupload dosen</p>
                </div>

                <div class="bg-[#0d1522] border border-orange-500/20 rounded-2xl p-5 shadow-lg">
                    <p class="text-sm text-gray-400 mb-2">Nilai Masuk</p>
                    <h3 class="text-3xl font-bold">8</h3>
                    <p class="text-xs text-gray-500 mt-2">Update terbaru</p>
                </div>
            </div>

            {{-- Menu utama --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

                {{-- Mata Kuliah --}}
                <div class="bg-[#0d1522] border border-cyan-500/30 rounded-2xl p-5 shadow-lg">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-cyan-500 flex items-center justify-center text-xl">
                            📘
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold">Mata Kuliah</h3>
                            <p class="text-sm text-gray-400">Lihat daftar mata kuliah yang kamu ambil</p>
                        </div>
                    </div>
                    <a href="{{ route('courses.index') }}"
                       class="block w-full text-center bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 rounded-xl transition">
                        Buka Mata Kuliah
                    </a>
                </div>

                {{-- Materi --}}
                <div class="bg-[#0d1522] border border-pink-500/30 rounded-2xl p-5 shadow-lg">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-pink-500 flex items-center justify-center text-xl">
                            📄
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold">Materi Pembelajaran</h3>
                            <p class="text-sm text-gray-400">Akses file dan materi dari dosen</p>
                        </div>
                    </div>
                    <a href="{{ route('materials.index') }}"
                       class="block w-full text-center bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 rounded-xl transition">
                        Lihat Materi
                    </a>
                </div>

                {{-- Tugas --}}
                <div class="bg-[#0d1522] border border-green-500/30 rounded-2xl p-5 shadow-lg">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-green-500 flex items-center justify-center text-xl">
                            📝
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold">Tugas</h3>
                            <p class="text-sm text-gray-400">Kerjakan dan kumpulkan tugas kuliah</p>
                        </div>
                    </div>
                    <a href="{{ route('assignments.index') }}"
                       class="block w-full text-center bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 rounded-xl transition">
                        Lihat Tugas
                    </a>
                </div>

                {{-- Nilai --}}
                <div class="bg-[#0d1522] border border-orange-500/30 rounded-2xl p-5 shadow-lg">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-orange-500 flex items-center justify-center text-xl">
                            🏅
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold">Nilai</h3>
                            <p class="text-sm text-gray-400">Pantau hasil tugas dan penilaian</p>
                        </div>
                    </div>
                    <a href="{{ route('grades.index') }}"
                       class="block w-full text-center bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 rounded-xl transition">
                        Lihat Nilai
                    </a>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
