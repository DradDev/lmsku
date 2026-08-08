<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-700 flex items-center justify-center">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" />
                    <path d="M3 9h18" />
                    <path d="M9 21V9" />
                </svg>
            </div>

            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    Penawaran Kelas
                </h2>
                <p class="text-sm text-gray-500">
                    Kelola kelas paralel per semester.
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
                    <div class="flex items-center gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Daftar Kelas Penawaran</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Kelas paralel yang dibuka per semester.
                            </p>
                        </div>

                        {{-- Filter Semester --}}
                        <form method="GET" action="{{ route('admin.course-offerings.index') }}" class="flex items-center gap-2">
                            <select name="academic_term_id" class="border rounded p-2 text-sm" onchange="this.form.submit()">
                                <option value="">Semua Semester</option>
                                @foreach($terms as $t)
                                    <option value="{{ $t->id }}" {{ request('academic_term_id') == $t->id ? 'selected' : '' }}>
                                        {{ $t->name }} {{ $t->is_active ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <a href="{{ route('admin.course-offerings.create') }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14" />
                            <path d="M5 12h14" />
                        </svg>
                        Buka Kelas Baru
                    </a>
                </div>
            </div>

            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Semester</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Dosen Pengampu</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kuota</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Threshold</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-5 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($offerings as $offering)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-gray-800">{{ $offering->masterCourse->name ?? '-' }}</p>
                                        <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-mono rounded mt-1">
                                            {{ $offering->masterCourse->code ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 text-sm font-semibold rounded-full">
                                            {{ $offering->section_name ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="text-sm text-gray-600">{{ $offering->academicTerm->name ?? '-' }}</span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="text-sm text-gray-600">{{ $offering->lecturer->name ?? '-' }}</span>
                                    </td>

                                    <td class="px-5 py-4">
                                        @if($offering->capacity)
                                            <span class="text-sm font-semibold {{ $offering->enrollments_count >= $offering->capacity ? 'text-red-600' : 'text-gray-700' }}">
                                                {{ $offering->enrollments_count }}/{{ $offering->capacity }} Mhs
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-400">Unlimited</span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="text-sm font-semibold text-gray-700">{{ $offering->certificate_threshold }}</span>
                                    </td>

                                    <td class="px-5 py-4">
                                        @php
                                            $statusColors = [
                                                'draft' => 'bg-gray-100 text-gray-600',
                                                'published' => 'bg-green-50 text-green-700',
                                                'ongoing' => 'bg-blue-50 text-blue-700',
                                                'expired' => 'bg-yellow-50 text-yellow-700',
                                                'cancelled' => 'bg-red-50 text-red-700',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1.5 text-sm font-semibold rounded-full {{ $statusColors[$offering->status] ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ ucfirst($offering->status) }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.course-offerings.edit', $offering) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-semibold rounded-xl transition">
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.course-offerings.destroy', $offering) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin hapus kelas penawaran ini?')">
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
                                    <td colspan="8" class="px-5 py-12 text-center">
                                        <h3 class="text-lg font-bold text-gray-800">Belum ada kelas penawaran</h3>
                                        <p class="text-sm text-gray-500 mt-2 mb-5">
                                            Buka kelas baru untuk mulai menawarkan mata kuliah kepada mahasiswa.
                                        </p>
                                        <a href="{{ route('admin.course-offerings.create') }}"
                                           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                                            Buka Kelas Baru
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
