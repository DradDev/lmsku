<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <span>COMPRO Talent Screening Engine (Vendor)</span>
            </h2>

            <a href="{{ route('vendor.projects.show', $project) }}" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold text-xs rounded-xl">
                ← Kembali ke Detail Project
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
            <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-xl shadow-sm font-semibold text-xs">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="p-4 bg-red-100 border border-red-300 text-red-700 rounded-xl shadow-sm font-semibold text-xs">
                {{ session('error') }}
            </div>
            @endif

            <!-- Hero Banner Card -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-purple-950 rounded-2xl p-6 shadow-md text-white space-y-3">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-0.5 bg-purple-500/30 text-purple-200 border border-purple-400/30 rounded-md text-[10px] font-black uppercase">
                        {{ Auth::user()->name }}
                    </span>
                    <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 font-bold text-xs rounded-full border border-emerald-400/30">
                        Primary Skill: {{ $mainSkill->name ?? 'Skill Requirement' }}
                    </span>
                </div>

                <h3 class="text-xl font-black text-white">
                    Peringkat Kandidat Mahasiswa Berbakat — {{ $project->title }}
                </h3>
                <p class="text-xs text-indigo-200 max-w-2xl leading-relaxed">
                    Disaring secara real-time berdasarkan kelulusan kuis sertifikasi, penguasaan skill, dan skor kecocokan portofolio.
                </p>
            </div>

            <!-- Talent Candidate Grid -->
            @if ($recommendedStudents->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center space-y-2 shadow-sm">
                <p class="text-xs text-gray-500 font-medium">Belum ada mahasiswa yang memenuhi kualifikasi talent pool untuk skill ini.</p>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($recommendedStudents as $recStudent)
                @php
                    $mScore = $recStudent->match_score;
                    $scoreColor = $mScore >= 80 ? 'bg-emerald-500 text-white' : ($mScore >= 60 ? 'bg-indigo-600 text-white' : 'bg-amber-500 text-white');
                @endphp

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 space-y-4 flex flex-col justify-between hover:shadow-md transition">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-black text-sm">
                                    {{ strtoupper(substr($recStudent->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-gray-900">{{ $recStudent->name }}</h4>
                                    <span class="text-[10px] text-gray-500 font-medium block">{{ $recStudent->peminatan ?? 'General' }}</span>
                                </div>
                            </div>

                            <span class="px-2.5 py-1 rounded-full text-xs font-black {{ $scoreColor }}">
                                {{ $mScore }}%
                            </span>
                        </div>

                        <div class="pt-2 border-t border-gray-100 space-y-1.5 text-xs text-gray-600">
                            <div class="flex justify-between">
                                <span>Main Skill Verified:</span>
                                <span class="font-bold {{ $recStudent->has_main_skill ? 'text-emerald-600' : 'text-gray-400' }}">
                                    {{ $recStudent->has_main_skill ? '✓ Ya' : '✗ Tidak' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span>Sertifikat Terverifikasi:</span>
                                <span class="font-bold {{ $recStudent->has_verified_certificate ? 'text-emerald-600' : 'text-gray-400' }}">
                                    {{ $recStudent->has_verified_certificate ? '✓ Ya' : '✗ Tidak' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center gap-2">
                        <a href="{{ route('vendor.students.portfolio', $recStudent) }}" target="_blank"
                           class="flex-1 text-center py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">
                            Portofolio
                        </a>

                        @if($recStudent->invitation_status === 'invited')
                            <span class="py-2 px-3 bg-amber-100 text-amber-800 font-bold text-xs rounded-xl">
                                Invited
                            </span>
                        @elseif(in_array($recStudent->invitation_status, ['in_progress', 'development', 'review', 'completed']))
                            <span class="py-2 px-3 bg-emerald-100 text-emerald-800 font-bold text-xs rounded-xl">
                                Joined
                            </span>
                        @else
                            <form action="{{ route('vendor.projects.invite', [$project, $recStudent]) }}" method="POST">
                                @csrf
                                <button type="submit" class="py-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition shadow-sm">
                                    + Invite Talent
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
