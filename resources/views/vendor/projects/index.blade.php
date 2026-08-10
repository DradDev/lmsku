<x-app-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-wrap {
    min-height: 100vh;
    background: linear-gradient(180deg, #f8faff 0%, #f3f6fc 100%);
    color: #1e2435;
    padding: 2.5rem 0 4rem;
    font-family: 'Inter', sans-serif;
}

.tabs-nav {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    border-bottom: 1px solid #e4e7ec;
    padding-bottom: 1rem;
}

.tab-btn {
    background: none;
    border: none;
    font-size: 15px;
    font-weight: 600;
    color: #667085;
    cursor: pointer;
    padding: 8px 16px;
    border-radius: 8px;
    transition: all 0.2s;
}

.tab-btn:hover {
    color: #101828;
    background: #f8fafc;
}

.tab-btn.active {
    color: #6b21a8;
    background: #f3e8ff;
}

.page-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
}

.page-header {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
}

@media (min-width: 768px) {
    .page-header {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }
}

.page-eyebrow {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.3px;
    text-transform: uppercase;
    color: #6b21a8;
    margin-bottom: 6px;
}

.page-title {
    font-size: 28px;
    font-weight: 800;
    color: #101828;
    letter-spacing: -0.5px;
}

.page-sub {
    font-size: 14px;
    color: #667085;
    margin-top: 6px;
    max-width: 720px;
}

.alert-success {
    padding: 12px 16px;
    border-radius: 14px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 1.5rem;
    background: #ecfdf3;
    border: 1px solid #abefc6;
    color: #067647;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 700;
    padding: 10px 18px;
    border-radius: 12px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    font-family: 'Inter', sans-serif;
    white-space: nowrap;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
}

.btn-primary {
    background: linear-gradient(135deg, #6b21a8 0%, #7e22ce 100%);
    color: #fff;
    box-shadow: 0 4px 14px rgba(107, 33, 168, 0.25);
}
.btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(107, 33, 168, 0.35); }

.stat-strip {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 2rem;
}

.stat-card {
    background: #fff;
    border: 1px solid #e4e7ec;
    border-radius: 16px;
    padding: 1.25rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}

.stat-label {
    font-size: 11px;
    font-weight: 700;
    color: #667085;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 24px;
    font-weight: 800;
    color: #101828;
    margin-top: 4px;
}

