<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Project Catalog
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Select projects aligned with your skills and career specialization.
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-5 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            @if(isset($invitedParticipations) && $invitedParticipations->isNotEmpty())
                <div class="mb-6 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-lg text-amber-950 flex items-center gap-2">
                            <span>📩 Undangan Project Untukmu</span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-amber-600 text-white">
                                {{ $invitedParticipations->count() }} Undangan Baru
                            </span>
                        </h4>
                        <span class="text-xs text-amber-800 font-medium">Dosen/Vendor mengundangmu bergabung ke project!</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($invitedParticipations as $invitation)
                            @php $invProj = $invitation->project; @endphp
                            @if($invProj)
                                <div class="bg-white border border-amber-200 rounded-xl p-4 shadow-sm flex flex-col justify-between space-y-3">
                                    <div>
                                        <div class="flex items-center justify-between gap-2 mb-1.5">
                                            @if(($invProj->provider_type ?? 'internal') === 'external' || ($invProj->user->role ?? '') === 'vendor')
                                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md bg-purple-100 text-purple-800 border border-purple-200">
                                                    🏢 External: {{ $invProj->user->name ?? 'Vendor' }}
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md bg-blue-50 text-blue-700 border border-blue-200">
                                                    🎓 Internal: {{ $invProj->user->name ?? 'Dosen' }}
                                                </span>
                                            @endif
                                            <span class="text-[11px] text-gray-400 font-medium">Diundang {{ $invitation->created_at->diffForHumans() }}</span>
                                        </div>

                                        <h5 class="font-bold text-base text-gray-900 leading-snug">
                                            {{ $invProj->title }}
                                        </h5>
                                        <p class="text-xs text-gray-600 line-clamp-2 mt-1">
                                            {{ $invProj->description }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
                                        <form action="{{ route('student.projects.accept-invite', $invProj) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition shadow-sm flex items-center justify-center gap-1">
                                                🚀 Terima Undangan
                                            </button>
                                        </form>

                                        <form action="{{ route('student.projects.decline-invite', $invProj) }}" method="POST" onsubmit="return confirm('Tolak undangan project ini?')">
                                            @csrf
                                            <button type="submit" class="px-3 py-2 bg-gray-100 hover:bg-red-50 hover:text-red-700 text-gray-600 font-semibold text-xs rounded-xl transition border border-gray-200">
                                                ✕ Tolak
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- SSO UNDIP Style Instructor & Provider Filter Card for Projects -->
            <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <label class="text-sm font-bold text-gray-800 flex items-center gap-2">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-indigo-600">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                        </svg>
                        <span>Filter Katalog Project & Provider</span>
                    </label>
                    <span class="text-xs text-gray-500 font-medium">Multi-Tenant Filtering</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Pilih Author / Pembuat</label>
                        <select id="author-project-filter" onchange="filterProjects()" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs font-semibold text-gray-800 py-2.5 px-3 shadow-sm cursor-pointer">
                            <option value="all">-- Semua Author --</option>
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}">{{ $author->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Tipe Provider Project</label>
                        <select id="provider-type-filter" onchange="filterProjects()" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs font-semibold text-gray-800 py-2.5 px-3 shadow-sm cursor-pointer">
                            <option value="all">-- Semua Provider (Internal & External) --</option>
                            <option value="internal">🎓 Internal Dosen Akademik</option>
                            <option value="external">🏢 External Mitra Vendor Industri</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Status Kelayakan Mahasiswa</label>
                        <select id="eligibility-filter" onchange="filterProjects()" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs font-semibold text-gray-800 py-2.5 px-3 shadow-sm cursor-pointer">
                            <option value="all">-- Semua Project (Eligible & Terkunci) --</option>
                            <option value="eligible">🟢 Hanya Yang Memenuhi Syarat (Eligible)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Available Projects
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Browse projects you can apply for.
                    </p>
                </div>

                <a href="{{ route('student.projects.my') }}"
                   class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition">
                    Project Saya
                </a>
            </div>

            @if ($projects->isEmpty())
                <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-10 text-center">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Belum ada project tersedia
                    </h3>

                    <p class="text-sm text-gray-500 mt-2">
                        Project yang sudah dipublish oleh lecturer akan muncul di halaman ini.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" id="projects-grid">
                    @foreach ($projects as $project)
                        @php
                            $joinedCount = $project->participations_count ?? 0;
                            $maxStudents = $project->max_students ?? 1;
                            $isFull = $joinedCount >= $maxStudents;
                            $alreadyJoined = in_array($project->id, $joinedProjectIds);
                            $provType = $project->provider_type ?? (($project->user->role ?? '') === 'vendor' ? 'external' : 'internal');
                            $isEligible = $project->eligibility['is_eligible'] ?? false;
                        @endphp

                        <div class="project-card bg-white border border-gray-100 shadow-sm hover:shadow-md transition rounded-2xl overflow-hidden flex flex-col"
                             data-author-id="{{ $project->created_by }}"
                             data-provider-type="{{ $provType }}"
                             data-eligible="{{ $isEligible ? 'true' : 'false' }}">
                            <div class="p-5 flex-1">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <div>
                                        <div class="mb-2">
                                            @if($provType === 'external')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                                    🏢 External: {{ $project->user->name ?? 'Vendor' }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                    🎓 Internal: {{ $project->user->name ?? 'Dosen' }}
                                                </span>
                                            @endif
                                        </div>
                                        <h3 class="font-semibold text-lg text-gray-800 leading-snug">
                                            {{ $project->title }}
                                        </h3>
                                    </div>

                                    @if ($alreadyJoined)
                                        <span class="shrink-0 px-2.5 py-1 bg-green-50 text-green-700 text-xs font-medium rounded-full">
                                            Diambil
                                        </span>
                                    @endif
                                </div>

                                <p class="text-sm text-gray-600 line-clamp-3 mb-4">
                                    {{ $project->description }}
                                </p>

                                <div class="space-y-2 text-xs text-gray-500">
                                    <div class="flex items-center justify-between">
                                        <span>Level:</span>
                                        <span class="font-medium text-gray-700 capitalize">{{ $project->difficulty_level }}</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <span>Durasi:</span>
                                        <span class="font-medium text-gray-700">{{ $project->duration_days }} Hari</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <span>Kuota:</span>
                                        <span class="font-medium text-gray-700">{{ $joinedCount }} / {{ $maxStudents }} Pendaftar</span>
                                    </div>
                                </div>

                                @if ($project->skills->count() > 0)
                                    <div class="mt-4 pt-3 border-t border-gray-100">
                                        <p class="text-xs font-medium text-gray-500 mb-2">Required Skills:</p>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach ($project->skills as $skill)
                                                <span class="px-2 py-1 bg-indigo-50 text-indigo-700 text-xs rounded-md font-medium">
                                                    {{ $skill->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if ($project->tags->count() > 0)
                                    <div class="mt-3">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($project->tags as $tag)
                                                <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-md">
                                                    #{{ $tag->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-3">
                                <a href="{{ route('student.projects.show', $project) }}"
                                   class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                    Detail Project →
                                </a>

                                <div>
                                    @if ($alreadyJoined)
                                        <span class="text-xs font-medium text-gray-500">
                                            Sudah Bergabung
                                        </span>
                                    @elseif ($isFull)
                                        <button type="button"
                                                class="inline-flex items-center justify-center px-3 py-2 bg-gray-200 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed"
                                                disabled>
                                            Kuota Penuh
                                        </button>
                                    @elseif(!$isEligible)
                                        <a href="{{ route('student.projects.show', $project) }}"
                                           class="inline-flex items-center justify-center px-3 py-1.5 bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold rounded-lg hover:bg-amber-100 transition">
                                            🔒 Terkunci
                                        </a>
                                    @else
                                        <form action="{{ route('student.projects.join', $project) }}" method="POST">
                                            @csrf

                                            <button type="submit"
                                                    class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition shadow-sm">
                                                🚀 Ambil
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    <script>
        function filterProjects() {
            const authorId = document.getElementById('author-project-filter').value;
            const providerType = document.getElementById('provider-type-filter').value;
            const eligibility = document.getElementById('eligibility-filter').value;

            const cards = document.querySelectorAll('.project-card');

            cards.forEach(card => {
                const cardAuthor = card.getAttribute('data-author-id');
                const cardProvider = card.getAttribute('data-provider-type');
                const cardEligible = card.getAttribute('data-eligible');

                const matchAuthor = (authorId === 'all' || cardAuthor === authorId);
                const matchProvider = (providerType === 'all' || cardProvider === providerType);
                const matchEligible = (eligibility === 'all' || cardEligible === 'true');

                if (matchAuthor && matchProvider && matchEligible) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</x-app-layout>