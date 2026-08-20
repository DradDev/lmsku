<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-6">

            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                    Student Portal · Credentials
                </p>
                <h1 class="text-3xl font-bold text-slate-900">Verified Certificates</h1>
                <p class="mt-2 text-slate-500">
                    Sertifikat resmi kelulusan Mata Kuliah Kompetensi dan Penyelesaian Proyek Industri terverifikasi.
                </p>
            </div>

            <!-- Tab Buttons (Course Certificates vs Project Certificates) -->
            <div class="flex border-b border-gray-200 mb-8 gap-2">
                <button type="button"
                        onclick="switchTab('courses', this)"
                        class="tab-btn active inline-flex items-center gap-2 px-6 py-3 border-b-2 border-indigo-600 font-bold text-sm text-indigo-600 focus:outline-none transition">
                    <span>Course Certificates</span>
                    <span class="px-2 py-0.5 rounded-full text-xs bg-indigo-100 text-indigo-700 font-extrabold">{{ $courses->count() }}</span>
                </button>

                <button type="button"
                        onclick="switchTab('projects', this)"
                        class="tab-btn inline-flex items-center gap-2 px-6 py-3 border-b-2 border-transparent font-bold text-sm text-gray-500 hover:text-gray-700 focus:outline-none transition">
                    <span>Project Certificates</span>
                    <span class="px-2 py-0.5 rounded-full text-xs bg-purple-100 text-purple-700 font-extrabold">{{ $projects->count() }}</span>
                </button>
            </div>

            <!-- TAB 1: COURSE CERTIFICATES -->
            <div id="tab-courses" class="tab-content">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    @forelse($courses as $course)
                        @php
                            $courseName = $course->name ?? ($course->masterCourse->name ?? 'Course');
                            $instructor = $course->user ?? ($course->lecturer ?? null);
                            $instructorName = $instructor?->name ?? 'Dosen Pengampu';
                            $institutionName = $instructor?->institution?->name ?? null;
                        @endphp
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4 hover:shadow-md transition flex flex-col">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <span class="text-[11px] font-mono font-bold tracking-wider text-indigo-600 uppercase bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100 block mb-2 w-max">
                                        {{ $course->credential_code }}
                                    </span>
                                    <h2 class="text-lg font-bold text-slate-900 leading-snug">{{ $courseName }}</h2>
                                </div>

                                @if($course->can_get_certificate)
                                    <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 shrink-0">
                                        Verified
                                    </span>
                                @elseif(isset($course->certificate_record) && $course->certificate_record?->status === 'pending')
                                    <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700 shrink-0">
                                        Pending Review
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600 shrink-0">
                                        Locked
                                    </span>
                                @endif
                            </div>

                            <div class="space-y-2 text-xs flex-1">
                                <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                    <p class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Instructor / Institution</p>
                                    <p class="mt-0.5 font-bold text-slate-800">
                                        {{ $instructorName }}
                                        @if($institutionName)
                                            <span class="font-normal text-slate-500">• {{ $institutionName }}</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                    <p class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Status Kualifikasi</p>
                                    <p class="mt-0.5 font-semibold text-slate-700">{{ $course->certificate_status_text }}</p>
                                </div>

                                @if($course->verified_final_attempt)
                                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 flex justify-between items-center">
                                        <p class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Nilai Akhir Terverifikasi</p>
                                        <p class="font-black text-indigo-600 text-lg">{{ $course->verified_final_attempt->score }} / 100</p>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-wrap gap-2 pt-3 border-t border-slate-100 mt-auto">
                                @if($course->can_get_certificate)
                                    <a href="{{ route('student.certificate.show', $course->id) }}"
                                       class="inline-flex items-center gap-1.5 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-bold text-white hover:bg-slate-800 transition">
                                        View Certificate
                                    </a>

                                    <a href="{{ route('student.certificate.download', $course->id) }}"
                                       class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white hover:bg-indigo-700 transition shadow-sm">
                                        Download PDF
                                    </a>
                                @else
                                    <a href="{{ route('student.courses.show', $course->id) }}"
                                       class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                                        Buka Kursus
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-slate-500 shadow-sm">
                            Belum ada kursus yang terdaftar.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- TAB 2: PROJECT CERTIFICATES -->
            <div id="tab-projects" class="tab-content hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    @forelse($projects as $project)
                        @php
                            $creator = $project->creator ?? $project->user;
                            $creatorName = $creator?->name ?? 'Pembimbing';
                            $instName = $creator?->institution?->name ?? null;
                            $part = $project->participation;
                        @endphp
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4 hover:shadow-md transition flex flex-col">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <span class="text-[11px] font-mono font-bold tracking-wider text-purple-600 uppercase bg-purple-50 px-2.5 py-1 rounded-md border border-purple-100 block mb-2 w-max">
                                        {{ $project->credential_code }}
                                    </span>
                                    <h2 class="text-lg font-bold text-slate-900 leading-snug">{{ $project->title }}</h2>
                                </div>

                                @if($project->status_badge === 'Verified')
                                    <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-extrabold text-emerald-800 shrink-0">
                                        <span>⛓️ Verified Blockchain</span>
                                    </span>
                                @elseif($project->status_badge === 'Pending')
                                    <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-extrabold text-amber-800 shrink-0">
                                        <span>⏳ Menunggu Verifikasi Admin</span>
                                    </span>
                                @elseif($project->status_badge === 'Review')
                                    <span class="inline-flex items-center rounded-full border border-purple-200 bg-purple-50 px-3 py-1 text-xs font-bold text-purple-700 shrink-0">
                                        Under Review
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700 shrink-0">
                                        In Progress ({{ $part->progress_percent ?? 0 }}%)
                                    </span>
                                @endif
                            </div>

                            <div class="space-y-2 text-xs flex-1">
                                <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                    <p class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Author / Mitra Vendor</p>
                                    <p class="mt-0.5 font-bold text-slate-800">
                                        {{ $creatorName }}
                                        @if($instName)
                                            <span class="font-normal text-slate-500">• {{ $instName }}</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                    <p class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Tipe & Level Proyek</p>
                                    <p class="mt-0.5 font-semibold text-slate-700">
                                        {{ $project->provider_type === 'internal' ? 'Proyek Kampus' : 'Proyek Industri' }}
                                        • Level: <span class="font-bold text-purple-700">{{ ucfirst($project->difficulty_level) }}</span>
                                    </p>
                                </div>

                                <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                    <p class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Status Sertifikat</p>
                                    <p class="mt-0.5 font-semibold text-slate-700 leading-relaxed">{{ $project->certificate_status_text }}</p>
                                </div>

                                @if($project->skills->count() > 0)
                                    <div class="pt-1 flex flex-wrap gap-1.5">
                                        @foreach($project->skills as $sk)
                                            <span class="px-2 py-0.5 rounded-md bg-purple-50 text-purple-800 text-[10px] font-bold border border-purple-100">
                                                {{ $sk->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-wrap gap-2 pt-3 border-t border-slate-100 mt-auto">
                                @if($project->can_get_certificate)
                                    <a href="{{ route('student.certificate.project.show', $project->id) }}"
                                       class="inline-flex items-center gap-1.5 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-bold text-white hover:bg-slate-800 transition">
                                        <span>👁️ Lihat Sertifikat</span>
                                    </a>

                                    <a href="{{ route('student.certificate.project.download', $project->id) }}"
                                       class="inline-flex items-center gap-1.5 rounded-xl bg-purple-600 px-4 py-2.5 text-xs font-bold text-white hover:bg-purple-700 transition shadow-sm">
                                        <span>📥 Unduh PDF</span>
                                    </a>
                                @else
                                    <a href="{{ route('student.projects.show', $project->id) }}"
                                       class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                                        <span>Buka Detail Proyek</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-slate-500 shadow-sm">
                            Belum ada project yang Anda ikuti. Silakan jelajahi menu <strong>Projects</strong> untuk mengambil proyek kampus atau industri.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <script>
        function switchTab(tabName, btn) {
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('border-indigo-600', 'text-indigo-600');
                b.classList.add('border-transparent', 'text-gray-500');
            });
            btn.classList.remove('border-transparent', 'text-gray-500');
            btn.classList.add('border-indigo-600', 'text-indigo-600');

            document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
            document.getElementById('tab-' + tabName).classList.remove('hidden');
        }
    </script>
</x-app-layout>
