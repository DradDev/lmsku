<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'COMPRO TEKKOM') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --lms-primary: #2563EB;
                --lms-primary-hover: #1D4ED8;
                --lms-primary-glow: rgba(37,99,235,.20);
                --lms-surface: #FFFFFF;
                --lms-bg: #F8FAFC;
                --lms-border: #E2E8F0;
                --lms-text: #0F172A;
                --lms-muted: #64748B;
                --admin-sidebar-width: 250px;
            }

            *, *::before, *::after { box-sizing: border-box; }

            body {
                font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif !important;
                background-color: var(--lms-bg) !important;
                color: var(--lms-text) !important;
                margin: 0;
                padding: 0;
                -webkit-font-smoothing: antialiased;
            }

            /* ADMIN LEFT SIDEBAR LAYOUT matching TampilanAdmin.jpeg */
            .admin-wrapper {
                display: flex;
                min-height: 100vh;
                width: 100%;
            }

            .admin-sidebar {
                width: var(--admin-sidebar-width);
                background: #FFFFFF;
                border-right: 1px solid #E2E8F0;
                display: flex;
                flex-direction: column;
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 40;
                padding: 1.25rem 1rem;
                overflow-y: auto;
            }

            .admin-brand {
                display: flex;
                align-items: center;
                gap: 12px;
                text-decoration: none;
                padding: 0.25rem 0.5rem 1.25rem 0.5rem;
                border-bottom: 1px solid #F1F5F9;
                margin-bottom: 1.25rem;
            }

            .admin-brand-icon {
                width: 38px;
                height: 38px;
                border-radius: 10px;
                background: linear-gradient(135deg, #1E3A8A, #2563EB);
                display: flex;
                align-items: center;
                justify-content: center;
                color: #FFFFFF;
                box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
                flex-shrink: 0;
            }

            .admin-brand-title {
                font-size: 15px;
                font-weight: 800;
                color: #0F172A;
                line-height: 1.1;
                letter-spacing: -0.3px;
            }

            .admin-brand-sub {
                font-size: 11px;
                font-weight: 600;
                color: #64748B;
                letter-spacing: 0.5px;
            }

            .admin-nav {
                display: flex;
                flex-direction: column;
                gap: 4px;
                flex: 1;
            }

            .admin-nav-item {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 10px 14px;
                border-radius: 10px;
                font-size: 13.5px;
                font-weight: 600;
                color: #475569;
                text-decoration: none;
                transition: all 0.15s ease;
                position: relative;
            }

            .admin-nav-item:hover {
                background-color: #F8FAFC;
                color: #1E293B;
            }

            .admin-nav-item.active {
                background-color: #EFF6FF;
                color: #2563EB;
            }

            .admin-nav-item.active::before {
                content: '';
                position: absolute;
                left: 0;
                top: 6px;
                bottom: 6px;
                width: 4px;
                background-color: #2563EB;
                border-radius: 0 4px 4px 0;
            }

            .admin-hierarchy-widget {
                background: #FAFAFA;
                border: 1px solid #E2E8F0;
                border-radius: 14px;
                padding: 1rem;
                margin-top: 1.5rem;
            }

            .admin-hierarchy-title {
                font-size: 12px;
                font-weight: 700;
                color: #0F172A;
                margin-bottom: 0.75rem;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .admin-hierarchy-list {
                display: flex;
                flex-direction: column;
                gap: 8px;
                margin-bottom: 0.75rem;
            }

            .admin-hierarchy-step {
                display: flex;
                align-items: flex-start;
                gap: 8px;
                font-size: 11.5px;
                line-height: 1.3;
            }

            .admin-hierarchy-icon {
                width: 22px;
                height: 22px;
                border-radius: 6px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 10px;
                flex-shrink: 0;
            }

            .admin-hierarchy-step-mc { background: #EEF2FF; color: #4338CA; }
            .admin-hierarchy-step-sem { background: #E0F2FE; color: #0369A1; }
            .admin-hierarchy-step-off { background: #DCFCE7; color: #15803D; }

            .admin-hierarchy-subtext {
                font-size: 10.5px;
                color: #64748B;
                line-height: 1.4;
                border-top: 1px border-dashed #CBD5E1;
                padding-top: 6px;
            }

            .admin-main {
                margin-left: var(--admin-sidebar-width);
                flex: 1;
                display: flex;
                flex-direction: column;
                min-width: 0;
            }

            .admin-topbar {
                height: 64px;
                background: #FFFFFF;
                border-bottom: 1px solid #E2E8F0;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 2rem;
                position: sticky;
                top: 0;
                z-index: 30;
            }

            .admin-breadcrumb {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 13px;
                font-weight: 500;
                color: #64748B;
            }

            .admin-breadcrumb strong {
                color: #0F172A;
                font-weight: 600;
            }

            .admin-user-nav {
                display: flex;
                align-items: center;
                gap: 14px;
            }

            .admin-user-pill {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 4px 10px 4px 4px;
                border-radius: 100px;
                background: #F8FAFC;
                border: 1px solid #E2E8F0;
                text-decoration: none;
                color: #0F172A;
            }

            .admin-avatar {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: linear-gradient(135deg, #2563EB, #1D4ED8);
                color: #FFFFFF;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 13px;
                font-weight: 700;
            }

            .admin-user-info {
                display: flex;
                flex-direction: column;
                line-height: 1.1;
            }

            .admin-user-name {
                font-size: 12.5px;
                font-weight: 700;
                color: #0F172A;
            }

            .admin-user-role {
                font-size: 10.5px;
                color: #64748B;
            }

            .admin-content {
                padding: 2rem;
                flex: 1;
            }

            /* LECTURER & STUDENT STYLES (UNTOUCHED) */
            .min-h-screen.bg-gray-100, .bg-gray-100 { background-color: var(--lms-bg) !important; }
            header.bg-white, header.shadow { background-color: var(--lms-surface) !important; border-bottom: 1px solid var(--lms-border) !important; }
            .lms-footer { margin-top: auto; background: #0f1117; border-top: 1px solid rgba(255,255,255,0.06); }
            .lms-footer-inner { max-width: 1280px; margin: 0 auto; padding: 1.4rem 1.5rem; display: flex; flex-wrap: wrap; align-items: flex-start; justify-content: space-between; gap: 1.5rem; }
            .lms-footer-brand-wrap { display: flex; align-items: flex-start; gap: 12px; }
            .lms-footer-brand-icon { width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #1e3a8a, #2563eb); display: flex; align-items: center; justify-content: center; color: #fff; flex-shrink: 0; }
            .lms-footer-brand-title { font-size: 15px; font-weight: 700; color: #ffffff; line-height: 1.1; }
            .lms-footer-brand-subtitle { font-size: 12px; color: #94a3b8; margin-top: 2px; }
            .lms-footer-text { font-size: 13px; color: #94a3b8; max-width: 500px; line-height: 1.7; margin-top: 10px; }
            .lms-footer-links { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; }
            .lms-footer-link { font-size: 13px; font-weight: 500; color: #cbd5e1; text-decoration: none; transition: color .15s ease; }
            .lms-footer-link:hover { color: #60a5fa; }
            .lms-footer-bottom { border-top: 1px solid rgba(255,255,255,0.06); padding: .95rem 1.5rem 1.15rem; text-align: center; font-size: 12px; color: #94a3b8; background: #0b0f14; }

            ::-webkit-scrollbar { width: 5px; height: 5px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
            ::-webkit-scrollbar-thumb:hover { background: var(--lms-primary); }
        </style>
    </head>
    <body class="font-sans antialiased">
        @if(auth()->check() && auth()->user()->role === 'admin')
            <!-- ADMIN LEFT SIDEBAR LAYOUT -->
            <div class="admin-wrapper">
                <aside class="admin-sidebar">
                    <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                        <div class="admin-brand-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <div class="admin-brand-texts">
                            <div class="admin-brand-title">COMPRO</div>
                            <div class="admin-brand-sub">TEKKOM</div>
                        </div>
                    </a>

                    <nav class="admin-nav">
                        <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 13h8V3H3zM13 21h8v-6h-8zM13 10h8V3h-8zM3 21h8v-6H3z"/></svg>
                            <span>Dashboard</span>
                        </a>

                        <a href="{{ route('admin.master-courses.index') }}" class="admin-nav-item {{ request()->routeIs('admin.master-courses.*') ? 'active' : '' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                            <span>Master Courses</span>
                        </a>

                        <a href="{{ route('admin.users.index') }}" class="admin-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <span>Users</span>
                        </a>

                        <a href="{{ route('admin.results.index') }}" class="admin-nav-item {{ request()->routeIs('admin.results.*') ? 'active' : '' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                            <span>Results</span>
                        </a>

                        <a href="{{ route('admin.skills.index') }}" class="admin-nav-item {{ request()->routeIs('admin.skills.*') || request()->routeIs('admin.tags.*') ? 'active' : '' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v6M12 16v6M4.93 4.93l4.24 4.24M14.83 14.83l4.24 4.24M2 12h6M16 12h6M4.93 19.07l4.24-4.24M14.83 9.17l4.24-4.24"/></svg>
                            <span>Skills & Tags</span>
                        </a>

                        <a href="{{ route('admin.projects.index') }}" class="admin-nav-item {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                            <span>Projects Audit</span>
                        </a>
                    </nav>

                    <!-- Bottom Hierarchy Widget matching TampilanAdmin.jpeg -->
                    <div class="admin-hierarchy-widget">
                        <div class="admin-hierarchy-title">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h18v18H3z"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                            Struktur Hirarki
                        </div>
                        <div class="admin-hierarchy-list">
                            <div class="admin-hierarchy-step">
                                <div class="admin-hierarchy-icon admin-hierarchy-step-mc">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                                </div>
                                <div>
                                    <strong>Master Course</strong><br>
                                    <span style="color:#64748B;">Mata kuliah induk</span>
                                </div>
                            </div>
                            <div class="admin-hierarchy-step">
                                <div class="admin-hierarchy-icon admin-hierarchy-step-sem">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                </div>
                                <div>
                                    <strong>Semester</strong><br>
                                    <span style="color:#64748B;">Periode akademik</span>
                                </div>
                            </div>
                            <div class="admin-hierarchy-step">
                                <div class="admin-hierarchy-icon admin-hierarchy-step-off">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                                </div>
                                <div>
                                    <strong>Penawaran Kelas</strong><br>
                                    <span style="color:#64748B;">Kelas paralel di semester</span>
                                </div>
                            </div>
                        </div>
                        <div class="admin-hierarchy-subtext">
                            Pengelolaan semester & kelas diakses melalui Gerbang Utama Master Course.
                        </div>
                    </div>
                </aside>

                <div class="admin-main">
                    <header class="admin-topbar">
                        <div class="admin-breadcrumb">
                            <span>Master Courses</span>
                            @isset($breadcrumbSub)
                                <span style="color: #CBD5E1;">/</span>
                                <strong>{{ $breadcrumbSub }}</strong>
                            @endisset
                        </div>

                        <div class="admin-user-nav">
                            <a href="{{ route('profile.edit') }}" class="admin-user-pill">
                                <div class="admin-avatar">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <div class="admin-user-info">
                                    <span class="admin-user-name">{{ auth()->user()->name }}</span>
                                    <span class="admin-user-role">Administrator</span>
                                </div>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-left: 4px; color: #94A3B8;"><path d="m6 9 6 6 6-6"/></svg>
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" title="Logout" style="all: unset; cursor: pointer; color: #64748B; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; border: 1px solid #E2E8F0; background: #FFF;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16,17 21,12 16,7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                </button>
                            </form>
                        </div>
                    </header>

                    <main class="admin-content">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        @else
            <!-- LECTURER & STUDENT LAYOUT (UNTOUCHED) -->
            <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col">
                @include('layouts.navigation')

                @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="flex-1">
                    {{ $slot }}
                </main>

                <footer class="lms-footer">
                    <div class="lms-footer-inner">
                        <div>
                            <div class="lms-footer-brand-wrap">
                                <div class="lms-footer-brand-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                                    </svg>
                                </div>

                                <div>
                                    <div class="lms-footer-brand-title">COMPRO</div>
                                    <div class="lms-footer-brand-subtitle">TEKKOM</div>
                                </div>
                            </div>

                            <p class="lms-footer-text">
                                COMPRO System for Student Talent Development and Talent Matching.
                            </p>
                        </div>

                        <div class="lms-footer-links">
                            @auth
                                @if(auth()->user()->role === 'student')
                                    <a href="{{ route('student.dashboard') }}" class="lms-footer-link">Dashboard</a>
                                    <a href="{{ route('student.courses.index') }}" class="lms-footer-link">My Courses</a>
                                    <a href="{{ route('student.materials.index') }}" class="lms-footer-link">Materials</a>
                                    <a href="{{ route('student.results.index') }}" class="lms-footer-link">Results</a>
                                    <a href="{{ route('student.certificate.index') }}" class="lms-footer-link">Certificates</a>
                                @elseif(auth()->user()->role === 'lecturer')
                                    <a href="{{ route('lecturer.dashboard') }}" class="lms-footer-link">Dashboard</a>
                                    <a href="{{ route('lecturer.courses.index') }}" class="lms-footer-link">Courses</a>
                                    <a href="{{ route('lecturer.projects.index') }}" class="lms-footer-link">Projects</a>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <div class="lms-footer-bottom">
                        © {{ date('Y') }} COMPRO — TEKKOM. All rights reserved.
                    </div>
                </footer>
            </div>
        @endif
    </body>
</html>
