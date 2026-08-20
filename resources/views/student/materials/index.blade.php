<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

.page-wrap {
    min-height: 100vh;
    background: #f8fafc;
    color: #1e293b;
    padding: 2.5rem 0 4rem;
    font-family: 'Inter', sans-serif;
}
.page-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
}
.page-top {
    margin-bottom: 2rem;
}
.page-eyebrow {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #4f46e5;
    margin-bottom: 6px;
}
.page-title {
    font-size: 26px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.4px;
}
.page-sub {
    font-size: 13px;
    color: #64748b;
    margin-top: 4px;
}

/* Stats */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 2rem;
}
.stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 1.25rem;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
}
.stat-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: #94a3b8;
    margin-bottom: 8px;
}
.stat-value {
    font-size: 26px;
    font-weight: 800;
    color: #0f172a;
}

/* Empty state */
.empty-state {
    background: #ffffff;
    border: 1px dashed #cbd5e1;
    border-radius: 24px;
    padding: 4rem 2rem;
    text-align: center;
}
.empty-icon {
    width: 60px;
    height: 60px;
    border-radius: 18px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.25rem;
}
.empty-title {
    font-size: 16px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 6px;
}
.empty-sub { font-size: 13px; color: #94a3b8; max-width: 440px; margin: 0 auto 1.5rem; line-height: 1.5; }

/* Materials grid */
.materials-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
}
.material-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    transition: all 0.2s;
    position: relative;
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
}
.material-card:hover {
    border-color: #cbd5e1;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}

.file-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    flex-shrink: 0;
}
.file-icon.pdf { background: #fef2f2; border: 1px solid #fecaca; }
.file-icon.doc { background: #eff6ff; border: 1px solid #bfdbfe; }
.file-icon.vid { background: #faf5ff; border: 1px solid #e9d5ff; }
.file-icon.default { background: #f0f9ff; border: 1px solid #bae6fd; }

.course-tag {
    display: inline-flex;
    align-items: center;
    font-size: 10px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 100px;
    background: #eff6ff;
    color: #4338ca;
    border: 1px solid #c7d2fe;
    margin-bottom: 10px;
    letter-spacing: 0.3px;
    text-transform: uppercase;
    width: fit-content;
}
.material-title {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 6px;
    line-height: 1.35;
}
.material-meta {
    font-size: 11.5px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 1.2rem;
    flex: 1;
}

.material-actions {
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid #f1f5f9;
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 8px;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 12.5px;
    font-weight: 700;
    padding: 9px 14px;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.15s;
    text-align: center;
    cursor: pointer;
}
.btn-view {
    background: #0f172a;
    color: #ffffff;
    border: 1px solid #0f172a;
}
.btn-view:hover {
    background: #1e293b;
    border-color: #1e293b;
}
.btn-unsave {
    background: #ffffff;
    color: #dc2626;
    border: 1px solid #fecaca;
    padding: 9px 12px;
}
.btn-unsave:hover {
    background: #fef2f2;
}

@media (max-width: 1024px) {
    .stats-grid,
    .materials-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 640px) {
    .page-container {
        padding: 0 1rem;
    }
    .stats-grid,
    .materials-grid,
    .material-actions {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="page-wrap">
    <div class="page-container">

        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold shadow-xs">
                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @php
            $totalSaved = ($savedMaterials ?? collect())->count();
            $totalCourses = ($savedMaterials ?? collect())->pluck('course_offering_id')->filter()->unique()->count();
            $latestSaved = ($savedMaterials ?? collect())->first();
        @endphp

        <!-- TOP BAR -->
        <div class="page-top">
            <a href="{{ route('student.courses.index') }}"
               class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-900 transition mb-3">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                <span>Kembali ke Katalog Kursus</span>
            </a>
            <p class="page-eyebrow">Perpustakaan Belajar Mahasiswa</p>
            <h1 class="page-title">Materi Tersimpan</h1>
            <p class="page-sub">Kumpulan modul dan file materi pembelajaran yang telah kamu simpan sebagai referensi belajar cepat.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Materi Tersimpan</div>
                <div class="stat-value text-indigo-600">{{ $totalSaved }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Asal Kursus / Kelas</div>
                <div class="stat-value text-slate-900">{{ $totalCourses }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Terakhir Disimpan</div>
                <div class="stat-value text-slate-700" style="font-size:18px; font-weight:700;">
                    {{ $latestSaved?->created_at?->format('d M Y H:i') ?? '-' }}
                </div>
            </div>
        </div>

        @if(($savedMaterials ?? collect())->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2">
                        <path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/>
                    </svg>
                </div>
                <div class="empty-title">Belum ada materi yang disimpan.</div>
                <div class="empty-sub">
                    Saat membuka modul pembelajaran di kelas kursus, klik tombol <strong>"Simpan Materi"</strong> agar materi penting kamu terkumpul rapi di sini.
                </div>
                <a href="{{ route('student.courses.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    <span>Buka Kursus Saya</span>
                </a>
            </div>
        @else
            <div class="materials-grid">
                @foreach($savedMaterials as $item)
                    @php
                        $material = $item->material;
                        $ext = strtolower(pathinfo($material->file_path ?? '', PATHINFO_EXTENSION));
                        $iconClass = in_array($ext, ['pdf']) ? 'pdf' : (in_array($ext, ['doc','docx']) ? 'doc' : (in_array($ext, ['mp4','mov','avi']) ? 'vid' : 'default'));
                        $courseName = $item->courseOffering?->masterCourse?->name ?? ($material->masterCourse?->name ?? ($material->course?->name ?? 'Course'));
                    @endphp

                    <div class="material-card">
                        <div class="flex items-center justify-between">
                            <div class="file-icon {{ $iconClass }}">
                                @if($iconClass === 'pdf')
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/>
                                    </svg>
                                @elseif($iconClass === 'doc')
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/>
                                    </svg>
                                @elseif($iconClass === 'vid')
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a855f7" stroke-width="2">
                                        <polygon points="23,7 16,12 23,17"/><rect x="1" y="5" width="15" height="14" rx="2"/>
                                    </svg>
                                @else
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/>
                                    </svg>
                                @endif
                            </div>

                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200 flex items-center gap-1">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                                Tersimpan
                            </span>
                        </div>

                        <span class="course-tag">{{ $courseName }}</span>

                        <div class="material-title">{{ $material->title }}</div>

                        <div class="material-meta">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/>
                            </svg>
                            Disimpan: {{ $item->created_at ? $item->created_at->format('d M Y H:i') : '-' }}
                        </div>

                        <div class="material-actions">
                            <a href="{{ route('student.materials.show', $material->id) }}"
                               class="btn btn-view">
                                Buka Materi
                            </a>

                            <form action="{{ route('student.materials.toggle-save', $material->id) }}" method="POST" onsubmit="return confirm('Hapus materi ini dari daftar simpanan?')">
                                @csrf
                                <button type="submit" class="btn btn-unsave" title="Hapus dari tersimpan">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
</x-app-layout>
