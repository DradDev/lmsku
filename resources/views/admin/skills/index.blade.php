<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2v6" />
                    <path d="M12 16v6" />
                    <path d="M4.93 4.93l4.24 4.24" />
                    <path d="M14.83 14.83l4.24 4.24" />
                    <path d="M2 12h6" />
                    <path d="M16 12h6" />
                    <path d="M4.93 19.07l4.24-4.24" />
                    <path d="M14.83 9.17l4.24-4.24" />
                </svg>
            </div>

            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    Master Skill
                </h2>
                <p class="text-sm text-gray-500">
                    Kelola skill utama dan detail skill untuk rekomendasi pembelajaran.
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
                            Daftar Skill
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Tambahkan, ubah, atau hapus data skill yang tersedia di sistem.
                        </p>
                    </div>

                    <a href="{{ route('admin.skills.create') }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14" />
                            <path d="M5 12h14" />
                        </svg>
                        Tambah Skill
                    </a>
                </div>
            </div>

            <div class="space-y-5">
                @forelse ($mainSkills as $mainSkill)
                    <div class="bg-white border border-gray-100 shadow-sm hover:shadow-md transition rounded-2xl overflow-hidden">
                        <div class="p-5 md:p-6">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center flex-shrink-0">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <path d="M12 2l1.8 5.5L19 9.3l-5.2 1.8L12 17l-1.8-5.9L5 9.3l5.2-1.8z" />
                                        </svg>
                                    </div>

                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="font-bold text-lg text-gray-800">
                                                {{ $mainSkill->name }}
                                            </h3>

                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                     stroke-linejoin="round">
                                                    <path d="M4 6h16" />
                                                    <path d="M4 12h16" />
                                                    <path d="M4 18h16" />
                                                </svg>
                                                {{ $mainSkill->children->count() }} Detail
                                            </span>
                                        </div>

                                        @if ($mainSkill->description)
                                            <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                                                {{ $mainSkill->description }}
                                            </p>
                                        @else
                                            <p class="text-sm text-gray-400 mt-2 italic">
                                                Belum ada deskripsi untuk skill ini.
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 md:flex-shrink-0">
                                    <a href="{{ route('admin.skills.edit', $mainSkill) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-semibold rounded-xl transition">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <path d="M12 20h9" />
                                            <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z" />
                                        </svg>
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.skills.destroy', $mainSkill) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin hapus skill ini?')">
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
                            </div>

                            <div class="mt-6 border-t border-gray-100 pt-5">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center">
                                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <path d="M9 11l3 3L22 4" />
                                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                                        </svg>
                                    </div>

                                    <p class="font-semibold text-gray-800">
                                        Detail Skill
                                    </p>
                                </div>

                                <div class="space-y-3">
                                    @forelse ($mainSkill->children as $childSkill)
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 p-4 bg-gray-50 hover:bg-gray-100 border border-gray-100 rounded-xl transition">
                                            <div class="flex items-start gap-3">
                                                <div class="w-9 h-9 rounded-lg bg-white text-gray-600 border border-gray-200 flex items-center justify-center flex-shrink-0">
                                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                                         stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                         stroke-linejoin="round">
                                                        <path d="M20 7h-9" />
                                                        <path d="M14 17H5" />
                                                        <circle cx="17" cy="17" r="3" />
                                                        <circle cx="7" cy="7" r="3" />
                                                    </svg>
                                                </div>

                                                <div>
                                                    <p class="font-semibold text-gray-800">
                                                        {{ $childSkill->name }}
                                                    </p>

                                                    @if ($childSkill->description)
                                                        <p class="text-sm text-gray-500 mt-1 leading-relaxed">
                                                            {{ $childSkill->description }}
                                                        </p>
                                                    @else
                                                        <p class="text-sm text-gray-400 mt-1 italic">
                                                            Belum ada deskripsi.
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-2 md:flex-shrink-0">
                                                <a href="{{ route('admin.skills.edit', $childSkill) }}"
                                                   class="inline-flex items-center gap-1.5 px-3 py-2 bg-white hover:bg-blue-50 text-blue-700 border border-blue-100 text-sm font-semibold rounded-xl transition">
                                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                                         stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                         stroke-linejoin="round">
                                                        <path d="M12 20h9" />
                                                        <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z" />
                                                    </svg>
                                                    Edit
                                                </a>

                                                <form action="{{ route('admin.skills.destroy', $childSkill) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Yakin hapus skill ini?')">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-white hover:bg-red-50 text-red-700 border border-red-100 text-sm font-semibold rounded-xl transition">
                                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                                             stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                             stroke-linejoin="round">
                                                            <path d="M3 6h18" />
                                                            <path d="M8 6V4h8v2" />
                                                            <path d="M19 6l-1 14H6L5 6" />
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="flex items-center gap-3 p-4 bg-gray-50 border border-dashed border-gray-300 rounded-xl">
                                            <div class="w-10 h-10 rounded-xl bg-white text-gray-400 flex items-center justify-center">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                     stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="10" />
                                                    <path d="M12 8v4" />
                                                    <path d="M12 16h.01" />
                                                </svg>
                                            </div>

                                            <div>
                                                <p class="font-semibold text-gray-600">
                                                    Belum ada detail skill.
                                                </p>
                                                <p class="text-sm text-gray-400">
                                                    Detail skill akan muncul di bagian ini.
                                                </p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-10 text-center">
                        <div class="mx-auto w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 5v14" />
                                <path d="M5 12h14" />
                            </svg>
                        </div>

                        <h3 class="text-lg font-bold text-gray-800">
                            Belum ada skill
                        </h3>

                        <p class="text-sm text-gray-500 mt-2 mb-5">
                            Mulai tambahkan skill utama dan detail skill untuk kebutuhan sistem.
                        </p>

                        <a href="{{ route('admin.skills.create') }}"
                           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 5v14" />
                                <path d="M5 12h14" />
                            </svg>
                            Tambah Skill
                        </a>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>