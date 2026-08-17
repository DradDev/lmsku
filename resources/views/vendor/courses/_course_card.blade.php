<div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex flex-col justify-between space-y-4 hover:shadow-md transition">
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <span class="px-2.5 py-0.5 bg-purple-100 text-purple-800 border border-purple-200 text-[10px] font-black uppercase rounded-md">
                {{ Auth::user()->name }}
            </span>
            <div class="flex items-center gap-1.5">
                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $course->is_archived ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200' }}">
                    {{ $course->is_archived ? 'Archived' : 'Active' }}
                </span>
                <span class="px-2 py-0.5 bg-gray-100 text-gray-700 text-[10px] font-bold rounded-md">
                    {{ $course->level }}
                </span>
            </div>
        </div>

        <h3 class="font-extrabold text-base text-gray-900 leading-snug">
            {{ $course->name }}
        </h3>

        <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed">
            {{ $course->description }}
        </p>
    </div>

    <div class="pt-3 border-t border-gray-100 space-y-3">
        <div class="flex items-center justify-between text-xs text-gray-600 font-semibold">
            <span>Passing Grade Kuis:</span>
            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 font-black rounded-md">{{ $course->certificate_threshold }}</span>
        </div>

        <div class="flex items-center justify-between gap-2 pt-1">
            <a href="{{ route('vendor.courses.show', $course) }}"
               class="flex-1 text-center py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition">
                Kelola Materials & Quiz
            </a>
            <a href="{{ route('vendor.courses.edit', $course) }}"
               class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition inline-flex items-center justify-center">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
            </a>
        </div>
    </div>
</div>
