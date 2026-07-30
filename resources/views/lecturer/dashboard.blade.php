<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.lec-wrap {
    min-height: 100vh;
    background: #f4f6fb;
    color: #1e2435;
    padding: 2.5rem 0 4rem;
    font-family: 'Inter', sans-serif;
}

.lec-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* ── Header ── */
.lec-header {
    margin-bottom: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

@media (min-width: 768px) {
    .lec-header { flex-direction: row; align-items: center; justify-content: space-between; }
}

.lec-eyebrow {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #10b981;
    margin-bottom: 5px;
}

.lec-title {
    font-size: 26px;
    font-weight: 600;
    color: #1e2435;
}

.lec-sub {
    font-size: 13px;
    color: #7b8399;
    margin-top: 3px;
}

.header-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

/* ── Buttons ── */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 9px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.15s;
}

.btn-green  { background: #10b981; color: #fff; }
.btn-green:hover  { background: #059669; }

.btn-primary { background: #6366f1; color: #fff; }
.btn-primary:hover { background: #4f46e5; }

.btn-blue   { background: #3b82f6; color: #fff; }
.btn-blue:hover   { background: #2563eb; }

.btn-purple { background: #8b5cf6; color: #fff; }
.btn-purple:hover { background: #7c3aed; }

.btn-red    { background: #ef4444; color: #fff; }
.btn-red:hover    { background: #dc2626; }

.btn-ghost {
    background: #fff;
    color: #1e2435;
    border: 1px solid #e8eaf2;
}
.btn-ghost:hover { background: #f4f6fb; }

.btn-outline {
    background: #f0f1fb;
    color: #6366f1;
    border: 1px solid #dde0f9;
}
.btn-outline:hover { background: #e4e6f9; }

.btn-muted {
    background: #f0f2fa;
    color: #5a607a;
    border: 1px solid #e8eaf2;
}
.btn-muted:hover { background: #e8eaf2; }

/* ── Tabs ── */
.tabs-wrap {
    display: flex;
    align-items: center;
    gap: 4px;
    background: #fff;
    border: 1px solid #e8eaf2;
    border-radius: 13px;
    padding: 5px;
    width: fit-content;
    margin-bottom: 2rem;
}

.tab-link {
    padding: 8px 20px;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.15s;
    color: #7b8399;
}

.tab-link:hover { background: #f4f6fb; color: #1e2435; }
.tab-link.tab-active-overview  { background: #1e2435; color: #fff; }
.tab-link.tab-active-materials { background: #3b82f6; color: #fff; }
.tab-link.tab-active-questions { background: #8b5cf6; color: #fff; }

/* ── Alerts ── */
.alert {
    padding: 11px 16px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 1.5rem;
    border: 1px solid transparent;
}

.alert-success { background: #edfaf4; border-color: #a7e9c8; color: #1a7a4a; }
.alert-error   { background: #fff0f0; border-color: #ffc2c2; color: #b91c1c; }
.alert-warning { background: #fffbeb; border-color: #fcd34d; color: #92400e; }

/* ── Stat Grid ── */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 2rem;
}

.stat-card {
    background: #fff;
    border: 1px solid #e8eaf2;
    border-radius: 14px;
    padding: 1.2rem 1.4rem;
    border-top: 3px solid transparent;
    transition: box-shadow 0.2s;
}

.stat-card:hover { box-shadow: 0 4px 18px rgba(0,0,0,0.07); }
.stat-card.sc1 { border-top-color: #6366f1; }
.stat-card.sc2 { border-top-color: #8b5cf6; }
.stat-card.sc3 { border-top-color: #3b82f6; }
.stat-card.sc4 { border-top-color: #10b981; }

.stat-label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    color: #9399b0;
    margin-bottom: 8px;
}

.stat-value {
    font-size: 30px;
    font-weight: 600;
    color: #1e2435;
}

.stat-sub { font-size: 11px; color: #b0b4c9; margin-top: 4px; }

/* ── Two Column ── */
.two-col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

@media (max-width: 1024px) { .two-col { grid-template-columns: 1fr; } }

/* ── Card ── */
.panel {
    background: #fff;
    border: 1px solid #e8eaf2;
    border-radius: 16px;
    padding: 1.4rem;
}

.panel-title {
    font-size: 14px;
    font-weight: 600;
    color: #1e2435;
    margin-bottom: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.panel-title-link { font-size: 12px; color: #6366f1; text-decoration: none; font-weight: 500; }
.panel-title-link:hover { text-decoration: underline; }

/* ── Quick Actions Grid ── */
.quick-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.quick-item {
    background: #f7f8fc;
    border: 1px solid #e8eaf2;
    border-radius: 11px;
    padding: 14px;
    text-decoration: none;
    transition: all 0.15s;
    display: block;
}

.quick-item:hover { background: #eef0fb; border-color: #dde0f9; }
.quick-item-cat { font-size: 11px; color: #9399b0; font-weight: 500; margin-bottom: 3px; }
.quick-item-label { font-size: 13px; font-weight: 600; color: #1e2435; }

/* ── Question Preview Card ── */
.q-preview {
    background: #f7f8fc;
    border: 1px solid #e8eaf2;
    border-radius: 11px;
    padding: 13px;
    margin-bottom: 10px;
}

.q-preview:last-child { margin-bottom: 0; }

.badge-row { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 8px; }

.badge {
    display: inline-flex;
    align-items: center;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 100px;
}

.badge-gray    { background: #f0f2fa; color: #5a607a; border: 1px solid #e8eaf2; }
.badge-purple  { background: #f1effe; color: #7c3aed; border: 1px solid #e9e4fd; }
.badge-green   { background: #edfaf4; color: #1a7a4a; border: 1px solid #a7e9c8; }
.badge-red     { background: #fff0f0; color: #b91c1c; border: 1px solid #ffc2c2; }
.badge-blue    { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.badge-amber   { background: #fffbeb; color: #92400e; border: 1px solid #fcd34d; }
.badge-draft   { background: #f4f6fb; color: #7b8399; border: 1px solid #e8eaf2; }

.q-preview-text { font-size: 13px; color: #3d4460; line-height: 1.5; }

/* ── Section Header ── */
.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.1rem;
}

.section-title {
    font-size: 15px;
    font-weight: 600;
    color: #1e2435;
    display: flex;
    align-items: center;
    gap: 9px;
}

.section-title::before {
    content: '';
    width: 3px;
    height: 15px;
    border-radius: 2px;
    display: inline-block;
    flex-shrink: 0;
}

.section-title.bar-blue::before  { background: #3b82f6; }
.section-title.bar-purple::before { background: #8b5cf6; }

/* ── Materials List ── */
.material-row {
    background: #fff;
    border: 1px solid #e8eaf2;
    border-radius: 12px;
    padding: 1.1rem 1.3rem;
    margin-bottom: 10px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    transition: box-shadow 0.15s;
}

.material-row:hover { box-shadow: 0 3px 14px rgba(0,0,0,0.06); }
.material-row:last-child { margin-bottom: 0; }

@media (min-width: 640px) {
    .material-row { flex-direction: row; align-items: center; justify-content: space-between; }
}

.material-name { font-size: 14px; font-weight: 600; color: #1e2435; }
.material-desc { font-size: 12px; color: #9399b0; margin-top: 2px; }
.material-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }

/* ── Empty State ── */
.empty-box {
    background: #fff;
    border: 1.5px dashed #dde0ec;
    border-radius: 14px;
    padding: 3rem;
    text-align: center;
}

.empty-box p { font-size: 13px; color: #b0b4c9; }

/* ── Questions Tab ── */
.q-form-wrap {
    background: #fff;
    border: 1px solid #e8eaf2;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.q-form-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.4rem;
}

.q-form-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 600;
    color: #1e2435;
}

.q-form-icon {
    background: #f1effe;
    border-radius: 8px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ── Form Elements ── */
.form-group { margin-bottom: 1.1rem; }

.form-label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    color: #9399b0;
    margin-bottom: 7px;
}

.form-control {
    width: 100%;
    border: 1px solid #e8eaf2;
    background: #f7f8fc;
    border-radius: 9px;
    padding: 10px 14px;
    font-size: 13px;
    color: #1e2435;
    font-family: 'Inter', sans-serif;
    transition: border-color 0.15s;
    outline: none;
}

.form-control:focus { border-color: #8b5cf6; background: #fff; }

textarea.form-control { resize: vertical; }

.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

@media (max-width: 640px) { .form-grid-2 { grid-template-columns: 1fr; } }

/* ── Question Card (in form) ── */
.question-card {
    background: #f7f8fc;
    border: 1px solid #e8eaf2;
    border-radius: 13px;
    padding: 1.2rem;
    margin-bottom: 1rem;
}

.question-card:last-child { margin-bottom: 0; }

.question-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.question-card-title { font-size: 13px; font-weight: 600; color: #1e2435; }

.mc-fields {
    margin-top: 1rem;
    background: #fff;
    border: 1px solid #e8eaf2;
    border-radius: 10px;
    padding: 1rem;
}

.mc-fields-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.7px;
    text-transform: uppercase;
    color: #9399b0;
    margin-bottom: 12px;
    display: block;
}

.mc-option-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
}

.mc-option-row:last-child { margin-bottom: 0; }

.mc-letter {
    width: 30px;
    height: 30px;
    border-radius: 7px;
    background: #eef0fb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    color: #6366f1;
    flex-shrink: 0;
}

.mc-options-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 12px; }
@media (max-width: 640px) { .mc-options-grid { grid-template-columns: 1fr; } }

/* ── Question List Cards ── */
.q-list-card {
    background: #fff;
    border: 1px solid #e8eaf2;
    border-radius: 13px;
    padding: 1.2rem;
    margin-bottom: 10px;
    transition: box-shadow 0.15s;
}

.q-list-card:hover { box-shadow: 0 3px 14px rgba(0,0,0,0.06); }
.q-list-card:last-child { margin-bottom: 0; }

.q-list-top {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 12px;
}

@media (min-width: 1024px) {
    .q-list-top { flex-direction: row; align-items: flex-start; justify-content: space-between; }
}

.q-list-body { flex: 1; min-width: 0; }
.q-list-question { font-size: 14px; font-weight: 500; color: #1e2435; line-height: 1.55; margin-bottom: 6px; }
.q-list-quiz-ref { font-size: 12px; color: #9399b0; }
.q-list-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; flex-wrap: wrap; }

/* ── Answer Options Display ── */
.mc-answer-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
@media (max-width: 640px) { .mc-answer-grid { grid-template-columns: 1fr; } }

.mc-answer-item {
    display: flex;
    align-items: center;
    gap: 10px;
    border-radius: 9px;
    padding: 9px 12px;
    border: 1px solid transparent;
}

.mc-answer-item.correct { background: #edfaf4; border-color: #a7e9c8; }
.mc-answer-item.wrong   { background: #f7f8fc; border-color: #e8eaf2; }

.mc-answer-letter {
    width: 24px;
    height: 24px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    flex-shrink: 0;
}

.mc-answer-letter.correct { background: #10b981; color: #fff; }
.mc-answer-letter.wrong   { background: #dde0ec; color: #5a607a; }

.mc-answer-text { font-size: 13px; }
.mc-answer-text.correct { color: #1a7a4a; }
.mc-answer-text.wrong   { color: #5a607a; }

.essay-note {
    background: #f7f8fc;
    border: 1px solid #e8eaf2;
    border-radius: 9px;
    padding: 10px 13px;
    font-size: 13px;
    color: #9399b0;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding-top: 8px;
    flex-wrap: wrap;
}


/* ── Skill Mapping Fields ── */
.hidden { display: none !important; }

.skill-detail-panel {
    background: #fff;
    border: 1px solid #e8eaf2;
    border-radius: 10px;
    padding: 12px;
    margin-top: 10px;
}

.skill-detail-title {
    font-size: 12px;
    font-weight: 700;
    color: #1e2435;
    margin-bottom: 9px;
}

.skill-checkbox {
    display: block;
    font-size: 13px;
    color: #3d4460;
    margin-bottom: 7px;
}

.skill-checkbox input { margin-right: 7px; }

.skill-help {
    font-size: 12px;
    color: #9399b0;
    margin-top: 6px;
}

/* ── Responsive ── */
@media (max-width: 1024px) {
    .stat-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 640px) {
    .lec-container { padding: 0 1rem; }
    .stat-grid { grid-template-columns: 1fr; }
    .quick-grid { grid-template-columns: 1fr; }
}
</style>

<div class="lec-wrap">
    <div class="lec-container">

        {{-- Header --}}
        <div class="lec-header">
            <div class="lec-header-left">
                <p class="lec-eyebrow">Lecturer Portal</p>
                <h1 class="lec-title">Lecturer Dashboard</h1>
                <p class="lec-sub">Kelola materi pembelajaran, soal, dan aktivitas pengajaran Anda.</p>
            </div>

            <div class="header-actions">
                <a href="{{ route('lecturer.courses.create') }}" class="btn btn-green">+ New Course</a>
                <a href="{{ route('lecturer.courses.index') }}" class="btn btn-ghost">Manage Courses</a>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="tabs-wrap">
            <a href="{{ route('lecturer.dashboard', ['tab' => 'overview']) }}"
               class="tab-link {{ $tab === 'overview' ? 'tab-active-overview' : '' }}">
                Overview
            </a>
            <a href="{{ route('lecturer.dashboard', ['tab' => 'materials']) }}"
               class="tab-link {{ $tab === 'materials' ? 'tab-active-materials' : '' }}">
                Learning Materials
            </a>
            <a href="{{ route('lecturer.dashboard', ['tab' => 'questions']) }}"
               class="tab-link {{ $tab === 'questions' ? 'tab-active-questions' : '' }}">
                Questions
            </a>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <p style="font-weight:600; margin-bottom:6px;">Please fix the following errors:</p>
                <ul style="list-style:disc; padding-left:1.2rem; display:flex; flex-direction:column; gap:3px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ==================== OVERVIEW TAB ==================== --}}
        @if($tab === 'overview')
            <div class="stat-grid">
                <div class="stat-card sc1">
                    <div class="stat-label">Total Materials</div>
                    <div class="stat-value">{{ $materials->count() }}</div>
                    <div class="stat-sub">Learning materials uploaded</div>
                </div>
                <div class="stat-card sc2">
                    <div class="stat-label">Total Questions</div>
                    <div class="stat-value">{{ $questions->count() }}</div>
                    <div class="stat-sub">Questions created</div>
                </div>
                <div class="stat-card sc3">
                    <div class="stat-label">Multiple Choice</div>
                    <div class="stat-value">{{ $questions->where('question_type', 'multiple_choice')->count() }}</div>
                    <div class="stat-sub">MC questions</div>
                </div>
                <div class="stat-card sc4">
                    <div class="stat-label">Essay</div>
                    <div class="stat-value">{{ $questions->where('question_type', 'essay')->count() }}</div>
                    <div class="stat-sub">Essay questions</div>
                </div>
            </div>

            <div class="two-col">
                <div class="panel">
                    <div class="panel-title">Quick Actions</div>
                    <div class="quick-grid">
                        <a href="{{ route('lecturer.courses.index') }}" class="quick-item">
                            <div class="quick-item-cat">Courses</div>
                            <div class="quick-item-label">Manage Courses & Materials</div>
                        </a>
                        <a href="{{ route('lecturer.dashboard', ['tab' => 'questions']) }}" class="quick-item">
                            <div class="quick-item-cat">Questions</div>
                            <div class="quick-item-label">Create Questions</div>
                        </a>
                        <a href="{{ route('lecturer.courses.index') }}" class="quick-item">
                            <div class="quick-item-cat">Courses</div>
                            <div class="quick-item-label">View My Courses</div>
                        </a>
                        <a href="{{ route('lecturer.assignments.index') }}" class="quick-item">
                            <div class="quick-item-cat">Assignments</div>
                            <div class="quick-item-label">Manage Assignments</div>
                        </a>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-title">
                        Recent Questions
                        <a href="{{ route('lecturer.dashboard', ['tab' => 'questions']) }}" class="panel-title-link">View all →</a>
                    </div>

                    @forelse($questions->take(5) as $question)
                        <div class="q-preview">
                             <div class="badge-row">
                                <span class="badge badge-purple">
                                    {{ $question->question_type === 'essay' ? 'Essay' : 'Multiple Choice' }}
                                </span>
                                <span class="badge badge-amber">{{ ucfirst($question->difficulty) }}</span>
                            </div>
                            <p class="q-preview-text" style="overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                                {{ $question->question }}
                            </p>
                        </div>
                    @empty
                        <div class="empty-box">
                            <p>Belum ada soal yang dibuat.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        {{-- ==================== MATERIALS TAB ==================== --}}
        @if($tab === 'materials')
            <div class="section-header" style="margin-bottom:1.5rem;">
                <div>
                    <h2 class="section-title bar-blue" style="font-size:16px;">Learning Materials</h2>
                    <p style="font-size:13px;color:#9399b0;margin-top:4px;padding-left:12px;">Kelola materi yang digunakan untuk pembelajaran.</p>
                </div>
                <a href="{{ route('lecturer.materials.create') }}" class="btn btn-blue">+ Add Material</a>
            </div>

            @forelse($materials as $material)
                <div class="material-row">
                    <div class="material-info">
                        <div class="material-name">{{ $material->title ?? 'Untitled Material' }}</div>
                        @if(!empty($material->description))
                            <div class="material-desc">{{ $material->description }}</div>
                        @endif
                    </div>
                    <div class="material-actions">
                        <a href="{{ route('lecturer.materials.show', $material->id) }}" class="btn btn-ghost">View</a>
                        <a href="{{ route('lecturer.materials.edit', $material->id) }}" class="btn btn-blue">Edit</a>
                        <form method="POST" action="{{ route('lecturer.materials.destroy', $material->id) }}"
                              onsubmit="return confirm('Yakin ingin menghapus materi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-red">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-box">
                    <p>Belum ada learning material.</p>
                </div>
            @endforelse
        @endif

        {{-- ==================== QUESTIONS TAB ==================== --}}
        @if($tab === 'questions')
            @php
                $groupedQuestions = $questions->groupBy('quiz_id');
            @endphp

            <div class="section-header" style="margin-bottom:1.5rem;">
                <div>
                    <h2 class="section-title bar-purple" style="font-size:16px;">Questions</h2>
                    <p style="font-size:13px;color:#9399b0;margin-top:4px;padding-left:12px;">
                        Buat banyak nomor soal dalam 1 quiz, lalu simpan sekaligus.
                    </p>
                </div>
            </div>

            {{-- Create Questions Form --}}
            <div class="q-form-wrap">
                <div class="q-form-header">
                    <div class="q-form-title">
                        <div class="q-form-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        Create Questions for One Quiz
                    </div>
                    <button type="button" id="add-question" class="btn btn-muted">+ Tambah Nomor Soal</button>
                </div>

                <form method="POST" action="{{ route('lecturer.questions.store') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Quiz</label>
                        <select name="quiz_id" class="form-control" required>
                            <option value="">Select quiz</option>
                            @forelse($quizzes as $quiz)
                                <option value="{{ $quiz->id }}">
                                    {{ $quiz->title }} — {{ $quiz->course->name }}
                                    @if($quiz->is_final ?? false)
                                        (Final Quiz)
                                    @endif
                                </option>
                            @empty
                                <option value="">No quiz available</option>
                            @endforelse
                        </select>
                    </div>

                    <div id="questions-wrapper">
                        <div class="question-card" data-index="0">
                            <div class="question-card-header">
                                <div class="question-card-title">Nomor Soal 1</div>
                                <button type="button" class="remove-question hidden btn btn-red" style="font-size:11px;padding:5px 11px;">Remove</button>
                            </div>

                            <div class="form-grid-2">
                                <div class="form-group" style="margin-bottom:0">
                                    <label class="form-label">Question Type</label>
                                    <select name="questions[0][question_type]" class="question-type form-control">
                                        <option value="essay">Essay</option>
                                        <option value="multiple_choice">Multiple Choice</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-bottom:0">
                                    <label class="form-label">Difficulty</label>
                                    <select name="questions[0][difficulty]" class="form-control">
                                        <option value="easy">Easy</option>
                                        <option value="medium" selected>Medium</option>
                                        <option value="hard">Hard</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-grid-2" style="margin-top:12px;">
                                <div class="form-group" style="margin-bottom:0">
                                    <label class="form-label">Bidang / Skill Utama</label>
                                    <select
                                        name="questions[0][main_skill_id]"
                                        class="question-main-skill form-control"
                                        data-question-index="0"
                                    >
                                        <option value="">-- Pilih Bidang Utama --</option>
                                        @foreach($mainSkills as $mainSkill)
                                            <option value="{{ $mainSkill->id }}">{{ $mainSkill->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="skill-help">Contoh: Software, ML / AI, Jaringan.</p>
                                </div>

                                <div class="form-group" style="margin-bottom:0">
                                    <label class="form-label">Detail Skill yang Diuji</label>
                                    @foreach($mainSkills as $mainSkill)
                                        <div
                                            class="question-skill-detail-group hidden"
                                            data-question-index="0"
                                            data-parent-id="{{ $mainSkill->id }}"
                                        >
                                            <div class="skill-detail-panel">
                                                <div class="skill-detail-title">Detail {{ $mainSkill->name }}</div>

                                                @forelse($mainSkill->children as $childSkill)
                                                    <label class="skill-checkbox">
                                                        <input
                                                            type="checkbox"
                                                            name="questions[0][skill_ids][]"
                                                            value="{{ $childSkill->id }}"
                                                        >
                                                        {{ $childSkill->name }}
                                                    </label>
                                                @empty
                                                    <p class="skill-help">Belum ada detail skill untuk bidang ini.</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group" style="margin-top:12px; margin-bottom:0;">
                                <label class="form-label">Question</label>
                                <textarea name="questions[0][question]" rows="3" class="form-control" placeholder="Write your question here..." required></textarea>
                            </div>

                            <div class="mc-fields hidden">
                                <span class="mc-fields-label">Answer Options</span>
                                <div class="mc-options-grid">
                                    @foreach(['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'] as $key => $label)
                                        <div class="mc-option-row">
                                            <div class="mc-letter">{{ $label }}</div>
                                            <input type="text"
                                                   name="questions[0][option_{{ $key }}]"
                                                   placeholder="Option {{ $label }}"
                                                   class="option-input form-control"
                                                   style="margin-bottom:0;">
                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label class="form-label">Correct Answer</label>
                                    <select name="questions[0][correct_answer]" class="correct-answer form-control">
                                        <option value="">Select correct answer</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($quizzes->isEmpty())
                        <div class="alert alert-warning" style="margin-top:1rem; margin-bottom:0;">
                            Anda belum memiliki quiz. Buat quiz terlebih dahulu sebelum menambahkan question.
                        </div>
                    @endif

                    <div class="form-actions" style="margin-top:1.2rem;">
                        <button type="button" id="add-question-bottom" class="btn btn-muted">+ Tambah Nomor Soal</button>
                        <button type="submit" class="btn btn-purple" {{ $quizzes->isEmpty() ? 'disabled' : '' }}>
                            Save All Questions
                        </button>
                    </div>
                </form>
            </div>

            {{-- Questions List Grouped by Quiz --}}
            <div class="section-header">
                <h2 class="section-title bar-purple">All Questions by Quiz</h2>
            </div>

            @forelse($groupedQuestions as $quizId => $quizQuestions)
                @php
                    $quizRef = $quizQuestions->first()?->quiz;
                    $mcCountPerQuiz = $quizQuestions->where('question_type', 'multiple_choice')->count();
                    $essayCountPerQuiz = $quizQuestions->where('question_type', 'essay')->count();
                @endphp

                <div class="panel" style="margin-bottom:1rem;">
                    <div class="section-header" style="margin-bottom:1rem;">
                        <div>
                            <h3 style="font-size:16px;font-weight:600;color:#1e2435;">
                                {{ $quizRef->title ?? 'Quiz' }}
                            </h3>
                            <p style="font-size:12px;color:#9399b0;margin-top:4px;">
                                {{ $quizRef->course->name ?? '-' }} • {{ $quizQuestions->count() }} question(s)
                                @if($quizRef->is_final ?? false)
                                    • Final Quiz
                                @endif
                            </p>
                        </div>

                        <div class="badge-row" style="margin-bottom:0;">
                            @if($mcCountPerQuiz > 0)
                                <span class="badge badge-purple">{{ $mcCountPerQuiz }} Multiple Choice</span>
                            @endif
                            @if($essayCountPerQuiz > 0)
                                <span class="badge badge-green">{{ $essayCountPerQuiz }} Essay</span>
                            @endif
                        </div>
                    </div>

                    @foreach($quizQuestions->values() as $index => $question)
                        <div class="q-list-card" style="{{ !$loop->last ? 'margin-bottom:10px;' : 'margin-bottom:0;' }}">
                            <div class="q-list-top">
                                <div class="q-list-body">
                                    <div class="badge-row" style="margin-bottom:10px;">
                                        <span class="badge badge-gray">No. {{ $index + 1 }}</span>

                                        <span class="badge badge-purple">
                                            {{ $question->question_type === 'essay' ? 'Essay' : 'Multiple Choice' }}
                                        </span>

                                        <span class="badge badge-amber">{{ ucfirst($question->difficulty) }}</span>
                                    </div>

                                    <p class="q-list-question">{{ $question->question }}</p>
                                </div>

                                <div class="q-list-actions">
                                    <a href="{{ route('lecturer.questions.edit', $question->id) }}" class="btn btn-blue">Edit</a>

                                    <form method="POST"
                                          action="{{ route('lecturer.questions.destroy', $question->id) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus soal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-red">Delete</button>
                                    </form>
                                </div>
                            </div>

                            @if($question->question_type === 'multiple_choice')
                                <div class="mc-answer-grid">
                                    @foreach(['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'] as $key => $label)
                                        @php $isCorrect = $question->correct_answer === $label; @endphp
                                        <div class="mc-answer-item {{ $isCorrect ? 'correct' : 'wrong' }}">
                                            <div class="mc-answer-letter {{ $isCorrect ? 'correct' : 'wrong' }}">{{ $label }}</div>
                                            <span class="mc-answer-text {{ $isCorrect ? 'correct' : 'wrong' }}">
                                                {{ $question->{'option_' . $key} }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="essay-note">Essay question — penilaian dilakukan secara manual.</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="empty-box">
                    <p>Belum ada soal yang dibuat.</p>
                </div>
            @endforelse

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    let questionIndex = document.querySelectorAll('.question-card').length;

                    const wrapper = document.getElementById('questions-wrapper');
                    const addBtnTop = document.getElementById('add-question');
                    const addBtnBottom = document.getElementById('add-question-bottom');

                    function refreshQuestionSkillDetails(selectElement) {
                        const questionIndexValue = selectElement.dataset.questionIndex;
                        const selectedParentId = selectElement.value;

                        const groups = document.querySelectorAll(
                            `.question-skill-detail-group[data-question-index="${questionIndexValue}"]`
                        );

                        groups.forEach(function (group) {
                            if (group.dataset.parentId === selectedParentId) {
                                group.classList.remove('hidden');
                            } else {
                                group.classList.add('hidden');

                                group.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
                                    checkbox.checked = false;
                                });
                            }
                        });
                    }

                    function setupCard(card) {
                        const typeSelect = card.querySelector('.question-type');
                        const mcFields = card.querySelector('.mc-fields');
                        const optionInputs = card.querySelectorAll('.option-input');
                        const correctAnswer = card.querySelector('.correct-answer');
                        const removeBtn = card.querySelector('.remove-question');
                        const mainSkillSelect = card.querySelector('.question-main-skill');

                        function toggleMc() {
                            const isMc = typeSelect.value === 'multiple_choice';
                            mcFields.classList.toggle('hidden', !isMc);
                            optionInputs.forEach(input => { input.required = isMc; });
                            correctAnswer.required = isMc;
                        }

                        typeSelect.addEventListener('change', toggleMc);
                        toggleMc();

                        if (mainSkillSelect) {
                            mainSkillSelect.addEventListener('change', function () {
                                refreshQuestionSkillDetails(mainSkillSelect);
                            });

                            refreshQuestionSkillDetails(mainSkillSelect);
                        }

                        removeBtn.addEventListener('click', function () {
                            card.remove();
                            renumberCards();
                        });
                    }

                    function renumberCards() {
                        document.querySelectorAll('.question-card').forEach((card, index) => {
                            card.dataset.index = index;
                            card.querySelector('.question-card-title').textContent = `Nomor Soal ${index + 1}`;

                            const removeBtn = card.querySelector('.remove-question');
                            removeBtn.classList.toggle('hidden', index === 0);

                            card.querySelectorAll('input, textarea, select').forEach(input => {
                                if (input.name) {
                                    input.name = input.name.replace(/questions\[\d+\]/, `questions[${index}]`);
                                }
                            });

                            const mainSkillSelect = card.querySelector('.question-main-skill');
                            if (mainSkillSelect) {
                                mainSkillSelect.dataset.questionIndex = index;
                            }

                            card.querySelectorAll('.question-skill-detail-group').forEach(group => {
                                group.dataset.questionIndex = index;
                            });
                        });

                        questionIndex = document.querySelectorAll('.question-card').length;
                    }

                    function clearNewCardValues(newCard) {
                        newCard.querySelectorAll('textarea').forEach(textarea => {
                            textarea.value = '';
                        });

                        newCard.querySelectorAll('input').forEach(input => {
                            if (input.type === 'checkbox' || input.type === 'radio') {
                                input.checked = false;
                            } else {
                                input.value = '';
                            }
                        });

                        newCard.querySelectorAll('select').forEach(select => {
                            if (select.classList.contains('question-type')) {
                                select.value = 'essay';
                            } else if (select.classList.contains('correct-answer')) {
                                select.value = '';
                            } else {
                                select.selectedIndex = 0;
                            }
                        });

                        newCard.querySelector('.mc-fields').classList.add('hidden');
                        newCard.querySelectorAll('.question-skill-detail-group').forEach(group => {
                            group.classList.add('hidden');
                        });
                    }

                    function addQuestion() {
                        const firstCard = document.querySelector('.question-card');
                        const newCard = firstCard.cloneNode(true);

                        newCard.dataset.index = questionIndex;
                        clearNewCardValues(newCard);

                        wrapper.appendChild(newCard);
                        renumberCards();
                        setupCard(newCard);
                    }

                    if (addBtnTop) {
                        addBtnTop.addEventListener('click', addQuestion);
                    }

                    if (addBtnBottom) {
                        addBtnBottom.addEventListener('click', addQuestion);
                    }

                    document.querySelectorAll('.question-card').forEach(setupCard);
                    renumberCards();
                });
            </script>
        @endif

    </div>
</div>
</x-app-layout>