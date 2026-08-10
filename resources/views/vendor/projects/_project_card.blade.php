<div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex flex-col justify-between space-y-4 hover:shadow-md transition">
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <span class="px-2.5 py-0.5 bg-purple-100 text-purple-800 border border-purple-200 text-[10px] font-black uppercase rounded-md">
                🏢 {{ Auth::user()->name }}
            </span>
            <div class="flex items-center gap-1.5">
                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $project->is_published ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                    {{ $project->is_published ? '🟢 Published' : '🔴 Draft' }}
                </span>
                <span class="px-2 py-0.5 bg-gray-100 text-gray-700 text-[10px] font-bold rounded-md">
                    {{ ucfirst($project->difficulty_level) }}
                </span>
            </div>
        </div>

        <h3 class="font-extrabold text-base text-gray-900 leading-snug">
            {{ $project->title }}
        </h3>

        <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed">
            {{ $project->description }}
        </p>
    </div>

    <div class="pt-3 border-t border-gray-100 space-y-3">
        <div class="flex items-center justify-between text-xs text-gray-600 font-semibold">
            <span>Kuota Mahasiswa:</span>
            <span class="px-2 py-0.5 bg-indigo-100 text-indigo-800 font-black rounded-md">{{ $project->participations->count() }}/{{ $project->max_students }}</span>
        </div>

        <div class="flex items-center justify-between gap-2 pt-1">
            <a href="{{ route('vendor.projects.talent-pool', $project) }}"
               class="py-2 px-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold text-xs rounded-xl shadow-sm hover:from-indigo-700 hover:to-purple-700 transition">
                🎯 Talent Screening
            </a>
            <a href="{{ route('vendor.projects.show', $project) }}"
               class="flex-1 text-center py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">
                Detail & Progress
            </a>
        </div>
    </div>
</div>
