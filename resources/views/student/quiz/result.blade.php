<x-app-layout>

<div class="min-h-screen bg-white p-10 text-center">

    @php
        $hasEssay = $quiz->questions()->where('question_type', 'essay')->exists();

        $essayAnswers = isset($attempt)
            ? $attempt->answers()
                ->with('question')
                ->whereHas('question', function ($query) {
                    $query->where('question_type', 'essay');
                })
                ->get()
            : collect();

        $gradedEssayAnswers = $essayAnswers->filter(function ($answer) {
            return !is_null($answer->score);
        });
    @endphp

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
    </div>

    {{-- Score / Status card --}}
    @php
        $mcQuestionCount = $quiz->questions()->where('question_type', 'multiple_choice')->count();
        $isFullyGraded = isset($attempt) && $attempt->is_verified;
    @endphp

    <div class="result-card">
        @if($mcQuestionCount > 0)
            <p class="result-label">
                {{ $hasEssay && !$isFullyGraded ? 'Nilai Sementara (Pilihan Ganda)' : 'Nilai Akhir' }}
            </p>
            <h2 class="result-score">{{ $score }}</h2>
        @endif

        @if($isFullyGraded)
            <div class="result-badge result-badge--success">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20,6 9,17 4,12"/></svg>
                {{ $hasEssay ? 'Quiz telah dinilai oleh lecturer' : 'Quiz telah diverifikasi' }}
            </div>
        @elseif($hasEssay)
            <div class="result-badge result-badge--pending">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>
                Menunggu Penilaian Essay
            </div>
        @endif

        @if($hasEssay && !$isFullyGraded)
            <p class="result-pending-desc" style="margin-top: 6px;">
                Nilai pilihan ganda kamu sudah keluar. Jawaban essay sedang menunggu penilaian dari lecturer,
                nilai akhir akan diperbarui setelah essay dinilai.
            </p>
        @endif
    </div>

    {{-- Essay grading results --}}
    @if(isset($attempt) && $attempt->is_verified && $gradedEssayAnswers->count() > 0)
        <div class="essay-section">
            <h3 class="essay-section-title">Hasil Penilaian Essay</h3>

            <div class="essay-list">
                @foreach($gradedEssayAnswers as $answer)
                    <div class="essay-card">
                        <div class="essay-card-header">
                            <span class="essay-card-badge">Essay</span>
                            <span class="essay-score-pill">{{ $answer->score }} pts</span>
                        </div>

                        <div class="essay-field">
                            <p class="essay-field-label">Pertanyaan</p>
                            <p class="essay-field-value">{{ $answer->question->question ?? '-' }}</p>
                        </div>

                        <div class="essay-divider"></div>

                        <div class="essay-field">
                            <p class="essay-field-label">Jawaban Kamu</p>
                            <p class="essay-field-value essay-answer">{{ $answer->answer_text ?? '-' }}</p>
                        </div>

                        <div class="essay-divider"></div>

                        <div class="essay-feedback">
                            <p class="essay-field-label">Feedback Lecturer</p>
                            <p class="essay-feedback-text">{{ $answer->feedback ?: 'Belum ada feedback dari lecturer.' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- CTA --}}
    <a href="{{ route('student.dashboard') }}" class="result-cta">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9,22 9,12 15,12 15,22"/></svg>
        Back to Dashboard
    </a>

</div>

<style>
/* ── Page wrapper ── */
.min-h-screen.bg-white.p-10 {
    background: #F9FAFB !important;
    padding: 3rem 1.5rem !important;
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* ── Hero ── */
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

/* ── Score card ── */
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
.result-badge--pending {
    background: #FFFBEB;
    color: #92400E;
    border: 1px solid #FDE68A;
}

/* ── Pending state ── */
.result-pending-icon {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.result-pending-icon--amber {
    background: #FFFBEB;
    color: #D97706;
    border: 1.5px solid #FDE68A;
}
.result-pending-title {
    font-size: 15px;
    font-weight: 600;
    color: #92400E;
    font-family: 'Inter', 'Figtree', system-ui, sans-serif;
    margin: 0;
}
.result-pending-desc {
    font-size: 13px;
    color: #6B7280;
    font-family: 'Inter', 'Figtree', system-ui, sans-serif;
    line-height: 1.65;
    margin: 0;
    text-align: center;
    max-width: 290px;
}

/* ── Essay section ── */
.essay-section {
    width: 100%;
    max-width: 640px;
    margin-bottom: 1.75rem;
    text-align: left;
}
.essay-section-title {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    font-family: 'Inter', 'Figtree', system-ui, sans-serif;
    margin: 0 0 12px 2px;
}
.essay-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.essay-card {
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 14px;
    padding: 1.25rem 1.5rem;
}
.essay-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
}
.essay-card-badge {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: #4338CA;
    background: #EEF2FF;
    border: 1px solid #C7D2FE;
    padding: 3px 10px;
    border-radius: 100px;
    font-family: 'Inter', 'Figtree', system-ui, sans-serif;
}
.essay-score-pill {
    font-size: 12px;
    font-weight: 700;
    color: #065F46;
    background: #ECFDF5;
    border: 1px solid #A7F3D0;
    padding: 3px 11px;
    border-radius: 100px;
    font-family: 'Inter', 'Figtree', system-ui, sans-serif;
}
.essay-field {
    margin-bottom: 10px;
}
.essay-field-label {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: #9CA3AF;
    font-family: 'Inter', 'Figtree', system-ui, sans-serif;
    margin: 0 0 5px 0;
}
.essay-field-value {
    font-size: 13.5px;
    color: #111827;
    font-family: 'Inter', 'Figtree', system-ui, sans-serif;
    line-height: 1.6;
    margin: 0;
}
.essay-answer {
    color: #374151;
    white-space: pre-line;
}
.essay-divider {
    height: 1px;
    background: #F3F4F6;
    margin: 11px 0;
}
.essay-feedback {
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
    border-radius: 9px;
    padding: 11px 13px;
}
.essay-feedback-text {
    font-size: 12.5px;
    color: #6B7280;
    font-family: 'Inter', 'Figtree', system-ui, sans-serif;
    line-height: 1.65;
    margin: 5px 0 0 0;
}

/* ── CTA button ── */
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
