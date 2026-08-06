<x-app-layout>

<div class="min-h-screen bg-white p-10 text-center">

    {{-- Header section --}}
    <div class="result-hero">
        <div class="result-icon-wrap">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22,4 12,14.01 9,11.01"/>
            </svg>
        </div>

        <h1 class="result-title">Quiz Completed</h1>
        <p class="result-subtitle">{{ $quiz->title }}</p>

        <span class="inline-flex items-center rounded-full border {{ $quiz->quiz_type_badge_class }} px-3 py-1 text-xs font-semibold mt-2">
            {{ $quiz->quiz_type_label }}
        </span>
    </div>

    {{-- Score card --}}
    <div class="result-card">
        <p class="result-label">Nilai Akhir</p>
        <h2 class="result-score">{{ $score }}</h2>

        <div class="result-badge result-badge--success">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20,6 9,17 4,12"/></svg>
            Quiz telah diverifikasi otomatis
        </div>

        <div style="margin-top: 12px; display: flex; gap: 16px; font-size: 13px; color: #6B7280;">
            <span>Benar: <strong style="color: #059669;">{{ $correctCount }}</strong></span>
            <span>Total: <strong style="color: #374151;">{{ $totalQuestions }}</strong></span>
        </div>
    {{-- Certificate status banner for Final Quiz --}}
    @if($quiz->isFinal())
        <div style="max-width: 400px; width: 100%; margin-bottom: 1.5rem; padding: 1rem; border-radius: 12px; font-size: 13px; text-align: center; {{ isset($certificate) && $certificate ? 'background: #FEF3C7; border: 1px solid #FCD34D; color: #92400E;' : 'background: #F3F4F6; border: 1px solid #E5E7EB; color: #4B5563;' }}">
            @if(isset($certificate) && $certificate)
                <div style="font-weight: 700; margin-bottom: 4px;">📜 Pengajuan Sertifikat Berhasil!</div>
                <div>Nilai Anda (<strong>{{ $score }}</strong>) mencapai batas minimal (<strong>{{ $quiz->course->certificate_threshold ?? 60 }}</strong>). Sertifikat sedang dalam proses verifikasi Admin.</div>
            @else
                <div style="font-weight: 600;">Syarat Sertifikat: Nilai Minimal {{ $quiz->course->certificate_threshold ?? 60 }}</div>
                <div style="margin-top: 2px;">Nilai Anda belum memenuhi batas minimal sertifikat. Silakan coba kembali jika ada sisa attempts.</div>
            @endif
        </div>
    @endif

    {{-- CTA --}}
    <a href="{{ route('student.courses.show', $quiz->course_id) }}" class="result-cta">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
        Back to Course
    </a>

</div>

<style>
.min-h-screen.bg-white.p-10 {
    background: #F9FAFB !important;
    padding: 3rem 1.5rem !important;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.result-hero {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    margin-bottom: 1.75rem;
}
.result-icon-wrap {
    width: 58px;
    height: 58px;
    border-radius: 50%;
    background: #ffffff;
    border: 1.5px solid #E5E7EB;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6366F1;
    margin-bottom: 6px;
}
.result-title {
    font-family: 'Inter', 'Figtree', system-ui, sans-serif;
    font-size: 22px;
    font-weight: 600;
    color: #111827;
    letter-spacing: -0.3px;
    margin: 0;
}
.result-subtitle {
    font-size: 13.5px;
    color: #6B7280;
    font-family: 'Inter', 'Figtree', system-ui, sans-serif;
    margin: 0;
}
.result-card {
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 16px;
    padding: 2.25rem 2.75rem;
    width: 100%;
    max-width: 400px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    margin-bottom: 1.75rem;
}
.result-label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: #9CA3AF;
    font-family: 'Inter', 'Figtree', system-ui, sans-serif;
    margin: 0;
}
.result-score {
    font-size: 68px;
    font-weight: 700;
    color: #6366F1;
    letter-spacing: -3px;
    line-height: 1;
    font-family: 'Inter', 'Figtree', system-ui, sans-serif;
    margin: 0;
}
.result-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 13px;
    border-radius: 100px;
    font-size: 11.5px;
    font-weight: 600;
    font-family: 'Inter', 'Figtree', system-ui, sans-serif;
}
.result-badge--success {
    background: #ECFDF5;
    color: #065F46;
    border: 1px solid #A7F3D0;
}
.result-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 22px;
    background: #6366F1;
    color: #FFFFFF;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 600;
    font-family: 'Inter', 'Figtree', system-ui, sans-serif;
    text-decoration: none;
    transition: background .15s, transform .12s;
    margin-top: 0.25rem;
}
.result-cta:hover {
    background: #4F46E5;
    transform: translateY(-1px);
    color: #FFFFFF;
}
.result-cta:active {
    transform: translateY(0);
}
</style>

</x-app-layout>
