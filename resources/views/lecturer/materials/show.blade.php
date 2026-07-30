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
                <a href="{{ route('lecturer.courses.show', $material->course_id) }}"
                   class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 mb-4">
                    ← Back to Course
                </a>

                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                            Lecturer Portal
                        </p>
                        <h1 class="text-3xl font-bold text-slate-900">{{ $material->title }}</h1>
                        <p class="mt-2 text-slate-500">
                            Detail materi pembelajaran yang telah Anda upload.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @if($fileUrl)
                            <a href="{{ $fileUrl }}"
                               target="_blank"
                               class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                                Open File
                            </a>

                            <a href="{{ $fileUrl }}"
                               download
                               class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                                Download
                            </a>
                        @endif

                        <a href="{{ route('lecturer.materials.edit', $material->id) }}"
                           class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Edit
                        </a>

                        <form method="POST"
                              action="{{ route('lecturer.materials.destroy', $material->id) }}"
                              onsubmit="return confirm('Yakin ingin menghapus materi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-700 hover:bg-rose-100">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
                <div class="xl:col-span-2 rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
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
                                        File dengan format <span class="font-semibold text-slate-700">{{ strtoupper($extension) }}</span>
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
                            <div class="rounded-2xl border border-dashed border-rose-200 bg-rose-50 p-10 text-center">
                                <h3 class="text-lg font-semibold text-rose-700">File tidak ditemukan</h3>
                                <p class="mt-2 text-sm text-rose-600">
                                    Materi ini belum memiliki file yang bisa ditampilkan.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-slate-900 mb-5">Material Info</h2>

                        <div class="space-y-4">
                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="text-sm text-slate-400">Title</p>
                                <p class="mt-1 text-base font-semibold text-slate-900">
                                    {{ $material->title }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="text-sm text-slate-400">Course</p>
                                <p class="mt-1 text-base font-semibold text-slate-900">
                                    {{ $material->course->name ?? 'No Course' }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="text-sm text-slate-400">Uploaded Date</p>
                                <p class="mt-1 text-base font-semibold text-slate-900">
                                    {{ optional($material->created_at)->format('d M Y, H:i') ?: '-' }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="text-sm text-slate-400">File Type</p>
                                <p class="mt-1 text-base font-semibold text-slate-900">
                                    {{ $extension ? strtoupper($extension) : '-' }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
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

                            <a href="{{ route('lecturer.materials.edit', $material->id) }}"
                               class="block w-full text-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Edit Material
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
