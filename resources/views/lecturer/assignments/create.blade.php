<x-app-layout>
<div class="min-h-screen bg-slate-950 text-white">
    <div class="mx-auto max-w-3xl px-4 py-8">

        <h1 class="mb-6 text-2xl font-bold">Buat Assignment</h1>

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-sm text-rose-300">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('lecturer.assignments.store') }}" method="POST"
              class="rounded-2xl border border-slate-800 bg-slate-900 p-6 space-y-5">
            @csrf

            <div>
                <label class="mb-2 block text-sm text-slate-300">Course</label>
                <select name="course_id" required
                        class="w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-white">
                    <option value="">Pilih Course</option>

                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm text-slate-300">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-white">
            </div>

            <div>
                <label class="mb-2 block text-sm text-slate-300">Description</label>
                <textarea name="description" rows="5"
                          class="w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-white">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="mb-2 block text-sm text-slate-300">Deadline</label>
                <input type="datetime-local" name="deadline" value="{{ old('deadline') }}"
                       class="w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-white">
            </div>

            <button type="submit"
                    class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-500">
                Simpan Assignment
            </button>
        </form>

    </div>
</div>
</x-app-layout>
