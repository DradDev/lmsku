<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Project Details
        </h2>
    </x-slot>

    @php
    $statusLabels = [
    'in_progress' => 'In Progress',
    'development' => 'Development',
    'review' => 'Review',
    'completed' => 'Done',
    ];

    $statusColors = [
    'in_progress' => 'bg-yellow-100 text-yellow-800',
    'development' => 'bg-blue-100 text-blue-800',
    'review' => 'bg-purple-100 text-purple-800',
    'completed' => 'bg-green-100 text-green-800',
    ];

    $joinedCount = $project->participations->count();
    $maxStudents = $project->max_students ?? 1;
    $isFull = $joinedCount >= $maxStudents;

    $comments = $project->comments ?? collect();
    @endphp

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
            <div class="p-4 bg-green-100 text-green-700 rounded-xl">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="p-4 bg-red-100 text-red-700 rounded-xl">
                {{ session('error') }}
            </div>
            @endif

            @if (isset($hasCourseForSkill) && !$hasCourseForSkill)
            <div class="p-4 bg-amber-50 border border-amber-200 text-amber-900 rounded-xl space-y-1 shadow-sm">
                <div class="flex items-center gap-2 font-bold text-amber-900">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-amber-600"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y3="13"/><line x1="12" y1="17" x2="12.01" y3="17"/></svg>
                    <span>⚠️ Peringatan: Belum Ada Course Aktif untuk Main Skill ({{ $mainSkill->name ?? 'Skill' }})</span>
                </div>
                <p class="text-xs leading-relaxed text-amber-800">
                    Project ini membutuhkan kompetensi <strong>{{ $mainSkill->name ?? 'Skill Utama' }}</strong>, namun saat ini belum ada Course aktif di sistem yang menguji skill tersebut. Mahasiswa belum bisa memenuhi prasyarat kompetensi project ini sampai Course terkait dibuat.
                </p>
                <p class="text-xs font-semibold text-amber-900 pt-1">
                    💡 Disarankan untuk membuat Course baru dan menyusun kuis kelulusannya terlebih dahulu!
                </p>
            </div>
            @endif

            @if ($errors->any())
            <div class="p-4 bg-red-100 text-red-700 rounded-xl">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white shadow rounded-2xl p-6">
                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ $project->title }}
                        </h3>

                        <div class="mt-3 flex flex-wrap gap-2 text-sm">
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full">
                                Level: {{ $project->difficulty_level }}
                            </span>

                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full">
                                Duration: {{ $project->duration_days }} days
                            </span>

                            <span class="px-3 py-1 rounded-full {{ $project->is_published ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $project->is_published ? 'Published' : 'Draft' }}
                            </span>

                            <span class="px-3 py-1 rounded-full {{ $isFull ? 'bg-red-100 text-red-700' : 'bg-indigo-100 text-indigo-700' }}">
                                Quota: {{ $joinedCount }}/{{ $maxStudents }} students
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('lecturer.projects.index') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-200 transition">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                            Back to Projects
                        </a>

                        <a href="{{ route('lecturer.projects.talent-pool', $project) }}"
                            class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl text-sm font-semibold flex items-center gap-1.5 shadow-sm transition">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m10 15 5-3-5-3v6z"/></svg>
                            🎯 Talent Pool & Matching
                        </a>

                        <a href="{{ route('lecturer.projects.edit', $project) }}"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition">
                            Edit Project
                        </a>
                    </div>
                </div>

                <div class="mt-6">
                    <h4 class="font-semibold text-gray-900 mb-2">
                        Project Description
                    </h4>

                    <p class="text-gray-700 whitespace-pre-line leading-relaxed">
                        {{ $project->description }}
                    </p>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">
                            Primary Skill Requirement
                        </h4>

                        @forelse ($project->skills as $skill)
                        <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 font-medium rounded-full text-sm mr-1 mb-2">
                            {{ $skill->name }}

                            @if ($skill->pivot->is_main)
                            <span class="font-semibold">(Main)</span>
                            @endif
                        </span>
                        @empty
                        <p class="text-sm text-gray-500">
                            No skill specified.
                        </p>
                        @endforelse
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">
                            Specialty Tags
                        </h4>

                        @forelse ($project->tags as $tag)
                        <span class="inline-block px-3 py-1 bg-green-50 text-green-700 font-medium rounded-full text-sm mr-1 mb-2">
                            {{ $tag->name }}
                        </span>
                        @empty
                        <p class="text-sm text-gray-500">
                            No tags specified.
                        </p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-2xl p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">
                    <div>
                        <h3 class="font-bold text-xl text-gray-900">
                            Student Project Participants & Progress
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Monitor progress and execution status of students enrolled in this project.
                        </p>
                    </div>

                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-semibold">
                        {{ $joinedCount }} Participants
                    </span>
                </div>

                @if ($project->participations->isEmpty())
                <div class="border border-gray-200 rounded-xl p-5 bg-gray-50 text-center">
                    <p class="text-gray-600 text-sm">
                        No students have joined this project yet.
                    </p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-200 text-sm rounded-xl overflow-hidden">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="border p-3 font-semibold text-gray-700">Student</th>
                                <th class="border p-3 font-semibold text-gray-700">Status</th>
                                <th class="border p-3 font-semibold text-gray-700">Progress</th>
                                <th class="border p-3 font-semibold text-gray-700">Last Activity</th>
                                <th class="border p-3 font-semibold text-gray-700">Latest Notes</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($project->participations as $participation)
                            @php
                            $latestHistory = $participation->statusHistories
                            ->sortByDesc('created_at')
                            ->first();

                            $status = $participation->status;
                            $progress = $participation->progress_percent ?? 0;
                            @endphp

                            <tr>
                                <td class="border p-3 align-top">
                                    <div class="font-semibold text-gray-900">
                                        {{ $participation->user->name ?? 'Unknown User' }}
                                    </div>

                                    <div class="text-xs text-gray-500">
                                        {{ $participation->user->email ?? '-' }}
                                    </div>
                                </td>

                                <td class="border p-3 align-top">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $statusLabels[$status] ?? $status }}
                                    </span>
                                </td>

                                <td class="border p-3 align-top min-w-[180px]">
                                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                                        <span>Progress</span>
                                        <span>{{ $progress }}%</span>
                                    </div>

                                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                        <div class="h-3 rounded-full {{ $progress >= 100 ? 'bg-green-600' : 'bg-blue-600' }}"
                                            style="width: {{ $progress }}%">
                                        </div>
                                    </div>
                                </td>

                                <td class="border p-3 align-top text-gray-700">
                                    {{ optional($participation->last_activity_at)->format('d M Y H:i') ?? '-' }}
                                </td>

                                <td class="border p-3 align-top">
                                    @if ($latestHistory && $latestHistory->note)
                                    <p class="text-gray-700">
                                        {{ $latestHistory->note }}
                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $latestHistory->created_at->format('d M Y H:i') }}
                                    </p>
                                    @else
                                    <span class="text-gray-400">
                                        No notes yet.
                                    </span>
                                    @endif
                                </td>
                            </tr>

                            @if ($participation->statusHistories->isNotEmpty())
                            <tr>
                                <td colspan="5" class="border p-3 bg-gray-50">
                                    <details>
                                        <summary class="cursor-pointer font-semibold text-sm text-blue-700">
                                            View progress history
                                        </summary>

                                        <div class="mt-3 space-y-3">
                                            @foreach ($participation->statusHistories->sortByDesc('created_at') as $history)
                                            <div class="border rounded-xl p-3 bg-white">
                                                <div class="flex flex-col md:flex-row md:justify-between gap-1">
                                                    <div>
                                                        <span class="text-gray-500">
                                                            {{ $statusLabels[$history->old_status] ?? $history->old_status ?? '-' }}
                                                        </span>

                                                        <span class="mx-2">→</span>

                                                        <span class="font-semibold">
                                                            {{ $statusLabels[$history->new_status] ?? $history->new_status }}
                                                        </span>
                                                    </div>

                                                    <div class="text-xs text-gray-500">
                                                        {{ $history->created_at->format('d M Y H:i') }}
                                                    </div>
                                                </div>

                                                <div class="text-sm text-gray-600 mt-1">
                                                    Progress:
                                                    {{ $history->old_progress_percent ?? 0 }}%
                                                    →
                                                    {{ $history->new_progress_percent ?? 0 }}%
                                                </div>

                                                @if ($history->note)
                                                <p class="text-sm text-gray-700 mt-2">
                                                    {{ $history->note }}
                                                </p>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </details>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            <div class="bg-white shadow rounded-2xl p-6">
                <h3 class="font-bold text-xl text-gray-900 mb-2">
                    Project Comments & Feedback
                </h3>

                <p class="text-sm text-gray-500 mb-4">
                    Reply to student inquiries or provide direct feedback on project progress.
                </p>

                <form action="{{ route('projects.comments.store', $project) }}" method="POST" class="mb-6">
                    @csrf

                    <input type="hidden" name="comment_type" value="feedback">

                    <textarea
                        name="comment"
                        class="border border-gray-300 rounded-xl w-full p-3 text-sm"
                        rows="3"
                        placeholder="Write feedback for students..."
                        required>{{ old('comment') }}</textarea>

                    @error('comment')
                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>
                    @enderror

                    <button type="submit"
                        class="mt-3 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition">
                        Send Feedback
                    </button>
                </form>

                <div class="space-y-3">
                    @forelse ($comments->sortByDesc('created_at') as $comment)
                    <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                        <div class="flex justify-between gap-3">
                            <div>
                                <p class="font-semibold text-gray-900">
                                    {{ $comment->user->name ?? 'Unknown User' }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    {{ ucfirst(str_replace('_', ' ', $comment->comment_type)) }}
                                    ·
                                    {{ $comment->created_at->format('d M Y H:i') }}
                                </p>
                            </div>
                        </div>

                        <p class="text-gray-700 mt-3 whitespace-pre-line text-sm">
                            {{ $comment->comment }}
                        </p>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">
                        No comments on this project yet.
                    </p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>