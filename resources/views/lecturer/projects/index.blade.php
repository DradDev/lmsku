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
                    Projects
                </h2>
                <p class="text-sm text-gray-500">
                    Kelola project pembelajaran untuk mahasiswa.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

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

            <div class="mb-6 bg-white border border-gray-100 shadow-sm rounded-2xl p-5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">
                            Daftar Project
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Tambahkan, edit, lihat detail, atau hapus project yang tersedia.
                        </p>
                    </div>

                    <a href="{{ route('lecturer.projects.create') }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14" />
                            <path d="M5 12h14" />
                        </svg>
                        Tambah Project
                    </a>
                </div>
            </div>

            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Judul
                                </th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Level
                                </th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Durasi
                                </th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-5 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($projects as $project)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center flex-shrink-0">
                                                <svg width="19" height="19" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                     stroke-linejoin="round">
                                                    <path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                                </svg>
                                            </div>

                                            <div>
                                                <p class="font-semibold text-gray-800">
                                                    {{ $project->title }}
                                                </p>
                                                <p class="text-sm text-gray-400">
                                                    Project pembelajaran
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-700 text-sm font-semibold rounded-full">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                 stroke-linejoin="round">
                                                <path d="M12 2l1.8 5.5L19 9.3l-5.2 1.8L12 17l-1.8-5.9L5 9.3l5.2-1.8z" />
                                            </svg>
                                            {{ $project->difficulty_level }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 text-orange-700 text-sm font-semibold rounded-full">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                 stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10" />
                                                <polyline points="12 6 12 12 16 14" />
                                            </svg>
                                            {{ $project->duration_days }} hari
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        @if ($project->is_published)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 text-green-700 text-sm font-semibold rounded-full">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                     stroke-linejoin="round">
                                                    <path d="M20 6 9 17l-5-5" />
                                                </svg>
                                                Published
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 text-sm font-semibold rounded-full">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                     stroke-linejoin="round">
                                                    <path d="M12 8v4" />
                                                    <path d="M12 16h.01" />
                                                    <circle cx="12" cy="12" r="10" />
                                                </svg>
                                                Draft
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('lecturer.projects.show', $project) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-100 text-sm font-semibold rounded-xl transition">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                     stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                                Detail
                                            </a>

                                            <a href="{{ route('lecturer.projects.edit', $project) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-semibold rounded-xl transition">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                     stroke-linejoin="round">
                                                    <path d="M12 20h9" />
                                                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z" />
                                                </svg>
                                                Edit
                                            </a>

                                            <form action="{{ route('lecturer.projects.destroy', $project) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin hapus project ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-50 hover:bg-red-100 text-red-700 text-sm font-semibold rounded-xl transition">
                                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                                         stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                         stroke-linejoin="round">
                                                        <path d="M3 6h18" />
                                                        <path d="M8 6V4h8v2" />
                                                        <path d="M19 6l-1 14H6L5 6" />
                                                        <path d="M10 11v6" />
                                                        <path d="M14 11v6" />
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-12">
                                        <div class="text-center">
                                            <div class="mx-auto w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                     stroke-linejoin="round">
                                                    <path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                                    <path d="M12 11v6" />
                                                    <path d="M9 14h6" />
                                                </svg>
                                            </div>

                                            <h3 class="text-lg font-bold text-gray-800">
                                                Belum ada project
                                            </h3>

                                            <p class="text-sm text-gray-500 mt-2 mb-5">
                                                Mulai tambahkan project pembelajaran untuk mahasiswa.
                                            </p>

                                            <a href="{{ route('lecturer.projects.create') }}"
                                               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                     stroke-linejoin="round">
                                                    <path d="M12 5v14" />
                                                    <path d="M5 12h14" />
                                                </svg>
                                                Tambah Project
                                            </a>
                                        </div>
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