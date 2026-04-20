<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        <div class="max-w-4xl mx-auto px-6 py-8">
            <div class="mb-6">
                <a href="{{ route('student.submissions.index') }}"
                   class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 mb-4">
                    ← Back to Submissions
                </a>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wide mb-2">
                        {{ $assignment->course->name ?? $assignment->course->title ?? 'Course' }}
                    </p>

                    <h1 class="text-3xl font-bold text-slate-900">
                        Submit Assignment
                    </h1>

                    <p class="text-slate-500 mt-2">
                        {{ $assignment->title ?? 'Assignment' }}
                    </p>

                    @if(!empty($assignment->description))
                        <p class="text-slate-600 mt-4 leading-relaxed">
                            {{ $assignment->description }}
                        </p>
                    @endif
                </div>
            </div>

            @if($errors->any())
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(isset($existingSubmission) && $existingSubmission)
                <div class="mb-6 rounded-2xl border border-yellow-200 bg-yellow-50 p-5 text-yellow-700">
                    Kamu sudah pernah mengirim submission untuk assignment ini.
                    Submission terbaru kamu masih tersimpan.
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-900 mb-5">Upload Your Work</h2>

                        <form action="{{ route('student.submissions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                            @csrf

                            <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

                            <div>
                                <label for="file" class="block text-sm font-medium text-slate-700 mb-2">
                                    Submission File
                                </label>
                                <input
                                    type="file"
                                    name="file"
                                    id="file"
                                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-slate-800"
                                    required
                                >
                                <p class="text-xs text-slate-500 mt-2">
                                    Upload your assignment file. Maximum file size follows system validation.
                                </p>
                            </div>

                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-medium text-white hover:bg-indigo-700">
                                Submit Assignment
                            </button>
                        </form>
                    </div>
                </div>

                <div>
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-900 mb-4">Assignment Info</h2>

                        <div class="space-y-4 text-sm">
                            <div class="rounded-xl bg-slate-50 p-4">
                                <p class="text-slate-500 mb-1">Course</p>
                                <p class="font-semibold text-slate-900">
                                    {{ $assignment->course->name ?? $assignment->course->title ?? '-' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4">
                                <p class="text-slate-500 mb-1">Assignment Title</p>
                                <p class="font-semibold text-slate-900">
                                    {{ $assignment->title ?? '-' }}
                                </p>
                            </div>

                            @if(!empty($assignment->deadline))
                                <div class="rounded-xl bg-slate-50 p-4">
                                    <p class="text-slate-500 mb-1">Deadline</p>
                                    <p class="font-semibold text-slate-900">
                                        {{ \Carbon\Carbon::parse($assignment->deadline)->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
