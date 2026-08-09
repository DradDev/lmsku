<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                </svg>
            </div>

            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    Master Course Induk
                </h2>
                <p class="text-sm text-gray-500">
                    Kelola katalog mata kuliah induk secara terpusat.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-5 flex items-start gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl shadow-sm">
                    <svg class="mt-0.5 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5" />
                    </svg>
                    <div>
                        <p class="font-semibold">Berhasil</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 flex items-start gap-3 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl shadow-sm">
                    <svg class="mt-0.5 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="15" y1="9" x2="9" y2="15" />
                        <line x1="9" y1="9" x2="15" y2="15" />
                    </svg>
                    <div>
                        <p class="font-semibold">Gagal</p>
                        <p class="text-sm">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <div class="mb-6 bg-white border border-gray-100 shadow-sm rounded-2xl p-5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Daftar Master Course</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Katalog mata kuliah induk yang dapat ditawarkan di berbagai semester.
                        </p>
                    </div>

                    <a href="{{ route('admin.master-courses.create') }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14" />
                            <path d="M5 12h14" />
                        </svg>
                        Tambah Master Course
                    </a>
                </div>
            </div>

            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kode</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Mata Kuliah</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Level</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jumlah Kelas</th>
                                <th class="px-5 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($masterCourses as $mc)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 bg-gray-100 text-gray-700 text-xs font-mono font-semibold rounded-lg">
                                            {{ $mc->code ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <a href="{{ route('admin.master-courses.show', $mc) }}" class="font-semibold text-gray-800 hover:text-blue-600 transition">
                                            {{ $mc->name }}
                                        </a>
                                        @if($mc->description)
                                            <p class="text-sm text-gray-400">{{ Str::limit($mc->description, 60) }}</p>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        @php
                                            $levelColors = [
                                                'Beginner' => 'bg-green-50 text-green-700',
                                                'Intermediate' => 'bg-yellow-50 text-yellow-700',
                                                'Advanced' => 'bg-red-50 text-red-700',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1.5 text-sm font-semibold rounded-full {{ $levelColors[$mc->level] ?? 'bg-gray-50 text-gray-700' }}">
                                            {{ $mc->level }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="text-sm text-gray-600">{{ $mc->category->name ?? '-' }}</span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 text-sm font-semibold rounded-full">
                                            {{ $mc->offerings_count }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.master-courses.show', $mc) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-sm font-semibold rounded-xl transition">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                                Kelola Hirarki
                                            </a>

                                            <a href="{{ route('admin.master-courses.edit', $mc) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-semibold rounded-xl transition">
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.master-courses.destroy', $mc) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin hapus master course ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-50 hover:bg-red-100 text-red-700 text-sm font-semibold rounded-xl transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center">
                                        <h3 class="text-lg font-bold text-gray-800">Belum ada Master Course</h3>
                                        <p class="text-sm text-gray-500 mt-2 mb-5">
                                            Tambahkan katalog mata kuliah induk untuk mulai membuka penawaran kelas.
                                        </p>
                                        <a href="{{ route('admin.master-courses.create') }}"
                                           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                                            Tambah Master Course
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
