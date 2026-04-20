<x-app-layout>
    @php
        $totalMaterials = $materials->count();
        $totalCourses = $materials->pluck('course_id')->filter()->unique()->count();
        $pdfCount = $materials->filter(function ($material) {
            return strtolower(pathinfo($material->file_path ?? '', PATHINFO_EXTENSION)) === 'pdf';
        })->count();
        $latestUpload = $materials->first();
    @endphp

    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-6">

            <div class="mb-8 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                        Lecturer Portal
                    </p>
                    <h1 class="text-3xl font-bold text-slate-900">Learning Materials</h1>
                    <p class="mt-2 text-slate-500 max-w-2xl">
                        Kelola semua materi pembelajaran yang Anda upload untuk course Anda.
                    </p>
                </div>

                <a href="{{ route('lecturer.materials.create') }}"
                   class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                    + Upload Material
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Materials</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $totalMaterials }}</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Active Courses</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $totalCourses }}</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">PDF Files</p>
                    <p class="mt-3 text-3xl font-bold text-indigo-600">{{ $pdfCount }}</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Latest Upload</p>
                    <p class="mt-3 text-base font-semibold text-slate-900">
                        {{ $latestUpload?->created_at?->format('d M Y') ?? '-' }}
                    </p>
                </div>
            </div>

            @if($materials->isEmpty())
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm">
                    <h3 class="text-xl font-bold text-slate-900">Belum ada materi</h3>
                    <p class="mt-2 text-sm text-slate-500">
                        Mulai upload materi pembelajaran pertama untuk course Anda.
                    </p>

                    <a href="{{ route('lecturer.materials.create') }}"
                       class="mt-6 inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">
                        Upload Material Pertama
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach($materials as $material)
                        @php
                            $extension = strtolower(pathinfo($material->file_path ?? '', PATHINFO_EXTENSION));
                            $fileName = basename($material->file_path ?? '');
                        @endphp

                        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                            <div class="flex items-start justify-between gap-3 mb-4">
                                <div class="flex flex-wrap gap-2">
                                    <span class="inline-flex items-center rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                        {{ $material->course->name ?? 'No Course' }}
                                    </span>

                                    <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600">
                                        {{ $extension ? strtoupper($extension) : 'FILE' }}
                                    </span>
                                </div>
                            </div>

                            <h3 class="text-lg font-semibold text-slate-900 leading-7">
                                {{ $material->title }}
                            </h3>

                            <div class="mt-4 space-y-3">
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Uploaded</p>
                                    <p class="mt-1 text-sm font-medium text-slate-800">
                                        {{ $material->created_at ? $material->created_at->format('d M Y, H:i') : '-' }}
                                    </p>
                                </div>

                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">File Name</p>
                                    <p class="mt-1 text-sm font-medium text-slate-800 break-all">
                                        {{ \Illuminate\Support\Str::limit($fileName, 38) ?: '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-5 grid grid-cols-2 md:grid-cols-4 gap-2">
                                <a href="{{ route('lecturer.materials.show', $material->id) }}"
                                   class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-3 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                                    View
                                </a>

                                <a href="{{ asset('storage/' . $material->file_path) }}"
                                   download
                                   class="inline-flex items-center justify-center rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-2.5 text-sm font-semibold text-indigo-700 hover:bg-indigo-100">
                                    Download
                                </a>

                                <a href="{{ route('lecturer.materials.edit', $material->id) }}"
                                   class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('lecturer.materials.destroy', $material->id) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus materi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full inline-flex items-center justify-center rounded-xl border border-rose-200 bg-rose-50 px-3 py-2.5 text-sm font-semibold text-rose-700 hover:bg-rose-100">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
