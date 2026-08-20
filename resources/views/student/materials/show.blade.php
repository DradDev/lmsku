<x-app-layout>
    @php
        $fileUrl = !empty($material->file_path) ? asset('storage/' . $material->file_path) : null;
        $fileName = !empty($material->file_path) ? basename($material->file_path) : 'No file';
        $extension = !empty($material->file_path) ? strtolower(pathinfo($material->file_path, PATHINFO_EXTENSION)) : null;
        $isPdf = $extension === 'pdf';
    @endphp

    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-6">

            <div class="mb-8">
                <a href="{{ route('student.courses.show', $enrolledOffering->id ?? ($material->master_course_id ?? 1)) }}"
                   class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-900 transition mb-4">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                    <span>Kembali ke Kursus ({{ $enrolledOffering->masterCourse->name ?? ($material->masterCourse->name ?? ($material->course->name ?? 'Course')) }})</span>
                </a>

                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                            Portal Belajar Mahasiswa
                        </p>

                        <p class="inline-flex items-center rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700 mb-4">
                            {{ $enrolledOffering->masterCourse->name ?? ($material->masterCourse->name ?? ($material->course->name ?? 'Course')) }}
                        </p>

                        <h1 class="text-3xl font-bold text-slate-900">
                            {{ $material->title }}
                        </h1>

                        <p class="text-slate-500 mt-2 text-xs">
                            Pengajar: {{ $enrolledOffering->lecturer->name ?? ($material->course->user->name ?? 'Dosen / Instruktur') }}
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <!-- TOGGLE BOOKMARK / SIMPAN MATERI -->
                        <form action="{{ route('student.materials.toggle-save', $material->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold transition shadow-xs {{ ($isSaved ?? false) ? 'bg-amber-500 hover:bg-amber-600 text-white' : 'bg-white border border-slate-200 hover:bg-slate-50 text-slate-700' }}"
                                    title="{{ ($isSaved ?? false) ? 'Hapus dari materi tersimpan' : 'Simpan materi ini ke perpustakaan belajar kamu' }}">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="{{ ($isSaved ?? false) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                                    <path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/>
                                </svg>
                                <span>{{ ($isSaved ?? false) ? 'Tersimpan di Koleksi' : 'Simpan Materi' }}</span>
                            </button>
                        </form>

                        @if($fileUrl)
                            <a href="{{ $fileUrl }}"
                               target="_blank"
                               class="inline-flex items-center gap-1.5 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-bold text-white hover:bg-slate-800 transition">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                <span>Buka File</span>
                            </a>

                            <a href="{{ $fileUrl }}"
                               download
                               class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white hover:bg-indigo-700 transition">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                <span>Download</span>
                            </a>
                        @endif

                        <a href="{{ route('student.courses.show', $enrolledOffering->id ?? ($material->master_course_id ?? 1)) }}"
                           class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                            <span>Detail Kursus</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2">
                    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="border-b border-slate-200 px-6 py-5">
                            <h2 class="text-xl font-semibold text-slate-900">Preview</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Lihat isi materi secara langsung jika file mendukung preview.
                            </p>
                        </div>

                        <div class="p-6">
                            @if($fileUrl && $isPdf)
                                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                                    <iframe
                                        src="{{ $fileUrl }}"
                                        class="w-full h-[720px] bg-white"
                                        title="Material Preview">
                                    </iframe>
                                </div>
                            @elseif($fileUrl)
                                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-10 text-center">
                                    <div class="mx-auto max-w-lg">
                                        <h3 class="text-lg font-semibold text-slate-900">Preview tidak tersedia</h3>
                                        <p class="mt-2 text-sm text-slate-500">
                                            File dengan format
                                            <span class="font-semibold text-slate-700">{{ strtoupper($extension) }}</span>
                                            tidak bisa ditampilkan langsung di halaman ini.
                                            Gunakan tombol <span class="font-semibold text-slate-700">Open File</span> atau
                                            <span class="font-semibold text-slate-700">Download</span>.
                                        </p>

                                        <div class="mt-6 flex flex-wrap justify-center gap-3">
                                            <a href="{{ $fileUrl }}"
                                               target="_blank"
                                               class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                                                Open File
                                            </a>

                                            <a href="{{ $fileUrl }}"
                                               download
                                               class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                                                Download File
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="rounded-2xl border border-dashed border-yellow-200 bg-yellow-50 p-10 text-center">
                                    <h3 class="text-lg font-semibold text-yellow-800">File belum tersedia</h3>
                                    <p class="mt-2 text-sm text-yellow-700">
                                        Materi ini belum memiliki file yang bisa dibuka.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-slate-900 mb-5">Material Info</h2>

                        <div class="space-y-4">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-sm text-slate-400">Title</p>
                                <p class="mt-1 text-base font-semibold text-slate-900">
                                    {{ $material->title }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-sm text-slate-400">Course</p>
                                <p class="mt-1 text-base font-semibold text-slate-900">
                                    {{ $material->course->name ?? $material->course->title ?? '-' }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-sm text-slate-400">Instructor</p>
                                <p class="mt-1 text-base font-semibold text-slate-900">
                                    {{ $material->course->user->name ?? 'Unknown Instructor' }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-sm text-slate-400">Uploaded At</p>
                                <p class="mt-1 text-base font-semibold text-slate-900">
                                    {{ !empty($material->created_at) ? $material->created_at->format('d M Y, H:i') : '-' }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-sm text-slate-400">File Type</p>
                                <p class="mt-1 text-base font-semibold text-slate-900">
                                    {{ $extension ? strtoupper($extension) : '-' }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-sm text-slate-400">File Name</p>
                                <p class="mt-1 break-all text-sm font-medium text-slate-800">
                                    {{ $fileName }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-slate-900 mb-5">Quick Actions</h2>

                        <div class="space-y-3">
                            @if($fileUrl)
                                <a href="{{ $fileUrl }}"
                                   target="_blank"
                                   class="block w-full text-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                                    Open Original File
                                </a>

                                <a href="{{ $fileUrl }}"
                                   download
                                   class="block w-full text-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                                    Download Material
                                </a>
                            @endif

                            <a href="{{ route('student.courses.show', $enrolledOffering->id ?? ($material->master_course_id ?? 1)) }}"
                               class="block w-full text-center rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-bold text-white hover:bg-slate-800 transition">
                                Kembali ke Kursus
                            </a>

                            <a href="{{ route('student.materials.index') }}"
                               class="block w-full text-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                                Koleksi Materi Tersimpan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
