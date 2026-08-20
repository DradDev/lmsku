<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-6">

            {{-- Header --}}
            <div class="mb-8 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                <div>
                    <a href="{{ route('admin.results.index', ['tab' => 'project']) }}"
                       class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-700 mb-3 transition">
                        ← Kembali ke Hasil Proyek
                    </a>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">Detail Pengerjaan Proyek</p>
                    <h1 class="text-3xl font-bold text-slate-900">{{ $project->title ?? 'Project Result' }}</h1>
                    <p class="mt-2 text-slate-500">
                        Rincian pengerjaan proyek mahasiswa, catatan kelulusan, dan status validasi sertifikat resmi berbasis Blockchain.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    @if(!$participation->is_verified && $participation->status === 'completed')
                        <form method="POST" action="{{ route('admin.results.project.verify', $participation->id) }}"
                              onsubmit="return confirm('Approve sertifikat project {{ addslashes($student->name ?? 'Mahasiswa') }} & catat ke Blockchain?');">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700 transition shadow-sm">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                <span>Approve & Catat ke Blockchain</span>
                            </button>
                        </form>
                    @elseif($participation->is_verified)
                        <form method="POST" action="{{ route('admin.results.project.integrity', $participation->id) }}">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white hover:bg-slate-800 transition shadow-sm">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                <span>Cek Integritas Blockchain</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 shadow-sm flex items-center gap-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700 shadow-sm flex items-center gap-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if(session('info'))
                <div class="mb-6 rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-700 shadow-sm flex items-center gap-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12.01" y2="16"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">

                {{-- Left: Project & Student Overview --}}
                <div class="xl:col-span-2 space-y-6">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between gap-4 mb-6">
                            <div>
                                <h2 class="text-xl font-semibold text-slate-900">Ringkasan Pengerjaan Proyek</h2>
                                <p class="mt-1 text-sm text-slate-500">Informasi partisipan, institusi pembuat, dan status pengerjaan.</p>
                            </div>
                            @if($participation->is_verified)
                                <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>
                                    <span>Verified Blockchain</span>
                                </span>
                            @elseif($participation->status === 'completed')
                                <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    <span>Menunggu Verifikasi Admin</span>
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                    {{ ucfirst($participation->status) }}
                                </span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Mahasiswa</p>
                                <p class="mt-1 text-lg font-bold text-slate-900">{{ $student->name ?? '-' }}</p>
                                <p class="text-xs text-slate-500">{{ $student->email ?? '-' }}</p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Pembuat / Instansi Proyek</p>
                                <p class="mt-1 text-lg font-bold text-slate-900">{{ $project->creator->name ?? 'Lecturer' }}</p>
                                <p class="text-xs text-slate-500">{{ $project->creator->institution->name ?? ($project->provider_type === 'external' ? 'Mitra Vendor' : 'Dosen Akademik') }}</p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Judul Proyek</p>
                                <p class="mt-1 text-base font-bold text-slate-900">{{ $project->title ?? '-' }}</p>
                                <p class="text-xs text-indigo-600 font-semibold">{{ $project->provider_type === 'external' ? 'Proyek Industri Eksternal' : 'Proyek Akademik Internal' }}</p>
                            </div>

                            <div class="rounded-2xl bg-indigo-50 border border-indigo-100 p-4">
                                <p class="text-xs font-bold uppercase tracking-wider text-indigo-400">Progres Akhir</p>
                                <p class="mt-1 text-3xl font-extrabold text-indigo-600">{{ $participation->progress_percent ?? 100 }}%</p>
                                <p class="text-xs text-indigo-700 font-semibold mt-0.5">Status: {{ ucfirst($participation->status) }}</p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Waktu Mulai</p>
                                <p class="mt-1 text-base font-semibold text-slate-900">
                                    {{ optional($participation->started_at ?? $participation->created_at)->format('d M Y, H:i') }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Waktu Selesai</p>
                                <p class="mt-1 text-base font-semibold text-slate-900">
                                    {{ optional($participation->completed_at ?? $participation->updated_at)->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>

                        @if($project->description)
                            <div class="mt-6 pt-6 border-t border-slate-200">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Deskripsi & Objektif Proyek</p>
                                <div class="text-sm text-slate-700 leading-relaxed bg-slate-50 rounded-2xl p-4 border border-slate-200">
                                    {{ $project->description }}
                                </div>
                            </div>
                        @endif

                        @if($project->skills && $project->skills->count())
                            <div class="mt-4">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Kompetensi Keahlian</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($project->skills as $sk)
                                        <span class="bg-indigo-50 text-indigo-700 border border-indigo-200 text-xs font-bold px-2.5 py-1 rounded-lg">
                                            {{ $sk->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Timeline Status Histori --}}
                    @if($participation->statusHistories && $participation->statusHistories->count())
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="text-xl font-semibold text-slate-900 mb-4">Riwayat Progres & Evaluasi</h2>
                            <div class="space-y-3">
                                @foreach($participation->statusHistories as $history)
                                    <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-2xl border border-slate-100 text-sm">
                                        <div class="w-2 h-2 rounded-full bg-indigo-600 mt-2 shrink-0"></div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="font-bold text-slate-900">{{ ucfirst($history->from_status) }} → {{ ucfirst($history->to_status) }}</p>
                                                <p class="text-xs text-slate-400">{{ optional($history->created_at)->format('d M Y H:i') }}</p>
                                            </div>
                                            @if($history->notes)
                                                <p class="text-xs text-slate-600 mt-1">{{ $history->notes }}</p>
                                            @endif
                                            <p class="text-[11px] text-slate-400 mt-0.5">Oleh: {{ $history->user->name ?? 'Sistem' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Right: Blockchain Certificate & Verification Panel --}}
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm h-fit">
                    <h2 class="text-xl font-semibold text-slate-900 mb-5">Validasi Sertifikat Blockchain</h2>

                    <div class="space-y-4">
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Validasi</p>
                            <p class="mt-1 text-lg font-bold {{ $participation->is_verified ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $participation->is_verified ? '✓ Terverifikasi di Blockchain' : 'Menunggu Approval Admin' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Credential Code</p>
                            <p class="text-xs font-mono font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-2.5 py-1.5 rounded-lg break-all">
                                {{ $certificate->credential_code ?? 'Belum diterbitkan' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Blockchain ID</p>
                            <p class="text-xs font-mono text-slate-700">
                                {{ $certificate->blockchain_id ?? ($participation->blockchain_id ?? 'Belum tersedia.') }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Transaction ID (TxID)</p>
                            <p class="break-all text-xs font-mono text-slate-600">
                                {{ $certificate->tx_id ?? ($participation->tx_id ?? 'Belum tersedia.') }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Cryptographic Hash (SHA-256)</p>
                            <p class="break-all text-xs font-mono text-slate-600">
                                {{ $certificate->blockchain_hash ?? 'Belum tersedia karena sertifikat belum diverifikasi.' }}
                            </p>
                        </div>

                        @if(!$participation->is_verified && $participation->status === 'completed')
                            <form method="POST" action="{{ route('admin.results.project.verify', $participation->id) }}"
                                  onsubmit="return confirm('Approve sertifikat project {{ addslashes($student->name ?? 'Mahasiswa') }} & catat ke Blockchain?');">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white hover:bg-emerald-700 transition shadow-sm">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                    <span>Approve & Catat ke Blockchain</span>
                                </button>
                            </form>
                        @elseif($participation->is_verified)
                            <form method="POST" action="{{ route('admin.results.project.integrity', $participation->id) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-slate-900 px-4 py-3 text-sm font-bold text-white hover:bg-slate-800 transition shadow-sm">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                    <span>Cek Integritas Blockchain</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
