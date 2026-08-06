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

        $currentStatus = $participation?->status;
        $currentProgress = $participation?->progress_percent ?? 0;

        $joinedCount = $project->participations()->count();
        $maxStudents = $project->max_students ?? 1;
        $isFull = $joinedCount >= $maxStudents;

        $comments = $project->comments ?? collect();
    @endphp

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

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
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-2xl text-gray-900">
                            {{ $project->title }}
                        </h3>

                        <div class="mt-3 flex flex-wrap gap-2 text-sm">
                            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700">
                                Level: {{ $project->difficulty_level }}
                            </span>

                            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700">
                                Duration: {{ $project->duration_days }} days
                            </span>

                            <span class="px-3 py-1 rounded-full {{ $isFull ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                Quota: {{ $joinedCount }}/{{ $maxStudents }} students
                            </span>

                            @if ($participation)
                                <span class="px-3 py-1 rounded-full {{ $statusColors[$currentStatus] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $statusLabels[$currentStatus] ?? $currentStatus }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('student.projects.index') }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                            Back to Projects
                        </a>

                        @if ($participation)
                            <a href="{{ route('student.projects.my') }}"
                               class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded-xl text-sm transition">
                                My Projects
                            </a>
                        @endif
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

            @if ($participation)
                <div class="bg-white shadow rounded-2xl p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">
                        <div>
                            <h4 class="font-semibold text-lg text-gray-900">
                                Your Project Progress
                            </h4>

                            <p class="text-sm text-gray-500 mt-1">
                                Update project execution status according to your current phase.
                            </p>
                        </div>

                        <div class="text-left md:text-right">
                            <p class="text-sm text-gray-500">
                                Current Status
                            </p>

                            <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-semibold {{ $statusColors[$currentStatus] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $statusLabels[$currentStatus] ?? $currentStatus }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-6">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Progress</span>
                            <span>{{ $currentProgress }}%</span>
                        </div>

                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div class="h-3 rounded-full bg-blue-600"
                                 style="width: {{ $currentProgress }}%">
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('student.projects.update-progress', $project) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold mb-2 text-sm text-gray-700">
                                    Update Status
                                </label>

                                <select name="status"
                                        class="border border-gray-300 rounded-xl w-full p-2.5 text-sm"
                                        required>
                                    <option value="in_progress" @selected($currentStatus === 'in_progress')>
                                        In Progress
                                    </option>

                                    <option value="development" @selected($currentStatus === 'development')>
                                        Development
                                    </option>

                                    <option value="review" @selected($currentStatus === 'review')>
                                        Review
                                    </option>

                                    <option value="completed" @selected($currentStatus === 'completed')>
                                        Done
                                    </option>
                                </select>

                                <p class="text-xs text-gray-400 mt-1">
                                    Select Done when project is completed.
                                </p>
                            </div>

                            <div>
                                <label class="block font-semibold mb-2 text-sm text-gray-700">
                                    Progress Notes
                                </label>

                                <textarea name="note"
                                          class="border border-gray-300 rounded-xl w-full p-2.5 text-sm"
                                          rows="3"
                                          placeholder="Example: core features completed, waiting for review..."></textarea>
                            </div>
                        </div>

                        <div class="mt-5">
                            <button type="submit"
                                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition">
                                Update Progress
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-white shadow rounded-2xl p-6">
                    <h4 class="font-semibold text-lg text-gray-900 mb-2">
                        Join This Project
                    </h4>

                    <p class="text-gray-600 text-sm mb-4">
                        After joining this project, you can update execution progress through In Progress, Development, Review, to Done.
                    </p>

                    <div class="mb-4">
                        <p class="text-sm {{ $isFull ? 'text-red-600' : 'text-green-600' }}">
                            <strong>Quota:</strong>
                            {{ $joinedCount }}/{{ $maxStudents }} students
                        </p>
                    </div>

                    @if ($isFull)
                        <button type="button"
                                class="px-5 py-2.5 bg-gray-400 text-white font-semibold text-sm rounded-xl cursor-not-allowed"
                                disabled>
                            Quota Full
                        </button>
                    @else
                        <form action="{{ route('student.projects.join', $project) }}" method="POST">
                            @csrf

                            <button type="submit"
                                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition">
                                Join Project
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            <div class="bg-white shadow rounded-2xl p-6">
                <h4 class="font-semibold text-lg text-gray-900 mb-2">
                    Comments & Feedback
                </h4>

                <p class="text-sm text-gray-500 mb-4">
                    Use this section to discuss with Author / Vendor and submit project links (GitHub / Drive).
                </p>

                @if ($participation)
                    <form action="{{ route('projects.comments.store', $project) }}" method="POST" class="mb-6">
                        @csrf

                        <input type="hidden" name="comment_type" value="comment">

                        <textarea name="comment"
                                  class="border border-gray-300 rounded-xl w-full p-3 text-sm"
                                  rows="3"
                                  placeholder="Write a comment or question about this project..."
                                  required>{{ old('comment') }}</textarea>

                        @error('comment')
                            <p class="text-red-600 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                        <button type="submit"
                                class="mt-3 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition">
                            Post Comment
                        </button>
                    </form>
                @else
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-xl text-sm">
                        You must join this project first to submit comments.
                    </div>
                @endif

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