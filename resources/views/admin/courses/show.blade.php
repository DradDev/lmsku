<x-app-layout>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        <!-- HERO HEADER CARD -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.5rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                    <span style="background: #F3E8FF; color: #6B21A8; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 6px; text-transform: uppercase;">Detail & Moderasi Course Admin</span>
                </div>
                <h1 style="font-size: 22px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.3px;">🎓 {{ $course->name }}</h1>
                <p style="font-size: 13px; color: #64748B; margin: 4px 0 0 0;">
                    Diterbitkan oleh: <strong>{{ optional($course->user)->name ?? 'Sistem' }}</strong> ({{ optional($course->user)->role === 'vendor' ? '🏢 Mitra Vendor Industri' : '🎓 Dosen Akademik' }})
                </p>
            </div>

            <div style="display: flex; align-items: center; gap: 10px;">
                <a href="{{ route('admin.courses.index') }}" style="padding: 8px 16px; background: #F1F5F9; color: #475569; font-size: 13px; font-weight: 700; border-radius: 10px; text-decoration: none;">
                    ← Kembali
                </a>

                <form action="{{ route('admin.courses.toggle-archive', $course) }}" method="POST" style="margin: 0;">
                    @csrf
                    @if($course->is_archived)
                        <button type="submit" style="padding: 8px 16px; background: #DCFCE7; color: #15803D; font-size: 13px; font-weight: 700; border-radius: 10px; border: none; cursor: pointer;">
                            🚀 Aktifkan Course
                        </button>
                    @else
                        <button type="submit" style="padding: 8px 16px; background: #FEF3C7; color: #B45309; font-size: 13px; font-weight: 700; border-radius: 10px; border: none; cursor: pointer;">
                            📦 Arsipkan Course
                        </button>
                    @endif
                </form>

                <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus course ini secara permanen?')" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="padding: 8px 16px; background: #FEF2F2; color: #B91C1C; font-size: 13px; font-weight: 700; border-radius: 10px; border: none; cursor: pointer;">
                        🗑️ Hapus Course
                    </button>
                </form>
            </div>
        </div>

        <!-- DETAILS CONTENT CARD -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.5rem; space-y: 1rem;">
            <h3 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0 0 10px 0;">📋 Silabus & Pokok Bahasan</h3>
            <p style="font-size: 13.5px; color: #334155; line-height: 1.6; white-space: pre-line; margin: 0;">
                {{ $course->description }}
            </p>

            <div style="display: flex; gap: 1.5rem; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #F1F5F9; font-size: 13px;">
                <div><strong>Level:</strong> {{ $course->level }}</div>
                <div><strong>Kategori:</strong> {{ optional($course->category)->name ?? 'General' }}</div>
                <div><strong>Passing Grade:</strong> {{ $course->certificate_threshold }} / 100</div>
                <div><strong>Total Modul:</strong> {{ $course->materials->count() }} Materials</div>
                <div><strong>Total Kuis:</strong> {{ $course->quizzes->count() }} Quizzes</div>
            </div>
        </div>

    </div>
</x-app-layout>
