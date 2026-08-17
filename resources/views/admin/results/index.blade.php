<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                    Admin Panel
                </p>

                <h1 class="text-3xl font-bold text-slate-900">
                    {{ $activeTab === 'project' ? 'Project Results & Certificates' : 'Final Quiz Results' }}
                </h1>
                <p class="mt-2 text-slate-500">
                    {{ $activeTab === 'project' ? 'Monitor student project completions, verify project certificates & track talent accomplishments.' : 'Monitor student quiz attempts, verification status, and detailed scores.' }}
                </p>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 shadow-sm flex items-center gap-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- STATS CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Results</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $totalResults }}</p>
                </div>

                <div class="rounded-2xl border border-emerald-200 bg-white p-5 shadow-xs">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Verified</p>
                    <p class="mt-3 text-3xl font-bold text-emerald-600">{{ $verifiedCount }}</p>
                </div>

                <div class="rounded-2xl border border-amber-200 bg-white p-5 shadow-xs">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Pending</p>
                    <p class="mt-3 text-3xl font-bold text-amber-500">{{ $pendingCount }}</p>
                </div>
            </div>

            {{-- TABS SWITCHER --}}
            <div class="mb-6 flex border-b border-slate-200 gap-6 text-sm font-bold">
                <a href="{{ route('admin.results.index', ['tab' => 'quiz']) }}"
                   class="pb-3 border-b-2 transition flex items-center gap-2 {{ $activeTab === 'quiz' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    <span>Final Quiz Results ({{ $totalQuizResults }})</span>
                </a>

                <a href="{{ route('admin.results.index', ['tab' => 'project']) }}"
                   class="pb-3 border-b-2 transition flex items-center gap-2 {{ $activeTab === 'project' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                    <span>Project Results ({{ $totalProjectResults }})</span>
                </a>
            </div>

            {{-- TAB CONTENT 1: FINAL QUIZ RESULTS --}}
            @if($activeTab === 'quiz')
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <h2 class="text-xl font-semibold text-slate-900">Antrean Verifikasi Sertifikat & Blockchain</h2>
                                <p class="mt-1 text-sm text-slate-500">
                                    Hanya menampilkan peserta yang <strong>lulus nilai minimum (Passing Grade Threshold ≥ 75)</strong> — siap di-approve Admin & dicatat ke Blockchain.
                                </p>
                            </div>
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-xl text-xs font-bold self-start sm:self-auto">
                                Filter Nilai Minimum (Lulus Threshold)
                            </span>
                        </div>
                    </div>

                    @if($results->count())
                        <div class="divide-y divide-slate-200">
                            @foreach ($results as $result)
                                <div class="px-6 py-5 hover:bg-slate-50 transition">
                                    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap mb-2">
                                                <h3 class="text-lg font-semibold text-slate-900">
                                                    {{ $result->quiz->title ?? 'Quiz' }}
                                                </h3>

                                                @if($result->is_verified)
                                                    <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                        ✓ Verified & Blockchain Registered
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                                        Pending Admin Approval
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                                                <div>
                                                    <p class="text-slate-400">Student</p>
                                                    <p class="font-medium text-slate-800">{{ $result->user->name ?? 'Unknown Student' }}</p>
                                                </div>

                                                <div>
                                                    <p class="text-slate-400">Course</p>
                                                    <p class="font-medium text-slate-800">{{ $result->quiz->course->name ?? '-' }}</p>
                                                </div>

                                                <div>
                                                    <p class="text-slate-400">Submitted</p>
                                                    <p class="font-medium text-slate-800">
                                                        {{ optional($result->created_at)->format('d M Y, H:i') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 xl:justify-end">
                                            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 px-5 py-3 min-w-[120px] text-center">
                                                <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">Nilai Final (Lulus)</p>
                                                <p class="mt-0.5 text-2xl font-black text-emerald-800">{{ $result->score }}</p>
                                            </div>

                                            <div class="flex flex-wrap gap-2">
                                                <a href="{{ route('admin.results.show', $result->id) }}"
                                                   class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 transition">
                                                    Detail Hasil
                                                </a>

                                                @if(!$result->is_verified)
                                                    <form method="POST" action="{{ route('admin.results.verify', $result->id) }}"
                                                          onsubmit="return confirm('Approve sertifikat & catat data kelulusan {{ addslashes($result->user->name ?? 'Mahasiswa') }} ke Blockchain?');">
                                                        @csrf
                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center rounded-xl bg-emerald-600 hover:bg-emerald-700 px-4 py-2 text-sm font-extrabold text-white shadow-sm transition">
                                                            Approve & Catat ke Blockchain
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-6 py-12 text-center">
                            <div class="mx-auto max-w-md">
                                <h3 class="text-lg font-semibold text-slate-900">Belum ada hasil quiz</h3>
                                <p class="mt-2 text-sm text-slate-500">
                                    Result quiz mahasiswa akan muncul di sini setelah ada submission.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- TAB CONTENT 2: PROJECT RESULTS --}}
            @if($activeTab === 'project')
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <h2 class="text-xl font-semibold text-slate-900">Project Completions & Certifications</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Daftar pengerjaan project mahasiswa — dasar penerbitan Sertifikat Project & verifikasi kredensial.
                        </p>
                    </div>

                    @if($projectParticipations->count())
                        <div class="divide-y divide-slate-200">
                            @foreach ($projectParticipations as $part)
                                <div class="px-6 py-5 hover:bg-slate-50 transition">
                                    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap mb-2">
                                                <h3 class="text-lg font-semibold text-slate-900">
                                                    {{ $part->project->title ?? 'Project' }}
                                                </h3>

                                                @if($part->is_verified)
                                                    <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                        Verified Certificate
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                                        Pending Admin Review
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                                                <div>
                                                    <p class="text-slate-400">Student Talent</p>
                                                    <p class="font-medium text-slate-800">{{ $part->user->name ?? 'Unknown Student' }}</p>
                                                </div>

                                                <div>
                                                    <p class="text-slate-400">Author / Vendor</p>
                                                    <p class="font-medium text-slate-800">{{ $part->project->creator->name ?? 'Lecturer' }}</p>
                                                </div>

                                                <div>
                                                    <p class="text-slate-400">Joined / Completed</p>
                                                    <p class="font-medium text-slate-800">
                                                        {{ optional($part->created_at)->format('d M Y, H:i') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 xl:justify-end">
                                            <div class="rounded-2xl bg-purple-50 border border-purple-100 px-5 py-3 min-w-[140px] text-center">
                                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Status Project</p>
                                                <p class="mt-1 text-sm font-bold text-purple-700">{{ ucfirst(str_replace('_', ' ', $part->status)) }}</p>
                                            </div>

                                            <div class="flex flex-wrap gap-2">
                                                @if(!$part->is_verified)
                                                    <form method="POST" action="{{ route('admin.results.project.verify', $part->id) }}">
                                                        @csrf
                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-emerald-700 transition shadow-sm">
                                                            ✅ Verify Project Certificate
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="px-3 py-2 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-xl border border-emerald-100 flex items-center gap-1">
                                                        <span>✓ Sertifikat Terverifikasi</span>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-6 py-12 text-center">
                            <div class="mx-auto max-w-md">
                                <h3 class="text-lg font-semibold text-slate-900">Belum ada partisipasi project</h3>
                                <p class="mt-2 text-sm text-slate-500">
                                    Daftar partisipasi project mahasiswa akan muncul di sini setelah ada pendaftaran project.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
