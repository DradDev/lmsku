<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                    <line x1="16" y1="2" x2="16" y2="6" />
                    <line x1="8" y1="2" x2="8" y2="6" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
            </div>

            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    Periode Semester
                </h2>
                <p class="text-sm text-gray-500">
                    Kelola kalender semester akademik.
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
                        <h3 class="text-lg font-bold text-gray-800">Daftar Semester</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Hanya satu semester yang boleh berstatus aktif pada satu waktu.
                        </p>
                    </div>

                    <a href="{{ route('admin.academic-terms.create') }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14" />
                            <path d="M5 12h14" />
                        </svg>
                        Tambah Semester
                    </a>
                </div>
            </div>

            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Semester</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tahun Ajaran</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tipe</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jumlah Kelas</th>
                                <th class="px-5 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($terms as $term)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-gray-800">{{ $term->name }}</p>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="text-sm text-gray-600">{{ $term->academic_year ?? '-' }}</span>
                                    </td>

                                    <td class="px-5 py-4">
                                        @if($term->term_type === 'ganjil')
                                            <span class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 text-sm font-semibold rounded-full">Ganjil</span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1.5 bg-purple-50 text-purple-700 text-sm font-semibold rounded-full">Genap</span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="text-sm text-gray-600">
                                            {{ $term->start_date?->format('d/m/Y') ?? '-' }} s/d {{ $term->end_date?->format('d/m/Y') ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <form action="{{ route('admin.academic-terms.toggle-active', $term) }}" method="POST" class="inline">
                                            @csrf
                                            @if($term->is_active)
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 text-green-700 text-sm font-semibold rounded-full hover:bg-green-100 transition" title="Klik untuk menonaktifkan">
                                                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                                    Aktif
                                                </button>
                                            @else
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-500 text-sm font-semibold rounded-full hover:bg-gray-200 transition" title="Klik untuk mengaktifkan">
                                                    <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                                    Non-Aktif
                                                </button>
                                            @endif
                                        </form>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 text-sm font-semibold rounded-full">
                                            {{ $term->offerings_count }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.academic-terms.edit', $term) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-semibold rounded-xl transition">
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.academic-terms.destroy', $term) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin hapus semester ini?')">
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
                                    <td colspan="7" class="px-5 py-12 text-center">
                                        <h3 class="text-lg font-bold text-gray-800">Belum ada periode semester</h3>
                                        <p class="text-sm text-gray-500 mt-2 mb-5">
                                            Tambahkan periode semester untuk mulai membuka kelas penawaran.
                                        </p>
                                        <a href="{{ route('admin.academic-terms.create') }}"
                                           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                                            Tambah Semester
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