.table-card {
    background: #fff;
    border: 1px solid #e4e7ec;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}

.empty-state {
    background: rgba(255,255,255,0.94);
    border: 1.5px dashed #d0d5dd;
    border-radius: 22px;
    padding: 4rem 2rem;
    text-align: center;
}
</style>

<div class="page-wrap" x-data="{ tab: 'active' }">
    <div class="page-container">

        <div class="page-header">
            <div>
                <p class="page-eyebrow">Author Mitra Vendor Portal &bull; Manajemen Project Real Client</p>
                <h1 class="page-title">Project Real Client & Industri Mitra Vendor</h1>
                <p class="page-sub">Kelola active project terpublikasi atau simpan draft project pada Project Bank Mitra Vendor.</p>
            </div>

            <a href="{{ route('vendor.projects.create') }}" class="btn btn-primary">
                + Publikasikan Project Baru
            </a>
        </div>

        @if(session('success'))
            <div class="alert-success flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-600 text-white font-bold text-xs">✓</span>
                <div>
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="stat-strip">
            <div class="stat-card cursor-pointer" @click="tab = 'active'">
                <div class="stat-label">Aktif Dipublikasikan</div>
                <div class="stat-value text-emerald-600">{{ $activeProjects->count() }}</div>
            </div>
            <div class="stat-card cursor-pointer" @click="tab = 'bank'">
                <div class="stat-label">Project Bank (Drafts)</div>
                <div class="stat-value text-amber-600">{{ $bankProjects->count() }}</div>
            </div>
            <div class="stat-card cursor-pointer" @click="tab = 'all'">
                <div class="stat-label">Total Semua Project</div>
                <div class="stat-value">{{ $allProjects->count() }}</div>
            </div>
        </div>

        <div class="tabs-nav">
            <button class="tab-btn" :class="{ 'active': tab === 'active' }" @click="tab = 'active'">
                Active Projects ({{ $activeProjects->count() }})
            </button>
            <button class="tab-btn" :class="{ 'active': tab === 'bank' }" @click="tab = 'bank'">
                Project Bank / Drafts ({{ $bankProjects->count() }})
            </button>
        </div>

        <!-- TAB 1: ACTIVE PROJECTS -->
        <div x-show="tab === 'active'">
            <div class="table-card">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                        <thead>
                            <tr style="background: #fafafa; border-bottom: 1px solid #e4e7ec; color: #667085; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                <th style="padding: 16px 20px;">Judul Project</th>
                                <th style="padding: 16px 20px;">Level Kesulitan</th>
                                <th style="padding: 16px 20px;">Tipe & Kuota</th>
                                <th style="padding: 16px 20px;">Status</th>
                                <th style="padding: 16px 20px; text-align: right;">Aksi Management</th>
                            </tr>
                        </thead>

                        <tbody style="divide-y: 1px solid #f1f5f9;">
                            @forelse ($activeProjects as $project)
                                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s ease;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                                    <td style="padding: 16px 20px;">
                                        <div style="font-size: 14px; font-weight: 800; color: #101828;">{{ $project->title }}</div>
                                        <div style="font-size: 11.5px; color: #667085; margin-top: 2px;">Dibuat: {{ $project->created_at ? $project->created_at->format('d M Y') : '-' }}</div>
                                    </td>
                                    <td style="padding: 16px 20px; font-weight: 700; color: #334155;">
                                        {{ ucfirst($project->difficulty_level) }}
                                    </td>
                                    <td style="padding: 16px 20px; color: #475569;">
                                        <span style="font-weight: 700; color: #0f172a;">{{ ucfirst($project->type ?? 'General') }}</span> &bull; {{ $project->participations->count() }}/{{ $project->max_students ?? 1 }} Mhs
                                    </td>
                                    <td style="padding: 16px 20px;">
                                        <span style="background: #ecfdf5; color: #047857; border: 1px solid #abefc6; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 100px;">
                                            🟢 Published
                                        </span>
                                    </td>
                                    <td style="padding: 16px 20px; text-align: right;">
                                        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                                            <a href="{{ route('vendor.projects.talent-pool', $project) }}" style="padding: 6px 12px; background: #fef3c7; color: #b45309; font-size: 12px; font-weight: 700; border-radius: 8px; text-decoration: none;">
                                                🎯 Talent Pool
                                            </a>
                                            <a href="{{ route('vendor.projects.show', $project) }}" class="btn btn-primary" style="padding: 6px 14px; font-size: 12px;">
                                                Detail & Kelola →
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="padding: 40px; text-align: center; color: #667085;">
                                        Belum ada project aktif yang dipublikasikan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 2: PROJECT BANK (DRAFTS) -->
        <div x-show="tab === 'bank'" style="display: none;">
            <div class="table-card">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                        <thead>
                            <tr style="background: #fafafa; border-bottom: 1px solid #e4e7ec; color: #667085; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                <th style="padding: 16px 20px;">Judul Draft Project</th>
                                <th style="padding: 16px 20px;">Level Kesulitan</th>
                                <th style="padding: 16px 20px;">Status</th>
                                <th style="padding: 16px 20px; text-align: right;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody style="divide-y: 1px solid #f1f5f9;">
                            @forelse ($bankProjects as $project)
                                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s ease;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                                    <td style="padding: 16px 20px; font-weight: 800; color: #101828;">{{ $project->title }}</td>
                                    <td style="padding: 16px 20px; font-weight: 700; color: #334155;">{{ ucfirst($project->difficulty_level) }}</td>
                                    <td style="padding: 16px 20px;">
                                        <span style="background: #fef2f2; color: #991b1b; border: 1px solid #fca5a5; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 100px;">
                                            🔴 Draft Bank
                                        </span>
                                    </td>
                                    <td style="padding: 16px 20px; text-align: right;">
                                        <a href="{{ route('vendor.projects.show', $project) }}" class="btn btn-primary" style="padding: 6px 14px; font-size: 12px;">
                                            Pratinjau & Edit →
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="padding: 40px; text-align: center; color: #667085;">
                                        Belum ada draft project di Project Bank.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
</x-app-layout>
