<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap');

.page-wrap {
    min-height: 100vh;
    background: #f8fafc;
    color: #1e293b;
    padding: 2.5rem 0 4rem;
    font-family: 'Figtree', sans-serif;
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
    font-weight: 600;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #1d4ed8;
    margin-bottom: 6px;
}
.page-title {
    font-size: 26px;
    font-weight: 700;
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
    box-shadow: 0 6px 24px rgba(15, 23, 42, 0.04);
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
    font-size: 28px;
    font-weight: 700;
    color: #0f172a;
}

/* Empty state */
.empty-state {
    background: #ffffff;
    border: 1px dashed #cbd5e1;
    border-radius: 18px;
    padding: 4rem 2rem;
    text-align: center;
}
.empty-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}
.empty-title {
    font-size: 15px;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 6px;
}
.empty-sub { font-size: 13px; color: #94a3b8; }

/* Materials grid */
.materials-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
}
.material-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    transition: all 0.2s;
    position: relative;
    overflow: hidden;
}
.material-card:hover {
    border-color: #93c5fd;
    transform: translateY(-3px);
    box-shadow: 0 12px 40px rgba(59,130,246,0.08);
}

/* File type icon */
.file-icon {
    width: 44px;
    height: 44px;
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
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    margin-bottom: 10px;
    letter-spacing: 0.3px;
    text-transform: uppercase;
    width: fit-content;
}
.material-title {
    font-size: 15px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 6px;
    line-height: 1.35;
}
.material-meta {
    font-size: 12px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 1.2rem;
    flex: 1;
}
.material-meta svg { opacity: 0.5; flex-shrink: 0; }

.material-actions {
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid #f1f5f9;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    font-size: 13px;
    font-weight: 600;
    padding: 10px 16px;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.15s;
    font-family: 'Figtree', sans-serif;
    text-align: center;
}
.btn-view {
    background: #1d4ed8;
    color: #ffffff;
    border: 1px solid #1d4ed8;
}
.btn-view:hover {
    background: #1e40af;
    border-color: #1e40af;
}
.btn-download {
    background: #ffffff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}
.btn-download:hover {
    background: #eff6ff;
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

        @php
            $totalMaterials = $materials->count();
            $totalCourses = $materials->pluck('master_course_id')->filter()->unique()->count();
            $latestUpload = $materials->first();
        @endphp

        <div class="page-top">
            <p class="page-eyebrow">Student Portal</p>
            <h1 class="page-title">Learning Materials</h1>
            <p class="page-sub">Access all materials from your enrolled courses.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Materials</div>
                <div class="stat-value">{{ $totalMaterials }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Courses</div>
                <div class="stat-value">{{ $totalCourses }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Latest Upload</div>
                <div class="stat-value" style="font-size:18px;">
                    {{ $latestUpload?->created_at?->format('d M Y') ?? '-' }}
                </div>
            </div>
        </div>

        @if($materials->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14,2 14,8 20,8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                </div>
                <div class="empty-title">Belum ada materi tersedia.</div>
                <div class="empty-sub">Materi akan muncul setelah kamu terdaftar di sebuah course.</div>
            </div>
        @else
            <div class="materials-grid">
                @foreach($materials as $material)
                    @php
                        $ext = strtolower(pathinfo($material->file_path ?? '', PATHINFO_EXTENSION));
                        $iconClass = in_array($ext, ['pdf']) ? 'pdf' : (in_array($ext, ['doc','docx']) ? 'doc' : (in_array($ext, ['mp4','mov','avi']) ? 'vid' : 'default'));
                    @endphp

                    <div class="material-card">
                        <div class="file-icon {{ $iconClass }}">
                            @if($iconClass === 'pdf')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="1.8">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14,2 14,8 20,8"/>
                                </svg>
                            @elseif($iconClass === 'doc')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="1.8">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14,2 14,8 20,8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                </svg>
                            @elseif($iconClass === 'vid')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#c084fc" stroke-width="1.8">
                                    <polygon points="23,7 16,12 23,17"/>
                                    <rect x="1" y="5" width="15" height="14" rx="2"/>
                                </svg>
                            @else
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a5b4fc" stroke-width="1.8">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14,2 14,8 20,8"/>
                                </svg>
                            @endif
                        </div>

                        <span class="course-tag">{{ $material->course->name ?? 'No Course' }}</span>

                        <div class="material-title">{{ $material->title }}</div>

                        <div class="material-meta">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12,6 12,12 16,14"/>
                            </svg>
                            {{ $material->created_at ? $material->created_at->format('d M Y') : '-' }}
                        </div>

                        <div class="material-actions">
                            <a href="{{ route('student.materials.show', $material->id) }}"
                               class="btn btn-view">
                                View
                            </a>

                            <a href="{{ asset('storage/' . $material->file_path) }}"
                               download
                               class="btn btn-download">
                                Download
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
</x-app-layout>
