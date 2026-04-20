@php
    $role = auth()->user()->role;
    $userName = auth()->user()->name;
    $userInitial = strtoupper(substr($userName, 0, 1));

    $homeRoute = match ($role) {
        'admin' => route('admin.dashboard'),
        'lecturer' => route('lecturer.dashboard'),
        default => route('student.dashboard'),
    };
@endphp

<nav class="lms-nav">
    <div class="lms-nav-inner">
        <a href="{{ $homeRoute }}" class="lms-brand">
            <div class="lms-brand-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                    <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                </svg>
            </div>

            <div class="lms-brand-texts">
                <span class="lms-brand-title">UNDIP LMS</span>
                <span class="lms-brand-subtitle">Computer Engineering</span>
            </div>
        </a>

        <div class="lms-nav-links">
            @if($role === 'student')
                <a href="{{ route('student.dashboard') }}"
                   class="lms-nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 13h8V3H3zM13 21h8v-6h-8zM13 10h8V3h-8zM3 21h8v-6H3z"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('student.courses.index') }}"
                   class="lms-nav-link {{ request()->routeIs('student.courses.*') ? 'active' : '' }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="3" width="20" height="14" rx="2"/>
                        <path d="M8 21h8M12 17v4"/>
                    </svg>
                    My Courses
                </a>

                <a href="{{ route('student.materials.index') }}"
                   class="lms-nav-link {{ request()->routeIs('student.materials.*') ? 'active' : '' }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14,2 14,8 20,8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    Materials
                </a>

                <a href="{{ route('student.results.index') }}"
                   class="lms-nav-link {{ request()->routeIs('student.results.*') ? 'active' : '' }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10"/>
                        <line x1="12" y1="20" x2="12" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="14"/>
                    </svg>
                    Results
                </a>

                <a href="{{ route('student.certificate.index') }}"
                   class="lms-nav-link {{ request()->routeIs('student.certificate.*') ? 'active' : '' }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="8" r="6"/>
                        <path d="M8.21 13.89 7 23l5-3 5 3-1.21-9.12"/>
                    </svg>
                    Certificates
                </a>
            @endif

            @if($role === 'lecturer')
                <a href="{{ route('lecturer.dashboard') }}"
                   class="lms-nav-link {{ request()->routeIs('lecturer.dashboard') ? 'active' : '' }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 13h8V3H3zM13 21h8v-6h-8zM13 10h8V3h-8zM3 21h8v-6H3z"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('lecturer.courses.index') }}"
                   class="lms-nav-link {{ request()->routeIs('lecturer.courses.*') ? 'active' : '' }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="3" width="20" height="14" rx="2"/>
                        <path d="M8 21h8M12 17v4"/>
                    </svg>
                    Courses
                </a>

                <a href="{{ route('lecturer.materials.index') }}"
                   class="lms-nav-link {{ request()->routeIs('lecturer.materials.*') ? 'active' : '' }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14,2 14,8 20,8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    Materials
                </a>
            @endif

            @if($role === 'admin')
                <a href="{{ route('admin.dashboard') }}"
                   class="lms-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 13h8V3H3zM13 21h8v-6h-8zM13 10h8V3h-8zM3 21h8v-6H3z"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.results.index') }}"
                   class="lms-nav-link {{ request()->routeIs('admin.results.*') ? 'active' : '' }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10"/>
                        <line x1="12" y1="20" x2="12" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="14"/>
                    </svg>
                    Results
                </a>
            @endif
        </div>

        <div class="lms-nav-right">
            <a href="{{ route('profile.edit') }}" class="lms-user-pill">
                @if(auth()->user()->avatar_url)
                    <img src="{{ auth()->user()->avatar_url }}"
                         alt="{{ auth()->user()->name }}"
                         class="lms-avatar-image">
                @else
                    <div class="lms-avatar">{{ $userInitial }}</div>
                @endif

                <span class="lms-username">{{ $userName }}</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="lms-logout-btn">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16,17 21,12 16,7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</nav>

<style>
.lms-nav {
    position: sticky;
    top: 0;
    z-index: 50;
    background: #0f1117;
    border-bottom: 1px solid rgba(255,255,255,0.07);
    backdrop-filter: blur(12px);
}
.lms-nav-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 1.5rem;
    min-height: 60px;
    display: flex;
    align-items: center;
    gap: 1rem;
}
.lms-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    flex-shrink: 0;
}
.lms-brand-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, #1e3a8a, #1d4ed8);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    flex-shrink: 0;
}
.lms-brand-texts {
    display: flex;
    flex-direction: column;
    line-height: 1.1;
}
.lms-brand-title {
    font-size: 15px;
    font-weight: 700;
    color: #ffffff;
    letter-spacing: -0.2px;
    font-family: 'Figtree', sans-serif;
}
.lms-brand-subtitle {
    font-size: 11px;
    font-weight: 500;
    color: #94a3b8;
    font-family: 'Figtree', sans-serif;
}
.lms-nav-links {
    display: flex;
    gap: 4px;
    flex: 1;
    overflow-x: auto;
    scrollbar-width: none;
}
.lms-nav-links::-webkit-scrollbar { display: none; }
.lms-nav-link {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 13px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 500;
    color: #94a3b8;
    text-decoration: none;
    transition: all 0.15s ease;
    font-family: 'Figtree', sans-serif;
    white-space: nowrap;
}
.lms-nav-link:hover {
    background: rgba(255,255,255,0.06);
    color: #e2e8f0;
}
.lms-nav-link.active {
    background: rgba(59,130,246,0.16);
    color: #dbeafe;
}
.lms-nav-right {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-left: auto;
    flex-shrink: 0;
}
.lms-user-pill {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 100px;
    padding: 4px 12px 4px 5px;
    text-decoration: none;
    transition: all 0.15s ease;
}
.lms-user-pill:hover {
    background: rgba(255,255,255,0.08);
}
.lms-avatar {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1e3a8a, #2563eb);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
}
.lms-avatar-image {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 1px solid rgba(255,255,255,0.15);
}
.lms-username {
    font-size: 12px;
    font-weight: 500;
    color: #cbd5e1;
    font-family: 'Figtree', sans-serif;
    max-width: 120px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.lms-logout-btn {
    all: unset;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: transparent !important;
    border: 1px solid rgba(255,255,255,0.08) !important;
    color: #64748b !important;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s ease;
}
.lms-logout-btn:hover {
    background: rgba(239,68,68,0.10) !important;
    border-color: rgba(239,68,68,0.30) !important;
    color: #f87171 !important;
}
@media (max-width: 768px) {
    .lms-nav-inner {
        padding: 0 1rem;
        gap: .75rem;
    }
    .lms-brand-subtitle,
    .lms-username {
        display: none;
    }
}
@media (max-width: 640px) {
    .lms-brand-title {
        font-size: 13px;
    }
}
</style>
