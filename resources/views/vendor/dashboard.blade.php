<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-lg">
                    🏢
                </div>
                <div>
                    <h2 class="font-black text-xl text-gray-900 leading-tight">
                        Vendor & Industry Partner Portal
                    </h2>
                    <p class="text-xs text-gray-500">
                        Kelola sertifikasi industri, publikasikan project real client, dan rekrut talenta mahasiswa.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('vendor.courses.create') }}"
                   class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm flex items-center gap-1.5">
                    <span>+ Buat Course Sertifikasi</span>
                </a>

                <a href="{{ route('vendor.projects.create') }}"
                   class="px-3.5 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition shadow-sm flex items-center gap-1.5">
                    <span>+ Publikasikan Project</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Executive Stats Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold">
                        🎓
                    </div>
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Course Sertifikasi</span>
                        <span class="text-2xl font-black text-gray-900">{{ $totalCourses }}</span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl font-bold">
                        📁
                    </div>
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Project Industri</span>
                        <span class="text-2xl font-black text-gray-900">{{ $totalProjects }}</span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                        👨‍🎓
                    </div>
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Mahasiswa Enrolled</span>
                        <span class="text-2xl font-black text-gray-900">{{ $totalEnrolledStudents }}</span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                        🎯
                    </div>
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Talent Rekrutmen</span>
                        <span class="text-2xl font-black text-gray-900">{{ $totalProjectStudents }}</span>
                    </div>
                </div>
            </div>

            <!-- Main Management 2-Column Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Left Card: Industry Certified Courses -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <div>
                            <h3 class="font-extrabold text-base text-gray-900 flex items-center gap-2">
                                <span>🎓 Course Sertifikasi Industri</span>
                            </h3>
                            <p class="text-xs text-gray-500">Pelatihan kompetensi & sertifikasi profesional mitra.</p>
                        </div>
                        <a href="{{ route('vendor.courses.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                            Lihat Semua →
                        </a>
                    </div>

                    @if($courses->isEmpty())
                        <div class="p-6 bg-gray-50 border border-dashed border-gray-200 rounded-xl text-center space-y-2">
                            <p class="text-xs text-gray-500 font-medium">Belum ada course sertifikasi industri yang dibuat.</p>
                            <a href="{{ route('vendor.courses.create') }}" class="inline-block px-3 py-1.5 bg-indigo-600 text-white font-bold text-xs rounded-lg">
                                + Buat Course Pertama
                            </a>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($courses->take(4) as $course)
                                <div class="p-3.5 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-between gap-3">
                                    <div>
                                        <h4 class="font-bold text-xs text-gray-900">{{ $course->name }}</h4>
                                        <div class="flex items-center gap-2 text-[10px] text-gray-500 mt-1">
                                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 font-bold rounded-md">Min Score: {{ $course->certificate_threshold }}</span>
                                            <span>• {{ $course->students_count }} Peserta</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('vendor.courses.show', $course) }}" class="px-2.5 py-1 bg-white border border-gray-200 text-gray-700 rounded-lg text-xs font-bold hover:bg-gray-100">
                                        Kelola
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Right Card: Industry Projects -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <div>
                            <h3 class="font-extrabold text-base text-gray-900 flex items-center gap-2">
                                <span>📁 Project Real Client & Industri</span>
                            </h3>
                            <p class="text-xs text-gray-500">Pekerjaan nyata industri dengan insentif & TOR Brief PDF.</p>
                        </div>
                        <a href="{{ route('vendor.projects.index') }}" class="text-xs font-bold text-purple-600 hover:text-purple-800">
                            Lihat Semua →
                        </a>
                    </div>

                    @if($projects->isEmpty())
                        <div class="p-6 bg-gray-50 border border-dashed border-gray-200 rounded-xl text-center space-y-2">
                            <p class="text-xs text-gray-500 font-medium">Belum ada project industri yang dipublikasikan.</p>
                            <a href="{{ route('vendor.projects.create') }}" class="inline-block px-3 py-1.5 bg-purple-600 text-white font-bold text-xs rounded-lg">
                                + Publikasikan Project Pertama
                            </a>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($projects->take(4) as $project)
                                <div class="p-3.5 bg-purple-50/50 border border-purple-100 rounded-xl flex items-center justify-between gap-3">
                                    <div>
                                        <h4 class="font-bold text-xs text-gray-900">{{ $project->title }}</h4>
                                        <div class="flex items-center gap-2 text-[10px] text-gray-500 mt-1">
                                            <span class="px-2 py-0.5 bg-purple-100 text-purple-800 font-bold rounded-md">{{ ucfirst($project->difficulty_level) }}</span>
                                            <span>• Kuota: {{ $project->participations_count }}/{{ $project->max_students }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ route('vendor.projects.talent-pool', $project) }}" class="px-2.5 py-1 bg-purple-600 text-white rounded-lg text-xs font-bold hover:bg-purple-700">
                                            🎯 Talent
                                        </a>
                                        <a href="{{ route('vendor.projects.show', $project) }}" class="px-2.5 py-1 bg-white border border-gray-200 text-gray-700 rounded-lg text-xs font-bold hover:bg-gray-100">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
