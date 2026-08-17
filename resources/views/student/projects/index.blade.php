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

            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Available Projects
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Browse projects you can apply for.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    @if(isset($invitedParticipations) && $invitedParticipations->isNotEmpty())
                        <a href="{{ route('student.projects.invitations') }}"
                           class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition shadow-sm animate-pulse">
                            <span>Undangan Project</span>
                            <span class="px-2 py-0.5 rounded-full bg-white text-amber-900 text-xs font-black">
                                {{ $invitedParticipations->count() }}
                            </span>
                        </a>
                    @else
                        <a href="{{ route('student.projects.invitations') }}"
                           class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                            <span>Undangan Project</span>
                        </a>
                    @endif

                    <a href="{{ route('student.projects.my') }}"
                       class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold rounded-xl transition">
                        Project Saya
                    </a>
                </div>
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
                                                    External: {{ $project->user->name ?? 'Vendor' }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                    Internal: {{ $project->user->name ?? 'Dosen' }}
                                                </span>
                                            @endif
                                        </div>
                                        <h3 class="font-semibold text-lg text-gray-800 leading-snug">
                                            {{ $project->title }}
                                        </h3>
                                    </div>

                                    @if ($alreadyJoined)
                                        <span class="shrink-0 px-2.5 py-1 bg-green-50 text-green-700 text-xs font-bold rounded-full border border-green-200">
                                            Diambil
                                        </span>
                                    @elseif(!$isEligible)
                                        <span class="shrink-0 px-2.5 py-1 bg-amber-50 text-amber-800 text-xs font-bold rounded-full border border-amber-200">
                                            🔒 Terkunci
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
                                @if ($alreadyJoined)
                                    <a href="{{ route('student.projects.show', $project) }}"
                                       class="text-sm text-indigo-600 hover:text-indigo-700 font-bold">
                                        Buka Proyek →
                                    </a>
                                    <span class="text-xs font-bold text-emerald-700">
                                        ✓ Terdaftar
                                    </span>
                                @elseif(!$isEligible)
                                    <a href="{{ route('student.projects.show', $project) }}"
                                       class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                        Lihat Prasyarat →
                                    </a>
                                    <a href="{{ route('student.projects.show', $project) }}"
                                       class="inline-flex items-center justify-center px-3 py-1.5 bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold rounded-lg hover:bg-amber-100 transition shadow-xs"
                                       title="Lihat mata kuliah prasyarat untuk membuka proyek ini">
                                        🔒 Terkunci (Syarat)
                                    </a>
                                @elseif ($isFull)
                                    <a href="{{ route('student.projects.show', $project) }}"
                                       class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                        Detail Project →
                                    </a>
                                    <button type="button"
                                            class="inline-flex items-center justify-center px-3 py-2 bg-gray-200 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed"
                                            disabled>
                                        Kuota Penuh
                                    </button>
                                @else
                                    <a href="{{ route('student.projects.show', $project) }}"
                                       class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                        Detail Project →
                                    </a>
                                    <form action="{{ route('student.projects.join', $project) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center justify-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg transition shadow-sm">
                                            Ambil
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