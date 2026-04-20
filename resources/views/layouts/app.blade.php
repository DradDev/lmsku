<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'UNDIP LMS') }}</title>

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
                --lms-shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
                --lms-shadow-md: 0 4px 16px rgba(37,99,235,.12);
            }

            *, *::before, *::after { box-sizing: border-box; }

            body {
                font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif !important;
                background-color: var(--lms-bg) !important;
                color: var(--lms-text) !important;
                -webkit-font-smoothing: antialiased;
            }

            .min-h-screen.bg-gray-100,
            .dark\:bg-gray-900,
            .bg-gray-100 {
                background-color: var(--lms-bg) !important;
            }

            header.bg-white,
            header.shadow,
            header.dark\:bg-gray-800 {
                background-color: var(--lms-surface) !important;
                border-bottom: 1px solid var(--lms-border) !important;
                box-shadow: var(--lms-shadow-sm) !important;
            }

            header .max-w-7xl h2,
            header .max-w-7xl h1 {
                font-size: 17px !important;
                font-weight: 600 !important;
                color: var(--lms-text) !important;
                letter-spacing: -0.2px !important;
            }

            .inline-flex.items-center.px-4.py-2.bg-gray-800,
            .inline-flex.items-center.px-4.py-2.dark\:bg-gray-200,
            button[type="submit"]:not(.lms-logout-btn),
            input[type="submit"] {
                background-color: var(--lms-primary) !important;
                color: #ffffff !important;
                border: none !important;
                border-radius: 9px !important;
                font-weight: 600 !important;
                font-size: 13px !important;
                letter-spacing: .01em !important;
                box-shadow: 0 2px 8px var(--lms-primary-glow) !important;
                transition: background .16s, transform .12s, box-shadow .16s !important;
            }

            .inline-flex.items-center.px-4.py-2.bg-gray-800:hover,
            button[type="submit"]:not(.lms-logout-btn):hover,
            input[type="submit"]:hover {
                background-color: var(--lms-primary-hover) !important;
                box-shadow: var(--lms-shadow-md) !important;
                transform: translateY(-1px) !important;
            }

            .inline-flex.items-center.px-4.py-2.bg-gray-800:active,
            button[type="submit"]:not(.lms-logout-btn):active {
                transform: translateY(0) !important;
            }

            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="number"],
            textarea,
            select {
                background-color: #fff !important;
                border-color: var(--lms-border) !important;
                border-radius: 9px !important;
                color: var(--lms-text) !important;
                font-family: 'Figtree', sans-serif !important;
                font-size: 14px !important;
                transition: border-color .16s, box-shadow .16s !important;
            }

            input:focus,
            textarea:focus,
            select:focus {
                border-color: var(--lms-primary) !important;
                box-shadow: 0 0 0 3px var(--lms-primary-glow) !important;
                outline: none !important;
            }

            .bg-white.overflow-hidden,
            .bg-white.rounded-lg,
            .bg-white.shadow,
            .bg-white.shadow-sm {
                background-color: var(--lms-surface) !important;
                border: 1px solid var(--lms-border) !important;
                border-radius: 12px !important;
                box-shadow: var(--lms-shadow-sm) !important;
            }

            .absolute.z-50.bg-white,
            .origin-top-right.absolute.bg-white,
            .absolute.bg-white {
                background-color: var(--lms-surface) !important;
                border: 1px solid var(--lms-border) !important;
                border-radius: 10px !important;
                box-shadow: 0 8px 24px rgba(15,23,42,.10) !important;
            }

            a.text-gray-600, a.text-gray-500,
            .text-gray-600, .text-gray-500 {
                color: var(--lms-muted) !important;
            }

            a:hover.text-gray-900, a.hover\:text-gray-900:hover {
                color: var(--lms-primary) !important;
            }

            .lms-footer {
                margin-top: auto;
                background: #0f1117;
                border-top: 1px solid rgba(255,255,255,0.06);
            }

            .lms-footer-inner {
                max-width: 1280px;
                margin: 0 auto;
                padding: 1.4rem 1.5rem;
                display: flex;
                flex-wrap: wrap;
                align-items: flex-start;
                justify-content: space-between;
                gap: 1.5rem;
            }

            .lms-footer-brand-wrap {
                display: flex;
                align-items: flex-start;
                gap: 12px;
            }

            .lms-footer-brand-icon {
                width: 40px;
                height: 40px;
                border-radius: 10px;
                background: linear-gradient(135deg, #1e3a8a, #2563eb);
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                flex-shrink: 0;
            }

            .lms-footer-brand-title {
                font-size: 15px;
                font-weight: 700;
                color: #ffffff;
                line-height: 1.1;
            }

            .lms-footer-brand-subtitle {
                font-size: 12px;
                color: #94a3b8;
                margin-top: 2px;
            }

            .lms-footer-text {
                font-size: 13px;
                color: #94a3b8;
                max-width: 500px;
                line-height: 1.7;
                margin-top: 10px;
            }

            .lms-footer-links {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                align-items: center;
            }

            .lms-footer-link {
                font-size: 13px;
                font-weight: 500;
                color: #cbd5e1;
                text-decoration: none;
                transition: color .15s ease;
            }

            .lms-footer-link:hover {
                color: #60a5fa;
            }

            .lms-footer-bottom {
                border-top: 1px solid rgba(255,255,255,0.06);
                padding: .95rem 1.5rem 1.15rem;
                text-align: center;
                font-size: 12px;
                color: #94a3b8;
                background: #0b0f14;
            }

            ::-webkit-scrollbar { width: 5px; height: 5px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
            ::-webkit-scrollbar-thumb:hover { background: var(--lms-primary); }
        </style>
    </head>
    <body class="font-sans antialiased">
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
                                <div class="lms-footer-brand-title">UNDIP LMS</div>
                                <div class="lms-footer-brand-subtitle">Computer Engineering</div>
                            </div>
                        </div>

                        <p class="lms-footer-text">
                            A modern learning management platform for courses, materials, quizzes, results, and certificates.
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
                                <a href="{{ route('lecturer.materials.index') }}" class="lms-footer-link">Materials</a>
                            @elseif(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="lms-footer-link">Dashboard</a>
                                <a href="{{ route('admin.results.index') }}" class="lms-footer-link">Results</a>
                            @endif
                        @endauth
                    </div>
                </div>

                <div class="lms-footer-bottom">
                    © {{ date('Y') }} UNDIP LMS — Computer Engineering. All rights reserved.
                </div>
            </footer>
        </div>
    </body>
</html>
