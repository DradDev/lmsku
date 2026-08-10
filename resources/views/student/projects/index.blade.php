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

            <!-- SSO UNDIP Style Instructor/Author Selector Dropdown Card for Projects -->
            <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-6 shadow-sm">
                <label class="block text-sm font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-indigo-600">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span>Daftar Author / Instructor</span>
                </label>

                <select id="author-project-filter" onchange="filterProjectsByAuthor()" class="w-full md:w-1/2 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-semibold text-gray-800 py-3 px-4 shadow-sm cursor-pointer">
                    <option value="all">-- Semua Author / Instructor --</option>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}">{{ $author->name }}</option>
                    @endforeach
                </select>

                <p class="text-xs text-gray-500 mt-2">
                    Pilih Author / Instructor untuk mengfilter dan menampilkan daftar project industri yang diunggah.
                </p>
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
                        @endphp

                        <div class="project-card bg-white border border-gray-100 shadow-sm hover:shadow-md transition rounded-2xl overflow-hidden flex flex-col" data-author-id="{{ $project->created_by }}">
                            <div class="p-5 flex-1">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <div>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 mb-2">
                                            Author: {{ $project->user->name ?? 'Vendor' }}
                                        </span>
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
                                    @elseif(!($project->eligibility['is_eligible'] ?? false))
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
        function filterProjectsByAuthor() {
            const selectedAuthorId = document.getElementById('author-project-filter').value;
            const cards = document.querySelectorAll('.project-card');

            cards.forEach(card => {
                const cardAuthorId = card.getAttribute('data-author-id');
                if (selectedAuthorId === 'all' || cardAuthorId === selectedAuthorId) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</x-app-layout>