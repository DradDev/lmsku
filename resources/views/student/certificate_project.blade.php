<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-5xl mx-auto px-6">
            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <a href="{{ route('student.certificate.index') }}"
                   class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                    ← Back to Certificates
                </a>

                <a href="{{ route('student.certificate.project.download', $project->id) }}"
                   class="inline-flex items-center rounded-xl bg-purple-600 px-5 py-3 text-sm font-semibold text-white hover:bg-purple-700 transition shadow-sm">
                    Download PDF Project Certificate
                </a>
            </div>

            <div class="bg-white border border-slate-200 rounded-3xl shadow-sm p-12 text-center relative overflow-hidden">
                <div class="mb-6">
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-purple-50 border border-purple-100 text-xs font-mono font-bold text-purple-700">
                        🔑 Credential ID: {{ $credentialCode }}
                    </span>
                </div>

                <p class="text-sm uppercase tracking-[0.3em] font-bold text-slate-400 mb-4">
                    Certificate of Project Completion
                </p>

                <h1 class="text-5xl font-bold text-slate-900 mb-4">
                    {{ $student->name }}
                </h1>

                <p class="text-lg text-slate-600 mb-8">
                    has successfully participated in, completed, and achieved verified completion for the industry project
                </p>

                <h2 class="text-3xl font-bold text-purple-600 mb-3">
                    {{ $project->title }}
                </h2>

                <p class="text-slate-500 mb-10 text-sm">
                    Author / Vendor: {{ $project->user->name ?? 'Vendor' }}
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Difficulty Level</p>
                        <p class="mt-1 text-2xl font-bold text-purple-600">{{ ucfirst($project->difficulty_level) }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Participation Status</p>
                        <p class="mt-1 text-xl font-bold text-emerald-600">Verified & Accepted</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Issued Date</p>
                        <p class="mt-1 text-xl font-bold text-slate-900">{{ optional($certificateRecord?->verified_at ?? $certificateRecord?->completed_at ?? now())->format('d M Y') }}</p>
                    </div>
                </div>

                @if($certificateRecord && $certificateRecord->blockchain_hash)
                    <div class="mt-8 p-5 bg-purple-50/70 border border-purple-200 rounded-2xl max-w-3xl mx-auto text-left space-y-2">
                        <div class="flex items-center justify-between border-b border-purple-200/60 pb-2">
                            <span class="text-xs font-extrabold text-purple-900 uppercase tracking-wider flex items-center gap-1.5">
                                <span>⛓️ Blockchain Cryptographic Ledger Verification</span>
                            </span>
                            <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-extrabold rounded-full border border-emerald-200">
                                Tamper-Proof Validated
                            </span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-purple-700">Blockchain ID:</span>
                                <p class="font-mono text-[11px] font-bold text-slate-800">{{ $certificateRecord->blockchain_id ?? 'BC-PRJ-001' }}</p>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-purple-700">Transaction ID (TxID):</span>
                                <p class="font-mono text-[11px] text-slate-700 truncate" title="{{ $certificateRecord->tx_id }}">{{ $certificateRecord->tx_id }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <span class="text-[10px] uppercase font-bold text-purple-700">Cryptographic SHA-256 Hash:</span>
                                <p class="font-mono text-[11px] text-purple-900 break-all bg-white/80 p-2 rounded-lg border border-purple-200 font-semibold">{{ $certificateRecord->blockchain_hash }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mt-10 pt-8 border-t border-slate-200 flex justify-between items-center text-xs text-slate-500">
                    <div>
                        <p class="font-bold text-slate-700">Project Publisher / Vendor</p>
                        <p>{{ $project->user->name ?? 'Vendor' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-slate-700">Credential ID</p>
                        <p class="font-mono text-purple-600 font-bold">{{ $credentialCode }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
